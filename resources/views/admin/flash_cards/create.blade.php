@extends('layout/admin-layout')

@section('space-work')
<div class="container">
    <h1>Create New Flash Question for {{ $course->name }}</h1>
    <a href="{{ route('flash-cards.show', $course->id) }}" class="btn btn-secondary mb-3">Back to Flash Questions</a>

    <form action="{{ route('flash-cards.store') }}" method="POST">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <div class="form-group">
            <label for="question">Question</label>
            <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="answer">Answer</label>
            <textarea class="form-control" id="answer" name="answer" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Flash Question</button>
    </form>
</div>
@endsection
