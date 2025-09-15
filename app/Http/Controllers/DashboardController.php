<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Define the method that handles the dashboard view
  
 public function index()
{
    if (Auth::user()->role != 'admin') {
        abort(403, 'Unauthorized action.');
    }

    $users = User::count();
    $movies = Movie::count();
    //$reviews = Review::count();  // if you have a Review model
    $user = Auth::user();

    return view('admin.adminblade.dashboard', compact('users', 'movies', 'user'));
}


}
