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
     */
    public function index(Request $request)
    {
        $query = Movie::with('categories'); // ✅ many-to-many relation

        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('categories', function ($q) use ($request) {
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
     * Search movies.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');

        $movies = Movie::where('title', 'like', '%' . $searchTerm . '%')
            ->orWhere('description', 'like', '%' . $searchTerm . '%')
            ->get();

        return view('user.search', compact('movies', 'searchTerm'));
    }

    /**
     * Add or remove a movie from the user's watchlist.
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
            $watchlist->delete(); // remove
        } else {
            Watchlist::create([
                'user_id' => $user->id,
                'movie_id' => $movieId,
            ]);
        }

        return back();
    }

    /**
     * Backend: movies list.
     */
    public function moviesdata()
    {
        $movies = Movie::withAvg('ratings', 'rating')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($movies as $movie) {
            $ids = explode(',', $movie->categories_id);
            $names = Category::whereIn('id', $ids)->pluck('name')->toArray();

            $movie->category_names = $names;
            $movie->average_rating = $movie->ratings_avg_rating
                ? number_format($movie->ratings_avg_rating, 2)
                : null;
        }

        return view('admin.adminblade.movies', compact('movies'));
    }

    /**
     * Backend: add movie.
     */
    public function insertmovies(Request $request)
    {
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
        $movie->trailer_url    = $request->trailer_url;
        $movie->release_year   = $request->release_year;
        $movie->is_featured    = $request->has('is_featured');
        $movie->is_trending    = $request->has('is_trending');

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
            $movie->poster = $posterPath;
        }

        $movie->save();

        if (!empty($request->category)) {
            $movie->categories()->attach($request->category);
        }

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new MovieAddedNotification($movie));
        }

        return redirect('/admin/movies')->with('success', 'Movie added successfully!');
    }

    public function addshow()
    {
        $generes = Category::get();
        return view('admin.adminblade.addmovies', compact('generes'));
    }

    /**
     * Show form to edit movie.
     */
    public function edit($id)
    {
        $generes = Category::get();
        $movie = Movie::findOrFail($id);
        $selectedGenres = explode(',', $movie->categories_id);

        return view('admin.adminblade.updatemovies', compact('movie', 'generes', 'selectedGenres'));
    }

    /**
     * Update movie.
     */
    public function update(Request $request, $id)
    {
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

        $movie = Movie::findOrFail($id);

        $movie->title          = $request->title;
        $movie->description    = $request->description;
        $movie->release_date   = $request->release_date;
        $movie->runtime        = $request->runtime;
        $movie->director       = $request->director;
        $movie->content_rating = $request->content_rating;
        $movie->writer         = $request->writer;
        $movie->production     = $request->production;
        $movie->cast           = $request->cast;
        $movie->trailer_url    = $request->trailer;
        $movie->release_year   = $request->release_year;
        $movie->is_featured    = $request->has('is_featured');
        $movie->is_trending    = $request->has('is_trending');

        if ($request->hasFile('poster')) {
            if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
                Storage::disk('public')->delete($movie->poster);
            }
            $posterPath = $request->file('poster')->store('posters', 'public');
            $movie->poster = $posterPath;
        }

        $movie->save();

        if (!empty($request->category)) {
            $movie->categories()->sync($request->category);
        }

        return redirect('/admin/movies')->with('success', 'Movie updated successfully!');
    }

    /**
     * Delete movie.
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return redirect('admin/movies')->with('success', 'Movie deleted successfully');
    }

    /**
     * Show details of a movie.
     */
    public function show($movieId)
    {
        $movie = Movie::with('categories', 'reviews.user', 'ratings')->findOrFail($movieId);

        $userrating = auth()->check()
            ? Rating::where('movie_id', $movie->id)->where('user_id', auth()->id())->value('rating')
            : null;

        $reviews = $movie->reviews()->with('user')->latest()->get();
        $allRatings = $movie->ratings()->get()->keyBy('user_id');

        $latestReviews = $reviews->take(3);
        $remainingReviews = $reviews->slice(3)->map(function ($review) use ($allRatings) {
            return [
                'id'         => $review->id,
                'review'     => $review->review,
                'created_at' => $review->created_at->toDateTimeString(),
                'user'       => [
                    'id'     => $review->user->id,
                    'name'   => $review->user->name,
                    'avatar' => $review->user->avatar
                        ? asset('storage/' . $review->user->avatar)
                        : asset('images/default-avatar.png'),
                ],
                'user_rating' => $allRatings->has($review->user->id)
                    ? $allRatings[$review->user->id]->rating
                    : null,
            ];
        })->values();

        $rating = $movie->ratings()->avg('rating');

        $reviewCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $reviewCounts[$i] = $movie->ratings()->where('rating', $i)->count();
        }

        $categoryIds = $movie->categories->pluck('id');
        $similarMovies = Movie::whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        })
            ->where('id', '!=', $movieId)
            ->with('categories')
            ->take(4)
            ->get();

        $inWatchlist = auth()->check()
            ? Watchlist::where('user_id', auth()->id())->where('movie_id', $movieId)->exists()
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
                'user_id'  => auth()->id(),
                'movie_id' => $id
            ],
            [
                'rating' => $request->rating
            ]
        );

        $averageRating = Rating::where('movie_id', $id)->avg('rating');

        \DB::table('movies')
            ->where('id', $id)
            ->update(['rating' => round($averageRating, 1)]);

        return response()->json([
            'success'       => true,
            'rating'        => $request->rating,
            'averageRating' => $averageRating
        ]);
    }

    public function loadMoreReviews(Movie $movie, Request $request)
    {
        $offset = (int) $request->query('offset', 0);

        $reviews = $movie->reviews()->with('user')->latest()->skip($offset)->take(2)->get();
        $allRatings = $movie->ratings()->get()->keyBy('user_id');

        return view('user.showmorereview', compact('reviews', 'allRatings'))->render();
    }
}
