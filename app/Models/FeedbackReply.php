<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackReply extends Model
{
    protected $table = 'feedback_replies';
   protected $fillable = ['feedback_id', 'user_id', 'reply', 'is_admin'];


    public function feedback()
    {
        return $this->belongsTo(\App\Models\Feedback::class);
    }

    // Admin (user) who replied
    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}


