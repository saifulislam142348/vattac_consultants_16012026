<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user()->clientProfile);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nid_no' => 'nullable|string',
            'passport_no' => 'nullable|string',
            'bin_no' => 'nullable|string',
            'tin_no' => 'nullable|string',
            'address' => 'nullable|string',
            'business_address' => 'nullable|string',
            'business_name' => 'nullable|string',
            'business_type' => 'nullable|string',
            'vat_circle' => 'nullable|string',
            'vat_zone' => 'nullable|string',
        ]);

        $profile = $request->user()->clientProfile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json($profile);
    }
}
