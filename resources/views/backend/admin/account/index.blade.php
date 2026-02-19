@extends('backend.app')

@section('title', 'Account Setting')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Account Setting</h4>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form id="accountForm" action="{{ route('backend.admin.account.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Account Details --}}
                        <h5 class="mb-3">Account Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">System Title <span class="text-danger">*</span></label>
                                <input type="text" name="system_title" class="form-control"
                                    value="{{ old('system_title', $settings->system_title ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Copyright Text <span class="text-danger">*</span></label>
                                <input type="text" name="copyright_text" class="form-control"
                                    value="{{ old('copyright_text', $settings->copyright_text ?? '') }}" required>
                            </div>
                        </div>

                        {{-- Logo & Favicon --}}
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                @include('backend.partials.form.image-input', [
                                    'name' => 'logo',
                                    'label' => 'Logo',
                                    'value' => $settings && $settings->logo ? asset('storage/' . $settings->logo) : null,
                                    'accept' => 'image/*',
                                    'height' => 160,
                                    'removeName' => 'logo_remove',
                                ])
                            </div>

                            <div class="col-md-6 mb-3">
                                @include('backend.partials.form.image-input', [
                                    'name' => 'favicon',
                                    'label' => 'Favicon',
                                    'value' => $settings && $settings->favicon ? asset('storage/' . $settings->favicon) : null,
                                    'accept' => 'image/*',
                                    'height' => 160,
                                    'removeName' => 'favicon_remove',
                                ])
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location.reload()">Discard</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
