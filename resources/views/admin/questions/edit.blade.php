@extends('layout/admin-layout')
@section('space-work')
<div class="container">
    <h1>Edit Question for {{ $mockTest->name }} ({{ $course->name }})</h1>
    <a href="{{ route('questions.index', ['mock_test_id' => $mockTest->id]) }}" class="btn btn-secondary mb-3">Back to Questions</a>

    <form action="{{ route('questions.update', $question->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="mock_test_id" value="{{ $mockTest->id }}">
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <div class="form-group">
            <label for="question">Question</label>
            <textarea class="form-control summernote" id="question" name="question" rows="5" required>{{ $question->question }}</textarea>
        </div>
        <div class="form-group">
            <label for="lavel">Level</label>
            <select class="form-control" id="lavel" name="lavel">
                <option value="Beginner" {{ $question->lavel == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="Intermediate" {{ $question->lavel == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="Expert" {{ $question->lavel == 'Expert' ? 'selected' : '' }}>Expert</option>
                <option value="All Level" {{ $question->lavel == 'All Level' ? 'selected' : '' }}>All Level</option>
            </select>
        </div>
        <div class="form-group">
            <label>Answers</label>
            @foreach($question->answers as $index => $answer)
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="answers[]" placeholder="Answer {{ $index + 1 }}" value="{{ $answer->answer }}" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <input type="radio" name="correct_answer" value="{{ $index }}" aria-label="Radio button for following text input" {{ $answer->is_correct ? 'checked' : '' }} required> Correct
                    </div>
                </div>
            </div>
            @endforeach
            @for ($i = count($question->answers); $i < 4; $i++)
                <div class="input-group mb-2">
                <input type="text" class="form-control" name="answers[]" placeholder="Answer {{ $i + 1 }}" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <input type="radio" class="mr-3" name="correct_answer" value="{{ $i }}" aria-label="Radio button for following text input" required> &nbsp; Correct
                    </div>
                </div>
        </div>
        @endfor
</div>
<div class="form-group">
    <label for="explanation">Explanation</label>
    <textarea class="form-control summernote" id="explanation" name="explanation" rows="3">{{ $question->explanation }}</textarea>
</div>
<button type="submit" class="btn btn-primary mt-3">Update Question</button>
</form>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    let editor = $(this);
                    let data = new FormData();
                    data.append("image", files[0]);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('upload.editor.image') }}",
                        type: "POST",
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            editor.summernote('insertImage', response.url);
                        }
                    });
                }
            }
        });
    });
</script>
@endpush
@endsection