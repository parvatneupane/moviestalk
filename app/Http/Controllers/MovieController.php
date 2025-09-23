<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\MovieAddedNotification;
use Illuminate\Support\Facades\Storage;
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
    $query = Movie::with('categories'); // ✅ many-to-many relation

// Filter by category
if ($request->has('category') && $request->category != 'all') {
    $query->whereHas('categories', function($q) use ($request) {
        $q->where('categories.id', $request->category); // table name fixed
    });
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
        return view('user.search', compact('movies','searchTerm'));
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
        'poster'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
        'trailer_url'    => 'nullable|url',  
        'release_year'   => 'nullable|integer',
        'category'       => 'required|array',
        'category.*'     => 'exists:categories,id'
    ]);

    // 2. Create movie
    $movie = new Movie();
    $movie->title          = $request->title;
    $movie->description    = $request->description;
    $movie->release_date   = $request->release_date;
    $movie->runtime        = $request->runtime;
    $movie->director       = $request->director;
    $movie->content_rating = $request->content_rating;
    $movie->writer         = $request->writer;
    $movie->production     = $request->production;
    $movie->cast           = $request->cast;
    $movie->trailer_url    = $request->trailer_url; // ✅ use trailer_url
    $movie->release_year   = $request->release_year;
    $movie->is_featured    = $request->has('is_featured');
    $movie->is_trending    = $request->has('is_trending');

    // 3. Poster upload
    if ($request->hasFile('poster')) {
        $posterPath = $request->file('poster')->store('posters', 'public');
        $movie->poster = $posterPath;
    }

    // 4. Save movie
    $movie->save();

    // 5. Attach categories
    if (!empty($request->category)) {
        $movie->categories()->attach($request->category);
    }

    // 6. Notify users
    $users = User::all();
    foreach ($users as $user) {
        $user->notify(new MovieAddedNotification($movie));
    }

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
        'cast'           => 'nullable|string',
        'poster'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
        'trailer'        => 'nullable|url',  
        'release_year'   => 'nullable|integer',
        'category'       => 'required|array',
        'category.*'     => 'exists:categories,id'
    ]);

    // 2. Find movie
    $movie = Movie::findOrFail($id);

    // 3. Update movie details
    $movie->title          = $request->title;
    $movie->description    = $request->description;
    $movie->release_date   = $request->release_date;
    $movie->runtime        = $request->runtime;
    $movie->director       = $request->director;
    $movie->content_rating = $request->content_rating;
    $movie->writer         = $request->writer;
    $movie->production     = $request->production;
    $movie->cast           = $request->cast;
    $movie->trailer_url    = $request->trailer; // update trailer URL
    $movie->release_year   = $request->release_year;
    $movie->is_featured    = $request->has('is_featured');
    $movie->is_trending    = $request->has('is_trending');

    // 4. Handle poster upload
    if ($request->hasFile('poster')) {
        // Delete old poster if exists
        if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
            Storage::disk('public')->delete($movie->poster);
        }
        // Store new poster
        $posterPath = $request->file('poster')->store('posters', 'public');
        $movie->poster = $posterPath;
    }

    // 5. Save movie
    $movie->save();

    // 6. Sync categories (detach old ones and attach new ones)
    if (!empty($request->category)) {
        $movie->categories()->sync($request->category);
    }

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
    // Load movie with categories, reviews, and ratings
    $movie = Movie::with('categories', 'reviews.user', 'ratings')->findOrFail($movieId);

    // Get the user's previous rating (if any)
    $userrating = auth()->check()
        ? Rating::where('movie_id', $movie->id)
                ->where('user_id', auth()->id())
                ->value('rating')
        : null;

    // Get all reviews for this movie (latest first)
    $reviews = $movie->reviews()->with('user')->latest()->get();

    // Preload all ratings keyed by user_id to avoid per-review queries
    $allRatings = $movie->ratings()->get()->keyBy('user_id');

    // Split reviews: latest 3 for immediate display, rest for "See More"
    $latestReviews = $reviews->take(3);
    $remainingReviews = $reviews->slice(3)->map(function($review) use ($allRatings) {
        return [
            'id' => $review->id,
            'review' => $review->review,
            'created_at' => $review->created_at->toDateTimeString(),
            'user' => [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'avatar' => $review->user->avatar
                    ? asset('storage/' . $review->user->avatar)
                    : asset('images/default-avatar.png'),
            ],
            'user_rating' => $allRatings->has($review->user->id)
                ? $allRatings[$review->user->id]->rating
                : null,
        ];
    })->values(); // reset keys for JSON

    // Calculate average rating
    $rating = $movie->ratings()->avg('rating');

    // Prepare review counts per star for chart
    $reviewCounts = [];
    for ($i = 1; $i <= 5; $i++) {
        $reviewCounts[$i] = $movie->ratings()->where('rating', $i)->count();
    }

    // Get similar movies based on shared categories
    $categoryIds = $movie->categories->pluck('id');
    $similarMovies = Movie::whereHas('categories', function($q) use ($categoryIds) {
                                $q->whereIn('categories.id', $categoryIds);
                            })
                            ->where('id', '!=', $movieId)
                            ->with('categories')
                            ->take(4)
                            ->get();

    // Check if the movie is in the user's watchlist
    $inWatchlist = auth()->check()
        ? Watchlist::where('user_id', auth()->id())
                   ->where('movie_id', $movieId)
                   ->exists()
        : false;

    return view('user.moviedetail', compact(
        'movie',
        'latestReviews',
        'remainingReviews',
        'similarMovies',
        'rating',
        'inWatchlist',
        'userrating',
        'reviewCounts',
        'allRatings'
    ));
}


// Add this to MovieController
public function ratingCounts($movieId)
{
    $movie = Movie::findOrFail($movieId);

    $ratingCounts = [];
    for ($i = 1; $i <= 5; $i++) {
        $ratingCounts[$i] = $movie->ratings()->where('rating', $i)->count();
    }

    return response()->json($ratingCounts);
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

   
    \DB::table('movies')
        ->where('id', $id)
        ->update(['rating' => round($averageRating, 1)]);

    return response()->json([
        'success' => true,
        'rating' => $request->rating,
        'averageRating' => $averageRating
    ]);
}

public function loadMoreReviews(Movie $movie, Request $request)
{
    $offset = (int) $request->query('offset', 0);

    // Load next 2 reviews with user
    $reviews = $movie->reviews()->with('user')->latest()->skip($offset)->take(2)->get();

    // Preload ratings keyed by user_id so the partial can show star ratings
    $allRatings = $movie->ratings()->get()->keyBy('user_id');

    // Return the partial (pass both reviews & allRatings)
    return view('user.showmorereview', compact('reviews', 'allRatings'))->render();
}



}