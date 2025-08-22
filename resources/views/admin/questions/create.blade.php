@extends('layout/admin-layout')
@section('space-work')
<div class="container">
    <h1>Create New Question for {{ $mockTest->name }} ({{ $course->name }})</h1>
    <a href="{{ route('questions.index', ['mock_test_id' => $mockTest->id]) }}" class="btn btn-secondary mb-3">Back to Questions</a>

    <form action="{{ route('questions.store') }}" method="POST">
        @csrf
        <input type="hidden" name="mock_test_id" value="{{ $mockTest->id }}">
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <div class="form-group">
            <label for="question">Question</label>
            <textarea class="form-control" id="question" name="question" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="lavel">Level</label>
            <select class="form-control" id="lavel" name="lavel">
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Expert">Expert</option>
                <option value="All Level">All Level</option>
            </select>
        </div>
        <div class="form-group">
            <label>Answers</label>
            @for ($i = 0; $i < 4; $i++)
                <div class="input-group mb-2">
                <input type="text" class="form-control" name="answers[]" placeholder="Answer {{ $i + 1 }}" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <input type="radio" name="correct_answer" value="{{ $i }}" aria-label="Radio button for following text input" required> &nbsp; Correct
                    </div>
                </div>
        </div>
        @endfor
</div>
<div class="form-group">
    <label for="explanation">Explanation</label>
    <textarea class="form-control" id="explanation" name="explanation" rows="3"></textarea>
</div>
<button type="submit" class="btn btn-primary mt-3">Create Question</button>
</form>
</div>
@endsection