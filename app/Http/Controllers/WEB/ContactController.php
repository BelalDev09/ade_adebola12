<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('backend.admin.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        ContactMessage::create($request->all());

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
