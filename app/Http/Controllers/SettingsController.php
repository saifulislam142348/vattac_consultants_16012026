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
            'otp_enabled' => Setting::get('client_otp_enabled', 'true') !== 'false'
        ]);
    }
}
