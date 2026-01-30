<?php

namespace App\Http\Controllers\API;

use App\Models\Report;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    /**
     * Report a review
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'review_id'   => 'required|exists:reviews,id',
            'reason_code' => 'required|string|max:100',
            'description' => 'nullable|string',
            'medias'      => 'nullable|array',
            'medias.*'    => 'image|max:2048',
        ]);

        if ($request->hasFile('medias')) {
            $data['medias'] = collect($request->file('medias'))
                ->map(fn($file) => $file->store('reports', 'public'))
                ->toArray();
        }
    $data['user_id'] = auth('api')->id();
        $report = Report::create($data);

        return response()->json([
            'message' => 'Reported successfully',
            'data'    => $report,
        ], 201);
    }
    public function reply(Request $request)
    {
        $data = $request->validate([
            'parent_id'   => 'required|exists:reviews,id',
            'description' => 'required|string|max:500',
            'medias'      => 'nullable|array',
            'medias.*'    => 'image|max:2048',
        ]);

        // Media
        if ($request->hasFile('medias')) {
            $data['medias'] = collect($request->file('medias'))
                ->map(fn($file) => $file->store('reviews', 'public'))
                ->toArray();
        }

        // Create reply
        $reply = Review::create([
            'parent_id'  => $data['parent_id'],
            'user_id'    => auth('api')->id(),
            'description' => $data['description'],
            'medias'     => $data['medias'] ?? null,
        ]);

        return response()->json([
            'message' => 'Reply added successfully',
            'data'    => $reply->load('replies', 'locations'),
        ], 201);
    }
}
