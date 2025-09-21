<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

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

    // Add this:
 public function replies()
{
    return $this->hasMany(FeedbackReply::class); // adjust model name
}

}
