<?php

namespace App\Http\Controllers;

use App\Models\TaxSubmission;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch all submissions with documents, ordered by newest
        $submissions = TaxSubmission::with('documents')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $submissions
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,rejected',
        ]);

        $submission = TaxSubmission::findOrFail($id);
        $submission->status = $request->status;
        $submission->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $submission
        ]);
    }
}
