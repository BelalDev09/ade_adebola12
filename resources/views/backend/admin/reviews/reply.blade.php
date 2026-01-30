<div class="reply-card mb-2" style="padding-left: 15px; border-left: 2px solid #555;">
    <div class="d-flex align-items-center gap-2 mb-1">
        <img src="{{ $reply->user && $reply->user->avatar
            ? asset('storage/' . $reply->user->avatar)
            : asset('backend/default-avatar.png') }}"
            style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
        <strong>{{ $reply->user->name ?? 'User' }}</strong>
        <small class="text-muted ms-2">
            {{ $reply->created_at->diffForHumans() }}
        </small>
    </div>
    <p>{{ $reply->description }}</p>

    @if ($reply->replies->count())
        <div class="nested-replies ms-3">
            @foreach ($reply->replies as $child)
                @include('backend.admin.reviews.reply', ['reply' => $child])
            @endforeach
        </div>
    @endif
</div>

{{-- {{ $user->avatar ? asset('storage/' . $user->avatar) --}}
