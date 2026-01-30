<?php

namespace App\Http\Controllers\WEB\CMS;

use App\Models\User;
use App\Models\CmsContent;
use Illuminate\Http\Request;
use App\Notifications\NotifyUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class HeroSectionController extends Controller
{
    /**
     * Display the hero section edit form
     */
    public function form()
    {
        $cms = CmsContent::where([
            'page_slug' => 'landing-page',
            'section'   => 'hero',
        ])->first();

        return view('backend.cms.hero', compact('cms'));
    }

    /**
     * Store or update the hero section
     */
    public function store(Request $request)
    {
        $users = User::all();
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'btn_text'    => 'nullable|string|max:100',
            'btn_link'    => 'nullable|url|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'nullable|boolean',
        ]);

        $data = $validated;

        $data['status'] = $request->boolean('status', true);


        if (empty(trim($data['btn_link'] ?? ''))) {
            $data['btn_link'] = null;
        }

        // Handle image
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $existing = CmsContent::where([
                'page_slug' => 'landing-page',
                'section'   => 'hero',
            ])->first();

            // Delete old image
            if ($existing && $existing->image_path) {
                Storage::disk('public')->delete($existing->image_path);
            }

            // Store new image
            $data['image_path'] = $request->file('image')
                ->store('cms/hero', 'public');
        }


        if ($request->boolean('delete_image') && isset($existing) && $existing->image_path) {
            Storage::disk('public')->delete($existing->image_path);
            $data['image_path'] = null;
        }

        // Save
        $hero = CmsContent::updateOrCreate(
            [
                'page_slug' => 'landing-page',
                'section'   => 'hero',
            ],
            array_merge($data, [
                'type'  => 'single',
                'order' => 1,
            ])
        );

        Notification::send($users, new NotifyUser($hero->toArray([
            
        ])));
        // $users->notify(new NotifyUser($data));

        return back()->with('success', 'Hero section updated successfully!');
    }
}

//
