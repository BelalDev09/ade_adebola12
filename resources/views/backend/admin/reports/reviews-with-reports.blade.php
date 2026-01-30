@extends('backend.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Reviews with Reports</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="reviewsWithReportsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Location</th>
                        <th>Reviewer</th>
                        <th>Rating</th>
                        <th>Description</th>
                        <th>Report Summary</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reviewReportsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Reports</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="reviewReportsModalBody">
                    Loading...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            var table = $('#reviewsWithReportsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.admin.reviews-with-reports.data') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'location'
                    },
                    {
                        data: 'reviewer'
                    },
                    {
                        data: 'rating'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'report_summary'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        // Open Review Reports Modal
        function openReviewReportsModal(reviewId) {
            $('#reviewReportsModalBody').html('Loading...');
            $('#reviewReportsModal').modal('show');

            $.get("/backend/admin/reviews/" + reviewId + "/reports", function(res) {
                $('#reviewReportsModalBody').html(res.html);
            });
        }
    </script>
@endpush
