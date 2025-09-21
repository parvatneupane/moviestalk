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
    public function submitReview(Request $request, $movieId){
    if(!auth()->check()){
        return response()->json(['success'=>false,'message'=>'You must be logged in!']);
    }

    $request->validate(['review'=>'required|string|max:5000']);
    $user = auth()->user();
    $reviewText = $request->review;

    // 1. Define bad words to check
    $badWords = ['sala', 'chor', 'pagal'];

    // 2. Check if review contains bad words
    if ($this->containsBadWords($reviewText, $badWords)) {
        // 3. Notify all admins about the bad word attempt
        $admins = \App\Models\User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\BadWordAttempted($user, $reviewText));
        }

        // 4. Return error to user immediately
        return response()->json([
            'success' => false,
            'message' => 'Your review contains inappropriate language and cannot be submitted.'
        ]);
    }

    // 5. Find existing review or create new one
    $review = Review::firstOrNew([
        'movie_id' => $movieId,
        'user_id'  => $user->id,
    ]);

    // 6. Prevent overwrite unless user confirmed update
    if ($review->exists && !$request->update) {
        return response()->json(['success' => false, 'message' => 'You already submitted a review!']);
    }

    // 7. Save or update review
    $review->review = $reviewText;
    $review->save();

    // 8. Notify admins about new or updated review
    $admins = \App\Models\User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new \App\Notifications\NewReviewSubmitted($review));
    }

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
