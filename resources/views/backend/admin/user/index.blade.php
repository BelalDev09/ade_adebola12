@extends('backend.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h4>Users List</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" data-action="add">
                Add User
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table id="usersTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="userModal">
        <div class="modal-dialog modal-dialog-centered">
            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="userModalLabel">Add User</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <img id="preview" src="https://ui-avatars.com/api/?name=User" class="rounded-circle"
                                width="100">
                            <input type="file" name="avatar" id="avatarInput" class="form-control mt-2">
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input class="form-control" name="first_name" id="firstName" placeholder="First name">
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="last_name" id="lastName" placeholder="Last name">
                            </div>
                            <div class="col-12">
                                <input class="form-control" name="email" id="email" placeholder="Email">
                            </div>

                            <div class="col-12 password-field">
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Password">
                                    <span class="input-group-text" id="togglePassword" style="cursor:pointer">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        $(function() {

            let table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Avatar preview
            $('#avatarInput').change(function() {
                if (this.files[0]) {
                    $('#preview').attr('src', URL.createObjectURL(this.files[0]));
                }
            });

            // Password eye toggle
            $(document).on('click', '#togglePassword', function() {
                let input = $('#password');
                let icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Modal open
            $('#userModal').on('show.bs.modal', function(e) {
                let btn = $(e.relatedTarget);
                let action = btn.data('action');
                let form = $('#userForm');

                form.trigger('reset');
                $('.password-field').show();
                form.find('input[name=_method]').remove();

                if (action === 'add') {
                    $('#userModalLabel').text('Add User');
                    form.attr('action', "{{ route('admin.users.store') }}");
                }

                if (action === 'edit') {
                    $('#userModalLabel').text('Edit User');
                    form.attr('action', "{{ route('admin.users.update', ':id') }}".replace(':id', btn.data(
                        'id')));
                    form.append('<input type="hidden" name="_method" value="PUT">');
                    $('#firstName').val(btn.data('first_name'));
                    $('#lastName').val(btn.data('last_name'));
                    $('#email').val(btn.data('email'));
                    $('.password-field');

                    let avatar = btn.data('avatar') || 'https://ui-avatars.com/api/?name=User';
                    $('#preview').attr('src', avatar);
                }
            });

            // Submit
            $('#userForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: res => {
                        $('#userModal').modal('hide');
                        table.ajax.reload(null, false);
                        Swal.fire('Success', res.success, 'success');
                    }
                });
            });

            // Delete
            $(document).on('click', '.delete-user', function() {
                let id = $(this).data('id');

                Swal.fire({
                        title: 'Delete user?',
                        icon: 'warning',
                        showCancelButton: true
                    })
                    .then(r => {
                        if (r.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.users.destroy', ':id') }}".replace(':id',
                                    id),
                                type: 'DELETE',
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: () => {
                                    table.ajax.reload(null, false);
                                    Swal.fire('Deleted', 'User removed', 'success');
                                }
                            });
                        }
                    });
            });

        });
    </script>
@endpush
