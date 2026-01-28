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

    public function reply(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string']);
        
        $submission = TaxSubmission::findOrFail($id);
        $submission->admin_reply = $request->reply;
        $submission->save();

        return response()->json(['message' => 'Reply sent successfully']);
    }

    public function updatePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'service_charge' => 'nullable|numeric',
            'payment_status' => 'nullable|in:pending,paid,partial',
            'payment_method' => 'nullable|string',
        ]);

        $submission = TaxSubmission::findOrFail($id);
        
        if (isset($validated['service_charge'])) {
            $submission->service_charge = $validated['service_charge'];
        }
        if (isset($validated['payment_status'])) {
            $submission->payment_status = $validated['payment_status'];
        }
        if (isset($validated['payment_method'])) {
            $submission->payment_method = $validated['payment_method'];
        }
        
        $submission->save();

        return response()->json([
            'message' => 'Payment info updated',
            'data' => $submission
        ]);
    }

    public function reports()
    {
        // Monthly Submissions (Last 12 Months)
        $monthlyStats = TaxSubmission::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Client Stats (Top 10 Active Clients)
        $clientStats = \App\Models\User::withCount('taxSubmissions')
            ->where('role', 'client')
            ->orderBy('tax_submissions_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'monthly' => $monthlyStats,
            'clients' => $clientStats
        ]);
    }

    public function updateReturnDetails(Request $request, $id)
    {
        $validated = $request->validate([
            'vat_month' => 'nullable|string',
            'return_data' => 'nullable|string',
            'calculation_notes' => 'nullable|string',
            'submission_date' => 'nullable|date',
            'nbr_ref_no' => 'nullable|string',
        ]);

        $submission = TaxSubmission::findOrFail($id);
        $submission->update($validated);

        return response()->json([
            'message' => 'Return details updated',
            'data' => $submission
        ]);
    }
    public function documents(Request $request)
    {
        $query = \App\Models\Document::with(['submission.user']);

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('doc_type', $request->type);
        }

        $documents = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $documents
        ]);
    }

    public function updateMissingDocs(Request $request, $id)
    {
        $validated = $request->validate([
            'missing_documents' => 'present|array', // 'present' allows empty array (clearing the flag)
        ]);

        $submission = TaxSubmission::findOrFail($id);
        $submission->missing_documents = $validated['missing_documents'];
        $submission->save();

        return response()->json([
            'message' => 'Missing documents list updated',
            'data' => $submission
        ]);
    }
}
