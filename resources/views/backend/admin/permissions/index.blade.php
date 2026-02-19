@extends('backend.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h4>Permissions</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#permissionModal" data-action="add">
                Add Permission
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table id="permissionsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="permissionModal">
        <div class="modal-dialog modal-dialog-centered">
            <form id="permissionForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="permissionModalLabel">Add Permission</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Permission Name</label>
                            <input class="form-control" name="name" id="permissionName" placeholder="permission.name">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#permissionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.permissions.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'roles'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#permissionModal').on('show.bs.modal', function(e) {
                let btn = $(e.relatedTarget);
                let action = btn.data('action');
                let form = $('#permissionForm');

                form.trigger('reset');
                form.find('input[name=_method]').remove();

                if (action === 'add') {
                    $('#permissionModalLabel').text('Add Permission');
                    form.attr('action', "{{ route('admin.permissions.store') }}");
                }

                if (action === 'edit') {
                    $('#permissionModalLabel').text('Edit Permission');
                    form.attr('action', "{{ route('admin.permissions.update', ':id') }}".replace(':id', btn.data('id')));
                    form.append('<input type="hidden" name="_method" value="PUT">');
                    $('#permissionName').val(btn.data('name'));
                }
            });

            $('#permissionForm').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: formData,
                    success: res => {
                        $('#permissionModal').modal('hide');
                        table.ajax.reload(null, false);
                        Swal.fire('Success', res.success, 'success');
                    },
                    error: xhr => {
                        let msg = xhr.responseJSON?.message || 'Something went wrong.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });

            $(document).on('click', '.delete-permission', function() {
                let id = $(this).data('id');

                Swal.fire({
                        title: 'Delete permission?',
                        icon: 'warning',
                        showCancelButton: true
                    })
                    .then(r => {
                        if (r.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.permissions.destroy', ':id') }}".replace(':id', id),
                                type: 'DELETE',
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: res => {
                                    table.ajax.reload(null, false);
                                    Swal.fire('Deleted', res.success, 'success');
                                },
                                error: xhr => {
                                    let msg = xhr.responseJSON?.message || 'Unable to delete permission.';
                                    Swal.fire('Error', msg, 'error');
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endpush