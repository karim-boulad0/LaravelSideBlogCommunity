<?php

namespace App\Repositories\WebSite;

use App\Http\Requests\WebSite\Comment\CommentEditRequest;
use App\Models\Comment;

class CommentRepository
{
    protected function findCommentById($id)
    {
        return Comment::find($id);
    }
    protected function isUserAuthorized($comment)
    {
        return $comment->user_id === auth()->id();
    }

    protected function updateCommentContent($comment, $content)
    {
        $comment->content = $content;
        $comment->save();
    }

    public function edit($request, $id)
    {
        $request->validated();

        // Find the comment by ID
        $comment = $this->findCommentById($id);

        // Check if the comment exists
        if (!$comment) {
            return ('Comment not found');
        }

        // Check if the user is authorized to update the comment
        if (!$this->isUserAuthorized($comment)) {
            return ('Unauthorized action');
        }

        // Update the comment content
        $this->updateCommentContent($comment, $request->content);

        return ('Comment updated successfully');
    }
}
