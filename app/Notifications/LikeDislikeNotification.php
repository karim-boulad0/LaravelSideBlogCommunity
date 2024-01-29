<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LikeDislikeNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $user;
    protected $like;
    public function __construct($post, $user, $like)
    {
        $this->post = $post;
        $this->user = $user;
        $this->like = $like;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'post' => $this->post,
            'user' => $this->user,
            'like' => $this->like,
        ];
    }
}
