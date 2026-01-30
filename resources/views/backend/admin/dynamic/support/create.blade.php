@extends('backend.app')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">Add Description</h4>

        <form action="{{ route('admin.support.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
            <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
