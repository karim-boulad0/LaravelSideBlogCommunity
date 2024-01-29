<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Category::factory()->count(5)->create();
        User::factory()->count(10)->create();
        Post::factory()->count(10)->create();

        User::create([
            'name' => "admin",
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'status' => 'admin',
            'photo' => 'http://127.0.0.1:8000/RequiredImages/user.png',
            'password' => Hash::make('admin123$%'), // password
            'remember_token' => Str::random(10),
        ]);
        Setting::create([
            'id' => 1,
            'icon' => "http://127.0.0.1:8000/RequiredImages/logo.jpg",
            'logo' => "http://127.0.0.1:8000/RequiredImages/logo.jpg",

        ]);
    }
}
