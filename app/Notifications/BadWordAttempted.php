<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BadWordAttempted extends Notification
{
    use Queueable;

    protected $user;
    protected $content;

    public function __construct($user, $content)
    {
        $this->user = $user;
        $this->content = $content;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
{
    return [
        'message' => $this->user->name . ' Try to submit badword language.',
        'content' => $this->content,
        'user_id' => $this->user->id,
    ];
}

}
