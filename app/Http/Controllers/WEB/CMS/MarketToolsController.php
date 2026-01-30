<?php

namespace App\Http\Controllers\WEB\CMS;

use App\Models\CmsContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MarketToolsController extends Controller
{
    // INDEX - DataTable
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cms = CmsContent::where('page_slug', 'landing-page')
                ->where('section', 'market-tools')
                ->orderBy('id', 'desc');

            return DataTables::of($cms)
                ->addIndexColumn()
                ->addColumn('status', fn($row) => $row->status ? 1 : 0)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-info view-btn" data-id="' . $row->id . '" title="View">View<i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '" title="Edit">Edit<i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Delete">Delete<i class="bi bi-trash"></i></button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.cms.market-tools');
    }

    // STORE / UPDATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean'
        ]);

        // Handle Image
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($request->id) {
                $old = CmsContent::find($request->id);
                if ($old && $old->image_path) {
                    Storage::disk('public')->delete($old->image_path);
                }
            }
            $data['image_path'] = $request->file('image')->store('cms', 'public');
        }

        $data['status'] = $request->status ?? 0;

        // Create or update
        CmsContent::updateOrCreate(
            ['id' => $request->id],
            array_merge($data, [
                'page_slug' => 'landing-page',
                'section' => 'market-tools',
                'type' => 'card',
                'order' => 1
            ])
        );

        return response()->json(['success' => 'Market Tool saved successfully']);
    }

    // SHOW - for edit/view modal
    public function show($id)
    {
        $cms = CmsContent::findOrFail($id);
        if ($cms->image_path) {
            $cms->image_path = asset('storage/' . $cms->image_path);
        }
        return response()->json($cms);
    }

    // DELETE
    public function delete($id)
    {
        $cms = CmsContent::findOrFail($id);
        if ($cms->image_path) {
            Storage::disk('public')->delete($cms->image_path);
        }
        $cms->delete();
        return response()->json(['success' => 'Market Tool deleted successfully']);
    }

    // UPDATE STATUS
    public function updateStatus(Request $request, $id)
    {
        $status = $request->status ? 1 : 0;
        CmsContent::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => 'Status updated']);
    }
}
