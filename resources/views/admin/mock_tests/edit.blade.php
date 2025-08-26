@extends('layout/admin-layout')
@section('space-work')
<div class="container">
    <h1>Edit Mock Test: {{ $mockTest->name }}</h1>
    <a href="{{ route('mock-tests.show', $mockTest->course_id) }}" class="btn btn-secondary mb-3">Back to Mock Tests</a>

    <form action="{{ route('mock-tests.update', $mockTest->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="course_id" value="{{ $mockTest->course_id }}">
        <div class="form-group">
            <label for="name">Mock Test Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $mockTest->name }}" required>
        </div>
        <div class="form-group">
            <label for="time">Time (minutes)</label>
            <input type="number" class="form-control" id="time" name="time" value="{{ $mockTest->time ?? '' }}" required min="1">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $mockTest->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Mock Test</button>
    </form>
</div>
@endsection