@extends('backend.app')

@section('title', 'Profile')

@section('content')

    <div class="row g-4">
        <div class="col-12 col-xxl-3">
            <div class="card">
                <div class="card-body text-center">
                    <img class="rounded-circle border mb-3"
                        src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') }}"
                        alt="Avatar" style="width:96px;height:96px;object-fit:cover;">
                    <h5 class="mb-1">{{ $user->name ?? 'N/A' }}</h5>
                    <p class="text-muted mb-2">{{ $user->designation ?? 'Member' }}</p>
                    <span class="badge bg-primary-subtle text-primary">{{ $user->email }}</span>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">Info</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Full Name</th>
                                    <td class="text-muted">{{ $user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Phone</th>
                                    <td class="text-muted">{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Email</th>
                                    <td class="text-muted">{{ $user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Location</th>
                                    <td class="text-muted">
                                        {{ trim(($user->city ?? '') . ' ' . ($user->country ?? '')) ?: 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Joined</th>
                                    <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">Skills</h6>
                    <div class="d-flex flex-wrap gap-2 fs-15">
                        @if (!empty($user->skills))
                            @foreach ($user->skills as $skill)
                                <span class="badge bg-primary-subtle text-primary">{{ $skill }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No skills added yet</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xxl-9">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">About</h6>
                    <p class="mb-4">{{ $user->description ?? 'No bio added yet.' }}</p>

                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 avatar-xs me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary material-shadow">
                                        <i class="ri-user-2-fill"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">Designation</p>
                                    <h6 class="mb-0">{{ $user->designation ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 avatar-xs me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary material-shadow">
                                        <i class="ri-global-line"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">Website</p>
                                    <a href="{{ $user->website ?? '#' }}" class="fw-semibold">
                                        {{ $user->website ?? 'N/A' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($user->cover_image)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $user->cover_image) }}" alt="Cover"
                                class="img-fluid rounded">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
