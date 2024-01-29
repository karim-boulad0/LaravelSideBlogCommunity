<?php


namespace App\Services\WebSite;

use App\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class CategoryService
{
    // all categories
    private function filter()
    {
        $categories =  QueryBuilder::for(Category::class)
            ->with(['posts.likes', 'posts.comments', 'posts.comments.user', 'posts.user'])
            ->allowedFilters([
                AllowedFilter::callback('item', function (Builder $query, $value) {
                    $query->where('title', 'like', "%{$value}%")
                        ->orWhere('content', 'like', "%{$value}%")
                        ->orWhereHas('posts', function ($query) use ($value) {
                            $query->where('tags', 'like', "%{$value}%")
                                ->orWhere('title', 'like', "%{$value}%")
                                ->orWhere('content', 'like', "%{$value}%")
                                ->orWhere('smallDesc', 'like', "%{$value}%");
                        })
                        ->orWhereHas('posts.user', function ($query) use ($value) {
                            $query->where('name', 'like', "%{$value}%");
                            $query->where('email', 'like', "%{$value}%");
                        });
                }),
            ])
            ->orderByDesc('created_at')->get();
        return $categories;
    }
    private function format($FilteredCategories)
    {
        $formattedCategories = $FilteredCategories->map(function ($category) {
            return [
                'id' => $category->id,
                'image' => $category->image,
                'parent' => $category->parent,
                'title' => $category->title,
                'content' => $category->content,
                'posts' => $category->posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'image' => $post->image,
                        'user' => $post->user,
                        'tags' => $post->tags,
                        'title' => $post->title,
                        'content' => $post->content,
                        'smallDesc' => $post->smallDesc,
                        'category_id' => $post->category_id,
                        'created_at' => $post->created_at,
                        'updated_at' => $post->updated_at,
                        'likes_count' => $post->likes->where('type', 'like')->count(),
                        'dislikes_count' => $post->likes->where('type', 'dislike')->count(),
                        'comments_count' => $post->comments->count(),
                        'likes' => $post->likes,
                        'comments' => $post->comments->map(function ($comment) {
                            return [
                                'id' => $comment->id,
                                'user_id' => $comment->user_id,
                                'post_id' => $comment->post_id,
                                'content' => $comment->content,
                                'created_at' => $comment->created_at,
                                'updated_at' => $comment->updated_at,
                                'user' => $comment->user,
                            ];
                        }),
                    ];
                }),
            ];
        });
        return $formattedCategories;
    }
    public function index()
    {
        $FilteredCategories = $this->filter();
        $formattedCategories = $this->format($FilteredCategories);
        return $formattedCategories;
    }
    //category by id
    public function formatCategoryById($category)
    {
        $formattedCategory = [
            'id' => $category->id,
            'image' => $category->image,
            'parent' => $category->parent,
            'title' => $category->title,
            'content' => $category->content,
            'posts' => $category->posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'image' => $post->image,
                    'user' => $post->user,
                    'tags' => $post->tags,
                    'title' => $post->title,
                    'content' => $post->content,
                    'smallDesc' => $post->smallDesc,
                    'category_id' => $post->category_id,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    'likes_count' => $post->likes->where('type', 'like')->count(),
                    'dislikes_count' => $post->likes->where('type', 'dislike')->count(),
                    'comments_count' => $post->comments->count(),
                    'likes' => $post->likes,
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'user_id' => $comment->user_id,
                            'post_id' => $comment->post_id,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at,
                            'updated_at' => $comment->updated_at,
                            'user' => $comment->user,
                        ];
                    }),
                ];
            }),
        ];
        return $formattedCategory;
    }
}
