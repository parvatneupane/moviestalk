<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FeedbackRepliedNotification extends Notification
{
    use Queueable;

    protected $reply;

    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['database']; // store in database
    }

    public function toArray($notifiable)
    {
        return [
            'feedback_id' => $this->reply->feedback_id,
            'reply_id' => $this->reply->id,
            'message' => 'Admin replied to your feedback: ' . substr($this->reply->reply, 0, 50) . '...',
        ];
    }
}
