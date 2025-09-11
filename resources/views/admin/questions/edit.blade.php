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
            <label for="question_type">Question Type</label>
            <select class="form-control" id="question_type" name="question_type">
                <option value="MCQ" {{ $question->question_type == 'MCQ' ? 'selected' : '' }}>MCQ (Multiple Choice Question)</option>
                <option value="True/False" {{ $question->question_type == 'True/False' ? 'selected' : '' }}>True/False</option>
                <option value="Multiple Answer" {{ $question->question_type == 'Multiple Answer' ? 'selected' : '' }}>Multiple Answer</option>
            </select>
        </div>

        <div id="mcq_answers" class="answer-section">
            <label>Answers (MCQ)</label>
            @for ($i = 0; $i < 4; $i++)
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="mcq_answers[]" placeholder="Answer {{ $i + 1 }}" value="{{ $question->answers[$i]->answer ?? '' }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input type="radio" name="mcq_correct_answer" value="{{ $i }}" aria-label="Radio button for following text input" {{ ($question->answers[$i]->is_correct ?? false) ? 'checked' : '' }} required> &nbsp; Correct
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div id="true_false_answers" class="answer-section" style="display: none;">
            <label>Answers (True/False)</label>
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="tf_answers[]" value="True" readonly>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <input type="radio" name="tf_correct_answer" value="0" aria-label="Radio button for True" {{ ($question->answers[0]->is_correct ?? false) ? 'checked' : '' }} required> &nbsp; Correct
                    </div>
                </div>
            </div>
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="tf_answers[]" value="False" readonly>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <input type="radio" name="tf_correct_answer" value="1" aria-label="Radio button for False" {{ ($question->answers[1]->is_correct ?? false) ? 'checked' : '' }} required> &nbsp; Correct
                    </div>
                </div>
            </div>
        </div>

        <div id="multiple_answer_answers" class="answer-section" style="display: none;">
            <label>Answers (Multiple Answer)</label>
            @for ($i = 0; $i < 4; $i++)
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="ma_answers[]" placeholder="Answer {{ $i + 1 }}" value="{{ $question->answers[$i]->answer ?? '' }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input type="checkbox" name="ma_correct_answers[]" value="{{ $i }}" aria-label="Checkbox for following text input" {{ ($question->answers[$i]->is_correct ?? false) ? 'checked' : '' }}> &nbsp; Correct
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
                    data.append("question_id", {{ $question->id }});
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

        // Function to show/hide answer sections and pre-populate based on question type
        function toggleAnswerSections() {
            var questionType = $('#question_type').val();
            $('.answer-section').hide(); // Hide all answer sections first

            // Reset required attributes for all answer inputs
            $('#mcq_answers input').prop('required', false);
            $('#true_false_answers input').prop('required', false);
            $('#multiple_answer_answers input').prop('required', false); // Reset all first

            if (questionType === 'MCQ') {
                $('#mcq_answers').show();
                $('#mcq_answers input[type="text"]').prop('required', true); // Only text inputs
                $('#mcq_answers input[type="radio"]').prop('required', true); // Radio buttons for correct answer
            } else if (questionType === 'True/False') {
                $('#true_false_answers').show();
                $('#true_false_answers input[type="radio"]').prop('required', true); // Radio buttons for correct answer
            } else if (questionType === 'Multiple Answer') {
                $('#multiple_answer_answers').show();
                $('#multiple_answer_answers input[type="text"]').prop('required', true); // Only text inputs
                // Checkboxes are not required, backend validation handles min:1
            }
        }

        // Initial call to set the correct section and pre-populate on page load
        toggleAnswerSections();

        // Event listener for question type change
        $('#question_type').on('change', toggleAnswerSections);
    });
</script>
@endpush
@endsection