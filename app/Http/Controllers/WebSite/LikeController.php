<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebSite\Like\LikeStoreRequest;
use App\Models\Like;
use App\Models\Post;
use App\Repositories\WebSite\LikeRepository;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function interact(LikeStoreRequest $request)
    {
        return (new LikeRepository())->interact($request);
    }
}
