@extends('layout/admin-layout')
@section('space-work')

<div class="container">
    <h1>Questions for {{ $mockTest->name }}</h1>
    <a href="{{ route('mock-tests.show', $mockTest->course_id) }}" class="btn btn-secondary mb-3">Back to Mock Tests</a>
    <a href="{{ route('questions.create', ['mock_test_id' => $mockTest->id, 'course_id' => $mockTest->course_id]) }}" class="btn btn-primary mb-3">Create New Question</a>

    @if($questions->isEmpty())
    <p>No questions found for this mock test.</p>
    @else
    <p>Total Questions: {{ $questions->count() }}</p>
    <table class="table table-bordered table-striped" id="questionTable">
        <thead>
            <tr>
                <th>#</th>
                <th width="5%">Q ID</th>
                <th>Question</th>
                <th>Level</th>
                <th width="15%">Question Type</th>
                <th width="20%">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $question->id }}</td>
                <td>{!! $question->question !!}</td>
                <td>{{ $question->lavel }}</td>
                <td>{{ $question->question_type }}</td>
                <td>
                    <a href="{{ route('questions.show', $question->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-dark">Edit</a>
                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection