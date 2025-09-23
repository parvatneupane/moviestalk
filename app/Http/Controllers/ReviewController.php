<?php

namespace App\Http\Controllers;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // Require user to be logged in
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Submit a review for a specific movie
    // AJAX: check if user already reviewed
    public function checkReview($movieId){
        $exists = Review::where('movie_id', $movieId)
                        ->where('user_id', auth()->id())
                        ->exists();
        return response()->json(['exists' => $exists]);
    }

    // AJAX: submit or update review
public function submitReview(Request $request, $movieId)
{
    if (!auth()->check()) {
        return response()->json(['success' => false, 'message' => 'You must be logged in!']);
    }

    $request->validate(['review' => 'required|string|max:5000']);
    $user = auth()->user();
    $reviewText = $request->review;

    // Bad words check
    $badWords = ['sala', 'chor', 'pagal'];
    if ($this->containsBadWords($reviewText, $badWords)) {
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\BadWordAttempted($user, $reviewText));
        }
        return response()->json([
            'success' => false,
            'message' => 'Your review contains inappropriate language and cannot be submitted.'
        ]);
    }

    // Find or create review
    $review = Review::firstOrNew([
        'movie_id' => $movieId,
        'user_id'  => $user->id,
    ]);

    if ($review->exists && !$request->update) {
        return response()->json(['success' => false, 'message' => 'You already submitted a review!']);
    }

    // Save review
    $review->review = $reviewText;
    $review->save();

    // Notify admins
    $admins = \App\Models\User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new \App\Notifications\NewReviewSubmitted($review));
    }

    // Notify user with a simple inline message
    $user->notify(new class($review) extends \Illuminate\Notifications\Notification {
        use \Illuminate\Bus\Queueable;
        public $review;
        public function __construct($review) { $this->review = $review; }
        public function via($notifiable) { return ['database']; }
        public function toArray($notifiable) {
            return [
                'message' => 'A new review is submitted by you for "' . $this->review->movie->title . '".',
                'review_id' => $this->review->id,
                'movie_id' => $this->review->movie_id,
            ];
        }
    });

    $message = $request->update ? 'Review updated successfully!' : 'Review submitted successfully!';
    return response()->json(['success' => true, 'message' => $message]);
}


// Helper function for checking bad words inside the same controller
private function containsBadWords($text, $badWords)
{
    foreach ($badWords as $word) {
        if (stripos($text, $word) !== false) {
            return true;
        }
    }
    return false;
}


 //admin review shownamespace App\Http\Controllers;


    public function selectedmoviereview($id)
    {
        // Find the movie
        $movie = Movie::findOrFail($id);

        // Fetch reviews for that movie, including user info
        $reviews = Review::with('user')
                        ->where('movie_id', $id)
                        ->latest()
                        ->get();

        // Reuse your styled admin blade
        return view('admin.adminblade.reviewshow', compact('movie', 'reviews'));
    }



}
