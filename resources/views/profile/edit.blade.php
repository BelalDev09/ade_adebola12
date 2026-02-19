@extends('backend.app')

@section('title', 'Profile Settings')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">Profile Settings</h4>
                <p class="text-muted mb-0">Manage your personal details, media, and security.</p>
            </div>
            @can('profile.view')
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    View Profile
                </a>
            @endcan
        </div>

        {{-- Success Message --}}
        <div id="successMsg" class="alert alert-success{{ session('status') == 'profile-updated' ? '' : ' d-none' }}">
            {{ session('status') == 'profile-updated' ? 'Profile updated successfully.' : '' }}
        </div>

        {{-- Error Message --}}
        <div id="errorMsg" class="alert alert-danger d-none"></div>

        <form id="profileForm" enctype="multipart/form-data" method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img class="rounded-circle border"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') }}"
                                    alt="Avatar" style="width:96px;height:96px;object-fit:cover;">
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-2">{{ $user->designation ?? 'Member' }}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="badge bg-primary-subtle text-primary">{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Profile Media</h6>
                        </div>
                        <div class="card-body">
                            @include('backend.partials.form.image-input', [
                                'name' => 'avatar',
                                'label' => 'Avatar',
                                'value' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                                'accept' => 'image/*',
                                'height' => 180,
                                'removeName' => 'avatar_remove',
                                'id' => 'avatarInput',
                            ])

                            <div class="mt-4">
                                @include('backend.partials.form.image-input', [
                                    'name' => 'cover_image',
                                    'label' => 'Cover Image',
                                    'value' => $user->cover_image ? asset('storage/' . $user->cover_image) : null,
                                    'accept' => 'image/*',
                                    'height' => 220,
                                    'removeName' => 'cover_image_remove',
                                    'id' => 'coverInput',
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Profile Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name"
                                        class="form-control @error('first_name') is-invalid @enderror"
                                        value="{{ old('first_name', $user->first_name) }}">
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name"
                                        class="form-control @error('last_name') is-invalid @enderror"
                                        value="{{ old('last_name', $user->last_name) }}">
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city"
                                        class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city', $user->city) }}">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country"
                                        class="form-control @error('country') is-invalid @enderror"
                                        value="{{ old('country', $user->country) }}">
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Zip Code</label>
                                    <input type="text" name="zip_code"
                                        class="form-control @error('zip_code') is-invalid @enderror"
                                        value="{{ old('zip_code', $user->zip_code) }}">
                                    @error('zip_code')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Designation</label>
                                    <input type="text" name="designation"
                                        class="form-control @error('designation') is-invalid @enderror"
                                        value="{{ old('designation', $user->designation) }}">
                                    @error('designation')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Website</label>
                                    <input type="url" name="website"
                                        class="form-control @error('website') is-invalid @enderror"
                                        value="{{ old('website', $user->website) }}">
                                    @error('website')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Skills</label>
                                    <textarea name="skills" class="form-control @error('skills') is-invalid @enderror" rows="3"
                                        placeholder="One per line or comma separated">{{ old('skills', is_array($user->skills ?? null) ? implode("\n", $user->skills) : $user->skills ?? '') }}</textarea>
                                    @error('skills')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $user->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.password') }}" id="changePasswordForm" autocomplete="off"
            class="mt-4">
            @csrf
            <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Account Security</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password"
                                        class="form-control @error('current_password', 'passwordChange') is-invalid @enderror"
                                        autocomplete="current-password">
                                    @error('current_password', 'passwordChange')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password', 'passwordChange') is-invalid @enderror"
                                        autocomplete="new-password">
                                    @error('password', 'passwordChange')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        autocomplete="new-password">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-warning" type="submit">Change Password</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                            const data = await response.json();
                            let errorText = '';
                            if (data.errors) {
                                errorText = Object.values(data.errors).flat().join('<br>');
                            }
                            showMessage('errorMsg', errorText || 'Validation failed.', true);
                        } else {
                            showMessage('errorMsg',
                                'Profile update failed. Please try again. Server error: ' + response.status, true);
                        }
                    }).catch(function() {
                        showMessage('errorMsg', 'Profile update failed. Please try again.', true);
                    }).finally(function() {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalBtnText;
                        }
                    });
                });
            }

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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                            const data = await response.json();
                            let errorText = '';
                            if (data.errors) {
                                errorText = Object.values(data.errors).flat().join('<br>');
                            }
                            showMessage('errorMsg', errorText || 'Validation failed.', true);
                        } else {
                            showMessage('errorMsg',
                                'Password update failed. Please try again. Server error: ' + response.status, true);
                        }
                    }).catch(function() {
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
