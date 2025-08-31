@extends('layout/admin-layout')
@section('space-work')

<div class="container">
    <h1>Questions for {{ $mockTest->name }}</h1>
    <a href="{{ route('mock-tests.show', $mockTest->course_id) }}" class="btn btn-secondary mb-3">Back to Mock Tests</a>
    <a href="{{ route('questions.create', ['mock_test_id' => $mockTest->id, 'course_id' => $mockTest->course_id]) }}" class="btn btn-primary mb-3">Create New Question</a>

    @if($questions->isEmpty())
    <p>No questions found for this mock test.</p>
    @else
    <table class="table table-bordered table-striped" id="questionTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Level</th>
                <th>Explanation</th>
                <th>Answers</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr>
                <td>{{ $question->id }}</td>
                <td>{!! $question->question !!}</td>
                <td>{{ $question->lavel }}</td>
                <td>{!! $question->explanation !!}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary viewAnswersBtn" data-id="{{ $question->id }}" data-bs-toggle="modal" data-bs-target="#answersModal">View Answers</button>
                </td>
                <td>
                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<!-- Answers Modal -->
<div class="modal fade" id="answersModal" tabindex="-1" aria-labelledby="answersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="answersModalLabel">Answers for Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="answersList" class="list-group">
                    <!-- Answers will be loaded here via AJAX -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#questionTable').DataTable();

        $('.viewAnswersBtn').on('click', function() {
            var questionId = $(this).data('id');
            $.ajax({
                url: '/admin/questions/' + questionId + '/answers', // New route to be defined
                method: 'GET',
                success: function(response) {
                    $('#answersList').empty();
                    if (response.length > 0) {
                        $.each(response, function(index, answer) {
                            var listItem = $('<li class="list-group-item"></li>').text(answer.answer);
                            if (answer.is_correct) {
                                listItem.append('<span class="badge bg-success ms-2">Correct</span>');
                            }
                            $('#answersList').append(listItem);
                        });
                    } else {
                        $('#answersList').append('<li class="list-group-item">No answers found.</li>');
                    }
                    $('#answersModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error fetching answers:', xhr);
                    alert('Error fetching answers. Please try again.');
                }
            });
        });
    });
</script>
@endpush
@endsection