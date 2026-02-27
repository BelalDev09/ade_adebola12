@extends('backend.app')

@section('content')
    <div class="row mb-3">
        <div class="col-lg-6">
            <h2>Role Management</h2>
        </div>
        <div class="col-lg-6 text-end">
            @can('role-create')
                <a href="{{ route('backend.admin.roles.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Create New Role
                </a>
            @endcan
        </div>
    </div>

    {{-- Toastr Notification Script --}}
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    @endpush
    {{-- Swift Toastr Notification Script --}}
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            $(function() {
                @if (session('success'))
                    toastr.success("{{ session('success') }}", 'Success', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3500
                    });
                @endif

                @if (session('error'))
                    toastr.error("{{ session('error') }}", 'Error', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3500
                    });
                @endif

                @if (session('info'))
                    toastr.info("{{ session('info') }}", 'Info', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3500
                    });
                @endif

                @if (session('warning'))
                    toastr.warning("{{ session('warning') }}", 'Warning', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3500
                    });
                @endif
            });
        </script>
    @endpush

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table id="roles-table" class="table table-bordered table-hover mb-0" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="8%">No</th>
                        <th>Role Name</th>
                        <th width="28%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <p class="text-center text-primary mt-4"><small>Belal Hasan</small></p>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.admin.roles.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center fw-bold"
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                language: {
                    processing: "<div class='text-center py-5'><i class='fa fa-spinner fa-spin fa-2x text-primary'></i><br>Loading roles...</div>"
                }
            });
        });
    </script>
@endpush
