<?php

namespace App\Http\Controllers\WEB\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Category\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:category-list')->only('index');
        $this->middleware('permission:category-create')->only(['create', 'store']);
        $this->middleware('permission:category-edit')->only(['edit', 'update']);
        $this->middleware('permission:category-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::with('parent')->select('categories.*');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('parent', fn($row) => $row->parent ? $row->parent->name : '-')
                ->addColumn('status', fn($row) => $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>')
                ->addColumn('action', function ($row) {
                    $editUrl = route('categories.edit', $row->id);
                    $deleteUrl = route('categories.destroy', $row->id);
                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>
                        <form action="' . $deleteUrl . '" method="POST" style="display:inline-block;">
                            ' . csrf_field() . method_field("DELETE") . '
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.admin.categories.index');
    }

    public function create()
    {
        $parents = Category::where('parent_id', 0)->get();
        return view('backend.admin.categories.create', compact('parents'));
    }

    public function store(CategoryRequest $request)
    {
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id ?? 0,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('backend.admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('parent_id', 0)->where('id', '!=', $category->id)->get();
        return view('backend.admin.categories.edit', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('backend.admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('backend.admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
