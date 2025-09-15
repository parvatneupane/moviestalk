<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    // Specify the table name if it's different from 'watchlists'
    protected $table = 'watchlist'; // or 'watchlists' depending on your actual table name

    protected $fillable = [
        'user_id',
        'movie_id',
        'watched',
        'progress'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}