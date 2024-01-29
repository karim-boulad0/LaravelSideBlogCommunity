<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class WriterController extends Controller
{
    public function  index()
    {
        $writers =  QueryBuilder::for(User::class)->with(['posts.comments.user', 'posts.likes.user'])->where('status', 'writer')
            ->allowedFilters([
                AllowedFilter::callback('item', function (Builder $query, $value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }),
            ])->get();
        return $writers;
    }

    public function writer($id)
    {
        try {
            $writer = User::with([
                'posts' => function ($query) {
                    $query
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
                        ->with(['comments.user', 'likes.user', 'user']);
                }
            ])->find($id);

            if (!$writer || $writer->status !== "writer") {
                return response()->json(['message' => 'User not found or not a writer.'], 404);
            }

            return $writer;
        } catch (Exception $err) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    }
}
