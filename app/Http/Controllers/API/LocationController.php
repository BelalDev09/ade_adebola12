<?php

namespace App\Http\Controllers\API;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function search(Request $request)
    {
        $query = Location::query();

        // Search by title
        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        if ($request->filled('latitude')) {
            $query->where('latitude', $request->latitude);
        }

        if ($request->filled('longitude')) {
            $query->where('longitude', $request->longitude);
        }

        // Sorting
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');

        $query->orderBy($sort, $order);

        // If you want reviews also
        // $query->with(['reviews.replies']);

        $locations = $query->get();

        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = Location::firstOrCreate([
            'title'     => $validated['title'],
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json([
            'message' => 'Location created successfully',
            'data'    => $location
        ], 201);
    }
}
