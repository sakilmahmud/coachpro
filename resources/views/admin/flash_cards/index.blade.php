@extends('layout/admin-layout')

@section('space-work')
<div class="container">
    <h1>Flash Questions for {{ $course->name }}</h1>
    <a href="{{ route('flash-cards.index') }}" class="btn btn-secondary mb-3">Back to Courses</a>
    <a href="{{ route('flash-cards.create', ['course_id' => $course->id]) }}" class="btn btn-primary mb-3">Create New Flash Question</a>

    @if($flashQuestions->isEmpty())
        <p>No flash questions found for this course.</p>
    @else
        <table class="table table-bordered table-striped" id="flashQuestionTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flashQuestions as $flashQuestion)
                    <tr>
                        <td>{{ $flashQuestion->id }}</td>
                        <td>{!! $flashQuestion->question !!}</td>
                        <td>{!! $flashQuestion->answer !!}</td>
                        <td>
                            <a href="{{ route('flash-cards.edit', $flashQuestion->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('flash-cards.destroy', $flashQuestion->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#flashQuestionTable').DataTable();
    });
</script>
@endpush
@endsection
