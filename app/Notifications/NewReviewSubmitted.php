<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewSubmitted extends Notification
{
    use Queueable;

    protected $review;

    public function __construct($review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database']; // Or add 'mail' if you want to send emails too
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new review was submitted by ' . $this->review->user->name . ' for "' . $this->review->movie->title . '".',
            'review_id' => $this->review->id,
            'user_id' => $this->review->user_id,
            'movie_id' => $this->review->movie_id,
        ];
    }
}
