<?php

namespace App\Http\Controllers\WEB\CMS;

use App\Models\CmsContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HowItWorkController extends Controller
{
    public function form(Request $request)
    {
        $cms = CmsContent::where('page_slug', 'landing-page')
            ->where('section', 'how-it-works')
            ->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($cms)
                ->addIndexColumn()
                ->addColumn('status', fn($row) => $row->status ? 1 : 0)
                ->addColumn('image', fn($row) => $row->image_path ? asset('storage/' . $row->image_path) : asset('images/placeholder.png'))
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-info view-btn" data-id="' . $row->id . '">View</button>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.cms.how-it-works');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        if ($request->hasFile('image')) {
            if ($request->id) {
                $old = CmsContent::find($request->id);
                if ($old && $old->image_path) Storage::disk('public')->delete($old->image_path);
            }
            $data['image_path'] = $request->file('image')->store('cms', 'public');
        }

        $data['status'] = $request->status ?? 0;

        CmsContent::updateOrCreate(
            ['id' => $request->id],
            array_merge($data, ['page_slug' => 'landing-page', 'section' => 'how-it-works', 'type' => 'card', 'order' => 1])
        );

        return response()->json(['success' => 'Saved Successfully']);
    }

    public function show($id)
    {
        $cms = CmsContent::findOrFail($id);

        // Only add asset if image exists
        if ($cms->image_path && file_exists(storage_path('app/public/' . $cms->image_path))) {
            $cms->image_path = asset('storage/' . $cms->image_path);
        } else {
            $cms->image_path = asset('images/placeholder.png');
        }

        return response()->json($cms);
    }


    public function delete($id)
    {
        $cms = CmsContent::findOrFail($id);
        if ($cms->image_path) Storage::disk('public')->delete($cms->image_path);
        $cms->delete();
        return response()->json(['success' => 'Deleted Successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $status = $request->status ? 1 : 0;
        CmsContent::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => 'Status updated']);
    }
    public function deleteImage($id)
    {
        $cms = CmsContent::findOrFail($id);

        if ($cms->image_path) {
            Storage::disk('public')->delete($cms->image_path);
            $cms->image_path = null;
            $cms->save();
        }

        return response()->json(['success' => 'Image removed successfully']);
    }
}
