<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Location;

class ReviewController extends Controller
{
    public function index()
    {
        return view('backend.admin.reviews.index');
    }

    public function data()
    {
        $reviews = Review::with('location', 'replies')
            ->whereNull('parent_id')
            ->get()
            ->groupBy('location_id');

        $data = collect();

        foreach ($reviews as $locationId => $items) {
            $location = $items->first()->location;

            $data->push((object)[
                'location_id' => $locationId,
                'location' => $location->title ?? '-',
                'rating' => round($items->avg('rating'), 1),
                'description' => $items->first()->description,
                'total_replies' => $items->sum(fn($r) => $r->replies->count())
            ]);
        }

        return datatables($data)
            ->addColumn(
                'action',
                fn($r) =>
                '<a href="' . route("backend.admin.reviews.location", $r->location_id) . '" class="btn btn-sm btn-primary">View Details</a>'
            )
            ->rawColumns(['action'])
            ->make(true);
    }

    public function locationReviews($locationId)
    {
        $location = Location::findOrFail($locationId);

        $reviews = Review::where('location_id', $locationId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->latest()
            // ->get()
            ->paginate(3); 

        return view('backend.admin.reviews.google_reviews', compact('location', 'reviews'));
    }
}
