@extends('layouts.app')
@section('content')
<h1>Add Category</h1>
<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Name" required>
    <select name="parent_id">
        <option value="">Select Parent</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit">Save</button>
</form>
@endsection
