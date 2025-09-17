<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    /**
     * Display a listing of the movies with filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Movie::with('category');

        // Filter by category
      if ($request->has('category') && $request->category != 'all') {
    $query->where('category_id', $request->category);
}

        

        // Filter by year
        if ($request->has('year') && $request->year != 'all') {
            $query->where('release_year', $request->year);
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating != 'all') {
            $query->where('rating', '>=', $request->rating);
        }

        // Sort results
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('release_year', 'desc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $movies = $query->paginate(12);
        $categories = Category::all();
        $years = Movie::select('release_year')->distinct()->orderBy('release_year', 'desc')->get();

        return view('user.movie', compact('movies', 'categories', 'years'));
    }

    /**
     * Show the movie details page.
     *
     * @param int $movieId
     * @return \Illuminate\View\View
     */
  
public function search(Request $request)
    {
        // Get search term from the request
        $searchTerm = $request->input('query');

        // Perform the search query on the movies table
        $movies = Movie::where('title', 'like', '%' . $searchTerm . '%')
                       ->orWhere('description', 'like', '%' . $searchTerm . '%')
                       ->get();

        // Return a view with the search results
        return view('user.search', compact('movies'));
    }
    /**
     * Add or remove a movie from the user's watchlist.
     *
     * @param int $movieId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleWatchlist($movieId)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $user = Auth::user();
        $watchlist = Watchlist::where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->first();

        if ($watchlist) {
            // Remove from watchlist
            $watchlist->delete();
        } else {
            // Add to watchlist
            Watchlist::create([
                'user_id' => $user->id,
                'movie_id' => $movieId,
            ]);
        }

        return back();
    }

    /**
     * Submit a review for a movie.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $movieId
     * @return \Illuminate\Http\RedirectResponse
     */
  

    /**
     * Submit or update the rating for a movie.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $movieId
     * @return \Illuminate\Http\RedirectResponse
     */



    //this is backend movies view
public function moviesdata()
{
    // Fetch movies with avg rating and order by latest
    $movies = \App\Models\Movie::withAvg('ratings', 'rating')
        ->orderBy('id', 'desc')
        ->get();

    foreach ($movies as $movie) {
        // Convert "3,4,5" into [3,4,5]
        $ids = explode(',', $movie->categories_id);

        // Fetch category names from DB
        $names = \App\Models\Category::whereIn('id', $ids)->pluck('name')->toArray();

        // Add category names as extra property
        $movie->category_names = $names;

        // Add rating as short variable
        $movie->average_rating = $movie->ratings_avg_rating
            ? number_format($movie->ratings_avg_rating, 2)
            : null;
    }

    return view('admin.adminblade.movies', compact('movies'));
}



//this is backend addmovies 
public function insertmovies(Request $request)
{
     // 1. Validation
    $request->validate([
        'title'          => 'required|string|max:255',
        'description'    => 'required|string',
        'release_date'   => 'required|date',
        'runtime'        => 'required|integer|min:1',
        'director'       => 'nullable|string|max:255',
        'content_rating' => 'nullable|string|max:50',
        'writer'         => 'nullable|string|max:255',
        'production'     => 'nullable|string|max:255',          
        'cast'           => 'nullable|string',
        'poster'         => 'required|image|mimes:jpg,jpeg,png|max:2048', // poster must be image
        'trailer'        => 'nullable|url',
        'release_year'   => 'nullable|integer',
        'category'    => 'required',
    ]);
     $movie = new Movie;

    // 2. Assign fields
    $movie->title          = $request->title;
    $movie->description    = $request->description;
    $movie->release_date   = $request->release_date;
    $movie->runtime        = $request->runtime;
    $movie->director       = $request->director;
    $movie->content_rating = $request->content_rating;
    $movie->writer         = $request->writer;
    $movie->production     = $request->production;

    // store genres as comma separated
    $movie->category_id =  $request->category; // if you are mapping to categories

    $movie->cast = $request->cast;

    // 3. Poster file upload
    if ($request->hasFile('poster')) {

    // Delete old poster if it exists and isn't default/fallback image
    if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
        Storage::disk('public')->delete($movie->poster);
    }

    // Store new poster
    $posterPath = $request->file('poster')->store('posters', 'public');

    // Save new path
    $movie->poster = $posterPath;  // Just "posters/filename.jpg"
}

    $movie->trailer_url  = $request->trailer;
    $movie->release_year = $request->release_year;
