@extends('layout/admin-layout')
@section('space-work')
<div class="container">
    <h1>Create New Flash Card for {{ $course->name }}</h1>
    <a href="{{ route('flash-cards.show', $course->id) }}" class="btn btn-secondary mb-3">Back to Flash Cards</a>

    <form action="{{ route('flash-cards.store') }}" method="POST">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <div class="form-group">
            <label for="question">Question</label>
            <textarea class="form-control summernote" id="question" name="question" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="answer">Answer</label>
            <textarea class="form-control summernote" id="answer" name="answer" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn theme-primary mt-3">Create Flash Card</button>
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