@extends('backend.app')

@section('content')
    <div class="container">
        <div class="card p-4" style="max-width: 720px; margin:auto; border-radius:16px">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Report #{{ $report->id }}</h5>
                <small class="text-muted">
                    {{ $report->created_at->format('M d, Y') }}
                </small>
            </div>

            <span
                class="badge
            bg-{{ $report->status == 'approved' ? 'success' : ($report->status == 'rejected' ? 'danger' : 'warning') }}">
                {{ ucfirst($report->status) }}
            </span>

            <hr>

            {{-- Reason --}}
            <h6>Reason</h6>
            <div class="alert alert-light">
                {{ ucfirst(str_replace('_', ' ', $report->reason_code)) }}
            </div>

            {{-- Description --}}
            <h6>Description</h6>
            <div class="alert alert-light">
                {{ $report->description }}
            </div>

            {{-- Attached Images --}}
            @if (!empty($report->medias))
                <h6>Attached Image</h6>
                <div class="mb-3">
                    @foreach ($report->medias as $media)
                        <img src="{{ asset('storage/' . $media) }}" style="max-width:100px; height:auto; border-radius:4px;">
                    @endforeach
                </div>
            @endif

            <hr>

            {{-- Review Information --}}
            <h5>Review Information</h5>

            {{-- Rating --}}
            <p>
                <strong>Rating</strong><br>
                @for ($i = 1; $i <= 5; $i++)
                    <span style="color:{{ $i <= $report->review->rating ? '#fbbc04' : '#ccc' }}">
                        ★
                    </span>
                @endfor
                <small class="text-muted">{{ $report->review->rating }}/5</small>
            </p>

            {{-- Comment --}}
            <p>
                <strong>Comment</strong><br>
                {{ $report->review->description }}
            </p>

            {{-- Location --}}
            <p>
                <strong>Location</strong><br>
                {{ $report->review->location->title ?? '-' }}
            </p>

            <hr>

            {{-- Actions --}}
            <form method="POST" action="{{ route('backend.admin.reports.update-status', $report->id) }}"
                class="d-flex gap-2">
                @csrf

                @if ($report->status !== 'approved')
                    <button name="status" value="approved" class="btn btn-success">
                        Approve
                    </button>
                @endif

                @if ($report->status !== 'rejected')
                    <button name="status" value="rejected" class="btn btn-danger">
                        Reject
                    </button>
                @endif
            </form>

            @if ($report->status !== 'pending')
                <small class="text-muted">
                    Current status: <strong>{{ ucfirst($report->status) }}</strong>
                </small>
            @endif


        </div>
    </div>
@endsection
