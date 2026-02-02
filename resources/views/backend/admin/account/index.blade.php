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
                            {{-- Logo --}}
                            <div class="col-md-6 text-center mb-3">
                                <label class="form-label fw-semibold d-block">Logo</label>
                                <div class="position-relative d-inline-block border rounded p-2"
                                    style="width:160px;height:160px;">
                                    <img id="logoPreview"
                                        src="{{ $settings && $settings->logo ? asset('storage/' . $settings->logo) : asset('backend/images/default-logo.png') }}"
                                        style="width:150px;height:150px;object-fit:contain;" class="img-fluid rounded">
                                    <button type="button" class="btn btn-sm btn-primary position-absolute top-0 end-0"
                                        style="padding:2px 6px;"
                                        onclick="document.getElementById('logo').click()">✏️</button>
                                    <input type="file" name="logo" id="logo" class="d-none"
                                        accept=".png,.jpg,.jpeg">
                                </div>
                                <small class="text-muted d-block mt-2">Only *.png, *.jpg, *.jpeg</small>
                            </div>

                            {{-- Favicon --}}
                            <div class="col-md-6 text-center mb-3">
                                <label class="form-label fw-semibold d-block">Favicon</label>
                                <div class="position-relative d-inline-block border rounded p-2"
                                    style="width:160px;height:160px;">
                                    <img id="faviconPreview"
                                        src="{{ $settings && $settings->favicon ? asset('storage/' . $settings->favicon) : asset('backend/images/default-favicon.png') }}"
                                        style="width:150px;height:150px;object-fit:contain;" class="img-fluid rounded">
                                    <button type="button" class="btn btn-sm btn-primary position-absolute top-0 end-0"
                                        style="padding:2px 6px;"
                                        onclick="document.getElementById('favicon').click()">✏️</button>
                                    <input type="file" name="favicon" id="favicon" class="d-none"
                                        accept=".png,.jpg,.jpeg">
                                </div>
                                <small class="text-muted d-block mt-2">Only *.png, *.jpg, *.jpeg</small>
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function showImagePreview(inputId, previewId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);

                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            showImagePreview('logo', 'logoPreview');
            showImagePreview('favicon', 'faviconPreview');

            document.querySelectorAll('.btn-primary[onclick]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetInputId = this.getAttribute('onclick').match(/'(.+)'/)[1];
                    document.getElementById(targetInputId).click();
                });
            });

            document.getElementById('accountForm').addEventListener('submit', function(e) {

            });

        });
    </script>
@endsection
