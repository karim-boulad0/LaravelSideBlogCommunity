<?php


namespace App\Services\AuthServices;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterService
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function store($request)
    {
        $user = $this->model->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return $user;
    }

    public function createToken($user)
    {
        $token = $user->createToken('token')->accessToken;
        return $token;
    }

    public function refreshToken($user)
    {
        $refreshToken = $user->createToken('authTokenRefresh')->accessToken;
        return $refreshToken;
    }

    public function register($request)
    {
        $user = $this->store($request);
        $accessToken = $this->createToken($user);
        $refreshToken = $this->refreshToken($user);
        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);
    }
}
