<?php

namespace App\Http\Controllers\API;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'property_size' => 'required|integer|min:10',
            'number_of_bedrooms' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();

        Sale::updateOrCreate($validated);

        return response()->json([
            'message' => 'sale successfully done',
            'data' => $validated
        ], 201);
    }
}
