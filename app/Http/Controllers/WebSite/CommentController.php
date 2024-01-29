<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebSite\Comment\CommentEditRequest;
use App\Http\Requests\WebSite\Comment\CommentStoreRequest;
use App\Models\Comment;
use App\Repositories\WebSite\CommentRepository;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    protected function sendNotification($request, $comment)
    {

        $post = Post::find($request->post_id);
        if ($post->user_id !== auth()->user()->id) {
            $userPosted = User::find($post->user_id);
            // Check if the user exists before sending the notification
            Notification::send($userPosted, new CommentNotification($post, auth()->user(), $comment));
        }
    }
    public function store(CommentStoreRequest $request)
    {
        $request->validated();
        $comment = new Comment([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);
        $comment->save();
        $this->sendNotification($request, $comment);

        return ('Comment added successfully');
    }

    public function edit(CommentEditRequest $request, $id)
    {
        return (new CommentRepository())->edit($request, $id);
    }
    public function comment($id)
    {
        $comment = Comment::findOrFail($id);
        return $comment;
    }
    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return 'success delete comment';
    }
}
