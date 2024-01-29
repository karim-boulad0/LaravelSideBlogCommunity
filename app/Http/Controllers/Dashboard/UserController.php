<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\UserStoreRequest;
use App\Http\Requests\Dashboard\Users\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('item', function (Builder $query, $value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('status', 'like', "%{$value}%");
                }),
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        return $users;
    }
    public function user($id)
    {
        $auth = auth()->user();
        if ($auth->status == "admin" || $auth->status == "writer" && $auth->id  == $id) {
            return User::findOrFail($id);
        }
        return response()->json([
            'data' => 'ForbiddenWriter',
            'status' => 401,
            'statusText' => 'Forbidden'
        ], 401);
    }
    public function edit(UserUpdateRequest $request, $id)
    {
        $auth = auth()->user();
        if ($auth->status == "admin" || $auth->status == "writer" && $auth->id  == $id) {
            $request->validated();
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->password =  Hash::make($request->password);
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
                $path = 'UserImage';
                $file->move($path, $filename);
                $user->photo = url('/') . '/UserImage/' . $filename;
            }
            $user->save();
            return $user;
        }
        return response()->json([
            'data' => 'ForbiddenWriter',
            'status' => 401,
            'statusText' => 'Forbidden'
        ], 401);
    }
    public function create(UserStoreRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = 'UserImage';
            $file->move($path, $filename);
            $validatedData['photo'] = url('/') . '/UserImage/' . $filename;
        }
        $user = User::create($validatedData);
        return response()->json([
            'user' => $user,
        ], 201);
    }
    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return 'success delete';
    }
}
