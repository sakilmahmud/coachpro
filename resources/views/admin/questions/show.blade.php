@extends('layout/admin-layout')

@section('space-work')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Question Details</h1>
        <div>
            <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-info">Edit</a>
            <form action="{{ route('questions.destroy', $question->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
            <a href="{{ route('questions.index', ['mock_test_id' => $question->mock_test_id]) }}" class="btn btn-secondary">Back to Questions</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Question #{{ $question->id }}</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Question:</strong>
                <div>{!! $question->question !!}</div>
            </div>
            <div class="mb-3">
                <strong>Level:</strong>
                <p>{{ $question->lavel }}</p>
            </div>
            <div class="mb-3">
                <strong>Explanation:</strong>
                <div>{!! $question->explanation !!}</div>
            </div>

            <hr>

            <h5>Answers:</h5>
            <ul class="list-group">
                @foreach($question->answers as $answer)
                    <li class="list-group-item d-flex justify-content-between align-items-center @if($answer->is_correct) list-group-item-success @endif">
                        {!! $answer->answer !!}
                        @if($answer->is_correct)
                            <span class="badge bg-success">Correct</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection