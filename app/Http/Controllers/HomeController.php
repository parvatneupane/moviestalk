<?php

namespace App\Http\Controllers;
use App\Models\Movie;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $trendingMovies = Movie::orderBy('release_date', 'desc')
            ->take(8)
            ->get();
        $featuredMovies = Movie::with('category')->orderBy('rating', 'desc')->take(6)->get();
        $categories = Category::withCount('movies')->get();
        
        return view('home', compact('featuredMovies', 'categories','trendingMovies'));
    }
}