$movie->is_featured = $request->has('is_featured');
$movie->is_trending = $request->has('is_trending');

    $movie->save();

    return redirect('/admin/movies')->with('success', 'Movie added successfully!');
}


public function addshow(){
    $generes = Category::get();
    
    return view('admin.adminblade.addmovies',compact('generes'));
}
   



//form update show movie
    public function edit($id)
    {
        $generes = Category::get();
        $movie = Movie::findOrFail($id);
        $selectedGenres = explode(',', $movie->categories_id);

        return view('admin.adminblade.updatemovies', compact('movie','generes', 'selectedGenres'));
    }

//update movie
public function update(Request $request, $id)
{
    // 1. Validation
    $request->validate([
        'title'          => 'required|string|max:255',
        'description'    => 'required|string',
        'release_date'   => 'required|date',
        'runtime'        => 'required|integer|min:1',
        'director'       => 'nullable|string|max:255',
        'content_rating' => 'nullable|string|max:50',
        'writer'         => 'nullable|string|max:255',
        'production'     => 'nullable|string|max:255',
        'genres'         => 'required|array',          // expecting array from form
        'cast'           => 'nullable|string',
        'poster'         => 'required|image|mimes:jpg,jpeg,png|max:2048', // poster must be image
        'trailer'        => 'nullable|url',
        'release_year'   => 'nullable|integer',
        'category_id'    => 'required|array',
    ]);
    // Find movie
    $movie = Movie::findOrFail($id);

   // 2. Assign fields
    $movie->title          = $request->title;
    $movie->description    = $request->description;
    $movie->release_date   = $request->release_date;
    $movie->runtime        = $request->runtime;
    $movie->director       = $request->director;
    $movie->content_rating = $request->content_rating;
    $movie->writer         = $request->writer;
    $movie->production     = $request->production;

    // store genres as comma separated
    $movie->genres = implode(',', $request->genres); 
    $movie->category_id = implode(',', $request->genres); // if you are mapping to categories

    $movie->cast = $request->cast;

    // 3. Poster file upload
    if ($request->hasFile('poster')) {

    // Delete old poster if it exists and isn't default/fallback image
    if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
        Storage::disk('public')->delete($movie->poster);
    }

    // Store new poster
    $posterPath = $request->file('poster')->store('posters', 'public');

    // Save new path
    $movie->poster = $posterPath;  // Just "posters/filename.jpg"
}


    $movie->trailer_url  = $request->trailer;
    $movie->release_year = $request->release_year;
$movie->is_featured = $request->has('is_featured');
$movie->is_trending = $request->has('is_trending');

    $movie->save();

    return redirect('/admin/movies')->with('success', 'Movie updated successfully!');
}
// delete movies
public function destroy($id)
{
    $movie = Movie::findOrFail($id);
    $movie->delete();
    return redirect('admin/movies')->with('success', 'Movie deleted successfully');

}

//show review of particular movie

 // Show reviews for a specific movie
// Show reviews for a specific movie 


  public function show($movieId)
{
    // Get the movie details
    $movie = Movie::with('category')->findOrFail($movieId);

    // Get the user's previous rating (if any)
    $userrating = Rating::where('movie_id', $movie->id)
                        ->where('user_id', auth()->id())
                        ->value('rating');

    // Get reviews for this movie with user relation
    $reviews = Review::with('user')
                     ->where('movie_id', $movieId)
                     ->latest()
                     ->get();

    // Calculate average rating for the movie
    $rating = Rating::where('movie_id', $movieId)->avg('rating');

    // Get similar movies based on the same category
    $similarMovies = Movie::where('category_id', $movie->category_id)
                          ->where('id', '!=', $movieId)
                          ->orderBy('rating', 'desc')
                          ->take(4)
                          ->get();

    // Check if the movie is in the user's watchlist
    $inWatchlist = false;
    if (Auth::check()) {
        $inWatchlist = Watchlist::where('user_id', Auth::id())
                                ->where('movie_id', $movieId)
                                ->exists();
    }

    return view('user.moviedetail', compact('movie', 'reviews', 'similarMovies', 'rating', 'inWatchlist', 'userrating'));
}





public function rating($id, Request $request)
{
    $request->validate([
        'rating' => 'required|numeric|min:1|max:5'
    ]);

    Rating::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'movie_id' => $id
        ],
        [
            'rating' => $request->rating
        ]
    );

    // Get updated average rating
    $averageRating = Rating::where('movie_id', $id)->avg('rating');

    return response()->json([
        'success' => true,
        'rating' => $request->rating,
        'averageRating' => $averageRating
    ]);
}




}