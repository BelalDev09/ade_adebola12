@extends('backend.app')

@push('styles')
<style>
    /* Card & Table */
    .user-card {
        border: 1px solid rgba(0, 0, 0, .05);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .05);
        border-radius: 10px;
        padding: 15px;
    }

    .table thead th {
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
    }

    /* Role badges */
    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 8px;
        margin: 2px 4px 2px 0;
        font-weight: 500;
    }

    .role-yellow {
        background: #facc15;
        color: #000;
    }

    .role-gray {
        background: #e5e7eb;
        color: #111;
    }

    .role-pink {
        background: #f43f5e;
        color: #fff;
    }

    /* Close button inside badge */
    .btn-close {
        width: 14px;
        height: 14px;
        padding: 0;
        margin-left: 4px;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 50%;
        border: none;
    }

    /* Permissions badges */
    .permission-badge {
        background: #0ea5e9;
        color: #fff;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        margin: 3px;
        display: inline-block;
    }

    /* Manage button */
    .manage-btn {
        background: #0ea5e9;
        border: none;
        padding: 6px 14px;
        font-size: 13px;
        border-radius: 6px;
        color: #fff;
        cursor: pointer;
        text-decoration: none;
    }

    .manage-btn:hover {
        background: #0284c7;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="card user-card">
        <table id="usersTable" class="table align-middle table-striped">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="userForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Initialize DataTable
        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.roles.index') }}",
            columns: [{
                    data: 'email'
                },
                {
                    data: 'roles',
                    render: function(data) {
                        let html = '';
                        data.forEach((role, index) => {
                            let colorClass = ['role-yellow', 'role-gray', 'role-pink'][index] || 'role-gray';
                            html += `<span class="role-badge ${colorClass}">
                                ${role.name}
                                <button type="button" class="btn-close btn-close-white remove-role-btn" data-role-id="${role.id}" aria-label="Remove"></button>
                             </span>`;
                        });
                        return html;
                    }
                },
                {
                    data: 'permissions',
                    render: function(data) {
                        return data.map(p => `<span class="permission-badge">${p}</span>`).join('');
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function(data, type, row) {
                        if (row.first_role_id) {
                            return `<a href="/admin/roles/${row.first_role_id}/permissions" class="manage-btn">Manage Roles</a>`;
                        } else {
                            return `<span class="text-muted">No role assigned</span>`;
                        }
                    }
                }
            ]
        });

        // Modal form submit
        $('#userForm').submit(function(e) {
            e.preventDefault();
            $.post("{{ route('admin.users.roles.assign') }}", $(this).serialize())
                .done(function(res) {
                    alert(res.success || 'Role assigned successfully!');
                    $('#userModal').modal('hide');
                    table.ajax.reload();
                })
                .fail(function(xhr) {
                    alert(xhr.responseJSON?.message || 'Failed to assign role.');
                });
        });

        $(document).on('click', '.remove-role-btn', function() {
            let roleId = $(this).data('role-id');
            let rowData = table.row($(this).closest('tr')).data();
            let userId = rowData.id;

            if (confirm('Remove this role from this user?')) {
                $.ajax({
                    url: `/admin/users/${userId}/roles/${roleId}`,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        alert(res.success || 'Role removed!');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Failed to remove role.');
                    }
                });
            }
        });

        $('#userModal').on('hidden.bs.modal', function() {
            $('#userForm')[0].reset();
        });
    });
</script>
@endpush
