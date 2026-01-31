<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Report;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewReportController extends Controller
{

    //  Reports List Page

    public function index()
    {
        return view('backend.admin.reports.index');
    }

    /* ===============================
       Yajra DataTable Data
    =============================== */
    public function data()
    {
        $reports = Report::with(['review', 'user']);

        return datatables($reports)
            ->addColumn('review_desc', fn($r) => $r->review->description ?? '-')
            ->addColumn('reporter', fn($r) => $r->user->name ?? 'Unknown')

            ->addColumn('status', function ($r) {
                $class = match ($r->status) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning'
                };

                return '<span class="badge bg-' . $class . '">' . ucfirst($r->status) . '</span>';
            })

            ->addColumn('action', function ($r) {
                return '<a href="' . route('backend.admin.reports.show', $r->id) . '"
                        class="btn btn-sm btn-primary">
                        View Review
                    </a>';
            })

            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function show($id)
    {
        $report = Report::with([
            'user',
            'review.user',
            'review.location'
        ])->findOrFail($id);

        return view('backend.admin.reports.show', compact('report'));
    }

    //  Approve / Reject

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('backend.admin.reports.index', $id)
            ->with('success', 'Report status updated successfully');
    }


    public function reviewsWithReports()
    {
        return view('backend.admin.reports.reviews-with-reports');
    }

    public function reviewsWithReportsData()
    {
        $reviews = Review::with(['location', 'user'])
            ->whereNull('parent_id')
            ->whereHas('reports')
            ->get()
            ->map(function ($review) {
                return (object)[
                    'id' => $review->id,
                    'location' => $review->location->title ?? '-',
                    'reviewer' => $review->user->name ?? 'Unknown',
                    'rating' => $review->rating,
                    'description' => $review->description,
                    'total' => $review->reports()->count(),
                    'approved' => $review->reports()->where('status', 'approved')->count(),
                    'rejected' => $review->reports()->where('status', 'rejected')->count(),
                    'pending' => $review->reports()->where('status', 'pending')->count(),
                ];
            });

        return datatables($reviews)
            ->addColumn(
                'report_summary',
                fn($r) =>
                "Total: {$r->total} | Approved: {$r->approved} | Rejected: {$r->rejected} | Pending: {$r->pending}"
            )
            ->addColumn(
                'action',
                fn($r) =>
                '<a href="' . route('backend.admin.reports.show', $r->id) . '"
                    class="btn btn-sm btn-primary">
                    View Reports
                </a>'
            )
            ->rawColumns(['action'])
            ->make(true);
    }
}
