<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database']; // Use 'mail' too if you want email
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'New user registered: ' . $this->user->name,
        ];
    }
}
