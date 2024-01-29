<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    // Get posts with likes and comments, sorted by newest
    public function index()
    {
        $posts =  QueryBuilder::for(Post::class)
            ->with(['likes', 'comments', 'comments.user', 'user', 'category'])
            ->allowedFilters([
                AllowedFilter::callback('item', function (Builder $query, $value) {
                    $query->where('tags', 'like', "%{$value}%")
                        ->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('content', 'like', "%{$value}%")
                        ->orWhere('smallDesc', 'like', "%{$value}%")
                        ->orWhereHas('category', function ($query) use ($value) {
                            $query->where('title', 'like', "%{$value}%");
                            $query->where('content', 'like', "%{$value}%");
                        })
                        ->orWhereHas('user', function ($query) use ($value) {
                            $query->where('name', 'like', "%{$value}%");
                            $query->where('email', 'like', "%{$value}%");
                        });
                }),
            ])
            ->withCount([
                'likes as likes_count' => function ($query) {
                    $query->where('type', 'like');
                },
                'likes as dislikes_count' => function ($query) {
                    $query->where('type', 'dislike');
                },
                'comments as comments_count'
            ])
            ->orderByDesc('created_at')
            ->get();

        return $posts;
    }
    public function post($id)
    {
        $post = Post::with(['likes', 'comments', 'comments.user', 'user', 'category'])

            ->withCount([
                'likes as likes_count' => function ($query) {
                    $query->where('type', 'like');
                },
                'likes as dislikes_count' => function ($query) {
                    $query->where('type', 'dislike');
                },
                'comments as comments_count'
            ])
            ->orderByDesc('created_at')
            ->where('id', $id)
            ->get();

        return $post;
    }
    // Get the post with counts for likes, dislikes, and comments
    public function getPostWithLikesDislikesAndComments($postId)
    {
        $post = Post::with(['comments', 'comments.user', 'user', 'category'])
            ->withCount([
                'likes as likes_count' => function ($query) {
                    $query->where('type', 'like');
                },
                'likes as dislikes_count' => function ($query) {
                    $query->where('type', 'dislike');
                },
                'comments as comments_count',
            ])
            ->findOrFail($postId);

        return response()->json($post);
    }
    // Get posts with likes and comments, sorted by popularity (likes)
    public function getPopularPosts()
    {
        $posts = Post::with(['likes', 'comments', 'comments.user', 'category', 'user'])
            ->withCount([
                'likes as likes_count' => function ($query) {
                    $query->where('type', 'like');
                },
                'likes as dislikes_count' => function ($query) {
                    $query->where('type', 'dislike');
                },
                'comments as comments_count',

            ])
            ->orderByDesc('likes_count')
            ->get();

        return response()->json($posts);
    }
}
