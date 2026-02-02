@extends('backend.app')
@section('title', 'How It Works')

@push('styles')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- FORM -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 id="formTitle">Add How It Works Step</h5>
                    </div>
                    <div class="card-body">
                        <form id="howItWorksForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="howItWorksId" name="id">
                            <div class="mb-3">
                                <label>Title *</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea id="description" name="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" id="imageInput" name="image" class="form-control"
                                    onchange="previewImage(event)">
                                <label class="mt-2">Preview</label><br>
                                <img id="imagePreview" src="{{ asset('images/placeholder.png') }}" class="img-thumbnail"
                                    style="max-height:120px">
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <div class="form-check">
                                    <input type="checkbox" id="status" name="status" class="form-check-input" checked>
                                    <label class="form-check-label fw-bold text-success" id="statusLabel">Active</label>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary w-100" id="saveBtn">Save</button>
                                <button type="button" class="btn btn-secondary w-100 mt-2" id="resetBtn">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>How It Works List</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="howItWorksTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal for View/Edit -->
    <div class="modal fade" id="howItWorksModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleText">View / Edit How It Works</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modalHowId">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" id="modalTitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea id="modalDescription" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" id="modalStatus" class="form-check-input">
                            <span class="ms-2 fw-bold" id="modalStatusLabel"></span>
                        </div>
                    </div>
                    <div class="mb-3 position-relative" style="max-width:200px;">
                        <label class="form-label d-block">Image Preview</label>

                        <img id="modalImagePreview" src="{{ asset('images/placeholder.png') }}" class="img-thumbnail w-100"
                            style="object-fit:cover">

                        <button type="button" id="deleteImageBtn"
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle"
                            style="width:26px;height:26px;z-index:10"
                            data-url="{{ route('cms.how-it-works.image-delete', ['id' => 0]) }}">
                            &times;
                        </button>
                    </div>

                    <div class="mb-3" id="modalImageInputWrapper">
                        <label class="form-label btn btn-outline-primary d-inline-flex align-items-center">
                            <i class="bi bi-upload me-2"></i> Choose Image
                            <input type="file" class="d-none" id="modalImage" name="image"
                                onchange="previewImage(event,'modalImagePreview')">
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="modalSaveBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event, previewId = 'imagePreview') {
            const input = event.target;
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            function updateLabel(checkbox, label) {
                if (checkbox.prop('checked')) {
                    label.text('Active').removeClass('text-danger').addClass('text-success');
                } else {
                    label.text('Inactive').removeClass('text-success').addClass('text-danger');
                }
            }

            $('#status').on('change', function() {
                updateLabel($(this), $('#statusLabel'));
            });
            updateLabel($('#status'), $('#statusLabel'));

            // DataTable
            var table = $('#howItWorksTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('cms.how-it-works.form') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            let checked = data == 1 ? 'checked' : '';
                            let label = data == 1 ? 'Active' : 'Inactive';
                            let labelClass = data == 1 ? 'text-success' : 'text-secondary';
                            return `
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input status-switch" type="checkbox" data-id="${row.id}" ${checked}>
                            </div>
                            <span class="ms-2 fw-bold ${labelClass} status-label">${label}</span>
                        </div>`;
                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Save / Update
            function saveHowItWorks(isModal = false) {
                let formData = new FormData();

                if (isModal) {
                    formData.append('id', $('#modalHowId').val());
                    formData.append('title', $('#modalTitle').val());
                    formData.append('description', $('#modalDescription').val());
                    formData.append('status', $('#modalStatus').is(':checked') ? 1 : 0);
                    let modalImage = $('#modalImage')[0].files[0];
                    if (modalImage) formData.append('image', modalImage);
                } else {
                    formData = new FormData($('#howItWorksForm')[0]);
                    formData.set('status', $('#status').is(':checked') ? 1 : 0);
                }

                $.ajax({
                    url: "{{ route('cms.how-it-works.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        Toast.fire({
                            icon: 'success',
                            title: res.message || res.success
                        });
                        table.ajax.reload();

                        if (isModal) {
                            $('#howItWorksModal').modal('hide');
                        } else {
                            $('#howItWorksForm')[0].reset();
                            $('#imagePreview').attr('src', "{{ asset('images/placeholder.png') }}");
                            $('#formTitle').text('Add How It Works Step');
                            $('#saveBtn').text('Save');
                        }
                    }
                });
            }

            $('#howItWorksForm').submit(function(e) {
                e.preventDefault();
                saveHowItWorks();
            });

            $('#modalSaveBtn').click(function() {
                saveHowItWorks(true);
            });

            $('#resetBtn').click(function() {
                $('#howItWorksForm')[0].reset();
                $('#imagePreview').attr('src', "{{ asset('images/placeholder.png') }}");
                $('#formTitle').text('Add How It Works Step');
                $('#saveBtn').text('Save');
            });

            // Edit / View modal open
            $(document).on('click', '.edit-btn, .view-btn', function() {

                let id = $(this).data('id');
                let isView = $(this).hasClass('view-btn');

                $.get("/why/how-it-works/" + id, function(data) {

                    $('#modalHowId').val(data.id);
                    $('#modalTitle').val(data.title);
                    $('#modalDescription').val(data.description);
                    $('#modalStatus').prop('checked', data.status == 1);
                    updateLabel($('#modalStatus'), $('#modalStatusLabel'));

                    $('#modalImagePreview').attr(
                        'src',
                        data.image_path ? data.image_path :
                        "{{ asset('images/placeholder.png') }}"
                    );

                    if (isView) {
                        $('#modalTitleText').text('View How It Works');
                        $('#modalTitle,#modalDescription,#modalStatus').prop('disabled', true);
                        $('#modalImageInputWrapper').hide();
                        $('#modalSaveBtn').hide();
                        $('#deleteImageBtn').hide();

                    } else {
                        $('#modalTitleText').text('Edit How It Works');
                        $('#modalTitle,#modalDescription,#modalStatus').prop('disabled', false);
                        $('#modalImageInputWrapper').show();
                        $('#deleteImageBtn').show();
                        $('#modalSaveBtn').show();
                    }

                    $('#howItWorksModal').modal('show');
                });
            });

            $('#modalImage').on('change', function(e) {
                previewImage(e, 'modalImagePreview');
            });

            // Delete image
            $('#deleteImageBtn').on('click', function() {

                let imgSrc = $('#modalImagePreview').attr('src');

                if (imgSrc.includes('placeholder.png')) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No image to delete',
                        toast: true,
                        position: 'top-end',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    return;
                }

                let id = $('#modalHowId').val();
                if (!id) return;

                Swal.fire({
                    title: 'Remove image?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    let url = $(this).data('url').replace('/0', '/' + id);

                    $.post(url, {
                        _token: "{{ csrf_token() }}"
                    }, function(res) {

                        Swal.fire({
                            icon: 'success',
                            title: res.success,
                            toast: true,
                            position: 'top-end',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#modalImagePreview')
                            .attr('src', "{{ asset('images/placeholder.png') }}");

                        $('#modalImage').val('');
                    });
                });
            });

        });
    </script>
@endpush
