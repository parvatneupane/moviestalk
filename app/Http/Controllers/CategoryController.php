<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController
{
       // Show the add genre form
public function addGenreForm() {
    
    return view('admin.adminblade.addgenres'); // Create this Blade file
}

// Show all genres
public function viewgenres() {
    $genres = \App\Models\Category::orderBy('id', 'desc')->get(); // Fetch all genres
    return view('admin.adminblade.genresview', compact('genres'));
}

public function deleteGenre($id)
{
    // Find the genre by ID, or fail with 404
    $genres = \App\Models\Category::findOrFail($id);

    // Delete the genre
    $genres->delete();

    // Redirect back with success message
    return redirect('admin/genres')->with('success', 'Genre deleted successfully!');
}



// Insert the genre into database
public function insertgenres(Request $request) {
    $request->validate([
        'name' => 'required|unique:categories,name',
    ]);

    \App\Models\Category::create([
        'name' => $request->name,
    ]);

    return redirect('admin/genres')->with('success', 'Genre added successfully');
}

}
