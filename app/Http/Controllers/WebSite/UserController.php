<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebSite\User\UserEditRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return $user;
    }


    public function edit(UserEditRequest $request)
    {
        $id = auth()->user()->id;
        $request->validated();
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
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
}
