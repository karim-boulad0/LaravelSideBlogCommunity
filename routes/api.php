<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\AdminNotificationController;
use App\Http\Controllers\socialAuthController;
use App\Http\Controllers\WebSite\LikeController;
use App\Http\Controllers\Global\GlobalController;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\WebSite\CommentController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\StatisticController;
use App\Http\Controllers\WebSite\PostController as WebSitePostController;
use App\Http\Controllers\WebSite\UserController as WebSiteUserController;
use App\Http\Controllers\WebSite\CategoryController as WebSiteCategoryController;
use App\Http\Controllers\WebSite\WriterController;

/* ----------------postMan new routes---------------
-settings
-settings/edit
-auth
-editIsDarkMode
*/
//-------------------------------Global [Global Routes] --------------------------\\
//--------------------------------------------------------------------\\
Route::controller(GlobalController::class)->prefix('global')->group(function () {
    route::get('/settings', 'settings');
    route::get('/auth', 'auth')->middleware('auth:api');
    route::post('/editIsDarkMode', 'editIsDarkMode')->middleware('auth:api');
});
//-------------------------------Auth [Public Routes] --------------------------\\
//--------------------------------------------------------------------\\
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/logout', 'logout')->middleware('auth:api');
});
//-------------------------------login with google--------------------------\\
//--------------------------------------------------------------------\\
Route::get('/login-google', [socialAuthController::class, 'redirectToProvider']);
Route::get('/auth/google/callback', [socialAuthController::class, 'handleCallback']);

//------------------------------- dashboard --------------------------\\
//---------------------------------------------------------------------\\

Route::middleware(['auth:api'])->group(function () {

    Route::prefix('dashboard')->group(function () {
        //------------------------------- dashboard Admin--------------------------\\
        //--------------------------------------------------------------------\\

        route::middleware('checkAdmin')->group(function () {
            //------------------------------- settings --------------------------\\
            //--------------------------------------------------------------------\\
            Route::post('/settings/edit', [SettingController::class, 'edit']);

            //------------------------------- users --------------------------\\
            //--------------------------------------------------------------------\\
            Route::controller(UserController::class)
                ->prefix('users')
                ->group(function () {
                    Route::get('/', 'index');
                    Route::post('/add', 'create');
                    Route::delete('/{id}', 'delete');
                });
        });
        //------------------------------- dashboard writers and admin--------------------------\\
        //------------------------------- admin and  writer  --------------------------\\
        //--------------------------------------------------------------------\\
        Route::controller(UserController::class)
            ->prefix('users')
            ->group(function () {
                Route::get('/{id}', 'user');
                Route::post('/edit/{id}', 'edit');
            });
        //------------------------------- categories --------------------------\\
        //--------------------------------------------------------------------\\
        Route::controller(CategoryController::class)
            ->prefix('categories')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{id}', 'category');
                Route::post('/edit/{id}', 'edit');
                Route::post('/add', 'create');
                Route::delete('/{id}', 'delete');
            });
        //------------------------------- posts --------------------------\\
        //--------------------------------------------------------------------\\
        Route::controller(PostController::class)
            ->prefix('posts')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{id}', 'post');
                Route::post('/edit/{id}', 'edit');
                Route::post('/add', 'create');
                Route::delete('/{id}', 'delete');
            });

        //------------------------------- statistics --------------------------\\
        //--------------------------------------------------------------------\\
        Route::controller(StatisticController::class)
            ->prefix('statistics')
            ->group(function () {
                Route::get('showGlobalStatistics', 'showGlobalStatistics');
                Route::get('showCategoryStatistics/{id}', 'showCategoryStatistics');
            });
        // admin Notifications
        Route::prefix('/admin/notifications')->group(function () {
            Route::controller(AdminNotificationController::class)->group(function () {
                Route::get('/',  'index');
                Route::get('/notification/{id}',  'notification');
                Route::get('/countUnreadNotifications',  'countUnreadNotifications');
                Route::get('/unreadNotifications',  'unreadNotifications');
                Route::post('/markAllAsRead',  'markAllAsRead');
                Route::post('/markAsReadById/{id}',  'markAsReadById');
                Route::delete('/deleteAll',  'deleteAll');
                Route::delete('/deleteById/{id}',  'deleteById');
            });
        });
    });

    //------------------------------- website --------------------------\\
    Route::prefix('website')->group(function () {

        //------------------------------- likes --------------------------\\
        //--------------------------------------------------------------------\\

        Route::post('likes/interact', [LikeController::class, 'interact']);

        //------------------------------- comments --------------------------\\
        //--------------------------------------------------------------------\\

        Route::post('comments/add', [CommentController::class, 'store']);
        Route::get('comments/comment/{id}', [CommentController::class, 'comment']);
        Route::post('comments/{id}', [CommentController::class, 'edit']);
        Route::delete('comments/{id}', [CommentController::class, 'delete']);

        //------------------------------- user --------------------------\\
        //--------------------------------------------------------------------\\

        Route::get('user', [WebSiteUserController::class, 'index']);
        Route::post('user/edit', [WebSiteUserController::class, 'edit']);
    });
});
//------------------------------- Writers --------------------------\\
//--------------------------------------------------------------------\\
Route::controller(WriterController::class)
    ->prefix('website/writers')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'writer');
    });
//------------------------------- posts --------------------------\\
//--------------------------------------------------------------------\\

Route::controller(WebSitePostController::class)
    ->prefix('website/posts')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'post');
        Route::get(
            '/getPostWithLikesDislikesAndComments/{postId}',
            'getPostWithLikesDislikesAndComments'
        );
        Route::get('/getPopularPosts', 'getPopularPosts');
        Route::get('/getMostCommentedPosts', 'getMostCommentedPosts');
    });


//------------------------------- categories --------------------------\\
//--------------------------------------------------------------------\\

Route::get('website/categories', [WebSiteCategoryController::class, 'index']);
Route::get('website/categories/{id}', [WebSiteCategoryController::class, 'category']);
