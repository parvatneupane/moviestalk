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
public function submitReview(Request $request, $movieId)
{
    // Ensure the user is logged in
    if (!auth()->check()) {
        return redirect()->route('user.login.form')
                         ->with('error', 'You must be logged in to submit a review.');
    }

    // Validate the review input
    $request->validate([
        'review' => 'required|string|max:5000',
    ]);

    $userId = auth()->id();

    // Get the user's existing rating for this movie (from 'rating' table)
    $userRating = \DB::table('rating')
                     ->where('movie_id', $movieId)
                     ->where('user_id', $userId)
                     ->value('rating'); // returns null if no rating exists

    // Save the review including the rating if it exists
    \App\Models\Review::create([
        'movie_id' => $movieId,
        'user_id'  => $userId,
        'review'   => $request->review,
        'rating'   => $userRating, // will be null if user hasn't rated
    ]);

    return redirect()->back()->with('success', 'Review submitted successfully!');
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
