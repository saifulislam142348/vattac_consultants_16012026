<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return response()->json(Setting::all()->pluck('value', 'key'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::set($key, $value);
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function publicConfig()
    {
        return response()->json([
            'otp_enabled' => Setting::get('client_otp_enabled', 'true') !== 'false',
            'site_phone' => Setting::get('site_phone', '+880 1712 345678'),
            'site_email' => Setting::get('site_email', 'info@vatconsultant.com'),
            'site_logo' => Setting::get('site_logo', ''),
            'whatsapp_number' => Setting::get('whatsapp_number', '+8801712345678'),
            'about_image' => Setting::get('about_image', ''),
        ]);
    }
}
