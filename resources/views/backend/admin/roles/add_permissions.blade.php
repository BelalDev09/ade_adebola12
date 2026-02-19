@extends('backend.app')

@push('styles')
    <style>
        .perm-card {
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .perm-group {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            background: #fbfdff;
        }

        .perm-group:hover {
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.06);
            transform: translateY(-1px);
            transition: all .15s ease;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">Permissions for {{ ucfirst($role->name) }}</h4>
                <small class="text-muted">Assign the exact capabilities this role should have.</small>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-ghost" href="{{ route('admin.roles.edit', $role) }}">Back to Role</a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.roles.index') }}">All Roles</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card perm-card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
                    @csrf
                    @method('PUT')

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="permSelectAll">
                            <label class="form-check-label" for="permSelectAll">Select all permissions</label>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach ($permissionGroups as $group => $permissions)
                            <div class="col-md-6">
                                <div class="perm-group">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="text-capitalize">{{ str_replace('-', ' ', $group) }}</strong>
                                        <div class="form-check">
                                            <input class="form-check-input perm-group-toggle" type="checkbox"
                                                data-group="{{ $group }}" id="group-{{ $group }}">
                                            <label class="form-check-label" for="group-{{ $group }}">All</label>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($permissions as $perm)
                                            <div class="form-check">
                                                <input class="form-check-input perm-item" type="checkbox"
                                                    name="permissions[]" value="{{ $perm->id }}"
                                                    id="perm-{{ $perm->id }}" data-group="{{ $group }}"
                                                    {{ in_array($perm->id, $assigned, true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm-{{ $perm->id }}">
                                                    {{ $perm->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('permissions')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const syncGroupToggles = () => {
                $('.perm-group-toggle').each(function() {
                    const group = $(this).data('group');
                    const total = $(`.perm-item[data-group="${group}"]`).length;
                    const checked = $(`.perm-item[data-group="${group}"]:checked`).length;
                    $(this).prop('checked', total > 0 && total === checked);
                });

                const totalItems = $('.perm-item').length;
                const checkedItems = $('.perm-item:checked').length;
                $('#permSelectAll').prop('checked', totalItems > 0 && totalItems === checkedItems);
            };

            $('#permSelectAll').on('change', function() {
                $('.perm-item, .perm-group-toggle').prop('checked', $(this).is(':checked'));
            });

            $(document).on('change', '.perm-group-toggle', function() {
                const group = $(this).data('group');
                $(`.perm-item[data-group="${group}"]`).prop('checked', $(this).is(':checked'));
                syncGroupToggles();
            });

            $(document).on('change', '.perm-item', function() {
                syncGroupToggles();
            });

            syncGroupToggles();
        });
    </script>
@endpush