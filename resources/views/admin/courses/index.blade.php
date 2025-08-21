@extends('layout/admin-layout')
@section('space-work')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Courses</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="courseTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($courses) > 0)
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>
                                <button class="btn btn-info btn-sm editButton" data-id="{{ $course->id }}" data-bs-toggle="modal" data-bs-target="#editCourseModal">Edit</button>
                                <button class="btn btn-danger btn-sm deleteButton" data-id="{{ $course->id }}" data-bs-toggle="modal" data-bs-target="#deleteCourseModal">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4">No Courses Found!</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addCourseForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Course Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCourseForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_course_id" name="id">
                    <div class="form-group">
                        <label for="edit_name">Course Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Course Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" role="dialog" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="deleteCourseModalLabel">Delete Course</h5>
                <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteCourseForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this course?</p>
                    <input type="hidden" id="delete_course_id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Add Course
        $('#courseTable').DataTable(); // Initialize Datatable
        $('#addCourseForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('courses.store') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addCourseForm')[0].reset();
                        $('#addCourseModal').modal('hide');
                        location.reload();
                    } else {
                        // Show inline error instead of alert
                        $('#addCourseError').text(response.message).show();
                    }
                },
                error: function(xhr) {
                    $('#addCourseError').text('An error occurred: ' + xhr.responseText).show();
                }
            });
        });

        // Edit Course - Populate Modal
        $('.editButton').click(function() {
            var courseId = $(this).data('id');

            var editUrl = "{{ route('courses.edit', ':id') }}";
            editUrl = editUrl.replace(':id', courseId);

            $.ajax({
                url: editUrl,
                type: 'GET',
                success: function(response) {
                    $('#edit_course_id').val(response.id);
                    $('#edit_name').val(response.name);
                    $('#edit_description').val(response.description);
                },
                error: function(xhr) {
                    alert('Error fetching course data: ' + xhr.responseText);
                }
            });
        });

        // Update Course
        $('#editCourseForm').submit(function(e) {
            e.preventDefault();
            var courseId = $('#edit_course_id').val();

            var updateUrl = "{{ route('courses.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', courseId);

            $.ajax({
                url: updateUrl,
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        //alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });


        // Delete Course - Populate Modal
        $('.deleteButton').click(function() {
            var courseId = $(this).data('id');
            $('#delete_course_id').val(courseId);
        });

        // Delete Course
        $('#deleteCourseForm').submit(function(e) {
            e.preventDefault();
            var courseId = $('#delete_course_id').val();
            var courseId = $('#delete_course_id').val();
            var deleteUrl = "{{ route('courses.destroy', ':id') }}";
            deleteUrl = deleteUrl.replace(':id', courseId);
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        //alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });
    });
</script>
@endsection