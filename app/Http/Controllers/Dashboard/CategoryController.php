<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Dashboard\Category\CategoryEditRequest;
use App\Http\Requests\Dashboard\Category\CategoryStoreRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    public function index()
    {
        $categories =  QueryBuilder::for(Category::class)
            ->allowedFilters([
                AllowedFilter::callback('item', function (Builder $query, $value) {
                    $query->where('title', 'like', "%{$value}%")
                        ->orWhere('content', 'like', "%{$value}%");
                }),
            ])
            ->orderByDesc('created_at')->get();
        return $categories;
    }
    public function category($id)
    {
        return Category::findOrFail($id);
    }
    public function edit(CategoryEditRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->content = $request->content;
        if ($request->hasFile('image')) {
            $oldPath = public_path() . '/CategoryImages/' . substr($category['image'], strrpos($category['image'], '/') + 1);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = 'CategoryImages';
            $file->move($path, $filename);
            $category->image = url('/') . '/CategoryImages/' . $filename;
        }
        $category->save();
        return $category;
    }
    public function create(CategoryStoreRequest $request)
    {
        $category = Category::create($request->except('image'));
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = 'CategoryImages';
            $file->move($path, $filename);
            $category->image = url('/') . '/CategoryImages/' . $filename;
        }
        $category->save();
        return response()->json(
            [
                'message' => 'success create category',
                'category' => $category,
            ],
            200
        );
    }
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $path = public_path() . '/CategoryImages/' . substr($category['image'], strrpos($category['image'], '/') + 1);
        if (File::exists($path)) {
            File::delete($path);
        }
        $category->delete();
        return 'success';
    }
}
