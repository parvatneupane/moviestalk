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

    $watchlistQuery = Watchlist::with('movie')->where('user_id', $user->id);

    if ($filter === 'watched') {
        $watchlistQuery->where('watched', true);
    } elseif ($filter === 'unwatched') {
        $watchlistQuery->where('watched', false);
    }

    $watchlist = $watchlistQuery->latest()->get();

    $allWatchlist = $user->watchlist()->with('movie')->get();
    $stats = [
        'total' => $allWatchlist->count(),
        'watched' => $allWatchlist->where('watched', true)->count(),
        'unwatched' => $allWatchlist->where('watched', false)->count(),
    ];

    // If AJAX request, return only the movies grid
    if ($request->ajax()) {
        return view('user.mylist_movies', compact('watchlist'))->render();
    }

    return view('user.mylist', compact('watchlist', 'stats', 'filter'));
}




 

}