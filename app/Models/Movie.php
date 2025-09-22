<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';

    protected $fillable = [
        'title',
        'description',
        'release_date',
        'runtime',
        'director',
        'content_rating',
        'writer',
        'production',
        'cast',
        'poster',
        'trailer_url',
        'release_year',
        'is_featured',
        'is_trending'
        // 🚨 removed 'category_id'
    ];

    // 🔹 Many-to-many relationship with categories

public function categories() {
    return $this->belongsToMany(Category::class, 'category_movie', 'movie_id', 'category_id');
}

// Movie.php
public function category()
{
    return $this->belongsTo(Category::class, 'category_id'); // 'category_id' should match your column
}


    public function users()
    {
        return $this->belongsToMany(User::class, 'movie_user');  
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function watchlist() 
    {
        return $this->hasMany(Watchlist::class);
    }

    public function getPosterUrlAttribute()
    {
        if ($this->poster) {
            return asset('storage/' . $this->poster);
        }
        return asset('images/default-movie-poster.jpg');
    }

    protected static function booted()
    {
        static::deleting(function ($movie) {
            if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
                Storage::disk('public')->delete($movie->poster);
            }
        });
    }

    // In Movie.php model
// Movie.php
public function getYoutubeIdAttribute()
{
    if (!$this->trailer_url) return null;

    $url = $this->trailer_url;

    // Standard URL: youtube.com/watch?v=VIDEO_ID
    if (preg_match("/v=([a-zA-Z0-9_-]+)/", $url, $matches)) {
        return $matches[1];
    }

    // Short URL: youtu.be/VIDEO_ID
    if (preg_match("/youtu\.be\/([a-zA-Z0-9_-]+)/", $url, $matches)) {
        return $matches[1];
    }

    return null; // if URL is invalid
}


}
