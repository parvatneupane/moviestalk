<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    // Explicitly define the table name
    protected $table = 'feedbacks';

    // Allow mass assignment for these fields
protected $fillable = [
    'user_id',
    'name',
    'email',
    'subject',
    'message',
];


 public function user()
{
    return $this->belongsTo(User::class);
}

}
