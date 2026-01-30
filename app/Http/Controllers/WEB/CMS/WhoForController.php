<?php

namespace App\Http\Controllers\WEB\CMS;

use App\Models\CmsContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WhoForController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = CmsContent::where([
                'page_slug' => 'landing-page',
                'section'   => 'who-for'
            ])->orderBy('order', 'asc')->orderBy('id', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('type', fn($row) => $row->type ?? 'card')
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

        return view('backend.cms.who-for');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'          => 'nullable|exists:cms_contents,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1',
        ]);

        // Only on CREATE
        if (empty($validated['id'])) {
            $validated['order'] =
                CmsContent::where('section', 'who-for')->max('order') + 1 ?? 1;
        }

        CmsContent::updateOrCreate(
            [
                'id'        => $validated['id'] ?? null,
                'page_slug' => 'landing-page',
                'section'   => 'who-for',
            ],
            array_merge($validated, [
                'type' => 'card',
            ])
        );

        return response()->json(['message' => 'Saved successfully']);
    }

    public function show($id)
    {
        return response()->json(
            CmsContent::where('section', 'who-for')->findOrFail($id)
        );
    }

    public function delete($id)
    {
        CmsContent::where('section', 'who-for')->findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:0,1']);

        CmsContent::where('id', $id)->update([
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Status updated']);
    }
}
