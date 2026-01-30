<?php

// namespace App\Http\Controllers\API;

// use App\Models\Cms;
// use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Validator;

// class CmsController extends Controller
// {

//     public function index()
//     {
//         $cms = Cms::latest()->get();

//         return response()->json($cms->map(function ($item) {
//             return [
//                 'id' => $item->id,
//                 'page_name' => $item->page_name,
//                 'section_name' => $item->section_name,
//                 'title' => $item->title,
//                 'subtitle' => $item->subtitle,
//                 'description' => $item->description,
//                 'image_url' => $item->image_url,
//                 'extra_data' => $item->extra_data,
//                 'status' => $item->status,
//             ];
//         }));
//     }

    //Store CMS
//     public function store(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'page_name' => 'required|string',
//             'section_name' => 'required|string',
//             'title' => 'nullable|string',
//             'subtitle' => 'nullable|string',
//             'description' => 'nullable|string',
//             'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
//             'extra_data' => 'nullable|array',
//             'status' => 'required|in:active,inactive',
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

//         $data = $request->except('image');

//         if ($request->hasFile('image')) {
//             $filename = time() . '.' . $request->image->extension();
//             $request->image->storeAs('cms', $filename, 'public');
//             $data['image'] = 'cms/' . $filename;
//         }

//         $cms = Cms::create($data);

//         return response()->json([
//             'message' => 'CMS created successfully',
//             'data' => $cms
//         ], 201);
//     }
//     public function show($id)
//     {
//         $cms = Cms::findOrFail($id);

//         return response()->json($cms);
//     }

//     public function update(Request $request, $id)
//     {
//         $cms = Cms::findOrFail($id);

//         $data = $request->except('image');

//         if ($request->hasFile('image')) {
//             $filename = time() . '.' . $request->image->extension();
//             $request->image->storeAs('cms', $filename, 'public');
//             $data['image'] = 'cms/' . $filename;
//         }

//         $cms->update($data);

//         return response()->json([
//             'message' => 'CMS updated successfully',
//             'data' => $cms
//         ]);
//     }


//     public function destroy($id)
//     {
//         Cms::findOrFail($id)->delete();

//         return response()->json([
//             'message' => 'CMS deleted successfully'
//         ]);
//     }
// }
