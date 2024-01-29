<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Models\Post;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    public function showGlobalStatistics()
    {
        $totalCategories = Category::count();
        $totalPosts = Post::count();

        return response()->json([
            'totalCategories', $totalCategories,
            'totalPosts', $totalPosts,
        ]);
    }

    public function showCategoryStatistics($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $totalPosts = $category->posts()->count();
        $totalLikes = $category->posts()->sum('likes_count');
        $totalDislikes = $category->posts()->sum('dislikes_count');
        $totalComments = $category->posts()->sum('comments_count');
    }
}
