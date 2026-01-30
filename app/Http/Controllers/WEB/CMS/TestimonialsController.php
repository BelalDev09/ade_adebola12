<?php

namespace App\Http\Controllers\WEB\CMS;

use App\Models\CmsContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestimonialsController extends Controller
{
    public function form()
    {
        $cms = CmsContent::where([
            'page_slug' => 'landing-page',
            'section'   => 'testimonials'
        ])->first();

        return view('backend.cms.testimonials', compact('cms'));
    }

    public function store(Request $request)
    {
        // Validation
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|boolean', // unchecked allowed
        ]);

        // Checkbox: checked=1, unchecked=0
        $data['status'] = $request->has('status') ? 1 : 0;

        // Update or create testimonial
        CmsContent::updateOrCreate(
            ['page_slug' => 'landing-page', 'section' => 'testimonials'],
            array_merge($data, [
                'type'  => 'list',
                'order' => 1
            ])
        );

        // JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Testimonials updated successfully!'
        ]);
    }
}
