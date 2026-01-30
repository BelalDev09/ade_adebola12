@extends('backend.app')

@section('title', 'Who For')

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- LEFT SIDE FORM (CREATE ONLY) --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <input type="hidden" id="rowId">

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="title" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="description" class="form-control"></textarea>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="status" checked>
                            <label class="form-check-label fw-bold text-success" id="statusLabel">Active</label>
                        </div>

                        <button class="btn btn-primary w-100" id="saveBtn">Create</button>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE TABLE --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="whoForTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Page Slug</th>
                                    <th>Section</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL (Edit / View / Delete) --}}
    <div class="modal fade" id="whoForModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Edit Who For</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modalRowId">

                    {{-- Hidden by default in modal --}}
                    <div class="row d-none" id="modalHiddenFields">
                        <div class="col-md-4 mb-3">
                            <label>Page Slug</label>
                            <input type="text" id="modalPageSlug" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Section</label>
                            <input type="text" id="modalSection" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Type</label>
                            <input type="text" id="modalType" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" id="modalTitleInput" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea id="modalDescription" class="form-control"></textarea>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="modalStatus">
                        <label class="form-check-label fw-bold" id="modalStatusLabel">Inactive</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="modalSaveBtn">Update</button>
                    {{-- <button class="btn btn-danger" id="modalDeleteBtn">Delete</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            function updateLabel($checkbox, $label) {
                if ($checkbox.is(':checked')) {
                    $label.text('Active').removeClass('text-secondary').addClass('text-success');
                } else {
                    $label.text('Inactive').removeClass('text-success').addClass('text-secondary');
                }
            }

            $('#status').on('change', function() {
                updateLabel($(this), $('#statusLabel'));
            });
            $('#modalStatus').on('change', function() {
                updateLabel($(this), $('#modalStatusLabel'));
            });

            // Yajra DataTable
            let table = $('#whoForTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('cms.who-for.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'page_slug'
                    },
                    {
                        data: 'section'
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
                            return `<div class="form-check form-switch">
                        <input class="form-check-input table-status" type="checkbox" data-id="${row.id}" ${data==1?'checked':''}>
                    </div>`;
                        }
                    },
                    {
                        data: 'action',
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-info btn-sm view-btn" data-id="${row.id}" title="View">View<i class="bi bi-eye"></i></button>
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" title="Edit">Edit<i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" title="Delete">Delete<i class="bi bi-trash"></i></button>
                    `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // CREATE / UPDATE
            $('#saveBtn, #modalSaveBtn').on('click', function() {
                let isModal = $('#whoForModal').hasClass('show');
                $.post("{{ route('cms.who-for.store') }}", {
                    _token: "{{ csrf_token() }}",
                    id: isModal ? $('#modalRowId').val() : $('#rowId').val(),
                    page_slug: isModal ? $('#modalPageSlug').val() : null,
                    section: isModal ? $('#modalSection').val() : null,
                    type: isModal ? $('#modalType').val() : null,
                    title: isModal ? $('#modalTitleInput').val() : $('#title').val(),
                    description: isModal ? $('#modalDescription').val() : $('#description').val(),
                    status: isModal ? ($('#modalStatus').is(':checked') ? 1 : 0) : ($('#status').is(
                        ':checked') ? 1 : 0)
                }, function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: res.message || 'Saved'
                    });
                    table.ajax.reload(null, false);
                    $('#whoForModal').modal('hide');
                });
            });

            // EDIT BUTTON
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.get('/good/who-for/' + id, function(data) {
                    $('#modalRowId').val(data.id);
                    $('#modalTitleInput').val(data.title);
                    $('#modalDescription').val(data.description);
                    $('#modalStatus').prop('checked', data.status == 1);
                    updateLabel($('#modalStatus'), $('#modalStatusLabel'));

                    // hide page_slug, section, type
                    $('#modalHiddenFields').hide();

                    $('#whoForModal').modal('show');
                });
            });

            // VIEW BUTTON
            $(document).on('click', '.view-btn', function() {
                let id = $(this).data('id');
                $.get('/good/who-for/' + id, function(data) {
                    $('#modalTitle').text('View Who For');
                    $('#modalRowId').val(data.id);
                    $('#modalTitleInput').val(data.title).prop('disabled', true);
                    $('#modalDescription').val(data.description).prop('disabled', true);
                    $('#modalStatus').prop('checked', data.status == 1).prop('disabled', true);
                    updateLabel($('#modalStatus'), $('#modalStatusLabel'));

                    // hide page_slug, section, type
                    $('#modalHiddenFields').hide();

                    $('#modalSaveBtn, #modalDeleteBtn').hide();
                    $('#whoForModal').modal('show');
                });
            });

            // DELETE BUTTON
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/good/who-for/delete/' + id, {
                            _token: "{{ csrf_token() }}"
                        }, function(res) {
                            Toast.fire({
                                icon: 'success',
                                title: res.message || 'Deleted'
                            });
                            table.ajax.reload(null, false);
                        });
                    }
                });
            });

            // TABLE STATUS TOGGLE
            $(document).on('change', '.table-status', function() {
                let id = $(this).data('id');
                let status = this.checked ? 1 : 0;
                $.post('/good/who-for/status/' + id, {
                    _token: "{{ csrf_token() }}",
                    status: status
                }, function() {
                    Toast.fire({
                        icon: 'success',
                        title: 'Status updated'
                    });
                });
            });

            // RESET MODAL ON HIDE
            $('#whoForModal').on('hidden.bs.modal', function() {
                $(this).find('input,textarea').prop('disabled', false).val('');
                $('#modalSaveBtn, #modalDeleteBtn').show();
                $('#modalTitle').text('Edit Who For');
                $('#modalStatus').prop('checked', true);
                updateLabel($('#modalStatus'), $('#modalStatusLabel'));

                // show hidden fields next time
                $('#modalHiddenFields').show();
            });
        });
    </script>
@endpush
