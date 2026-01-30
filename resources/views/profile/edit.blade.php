@extends('backend.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Success Message --}}
            <div id="successMsg" class="alert alert-success{{ session('status') == 'profile-updated' ? '' : ' d-none' }}">
                {{ session('status') == 'profile-updated' ? 'Profile updated successfully.' : '' }}
            </div>

            {{-- Error Message --}}
            <div id="errorMsg" class="alert alert-danger d-none"></div>

            {{-- PROFILE UPDATE FORM --}}
            <form id="profileForm" enctype="multipart/form-data" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                {{-- ================= COVER & AVATAR ================= --}}
                <div class="position-relative mb-4">
                    {{-- Cover --}}
                    @if (!empty($user->cover_image))
                        <img id="coverPreview" src="{{ asset('storage/' . $user->cover_image) }}" class="w-100 rounded"
                            style="height:250px;object-fit:cover">
                    @else
                        <img id="coverPreview" src="" class="w-100 rounded d-none"
                            style="height:250px;object-fit:cover">
                        <div id="coverFallback" class="bg-primary rounded" style="height:250px;"></div>
                    @endif

                    <label class="btn btn-light position-absolute top-0 end-0 m-3" style="cursor:pointer;">
                        Change Cover
                        <input id="coverInput" type="file" name="cover_image" accept="image/*" hidden>
                    </label>

                    {{-- Avatar --}}
                    <div class="position-absolute bottom-0 start-50 translate-middle-x">
                        <div class="rounded-circle bg-light border" style="width:120px;height:120px;overflow:hidden">
                            @if (!empty($user->avatar))
                                <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" class="w-100 h-100">
                            @else
                                <img id="avatarPreview" src="" class="w-100 h-100 d-none">
                                <div id="avatarFallback" class="d-flex align-items-center justify-content-center h-100 fs-1"
                                    style="width:120px;height:120px;">
                                    {{ strtoupper(substr($user->first_name ?? ($user->name ?? ''), 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <label class="btn btn-primary btn-sm rounded-circle mt-n4 ms-5" style="cursor:pointer;">
                            <i class="ri-camera-line"></i>
                            <input id="avatarInput" type="file" name="avatar" accept="image/*" hidden>
                        </label>
                    </div>
                </div>

                {{-- ================= FORM FIELDS ================= --}}
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                    data-bs-target="#personal" type="button" role="tab" aria-controls="personal"
                                    aria-selected="true">Personal</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password"
                                    type="button" role="tab" aria-controls="password"
                                    aria-selected="false">Password</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">

                            {{-- PERSONAL TAB --}}
                            <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                aria-labelledby="personal-tab">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" name="first_name"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            placeholder="First Name" value="{{ old('first_name', $user->first_name) }}">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="last_name"
                                            class="form-control @error('last_name') is-invalid @enderror"
                                            placeholder="Last Name" value="{{ old('last_name', $user->last_name) }}">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror" placeholder="Phone"
                                            value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                            value="{{ old('email', $user->email) }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="city"
                                            class="form-control @error('city') is-invalid @enderror" placeholder="City"
                                            value="{{ old('city', $user->city) }}">
                                        @error('city')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="country"
                                            class="form-control @error('country') is-invalid @enderror"
                                            placeholder="Country" value="{{ old('country', $user->country) }}">
                                        @error('country')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="zip_code"
                                            class="form-control @error('zip_code') is-invalid @enderror"
                                            placeholder="Zip Code" value="{{ old('zip_code', $user->zip_code) }}">
                                        @error('zip_code')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="designation"
                                            class="form-control @error('designation') is-invalid @enderror"
                                            placeholder="Designation"
                                            value="{{ old('designation', $user->designation) }}">
                                        @error('designation')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="url" name="website"
                                            class="form-control @error('website') is-invalid @enderror"
                                            placeholder="Website" value="{{ old('website', $user->website) }}">
                                        @error('website')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <textarea name="skills" class="form-control @error('skills') is-invalid @enderror" rows="3"
                                            placeholder="Skills (one per line)">{{ old('skills', is_array($user->skills ?? null) ? implode("\n", $user->skills) : $user->skills ?? '') }}</textarea>
                                        @error('skills')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                            placeholder="Description">{{ old('description', $user->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Update Profile</button>
                            </div>
                            {{-- PASSWORD TAB --}}
                            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                {{-- Separate form for password --}}
                                <form method="POST" action="{{ route('profile.password') }}" id="changePasswordForm"
                                    autocomplete="off">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="password" name="current_password"
                                            class="form-control @error('current_password', 'passwordChange') is-invalid @enderror"
                                            placeholder="Current Password" autocomplete="current-password">
                                        @error('current_password', 'passwordChange')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password"
                                            class="form-control @error('password', 'passwordChange') is-invalid @enderror"
                                            placeholder="New Password" autocomplete="new-password">
                                        @error('password', 'passwordChange')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Confirm Password" autocomplete="new-password">
                                    </div>
                                    <button class="btn btn-warning" type="submit">Change Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- ================= JS ================= --}}
    <script>
        // Helper to show/hide success/error
        function showMessage(id, message, isError = false) {
            const el = document.getElementById(id);
            if (el) {
                el.innerHTML = message || '';
                el.classList.remove('d-none');
                if (isError) {
                    el.classList.remove('alert-success');
                    el.classList.add('alert-danger');
                } else {
                    el.classList.remove('alert-danger');
                    el.classList.add('alert-success');
                }
                el.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
            }
        }

        function hideMessage(id) {
            const el = document.getElementById(id);
            if (el) {
                el.innerHTML = '';
                el.classList.add('d-none');
            }
        }

        // Preview logic for images
        function previewFile(input, previewId, fallbackId = null) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                if (fallbackId) {
                    const fallback = document.getElementById(fallbackId);
                    if (fallback) fallback.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatarInput');
            if (avatarInput) {
                avatarInput.addEventListener('change', function() {
                    previewFile(this, 'avatarPreview', 'avatarFallback');
                });
            }

            const coverInput = document.getElementById('coverInput');
            if (coverInput) {
                coverInput.addEventListener('change', function() {
                    previewFile(this, 'coverPreview', 'coverFallback');
                });
            }

            // Handle profile form submit with AJAX
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    hideMessage('successMsg');
                    hideMessage('errorMsg');

                    const formData = new FormData(profileForm);
                    const btn = profileForm.querySelector('button[type="submit"]');
                    const originalBtnText = btn ? btn.innerHTML : '';
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = 'Saving...';
                    }

                    fetch(profileForm.action, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    }).then(async (response) => {
                        if (response.ok) {
                            let data = {};
                            try {
                                data = await response.json();
                            } catch {}
                            if (data.status && data.status === 'profile-updated') {
                                showMessage('successMsg', 'Profile updated successfully.');
                            } else {
                                showMessage('successMsg', 'Profile updated successfully.');
                            }
                        } else if (response.status === 422) {
                            // Validation error
                            const data = await response.json();
                            let errorText = '';
                            if (data.errors) {
                                errorText = Object.values(data.errors).flat().join('<br>');
                            }
                            showMessage('errorMsg', errorText || 'Validation failed.', true);
                        } else {
                            showMessage('errorMsg',
                                'Profile update failed. Please try again. Server error: ' +
                                response.status, true);
                        }
                    }).catch(function(err) {
                        showMessage('errorMsg', 'Profile update failed. Please try again.', true);
                    }).finally(function() {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalBtnText;
                        }
                    });
                });
            }

            // Password form submit via AJAX
            const passwordForm = document.getElementById('changePasswordForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    hideMessage('successMsg');
                    hideMessage('errorMsg');

                    const formData = new FormData(passwordForm);
                    const btn = passwordForm.querySelector('button[type="submit"]');
                    const originalBtnText = btn ? btn.innerHTML : '';
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = 'Saving...';
                    }

                    fetch(passwordForm.action, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    }).then(async (response) => {
                        if (response.ok) {
                            let data = {};
                            try {
                                data = await response.json();
                            } catch {}
                            if (data.status && data.status === 'password-updated') {
                                showMessage('successMsg', 'Password updated successfully.');
                            } else {
                                showMessage('successMsg', 'Password updated successfully.');
                            }
                        } else if (response.status === 422) {
                            // Validation error
                            const data = await response.json();
                            let errorText = '';
                            if (data.errors) {
                                errorText = Object.values(data.errors).flat().join('<br>');
                            }
                            showMessage('errorMsg', errorText || 'Validation failed.', true);
                        } else {
                            showMessage('errorMsg',
                                'Password update failed. Please try again. Server error: ' +
                                response.status, true);
                        }
                    }).catch(function(err) {
                        showMessage('errorMsg', 'Password update failed. Please try again.', true);
                    }).finally(function() {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalBtnText;
                        }
                    });
                });
            }
        });
    </script>
@endsection
