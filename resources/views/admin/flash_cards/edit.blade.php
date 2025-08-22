@extends('layout/admin-layout')

@section('space-work')
<div class="container">
    <h1>Edit Flash Question for {{ $course->name }}</h1>
    <a href="{{ route('flash-cards.show', $course->id) }}" class="btn btn-secondary mb-3">Back to Flash Questions</a>

    <form action="{{ route('flash-cards.update', $flashQuestion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <div class="form-group">
            <label for="question">Question</label>
            <textarea class="form-control" id="question" name="question" rows="3" required>{{ $flashQuestion->question }}</textarea>
        </div>
        <div class="form-group">
            <label for="answer">Answer</label>
            <textarea class="form-control" id="answer" name="answer" rows="3" required>{{ $flashQuestion->answer }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Flash Question</button>
    </form>
</div>
@endsection
