<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Team;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(Team::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'education' => 'nullable|string',
            'address' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'fb_link' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('teams', 'public');
        }

        $team = Team::create($validated);

        return response()->json($team, 201);
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'education' => 'nullable|string',
            'address' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'fb_link' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($team->image) {
                Storage::disk('public')->delete($team->image);
            }
            $validated['image'] = $request->file('image')->store('teams', 'public');
        }

        $team->update($validated);

        return response()->json($team);
    }

    public function destroy(Team $team)
    {
        if ($team->image) {
            Storage::disk('public')->delete($team->image);
        }
        $team->delete();
        return response()->json(['message' => 'Team member deleted']);
    }
}
