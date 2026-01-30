@extends('backend.app')
@section('title', 'Market Tools')

@push('styles')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 id="formTitle">Add Market Tool</h5>
                </div>
                <div class="card-body">
                    <form id="marketToolsForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="marketToolId" name="id">
                        <div class="mb-3">
                            <label>Title *</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Button Text</label>
                            <input type="text" id="btn_text" name="btn_text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Button Link</label>
                            <input type="url" id="btn_link" name="btn_link" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" id="status" name="status" class="form-check-input" checked>
                                <label class="form-check-label fw-bold text-success" id="statusLabel">Active</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" id="imageInput" name="image" class="form-control" onchange="previewImage(event)">
                            <label class="mt-2">Preview</label><br>
                            <img id="imagePreview" src="{{ asset('images/placeholder.png') }}" class="img-thumbnail" style="max-height:120px">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary w-100" id="saveBtn">Save</button>
                            <button type="button" class="btn btn-secondary w-100 mt-2" id="resetBtn">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Market Tools List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="marketToolsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Btn Text</th>
                                <th>Btn Link</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal for View/Edit -->
<div class="modal fade" id="marketToolModal" tabindex="-1" aria-labelledby="marketModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="marketModalTitle">View / Edit Market Tool</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalMarketToolId">
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" id="modalTitle" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea id="modalDescription" class="form-control" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label>Button Text</label>
                    <input type="text" id="modalBtnText" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Button Link</label>
                    <input type="url" id="modalBtnLink" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" id="modalStatus" class="form-check-input">
                        <span class="ms-2 fw-bold" id="modalStatusLabel"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Image Preview</label><br>
                    <img id="modalImagePreview" src="{{ asset('images/placeholder.png') }}" class="img-thumbnail" style="max-height:120px">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modalSaveBtn">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event, previewId = 'imagePreview') {
    const input = event.target;
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "{{ asset('images/placeholder.png') }}";
    }
}

$(function() {

    const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000 });

    function updateLabel($checkbox, $label){
        if($checkbox.is(':checked')){
            $label.text('Active').removeClass('text-secondary').addClass('text-success');
        } else {
            $label.text('Inactive').removeClass('text-success').addClass('text-secondary');
        }
    }

    // Create form status label
    $('#status').on('change', function(){ updateLabel($(this), $('#statusLabel')); });
    updateLabel($('#status'), $('#statusLabel'));

    // DataTable
    var table = $('#marketToolsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('cms.market-tools.index') }}",
        columns:[
            { data:'DT_RowIndex', orderable:false, searchable:false },
            { data:'type' },
            { data:'title' },
            { data:'description' },
            { data:'btn_text' },
            { data:'btn_link' },
            {
                data:'status',
                render:function(data,type,row){
                    let checked = data==1?'checked':'';
                    let label = data==1?'Active':'Inactive';
                    let labelClass = data==1?'text-success':'text-secondary';
                    return `<div class="d-flex align-items-center">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input status-switch" type="checkbox" data-id="${row.id}" ${checked}>
                        </div>
                        <span class="ms-2 fw-bold ${labelClass} status-label">${label}</span>
                    </div>`;
                }
            },
            {
                data:'action',
                orderable:false,
                searchable:false
            }
        ]
    });

    // Save / Update
    function saveMarketTool(isModal=false){
        var formData = new FormData();
        if(isModal){
            formData.append('id',$('#modalMarketToolId').val());
            formData.append('title',$('#modalTitle').val());
            formData.append('description',$('#modalDescription').val());
            formData.append('btn_text',$('#modalBtnText').val());
            formData.append('btn_link',$('#modalBtnLink').val());
            formData.append('status',$('#modalStatus').is(':checked')?1:0);
        } else {
            var form = $('#marketToolsForm')[0];
            formData = new FormData(form);
            formData.set('status',$('#status').is(':checked')?1:0);
        }

        $.ajax({
            url:"{{ route('cms.market-tools.store') }}",
            type:"POST",
            data:formData,
            processData:false,
            contentType:false,
            success:function(res){
                Toast.fire({icon:'success',title:res.success});
                table.ajax.reload();
                if(isModal) $('#marketToolModal').modal('hide');
                else{
                    $('#marketToolsForm')[0].reset();
                    $('#imagePreview').attr('src',"{{ asset('images/placeholder.png') }}");
                    $('#formTitle').text('Add Market Tool');
                    $('#saveBtn').text('Save');
                }
            }
        });
    }

    $('#marketToolsForm').submit(function(e){ e.preventDefault(); saveMarketTool(); });
    $('#modalSaveBtn').click(function(){ saveMarketTool(true); });

    // Reset form
    $('#resetBtn').click(function(){
        $('#marketToolsForm')[0].reset();
        $('#imagePreview').attr('src',"{{ asset('images/placeholder.png') }}");
        $('#formTitle').text('Add Market Tool');
        $('#saveBtn').text('Save');
    });

    // View/Edit Modal
    $(document).on('click','.edit-btn,.view-btn',function(){
        var id = $(this).data('id');
        var isView = $(this).hasClass('view-btn');
        $.get("{{ url('/hello/market-tools') }}/"+id,function(data){
            $('#modalMarketToolId').val(data.id);
            $('#modalTitle').val(data.title);
            $('#modalDescription').val(data.description);
            $('#modalBtnText').val(data.btn_text);
            $('#modalBtnLink').val(data.btn_link);
            $('#modalStatus').prop('checked',data.status==1);
            updateLabel($('#modalStatus'),$('#modalStatusLabel'));
            $('#modalImagePreview').attr('src',data.image_path?"{{ asset('storage') }}/"+data.image_path:"{{ asset('images/placeholder.png') }}");

            if(isView){
                $('#marketModalTitle').text('View Market Tool');
                $('#modalTitle,#modalDescription,#modalBtnText,#modalBtnLink,#modalStatus').prop('disabled',true);
                $('#modalSaveBtn').hide();
            } else {
                $('#marketModalTitle').text('Edit Market Tool');
                $('#modalTitle,#modalDescription,#modalBtnText,#modalBtnLink,#modalStatus').prop('disabled',false);
                $('#modalSaveBtn').show();
            }

            $('#marketToolModal').modal('show');
        });
    });

    // Delete
    $(document).on('click','.delete-btn',function(){
        if(!confirm('Are you sure?')) return;
        var id = $(this).data('id');
        $.ajax({
            url:"{{ url('/hello/market-tools') }}/"+id,
            type:"POST",
            data:{_method:'DELETE',_token:"{{ csrf_token() }}"},
            success:function(res){
                Toast.fire({icon:'success',title:res.success});
                table.ajax.reload();
            }
        });
    });

    // Status toggle
    $(document).on('change','.status-switch',function(){
        var id = $(this).data('id');
        var status = $(this).is(':checked')?1:0;
        var $label = $(this).closest('div').find('.status-label');
        if(status==1){
            $label.text('Active').removeClass('text-secondary').addClass('text-success');
        } else {
            $label.text('Inactive').removeClass('text-success').addClass('text-secondary');
        }

        $.post("{{ url('/hello/market-tools/status') }}/"+id,{_token:"{{ csrf_token() }}",status:status},function(res){
            Toast.fire({icon:'success',title:res.success});
        });
    });

});
</script>
@endsection
