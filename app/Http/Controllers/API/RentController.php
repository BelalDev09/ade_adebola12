<?php

namespace App\Http\Controllers\API;

use App\Models\Rent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'number_of_bedrooms' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();

        Rent::updateOrCreate($validated);

        return response()->json([
            'message' => 'Rent successfully updated or created',
            'data' => $validated
        ], 201);
    }
}
