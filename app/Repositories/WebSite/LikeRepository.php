<?php

namespace App\Repositories\WebSite;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\LikeDislikeNotification;
use Illuminate\Support\Facades\Notification;

class LikeRepository
{
    protected function findExistingLike($postId)
    {
        return Like::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->first();
    }
    protected function updateExistingLike($existingLike, $type)
    {
        if ($existingLike->type !== $type) {
            $existingLike->type = $type;
            $existingLike->save();
            return response()->json(['message' => 'Interaction updated successfully']);
        }
        $existingLike->delete();
        return response()->json(['message' => 'Interaction removed successfully']);
    }

    protected function createNewLike($postId, $type)
    {
        $like = new Like([
            'user_id' => auth()->id(),
            'post_id' => $postId,
            'type' => $type,
        ]);

        $like->save();

        return $like;
    }
    protected function sendNotification($request, $like)
    {
        $post = Post::find($request->post_id);

        if ($post->user_id !== auth()->user()->id) {
            $userPosted = User::find($post->user_id);
            // Check if the user exists before sending the notification
            Notification::send($userPosted, new LikeDislikeNotification($post, auth()->user(), $like));
        }
    }
    //  Handle the interaction with a post (like, dislike, or remove interaction).
    public function interact($request)
    {
        // Validate the incoming request data
        $request->validated();

        // Check if the user has already interacted with the post
        $existingLike = $this->findExistingLike($request->post_id);

        // If the user has already interacted, update or remove the interaction
        if ($existingLike) {
            return $this->updateExistingLike($existingLike, $request->type);
        }

        // If there is no existing interaction, create a new one
        $like =  $this->createNewLike($request->post_id, $request->type);
        // send notification
        $this->sendNotification($request, $like);
        return $like;
    }
}
