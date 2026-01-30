@extends('backend.app')
@section('title', 'How It Works')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('cms.how-it-works.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm"
            id="howItWorksForm">
            @csrf

            <div class="card-header">
                <h5>How It Works</h5>
            </div>

            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Title *</label>
                    <input name="title" class="form-control" value="{{ old('title', $cms?->title ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="col-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $cms?->description ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">How-It Image</label>
                    <input type="file" name="image" class="form-control" id="heroImageInput"
                        onchange="previewHeroImage(event)">

                    <label class="form-label d-block">Preview</label>
                    <img id="heroImagePreview" src="{{ asset('images/placeholder.png') }}" class="img-thumbnail"
                        style="max-height:120px">
                </div>

            </div>

            <div class="card-footer text-end">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function previewHeroImage(event) {
                const input = event.target;
                const preview = document.getElementById('heroImagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result; // selected image preview
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = "{{ asset('images/placeholder.png') }}";
                }
            }

            $(function() {

                $('#howItWorksForm').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let formData = new FormData(this); // for file upload

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
                            // Reset image preview
                            $('#heroImagePreview').attr('src',
                                "{{ asset('images/placeholder.png') }}");
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
