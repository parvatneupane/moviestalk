<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Movie;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{

// Helper to get stats
private function getStats($user)
{
    $allWatchlist = $user->watchlist()->get();
    return [
        'total' => $allWatchlist->count(),
        'watched' => $allWatchlist->where('watched', true)->count(),
        'unwatched' => $allWatchlist->where('watched', false)->count(),
    ];
}

// Toggle Add/Remove
public function toggle($movieId)
{
    $user = auth()->user();

    $watchlist = Watchlist::where('user_id', $user->id)
                          ->where('movie_id', $movieId)
                          ->first();

    if ($watchlist) {
        $watchlist->delete();
        $status = 'removed';
    } else {
        Watchlist::create([
            'user_id' => $user->id,
            'movie_id' => $movieId,
            'watched' => false
        ]);
        $status = 'added';
    }

    return response()->json([
        'status' => $status,
        'stats' => $this->getStats($user)
    ]);
}

// Mark as watched/unwatched
public function markWatched($id)
{
    $user = auth()->user();
    $watchlist = Watchlist::findOrFail($id);
    $watchlist->watched = !$watchlist->watched;
    $watchlist->save();

    return response()->json([
        'status' => 'updated',
        'watched' => $watchlist->watched,
        'stats' => $this->getStats($user)
    ]);
}

// Index (AJAX returns movies grid)
public function index(Request $request)
{
    $user = auth()->user();
    $filter = $request->query('filter', 'all');

    $watchlistQuery = Watchlist::with('movie')->where('user_id', $user->id);

    if ($filter === 'watched') {
        $watchlistQuery->where('watched', true);
    } elseif ($filter === 'unwatched') {
        $watchlistQuery->where('watched', false);
    }

    $watchlist = $watchlistQuery->latest()->get();
    $stats = $this->getStats($user);

    if ($request->ajax()) {
        return view('user.mylist_movies', compact('watchlist'))->render();
    }

    return view('user.mylist', compact('watchlist', 'stats', 'filter'));
}
    // Show watchlist page

    public function add($movieId)
    {
        $user = Auth::user();

        // Check if movie exists
        $movie = Movie::findOrFail($movieId);

        // Check if already in watchlist
        $watchlistItem = Watchlist::where('user_id', $user->id)
                                  ->where('movie_id', $movieId)
                                  ->first();

        if ($watchlistItem) {
            return response()->json(['status' => 'exists', 'message' => 'Movie already in your watchlist']);
        }

        // Add to watchlist
        Watchlist::create([
            'user_id' => $user->id,
            'movie_id' => $movieId,
            'watched' => false,
        ]);

        return response()->json(['status' => 'added', 'message' => 'Movie added to watchlist']);
    }
}
