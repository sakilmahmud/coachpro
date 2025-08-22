@extends('layout/admin-layout')
@section('space-work')
<div class="container">
    <h1>Create New Mock Test for {{ $course->name }}</h1>
    <a href="{{ route('mock-tests.show', $course->id) }}" class="btn btn-secondary mb-3">Back to Mock Tests</a>

    <form action="{{ route('mock-tests.store') }}" method="POST">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <div class="form-group">
            <label for="name">Mock Test Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create Mock Test</button>
    </form>
</div>
@endsection