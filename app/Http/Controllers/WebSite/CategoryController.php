<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\WebSite\CategoryService;


class CategoryController extends Controller
{
    public function index()
    {
        return (new CategoryService())->index();
    }

    public function category($id)
    {
        $category = Category::with(['posts.likes', 'posts.comments', 'posts.comments.user', 'posts.user'])->find($id);
        return (new CategoryService())->formatCategoryById($category);
    }
}
