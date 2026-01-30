<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactUSController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'topic' => 'required|string|max:255',
            'message' => 'required|string|max:255',
        ]);
        $data['user_id'] = auth()->id();
        $contact = ContactMessage::create($data);
        return response()->json([
            'message' => 'Messages send successfully.',
            'data' => $contact
        ], 201);
    }
}
