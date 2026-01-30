<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Review;
use App\Models\ReviewLike;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Recursive function to attach counts and nested replies
     */
    protected function attachCountsAndReplies($reviews)
    {
        return $reviews->map(function ($review) {

            // locations count
            $review->locations_count = $review->locations->count();

            // likes count
            $review->likes_count = $review->likes()->where('liked', true)->count();

            // recursively handle replies
            if ($review->replies->count() > 0) {
                $review->replies = $this->attachCountsAndReplies($review->replies);
            }

            return $review;
        });
    }
    // index
    public function index(Request $request)
    {
        $request->validate([
            'title'     => 'nullable|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $locations = Location::query()
            ->when($request->title, fn($q) => $q->where('title', 'like', "%{$request->title}%"))
            ->when($request->latitude, fn($q) => $q->where('latitude', $request->latitude))
            ->when($request->longitude, fn($q) => $q->where('longitude', $request->longitude))
            ->get();

        if ($locations->isEmpty()) {
            return response()->json([
                'message' => 'No locations found matching your filters',
                'reviews' => []
            ], 200);
        }

        $reviews = Review::with([
            'locations' => fn($q) => $q->whereIn('locations.id', $locations->pluck('id')),
            'replies' => fn($q) => $q->with([
                'locations' => fn($q2) => $q2->whereIn('locations.id', $locations->pluck('id')),
                'replies',
                'likes'
            ])
        ])
            ->whereHas('locations', fn($q) => $q->whereIn('locations.id', $locations->pluck('id')))
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $reviews = $this->attachCountsAndReplies($reviews);

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

    /**
     * Store a review
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id'   => 'required|array',
            'location_id.*' => 'exists:locations,id',
            'parent_id'     => 'nullable|exists:reviews,id',
            'rating'        => 'nullable|integer|min:0|max:5',
            'role'          => 'nullable|string|max:50',
            'description'   => 'nullable|string',
            'messages'      => 'nullable|string|max:500',
            'medias'        => 'nullable|array',
            'medias.*'      => 'image|max:2048',
        ]);


        if ($request->hasFile('medias')) {
            $data['medias'] = collect($request->file('medias'))
                ->map(fn($file) => $file->store('reviews', 'public'))
                ->toArray();
        }

        $mainLocationId = $data['location_id'][0];

        $reviewData = $data;
        unset($reviewData['location_id']);
        $reviewData['location_id'] = $mainLocationId;
        $reviewData['user_id'] = auth('api')->id();

        $review = Review::create($reviewData);

        $review->locations()->sync($data['location_id']);


        $review->load('locations', 'replies', 'likes');

        $review->likes_count = $review->likes()->where('liked', true)->count();
        $review->locations_count = $review->locations->count();

        return response()->json([
            'message' => 'Review submitted successfully',
            'data'    => $review,
        ], 201);
    }

    /**
     * Like / Unlike a review
     */
    public function toggleLike(Review $review)
    {
        $userId = auth('api')->id();

        $like = ReviewLike::firstOrNew([
            'review_id' => $review->id,
            'user_id'   => $userId,
        ]);

        $like->liked = !($like->liked ?? false);
        $like->save();

        $likesCount = ReviewLike::where('review_id', $review->id)
            ->where('liked', true)
            ->count();

        return response()->json([
            'liked' => $like->liked,
            'likes' => $likesCount,
        ]);
    }
}
