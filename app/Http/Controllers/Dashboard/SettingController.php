<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Setting\SettingStoreAndUpdateRequest;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrFail();
        return $setting;
    }


    public function edit(SettingStoreAndUpdateRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->getClientOriginalName();
            $pathIcon = $request->file('icon')->storeAs('icon',  $icon, 'setting');
            $data['icon'] = 'http://127.0.0.1:8000/public/' . $pathIcon;
        }
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->getClientOriginalName();
            $pathLogo = $request->file('logo')->storeAs('logo',  $logo, 'setting');
            $data['logo'] = 'http://127.0.0.1:8000/public/' . $pathLogo;
        }

        $settings = Setting::firstOrFail(); // Assuming there is always one row in the settings table

        $settings->update($data);

        return response()->json(['message' => 'Settings updated successfully']);
    }
}
