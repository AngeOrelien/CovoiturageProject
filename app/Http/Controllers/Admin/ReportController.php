<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser', 'ride']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest('created_at')->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }

    public function update(Report $report, Request $request)
    {
        $request->validate(['status' => 'required|in:reviewed,resolved,dismissed']);
        $report->update(['status' => $request->status]);
        return back()->with('success', 'Signalement mis à jour.');
    }
}
