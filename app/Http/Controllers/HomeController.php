<?php

namespace App\Http\Controllers;
use App\Models\Movie;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   
  
    public function index()
{
    return view('home', [
        'featuredMovies' => Movie::where('is_featured', true)->take(5)->get(),
        'trendingMovies' => Movie::where('is_trending', true)->take(8)->get(),
        'latestMovies'   => Movie::orderBy('release_year', 'desc')->take(8)->get(),
        'topRatedMovies' => Movie::orderBy('rating', 'desc')->take(5)->get(), // <-- add this line
        'categories'     => Category::withCount('movies')->get(),
    ]);

    
}

}

    
