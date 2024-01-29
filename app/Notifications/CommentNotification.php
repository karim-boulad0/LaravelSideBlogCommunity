<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $user;
    protected $comment;
    public function __construct($post, $user, $comment)
    {
        $this->post = $post;
        $this->user = $user;
        $this->comment = $comment;
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
            'comment' => $this->comment,
        ];
    }
}
