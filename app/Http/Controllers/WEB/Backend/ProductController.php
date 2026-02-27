<?php

namespace App\Http\Controllers\WEB\Backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Product\ProductCreateRequest;
use App\Http\Requests\Backend\Product\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['category', 'vendorprofile'])->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->setRowId('id')
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('category', fn($data) => $data->category->name ?? 'N/A')
                ->addColumn('vendor', fn($data) => $data->vendor->name ?? 'N/A')
                ->addColumn('price', fn($data) => number_format($data->price, 2))
                ->addColumn('stock', fn($data) => $data->stock)
                ->addColumn('views', fn($data) => $data->views)
                ->editColumn('status', function ($data) {
                    return '<div class="form-check form-switch mb-2"><input type="checkbox" class="form-check-input"
                            onclick="changeStatus(event,' . $data->id . ')"
                            ' . ($data->status === 'active' ? 'checked' : '') . '></div>';
                })
                ->addColumn('bulk_check', function ($data) {
                    return '<div class="form-checkbox">
                                <input type="checkbox" class="form-check-input select_data"
                                id="checkbox-' . $data->id . '"
                                value="' . $data->id . '"
                                onClick="select_single_item(' . $data->id . ')">
                                <label class="form-check-label" for="checkbox-' . $data->id . '"></label>
                            </div>';
                })
                ->addColumn('action', function ($data) {
                    return '<a href="' . route('product.edit', $data->id) . '" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button type="button" onclick="showDeleteAlert(' . $data->id . ')" class="btn btn-sm btn-danger">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>';
                })
                ->rawColumns(['status', 'bulk_check', 'action'])
                ->make(true);
        }

        return view('backend.layout.product.index');
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $vendors = VendorProfile::where('status', 'active')->get();
        return view('backend.layout.V1.product.create', compact('categories', 'vendors'));
    }

    public function store(ProductCreateRequest $request)
    {
        $product = new Product();
        $product->vendor_id = $request->vendor_id;
        $product->category_id = $request->category_id;
        $product->name = $request->name;

        // slug
        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
        $product->slug = $count > 0 ? "{$slug}-{$count}" : $slug;

        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;

        // medias
        if ($request->hasFile('medias')) {
            $medias = [];
            foreach ($request->file('medias') as $file) {
                $path = Helper::fileUpload($file, 'products', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                if ($path) $medias[] = $path;
            }
            $product->medias = json_encode($medias);
        }

        $product->views = 0;
        $product->status = 'active';
        $product->save();

        flash()->success('Product created successfully.');
        return redirect()->route('product.index');
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $vendors = VendorProfile::where('status', 'active')->get();
        return view('backend.layout.V1.product.edit', compact('product', 'categories', 'vendors'));
    }

    public function update(ProductUpdateRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        if ($request->filled('vendor_id')) $product->vendor_id = $request->vendor_id;
        if ($request->filled('category_id')) $product->category_id = $request->category_id;
        if ($request->filled('name')) {
            $product->name = $request->name;
            $slug = Str::slug($request->name);
            $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
            $product->slug = $count > 0 ? "{$slug}-{$count}" : $slug;
        }
        if ($request->filled('price')) $product->price = $request->price;
        if ($request->filled('stock')) $product->stock = $request->stock;
        if ($request->filled('description')) $product->description = $request->description;

        // medias
        if ($request->hasFile('medias')) {
            $existingMedias = $product->medias ? json_decode($product->medias, true) : [];
            foreach ($request->file('medias') as $file) {
                $path = Helper::fileUpload($file, 'products', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                if ($path) $existingMedias[] = $path;
            }
            $product->medias = json_encode($existingMedias);
        }

        $product->save();

        flash()->success('Product updated successfully.');
        return redirect()->route('product.index');
    }

    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->medias) {
                foreach (json_decode($product->medias, true) as $media) {
                    Helper::deleteFile($media);
                }
            }

            $product->delete();

            return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Product: ' . $e->getMessage()], 422);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->status = $product->status === 'active' ? 'inactive' : 'active';
            $product->save();

            return response()->json([
                'success' => true,
                'message' => $product->status === 'active' ? 'Published successfully' : 'Unpublished successfully'
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        if (!$request->ajax()) return response()->json(['success' => false, 'message' => 'Invalid request'], 400);

        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No items selected'], 400);

        try {
            $products = Product::whereIn('id', $ids)->get();

            foreach ($products as $product) {
                if ($product->medias) {
                    foreach (json_decode($product->medias, true) as $media) {
                        Helper::deleteFile($media);
                    }
                }
            }

            Product::whereIn('id', $ids)->delete();

            return response()->json(['success' => true, 'message' => count($ids) . ' Product(s) deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items'], 500);
        }
    }
}
