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
        $userId = auth()->id();

        $review = Review::firstOrNew([
            'movie_id' => $movieId,
            'user_id'  => $userId
        ]);

        // Prevent overwrite unless user confirmed update
        if($review->exists && !$request->update){
            return response()->json(['success'=>false,'message'=>'You already submitted a review!']);
        }

        $review->review = $request->review;
        $review->save();

        $message = $request->update ? 'Review updated successfully!' : 'Review submitted successfully!';
        return response()->json(['success'=>true,'message'=>$message]);
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
