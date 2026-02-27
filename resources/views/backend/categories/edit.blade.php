@extends('layouts.app')
@section('content')
<h1>Edit Category</h1>
<form action="{{ route('categories.update',$category->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $category->name }}" required>
    <select name="parent_id">
        <option value="">Select Parent</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" @if($category->parent_id == $cat->id) selected @endif>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit">Update</button>
</form>
@endsection
