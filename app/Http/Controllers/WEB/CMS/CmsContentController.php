<?php

namespace App\Http\Controllers\WEB\CMS;

use App\Models\User;
use App\Models\CmsContent;
use Illuminate\Http\Request;
use App\Notifications\NotifyUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Notification;

class CmsContentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CmsContent::orderBy('order')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.cms.index');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'page_slug' => 'required|string',
            'section' => 'required|string',
            'type' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('cms', 'public');
        }
        $data['status'] = $request->has('status') ? 1 : 0;
        $cms = CmsContent::updateOrCreate(['id' => $request->id], $data);


        return response()->json(['success' => 'CMS content saved successfully']);
    }

    public function show(CmsContent $cms)
    {
        return response()->json($cms);
    }

    public function destroy(CmsContent $cms)
    {
        if ($cms->image_path) Storage::disk('public')->delete($cms->image_path);
        $cms->delete();
        return response()->json(['success' => 'CMS content deleted successfully']);
    }

    public function statusToggle(Request $request, CmsContent $cms)
    {
        $cms->status = $request->status ? 1 : 0;
        $cms->save();

        return response()->json(['success' => 'Status updated successfully']);
    }
}
