<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyListController extends Controller
{
    public function index()
    {
        $watchlist = Watchlist::with('movie.category')
            ->where('user_id', Auth::id())
            ->get();
            
        $stats = [
            'total' => $watchlist->count(),
            'watched' => $watchlist->where('watched', true)->count(),
            'unwatched' => $watchlist->where('watched', false)->count(),
        ];
         $inwatchlist=0;
        return view('user.mylist', compact('watchlist', 'stats'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
        ]);
        
        // Check if already in watchlist
        $existing = Watchlist::where('user_id', Auth::id())
            ->where('movie_id', $request->movie_id)
            ->first();
            
        if ($existing) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie already in your watchlist!'
                ]);
            }
            return back()->with('info', 'Movie already in your watchlist!');
        }
        
        $watchlist = Watchlist::create([
            'user_id' => Auth::id(),
            'movie_id' => $request->movie_id,
            'watched' => false,
            'progress' => 0
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Movie added to your watchlist!'
            ]);
        }
        
        return back()->with('success', 'Movie added to your watchlist!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'watched' => 'sometimes|boolean',
            'progress' => 'sometimes|integer|min:0|max:100',
        ]);
        
        $watchlist = Watchlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $watchlist->update($request->only(['watched', 'progress']));
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Watchlist updated!',
                'watched' => $watchlist->watched
            ]);
        }
        
        return back()->with('success', 'Watchlist updated!');
    }
    
    public function destroy($id)
    {
        $watchlist = Watchlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $watchlist->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Movie removed from your watchlist!'
            ]);
        }
        
        return back()->with('success', 'Movie removed from your watchlist!');
    }
    
    // New method to toggle watched status
    public function toggleWatched($id)
    {
        $watchlist = Watchlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $watchlist->update([
            'watched' => !$watchlist->watched
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated!',
            'watched' => $watchlist->watched
        ]);
    }
    
    // New method to update progress
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);
        
        $watchlist = Watchlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $watchlist->update([
            'progress' => $request->progress,
            'watched' => $request->progress == 100 ? true : $watchlist->watched
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Progress updated!',
            'progress' => $watchlist->progress,
            'watched' => $watchlist->watched
        ]);
    }
}