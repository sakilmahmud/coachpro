@extends('layout/admin-layout')
@section('space-work')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Batches</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubjectModel">Add Batches</button>
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Batch Name</th>
                            <th scope="col">Course</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Explanation</th>
                            <th scope="col" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1;
                        @endphp
                        @if(count($subjects) > 0)
                        @foreach($subjects as $subject)
                        <tr>
                            <td> {{ $counter ++ }} </td>
                            <td> {{ $subject->subject }} </td>
                            <td> {{ $subject->course->name ?? 'N/A' }} </td> {{-- Display Course Name --}}
                            <td> {{ $subject->duration }} </td>
                            <td> {{ $subject->starting_date }} </td>
                            <td> {{ $subject->end_date }} </td>
                            <td> {{ $subject->explnation }} </td>
                            <td>
                                <a href="{{ route('batchDetail', ['id' => $subject->id]) }}" class="btn btn-sm btn-primary">View</a>
                                <button class="btn btn-sm btn-info editButton" data-id="{{$subject->id}}" data-subject="{{ $subject->subject }}" data-course_id="{{ $subject->course_id }}" data-duration="{{ $subject->duration }}" data-startdate="{{ $subject->starting_date }}" data-enddate="{{ $subject->end_date }}" data-explanation="{{ $subject->explnation }}" data-toggle="modal" data-target="#editSubjectModel">Edit</button>
                                <button class="btn btn-sm btn-danger deleteButton" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#deleteSubjectModel">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4">batches not Found!</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addSubjectModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="addSubject">
                @csrf
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Batches</h5>
                        <button type="button" class="btn btn-danger close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body form_section">
                        <div class="form_wrapper">
                            <label>Batch Name:</label>
                            <input type="text" name="subject" class="form-control w-100" placeholder="Enter Subject Name" required>

                            <!-- Course will here -->

                            <label>Duration:</label>
                            <input type="text" name="duration" class="form-control w-100" placeholder="Enter Duration" required>

                            <label>Starting Date:</label>
                            <input type="date" name="startdate" class="form-control w-100" required>

                            <label>Ending Date:</label>
                            <input type="date" name="enddate" class="form-control w-100" required>

                            <label>Explanation:</label>
                            <textarea name="explanation" class="form-control w-100" style="height: 100px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#addSubject").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('addSubject') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });
            //edit subject get value
            $(".editButton").click(function() {
                var subject_id = $(this).attr('data-id');
                var subject = $(this).attr('data-subject');
                var course_id = $(this).attr('data-course_id'); // New: Get course_id
                var duration = $(this).attr('data-duration');
                var startdate = $(this).attr('data-startdate');
                var enddate = $(this).attr('data-enddate');
                var explanation = $(this).attr('data-explanation');

                $("#edit_subject").val(subject);
                $("#edit_subject_id").val(subject_id);
                $("#edit_course_id").val(course_id); // New: Set course_id
                $("#edit_duration").val(duration);
                $("#edit_startdate").val(startdate);
                $("#edit_enddate").val(enddate);
                $("#edit_explanation").val(explanation);
            });
            /////Replace and update code with ajax
            $("#editSubject").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('editSubject') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });
            //delete subject
            $(".deleteButton").click(function() {
                var subject_id = $(this).attr('data-id');
                $("#delete_subject_id").val(subject_id);
            });
            $("#deleteSubject").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('deleteSubject') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });
        });
        //add questions part
        $('.addQuestion').click(function() {
            var id = $(this).data('id'); // Use data('id') to get the attribute value
            $('#addExamId').val(id);
            $.ajax({
                url: "{{ route('getStudent') }}",
                type: "GET",
                data: {
                    exam_id: id
                },
                success: function(data) {
                    if (data.success === true) {
                        var questions = data.data;
                        var html = '';
                        if (questions.length > 0) {
                            for (let i = 0; i < questions.length; i++) {
                                html += `
                                <tr>
                                    <td><input type="checkbox" value="${questions[i]['id']}" name="questions_ids[]"></td>
                                    <td>${questions[i]['email']}</td>
                                    <td>${questions[i]['name']}</td>
                                </tr>
                            `;
                            }
                        } else {
                            html += `
                            <tr>
                                <td colspan="3">Users not Available!</td>
                            </tr>`;
                        }
                        $('.addBody').html(html);
                    } else {
                        alert(data.msg);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while fetching student data.');
                }
            });
        });
        $("#addQna").submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "{{ route('addStudents') }}",
                type: "POST",
                data: formData,
                success: function(data) {
                    if (data.success === true) {
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while adding questions.');
                }
            });
        });
        //see questions
        $('.seeQuestions').click(function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('getStudentsee') }}",
                type: "GET",
                data: {
                    exam_id: id
                },
                success: function(data) {
                    console.log(data);
                    var html = '';
                    var questions = data.data;
                    if (questions.length > 0) {
                        for (let i = 0; i < questions.length; i++) {
                            html += `
                                <tr>
                                    <td>` + (i + 1) + `</td>
                                    <td>${questions[i]['email']}</td>
                                    <td>${questions[i]['name']}</td>
                                    <td>
                                        <button class="btn btn-danger deleteQuestion" data-id="` + questions[i]['id'] + `">Delete</button>
                                    </td>
                                </tr>
                            `;
                        }
                    } else {
                        html += `
                            <tr>
                                <td colspan="1">Students not available!</td>
                            </tr>
                        `;
                    }
                    $('.seeQuestionTable').html(html);
                }
            });
        });
        //delete question
        $(document).on('click', '.deleteQuestion', function() {
            var id = $(this).attr('data-id');
            var obj = $(this);
            $.ajax({
                url: "{{ route('deleteExamQuestions') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.success == true) {
                        obj.parent().parent().remove();
                    } else {
                        alert(data.msg);
                    }
                }
            })
        });
    </script>
    @endsection