@extends('backend.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Reviews by Location</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="reviewReportTable">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Average Rating</th>
                        <th>Description</th>
                        <th>Total Replies</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#reviewReportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.admin.reviews.data') }}",
                columns: [{
                        data: 'location'
                    },
                    {
                        data: 'rating'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'total_replies'
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
