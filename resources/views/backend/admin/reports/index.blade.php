@extends('backend.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Reports Table</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="reportTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Review Description</th>
                        <th>Reporter</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="reviewModalBody">
                    Loading...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Other plugins -->
    <script>
        $(function() {
            var table = $('#reportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.admin.reports.data') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'review_desc'
                    },
                    {
                        data: 'reporter'
                    },
                    {
                        data: 'reason_code'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        //  Open Review Modal
        function openReviewModal(reportId) {
            $('#reviewModalBody').html('Loading...');
            $('#reviewModal').modal('show');

            $.get("/backend/admin/reports/review/" + reportId, function(res) {
                $('#reviewModalBody').html(res.html);
            });
        }

        //  Update Report Status
        function updateReportStatus(reportId, status) {
            $.ajax({
                url: '/backend/admin/reports/' + reportId + '/update-status',
                type: 'POST',
                data: {
                    status: status,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.success) {
                        // Show success toast notification
                        toastr.success('Report ' + status.charAt(0).toUpperCase() + status.slice(1),
                            'Status Updated');

                        // Reload table
                        setTimeout(function() {
                            $('#reportTable').DataTable().ajax.reload();
                            if ($('#reviewModal').hasClass('show')) {
                                $('#reviewModal').modal('hide');
                            }
                        }, 500);
                    }
                },
                error: function(xhr) {
                    // Show error toast notification
                    toastr.error(xhr.responseJSON.error || 'Error updating status', 'Failed');
                }
            });
        }
    </script>
@endpush
