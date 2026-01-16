<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'tin_number' => 'nullable|string|max:50',
            'service_type' => 'required|in:personal_tax,company_vat,audit',
            'total_income' => 'nullable|numeric',
            'message' => 'nullable|string',
            'documents' => 'array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $submission = \App\Models\TaxSubmission::create([
            'user_id' => $request->user('sanctum')?->id,
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'tin_number' => $validated['tin_number'] ?? null,
            'service_type' => $validated['service_type'],
            'total_income' => $validated['total_income'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents', 'local'); // Store in storage/app/documents

                $submission->documents()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Submission received successfully.',
            'data' => $submission->load('documents'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
