@extends('backend.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h4>Users Support</h4>
            <a href="{{ route('admin.support.create') }}" class="btn btn-primary">Add Description</a>
        </div>

        <table id="usersTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.support.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        render: function(data) {
                            return data ? $('<div>').html(data).text().substring(0, 100) + (data
                                .length > 100 ? '...' : '') : '';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
