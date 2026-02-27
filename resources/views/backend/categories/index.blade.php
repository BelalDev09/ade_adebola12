@extends('backend.app')
@section('content')
<h1>Categories</h1>
<a href="{{ route('categories.create') }}">Add Category</a>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Parent</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    @foreach($categories as $cat)
    <tr>
        <td>{{ $cat->id }}</td>
        <td>{{ $cat->name }}</td>
        <td>{{ $cat->parent?->name ?? '-' }}</td>
        <td>{{ $cat->status ? 'Active' : 'Inactive' }}</td>
        <td>
            <a href="{{ route('categories.edit',$cat->id) }}">Edit</a>
            <form action="{{ route('categories.destroy',$cat->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
{{ $categories->links() }}
@endsection
