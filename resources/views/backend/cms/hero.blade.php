@extends('backend.app')
@section('title', 'Hero Section')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('cms.hero.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm"
            id="heroForm">
            @csrf

            <div class="card-header">
                <h5 class="mb-0">Hero Section</h5>
            </div>

            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $cms?->title ?? '') }}" class="form-control"
                        required>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $cms?->description ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="btn_text" value="{{ old('btn_text', $cms?->btn_text ?? '') }}"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Button Link</label>
                    <input type="url" name="btn_link" value="{{ old('btn_link', $cms?->btn_link ?? '') }}"
                        class="form-control" placeholder="https://example.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hero Image</label>
                    <input type="file" name="image" class="form-control" id="heroImageInput"
                        onchange="previewHeroImage(event)">

                    <label class="form-label d-block mt-2">Preview</label>
                    <img id="heroImagePreview"
                        src="{{ isset($cms) && $cms->image_path ? asset('storage/' . $cms->image_path) : asset('images/placeholder.png') }}"
                        class="img-thumbnail" style="max-height: 140px; object-fit: cover;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label><br>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                            {{ old('status', $cms?->status ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" id="statusLabel" style="color: #198754;">Active</label>
                    </div>
                </div>

            </div>

            <div class="card-footer text-end">
                <button class="btn btn-primary" type="submit">Save Changes</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Image preview
            function previewHeroImage(event) {
                const input = event.target;
                const preview = document.getElementById('heroImagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = "{{ asset('images/placeholder.png') }}";
                }
            }

            // Status label update
            document.addEventListener('DOMContentLoaded', function() {
                const checkbox = document.getElementById('status');
                const label = document.getElementById('statusLabel');

                function updateStatusLabel() {
                    if (checkbox.checked) {
                        label.textContent = 'Active';
                        label.style.color = '#198754';
                    } else {
                        label.textContent = 'Inactive';
                        label.style.color = '#6c757d';
                    }
                }

                updateStatusLabel();
                checkbox.addEventListener('change', updateStatusLabel);
            });

            $(function() {
                $('#heroForm').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let formData = new FormData(this);
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: res.message || 'Saved Successfully!',
                                toast: true,
                                position: 'top-end',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Reset form
                            form.trigger('reset');
                            // Reset image
                            $('#heroImagePreview').attr('src',
                                "{{ asset('images/placeholder.png') }}");
                            // Reset status
                            $('#status').prop('checked', true).trigger('change');
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message || 'Something went wrong!',
                                toast: true,
                                position: 'top-end',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
