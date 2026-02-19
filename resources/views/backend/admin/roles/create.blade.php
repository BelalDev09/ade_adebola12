@extends('backend.app')

@push('styles')
    <style>
        .role-form-card {
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h4>
                <small class="text-muted">Define a clear, professional role name.</small>
            </div>
            <a class="btn btn-ghost" href="{{ route('admin.roles.index') }}">
                Back to Roles
            </a>
        </div>

        <div class="card role-form-card">
            <div class="card-body">
                <form method="POST"
                    action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
                    @csrf
                    @if (isset($role))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input class="form-control" name="name" value="{{ old('name', $role->name ?? '') }}"
                            placeholder="e.g. manager" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit">
                            {{ isset($role) ? 'Update Role' : 'Create Role' }}
                        </button>
                        @if (isset($role))
                            <a class="btn btn-outline-secondary"
                                href="{{ route('admin.roles.permissions', $role) }}">
                                Manage Permissions
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection