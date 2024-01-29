<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;

class GlobalController extends Controller
{
    // get site settings
    public function settings()
    {
        $setting = Setting::firstOrFail();
        return $setting;
    }
    //get the current user
    public function auth()
    {
        $user = auth()->user();
        return $user;
    }
    // edit the mode of site if it 1 make it 0 and the opposite
    public function editIsDarkMode()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        $user->isDarkMode = !$user->isDarkMode;
        $user->save();
    }
}
