@extends('backend.app')
@section('title', 'CMS Content')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">CMS Content Management</h4>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary mb-3" id="addNewBtn">
                        Add New Content
                    </button>

                    <table id="cmsTable" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Page Slug</th>
                                <th>Section</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="cmsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="cmsForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cmsModalLabel">Create CMS Content</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Page</label>
                            <input type="text" name="page_slug" class="form-control" value="landing-page" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Section</label>
                            <select name="section" class="form-select" required>
                                @php
                                    $sections = [
                                        'hero' => 'Hero',
                                        'how_it_works' => 'How It Works',
                                        'market_tools' => 'Market',
                                        'testimonials' => 'Testimonials',
                                        'who_for' => 'Who For Us',
                                    ];

                                    $selectedSection = old('section') ?? ($cms->section ?? '');
                                @endphp
                                @foreach ($sections as $key => $val)
                                    <option value="{{ $key }}" {{ $selectedSection == $key ? 'selected' : '' }}>
                                        {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <input type="text" name="type" class="form-control"
                                value="{{ old('card', $cms?->card ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $cms?->title ?? '') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" onchange="previewHeroImage(event)">
                            <img id="heroImagePreview" src="{{ asset('images/placeholder.png') }}"
                                class="img-thumbnail mt-2" style="max-height:120px">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="btn_text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Link</label>
                            <input type="url" name="btn_link" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            @php
                                $selectedStatus = old('status') ?? ($cms->status ?? 1);
                            @endphp
                            <select name="status" class="form-select">
                                <option value="1" {{ $selectedStatus == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $selectedStatus == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Order</label>
                            <input type="number" name="order" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "3000"
        };
        let table;

        $(function() {
            table = $('#cmsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('cms.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'page_slug',
                        name: 'page_slug'
                    },
                    {
                        data: 'section',
                        name: 'section'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'order',
                        name: 'order'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<div class="form-check form-switch">
                        <input class="form-check-input status-switch" type="checkbox" data-id="${row.id}" ${data==1?'checked':''}>
                    </div>`;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-info edit-btn" data-id="${row.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>`;
                        }
                    }
                ]
            });

            // Add New
            $('#addNewBtn').click(function() {
                $('#cmsForm')[0].reset();
                $('#cmsForm input[name="id"]').val('');
                $('#cmsModalLabel').text('Create CMS Content');
                $('#heroImagePreview').attr('src', '{{ asset('images/placeholder.png') }}');
                $('#cmsModal').modal('show');
            });

            // Image preview
            window.previewHeroImage = function(event) {
                const input = event.target;
                const preview = document.getElementById('heroImagePreview');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => preview.src = e.target.result;
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '{{ asset('images/placeholder.png') }}';
                }
            }

            // Save / Update
            $('#cmsForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('cms.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        toastr.success(res.success);
                        $('#cmsModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = [];
                            for (let key in errors) {
                                messages.push(errors[key][0]);
                            }
                            toastr.error(messages.join('<br>'));
                        } else {
                            toastr.error('Something went wrong');
                        }
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.get("/cms/" + id, function(data) {
                    $('#cmsForm')[0].reset(); // Reset everything first
                    $('#cmsForm input[name="id"]').val(data.id);

                    $('#cmsForm input[name="page_slug"]').val(data.page_slug);
                    $('#cmsForm select[name="section"]').val(data.section).trigger('change');
                    $('#cmsForm input[name="type"]').val(data.type);
                    $('#cmsForm input[name="title"]').val(data.title);
                    $('#cmsForm textarea[name="description"]').val(data.description);
                    $('#cmsForm input[name="btn_text"]').val(data.btn_text);
                    $('#cmsForm input[name="btn_link"]').val(data.btn_link ?? '');
                    $('#cmsForm select[name="status"]').val(data.status);
                    $('#cmsForm input[name="order"]').val(data.order ?? 1);

                    $('#heroImagePreview').attr('src', data.image_path ? "/storage/" + data
                        .image_path : '/images/placeholder.png');

                    $('#cmsModalLabel').text('Edit CMS Content');
                    $('#cmsModal').modal('show');
                });
            });





            // Delete
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                if (confirm('Are you sure?')) {
                    $.ajax({
                        url: "{{ url('/cms') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            toastr.success(res.success);
                            table.ajax.reload();
                        }
                    });
                }
            });

            // Status Toggle
            $(document).on('change', '.status-switch', function() {
                let id = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;
                $.post("{{ url('/cms/status') }}/" + id, {
                    _token: "{{ csrf_token() }}",
                    status: status
                }, function(res) {
                    toastr.success(res.success);
                });
            });
        });
    </script>
@endpush
