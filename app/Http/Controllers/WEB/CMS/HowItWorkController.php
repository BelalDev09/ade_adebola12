<?php

namespace App\Http\Controllers\WEB\CMS;

use App\Models\CmsContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HowItWorkController extends Controller
{
    public function form()
    {
        $cms = CmsContent::where([
            'page_slug'=>'landing-page',
            'section'=>'how-it-works'
        ])->first();

        return view('backend.cms.how-it-works',compact('cms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'image'=>'nullable|image|mimes:jpg,jpeg,png,webp',
            'status'=>'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('cms','public');
        }

        CmsContent::updateOrCreate(
            ['page_slug'=>'landing-page','section'=>'how-it-works'],
            array_merge($data,[
                'type'=>'single',
                'order'=>1
            ])
        );

        return back()->with('success','How it works updated');
    }
}

