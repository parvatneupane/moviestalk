<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MovieAddedNotification extends Notification
{
    use Queueable;

    protected $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    public function via($notifiable)
    {
        return ['database']; // We're using database notifications here
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new movie "' . $this->movie->title . '" has been added!',
            'movie_id' => $this->movie->id,
        ];
    }
}
