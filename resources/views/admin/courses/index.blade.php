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
                            <th>Course Logo</th>
                            <th>Course Name</th>
                            <th>Course Description</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($courses) > 0)
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img src="{{ asset('uploads/courses/' . $course->logo) }}" alt="{{ $course->name }}" width="50"></td>
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
                            <td colspan="5">No Courses Found!</td>
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
            <form id="addCourseForm" method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">
                <div id="addCourseError" class="alert alert-danger" style="display:none;"></div>
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
                    <div class="form-group">
                        <label for="logo">Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo">
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
            <form id="editCourseForm" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="edit_logo">Logo</label>
                        <input type="file" class="form-control" id="edit_logo" name="logo">
                        <img src="" id="current_logo" width="100" class="mt-2">
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
            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('courses.store') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
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
                    console.error('AJAX Error:', xhr);
                    let errorMessage = 'An unknown error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = 'Server response: ' + xhr.responseText;
                    }
                    $('#addCourseError').text(errorMessage).show();
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
                    if(response.logo){
                        $('#current_logo').attr('src', "{{ asset('uploads/courses/') }}/" + response.logo);
                    } else {
                        $('#current_logo').attr('src', '');
                    }
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
            var formData = new FormData(this);

            var updateUrl = "{{ route('courses.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', courseId);

            $.ajax({
                url: updateUrl,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
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