<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyListController extends Controller
{

    public function index(Request $request)
{
    $user = auth()->user();
    $filter = $request->query('filter', 'all');
    $sort = $request->query('sort', 'recent');

    $watchlistQuery = Watchlist::with('movie')->where('user_id', $user->id);

    if ($filter === 'watched') {
        $watchlistQuery->where('watched', true);
    } elseif ($filter === 'unwatched') {
        $watchlistQuery->where('watched', false);
    }

    // Apply sorting
    if ($sort === 'recent') {
        $watchlistQuery->orderBy('created_at', 'desc'); // recently added first
    } elseif ($sort === 'rating') {
        // Join movie table to sort by rating
        $watchlistQuery->join('movies', 'watchlists.movie_id', '=', 'movies.id')
                       ->orderBy('movies.rating', 'desc')
                       ->select('watchlists.*'); // Important to select watchlist columns
    } elseif ($sort === 'title') {
        $watchlistQuery->join('movies', 'watchlists.movie_id', '=', 'movies.id')
                       ->orderBy('movies.title', 'asc')
                       ->select('watchlists.*');
    }

    $watchlist = $watchlistQuery->get();

    $allWatchlist = $user->watchlist()->with('movie')->get();
    $stats = [
        'total' => $allWatchlist->count(),
        'watched' => $allWatchlist->where('watched', true)->count(),
        'unwatched' => $allWatchlist->where('watched', false)->count(),
    ];

    if ($request->ajax()) {
        return view('user.mylist_movies', compact('watchlist'))->render();
    }

    return view('user.mylist', compact('watchlist', 'stats', 'filter', 'sort'));
}


public function remove(Request $request, $movieId)
{
    $user = auth()->user();

    $watchlistItem = Watchlist::where('user_id', $user->id)
        ->where('movie_id', $movieId)
        ->first();

    if (!$watchlistItem) {
        return response()->json(['status' => 'not_found', 'message' => 'Movie not in your watchlist']);
    }

    $watchlistItem->delete();

    return response()->json([
        'status' => 'removed',
        'message' => 'Movie removed from watchlist'
    ]);
}

public function add(Request $request, $movieId)
{
    $user = auth()->user();

    $exists = Watchlist::where('user_id', $user->id)
        ->where('movie_id', $movieId)
        ->first();

    if ($exists) {
        return response()->json([
            'status' => 'exists',
            'message' => 'Movie already in your watchlist'
        ]);
    }

    Watchlist::create([
        'user_id' => $user->id,
        'movie_id' => $movieId,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Movie added to your watchlist'
    ]);
}


 

}