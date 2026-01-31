@extends('backend.app')
@section('title', 'Testimonials')

@section('content')
    <div class="container-fluid">

        <form action="{{ route('cms.testimonials.store') }}" method="POST" class="card shadow-sm" id="testimonialForm">
            @csrf
            <input type="hidden" name="id" value="{{ $cms->id ?? '' }}">

            <div class="card-header">
                <h5>Testimonials</h5>
            </div>

            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label>Title *</label>
                    <input name="title" id="title" class="form-control" value="{{ old('title', $cms?->title ?? '') }}"
                        required>
                </div>

                <div class="col-12">
                    <label>Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $cms?->description ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                            {{ old('status', $cms->status ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" id="statusLabel">
                            {{ old('status', $cms->status ?? 1) ? 'Active' : 'Inactive' }}
                        </label>
                    </div>
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
            $(function() {

                // Status label update function
                function updateLabel($checkbox, $label) {
                    if ($checkbox.is(':checked')) {
                        $label.text('Active').removeClass('text-secondary').addClass('text-success');
                    } else {
                        $label.text('Inactive').removeClass('text-success').addClass('text-secondary');
                    }
                }

                // Initial label on page load
                updateLabel($('#status'), $('#statusLabel'));

                // Update label when toggled
                $('#status').on('change', function() {
                    updateLabel($(this), $('#statusLabel'));
                });

                // AJAX form submit
                $('#testimonialForm').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);

                    $.post(form.attr('action'), form.serialize(), function(res) {
                        // Success toast
                        Swal.fire({
                            icon: 'success',
                            title: res.message || 'Saved Successfully!',
                            toast: true,
                            position: 'top-end',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Reset the form
                        form.trigger('reset');

                        // Update status label after reset
                        updateLabel($('#status'), $('#statusLabel'));

                    }).fail(function(xhr) {
                        // Error toast
                        Swal.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Something went wrong!',
                            toast: true,
                            position: 'top-end',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                });

            });
        </script>
    @endpush
@endsection
