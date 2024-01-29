<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Post\PostEditRequest;
use App\Http\Requests\Dashboard\Post\PostStoreRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    private function format($FilteredPosts)
    {
        $formattedPosts = $FilteredPosts->map(function ($post) {

            return [
                'id' => $post->id,
                'image' => $post->image,
                'user_name' => $post->user->name,
                'user_email' => $post->user->email,
                'tags' => $post->tags,
                'title' => $post->title,
                'content' => $post->content,
                'smallDesc' => $post->smallDesc,
                'category_title' => $post->category->title,
                'likes_count' => $post->likes->where('type', 'like')->count(),
                'dislikes_count' => $post->likes->where('type', 'dislike')->count(),
                'comments_count' => $post->comments->count(),
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ];
        });
        return $formattedPosts;
    }
    public function index()
    {
        $posts =  QueryBuilder::for(Post::class)
            ->with(['category', 'user'])
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
            ->orderByDesc('created_at')->get();
        $formattedPosts = $this->format($posts);
        return $formattedPosts;
    }
    public function post($id)
    {
        $post = Post::findOrFail($id);
        return $post;
    }
    public function create(PostStoreRequest $request)
    {
        $data = $request->except('image');
        $data['user_id'] = auth()->user()->id;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = 'PostImages';
            $file->move($path, $filename);
            $data['image'] = url('/') . '/PostImages/' . $filename;
        }

        $post = Post::create($data);

        return response()->json(
            [
                'message' => 'success create post',
                'post' => $post,
            ],
            200
        );
    }


    public function edit(PostEditRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->except('image'));
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = 'PostImages';
            $file->move($path, $filename);
            $post->image = url('/') . '/PostImages/' . $filename;
        }
        $post->save();
        return response()->json(
            [
                'message' => 'success update post',
                'post' => $post,
            ],
            200
        );
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return 'success delete';
    }
}
