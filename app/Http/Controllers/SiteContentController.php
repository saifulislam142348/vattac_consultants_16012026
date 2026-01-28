<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use Illuminate\Http\Request;

class SiteContentController extends Controller
{
    public function index($section)
    {
        $contents = SiteContent::where('section', $section)->get()->pluck('value', 'key');
        return response()->json($contents);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string',
            'contents' => 'required|array', // ['hero_title' => 'New Title', 'hero_image' => File]
        ]);

        foreach ($validated['contents'] as $key => $value) {
            
            // Handle File Upload if value is an uploaded file
            if ($request->hasFile("contents.$key")) {
                $file = $request->file("contents.$key");
                $value = $file->store('site_contents', 'public');
                $type = 'image';
            } else {
                $type = 'text';
            }

            SiteContent::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'section' => $validated['section']
                ]
            );
        }

        return response()->json(['message' => 'Content updated successfully']);
    }
}
