@extends('backend.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Users List</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" data-action="add">
            Add User
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="usersTable" class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="userModal">
    <div class="modal-dialog modal-dialog-centered">
        <form id="userForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" id="userId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="userModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="first_name" id="firstName" class="form-control" placeholder="First name">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="last_name" id="lastName" class="form-control" placeholder="Last name">
                        </div>
                        <div class="col-12">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                        </div>

                        {{-- Roles --}}
                        <div class="col-12">
                            <label>Roles</label>
                            <select name="roles[]" id="rolesSelect" class="form-select" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password --}}
                        <div class="col-12 password-field">
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
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
        columns: [
            {data: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'email'},
            {
                data: 'roles',
                render: function(data) {
                    return data.map(role => `<span class="badge bg-warning text-dark me-1">${role}</span>`).join(' ');
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'permissions',
                render: function(data) {
                    return data.map(p => `<span class="badge bg-primary me-1">${p}</span>`).join(' ');
                },
                orderable: false,
                searchable: false
            },
            {data: 'action', orderable: false, searchable: false, className: 'text-center'}
        ]
    });

    // Password toggle
    $(document).on('click', '#togglePassword', function() {
        let input = $('#password');
        let icon = $(this).find('i');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        icon.toggleClass('bi-eye bi-eye-slash');
    });

    // Open modal for edit
    $('#userModal').on('show.bs.modal', function(e) {
        let button = $(e.relatedTarget);
        let action = button.data('action');

        if(action === 'edit') {
            $('#userModalLabel').text('Edit User');
            $('#userId').val(button.data('id'));
            $('#firstName').val(button.data('first_name'));
            $('#lastName').val(button.data('last_name'));
            $('#email').val(button.data('email'));
            let roles = button.data('roles') ? JSON.parse(button.data('roles')) : [];
            $('#rolesSelect').val(roles).trigger('change');
        } else {
            $('#userModalLabel').text('Add User');
            $('#userForm')[0].reset();
            $('#rolesSelect').val([]).trigger('change');
            $('#userId').val('');
        }
    });

    // Submit form via Ajax
    $('#userForm').submit(function(e){
        e.preventDefault();
        let id = $('#userId').val();
        let url = id ? "{{ url('admin/users') }}/" + id : "{{ route('admin.users.store') }}";
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(res){
                $('#userModal').modal('hide');
                table.ajax.reload();
                alert(res.success);
            },
            error: function(err){
                console.log(err);
                alert('Something went wrong!');
            }
        });
    });

    // Delete user
    $(document).on('click', '.delete-user', function(){
        if(!confirm('Are you sure?')) return;
        let id = $(this).data('id');

        $.ajax({
            url: "{{ url('admin/users') }}/" + id,
            method: 'DELETE',
            data: {_token: "{{ csrf_token() }}"},
            success: function(res){
                table.ajax.reload();
                alert(res.success);
            }
        });
    });
});
</script>
@endpush
