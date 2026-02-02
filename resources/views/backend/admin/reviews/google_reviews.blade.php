@extends('backend.app')

@section('content')
    <div class="container">
        <div class="mb-3">
            <h3>{{ $location->title }}</h3>
            <small class="text-muted">{{ $location->latitude }}, {{ $location->longitude }}</small>
            <div>
                ⭐ {{ number_format($reviews->avg('rating'), 1) }} / 5
                <span class="text-muted">({{ $reviews->count() }} reviews)</span>
            </div>
            <hr>
        </div>

        @foreach ($reviews as $review)
            <div class="review-card p-3 mb-3" style="background: #1E2A38; border-radius: 8px; color: #fff;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $review->user && $review->user->avatar
                            ? asset('storage/' . $review->user->avatar)
                            : asset('backend/default-avatar.png') }}"
                            alt="User"
                            style="
                                width:40px;
                                height:40px;
                                border-radius:50%;
                                object-fit:cover;
                                ">

                        <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                    </div>

                    <small class="text-muted">
                        {{ $review->created_at->diffForHumans() }}
                    </small>
                </div>


                <div class="review-rating mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= $review->rating ? 'active' : '' }}">★</span>
                    @endfor
                </div>


                <p>{{ $review->description }}</p>

                @if (!empty($review->medias))
                    <div class="review-media mb-2" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @foreach ($review->medias as $media)
                            <img src="{{ asset('storage/' . $media) }}"
                                style="max-width:100px; height:auto; border-radius:4px;">
                        @endforeach
                    </div>
                @endif

                @if ($review->replies->count())
                    <div class="replies ms-3 mt-2">
                        @foreach ($review->replies as $reply)
                            @include('backend.admin.reviews.reply', ['reply' => $reply])
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $reviews->links() }}
    </div>
@endsection
