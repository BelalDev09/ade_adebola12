@extends('backend.app')

@section('title', 'Profile')

@section('content')

    <div class="tab-content pt-4 text-muted">
        <div class="tab-pane active" id="overview-tab" role="tabpanel">
            <div class="row">
                <div class="col-xxl-3">
                    <!-- Profile Completion -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-5">Complete Your Profile</h5>
                            <div class="progress animated-progress custom-progress progress-label">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <div class="label">65%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Info</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0" scope="row">Full Name :</th>
                                            <td class="text-muted">{{ Auth::user()->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Mobile :</th>
                                            <td class="text-muted">{{ Auth::user()->mobile ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">E-mail :</th>
                                            <td class="text-muted">{{ Auth::user()->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Location :</th>
                                            <td class="text-muted">{{ Auth::user()->location ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Joining Date :</th>
                                            <td class="text-muted">
                                                {{ Auth::user()->joining_date ? \Carbon\Carbon::parse(Auth::user()->joining_date)->format('d M Y') : Auth::user()->created_at->format('d M Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Portfolio</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <!-- GitHub, Website, Dribbble, Pinterest ইত্যাদি যোগ করতে পারো যদি users টেবিলে ফিল্ড থাকে -->
                                <a href="#" class="avatar-xs d-block">
                                    <span class="avatar-title rounded-circle fs-16 bg-body text-body material-shadow">
                                        <i class="ri-github-fill"></i>
                                    </span>
                                </a>
                                <!-- আরও যোগ করতে পারো -->
                            </div>
                        </div>
                    </div>

                    <!-- Skills (যদি users টেবিলে json/array ফিল্ড রাখো) -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Skills</h5>
                            <div class="d-flex flex-wrap gap-2 fs-15">
                                @if (Auth::user()->skills)
                                    @foreach (json_decode(Auth::user()->skills ?? '[]') as $skill)
                                        <span class="badge bg-primary-subtle text-primary">{{ $skill }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No skills added yet</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xxl-9">
                    <!-- About -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">About</h5>
                            <p>{{ Auth::user()->about ?? 'No bio added yet.' }}</p>

                            <div class="row">
                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div
                                                class="avatar-title bg-light rounded-circle fs-16 text-primary material-shadow">
                                                <i class="ri-user-2-fill"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Designation :</p>
                                            <h6 class="text-truncate mb-0">{{ Auth::user()->designation ?? 'N/A' }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div
                                                class="avatar-title bg-light rounded-circle fs-16 text-primary material-shadow">
                                                <i class="ri-global-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Website :</p>
                                            <a href="{{ Auth::user()->website ?? '#' }}" class="fw-semibold">
                                                {{ Auth::user()->website ?? 'N/A' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity / Projects / Documents ইত্যাদি -->
                    <!-- এখানে তোমার অরিজিনাল কোড রাখো, কিন্তু যেখানে static ডাটা আছে সেগুলো dynamic করো -->

                    <!-- উদাহরণ: Profile Picture যোগ করতে চাইলে -->
                    <div class="text-center mb-4">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile"
                                class="avatar-xl rounded-circle img-thumbnail">
                        @else
                            <div
                                class="avatar-xl rounded-circle bg-light text-primary d-flex align-items-center justify-content-center mx-auto">
                                <span class="fs-3">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
