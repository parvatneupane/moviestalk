<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
'category_id',
'release_year',

    ];
  public function category()
    {
        return $this->belongsTo(Category::class);
    }


public function users()
{
    return $this->belongsToMany(User::class, 'movie_user');  // Assuming 'movie_user' is the pivot table
}

    
    public function ratings()
{
    return $this->hasMany(Rating::class);  // Assuming you have a Rating model
}

    public function reviews()
{
    return $this->hasMany(Review::class);  // Assuming you have a Rating model
}

  public function watchlist() {
    return $this->hasMany(Watchlist::class);
}

public function getPosterUrlAttribute()
{
    if ($this->poster) {
        return asset('storage/' . $this->poster);
    }
    
   
    return asset('images/default-movie-poster.jpg');
}

}