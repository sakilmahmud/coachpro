@extends('layout/admin-layout')
@section('space-work')

<div class="container">
    <h1>Mock Tests for {{ $course->name }}</h1>
    <a href="{{ route('mock-tests.index') }}" class="btn btn-secondary mb-3">Back to Courses</a>
    <a href="{{ route('mock-tests.create', ['course_id' => $course->id]) }}" class="btn btn-primary mb-3">Create New Mock Test</a>

    @if($mockTests->isEmpty())
    <p>No mock tests found for this course.</p>
    @else
    <table class="table table-bordered table-striped" id="mockTestTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mockTests as $mockTest)
            <tr>
                <td>{{ $mockTest->id }}</td>
                <td>{{ $mockTest->name }}</td>
                <td>{{ $mockTest->description }}</td>
                <td>
                    <a href="{{ route('mock-tests.edit', $mockTest->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('mock-tests.destroy', $mockTest->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    <a href="{{ route('questions.index', ['mock_test_id' => $mockTest->id]) }}" class="btn btn-sm btn-primary">View Questions</a>
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
        $('#mockTestTable').DataTable();
    });
</script>
@endpush
@endsection