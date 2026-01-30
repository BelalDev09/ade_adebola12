@extends('backend.app')

@section('title', 'Profile')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Cover Image --}}
            <div class="position-relative mb-4">
                @if ($user->cover_image)
                    <img src="{{ asset('storage/' . $user->cover_image) }}" class="w-100 rounded"
                        style="height:250px; object-fit:cover">
                @else
                    <div class="bg-primary rounded" style="height:250px;"></div>
                @endif

                {{-- Avatar --}}
                <div class="position-absolute bottom-0 start-50 translate-middle-x">
                    <div class="rounded-circle bg-light border" style="width:120px; height:120px; overflow:hidden">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-100 h-100">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 fs-1">
                                {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Personal Info --}}
            <div class="card">
                <div class="card-body">
                    <h4>{{ $user->name }}</h4>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
                    <p><strong>City:</strong> {{ $user->city ?? '-' }}</p>
                    <p><strong>Country:</strong> {{ $user->country ?? '-' }}</p>
                    <p><strong>Zip Code:</strong> {{ $user->zip_code ?? '-' }}</p>
                    <p><strong>Designation:</strong> {{ $user->designation ?? '-' }}</p>
                    <p><strong>Website:</strong> {{ $user->website ?? '-' }}</p>
                    <p><strong>Skills:</strong>
                        @if ($user->skills)
                            {{ implode(', ', json_decode($user->skills, true)) }}
                        @else
                            -
                        @endif
                    </p>
                    <p><strong>Description:</strong> {{ $user->description ?? '-' }}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
