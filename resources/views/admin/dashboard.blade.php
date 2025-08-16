@extends('layout/admin-layout')
@section('space-work')

<style>
    .form_section {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form_wrapper {
        width: 400px;
        margin: 20px 0;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Batches</h2>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubjectModel">Add Batches</button>
            </div>
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
                            <th scope="col">Subject</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Explanation</th>
                            <th scope="col">Add Students</th>
                            <th scope="col">Upload Pdf</th>
                            <th scope="col">Upload Video</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
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
                            <td> {{ $subject->titel }} </td>
                            <td> {{ $subject->duration }} </td>
                            <td> {{ $subject->starting_date }} </td>
                            <td> {{ $subject->end_date }} </td>
                            <td> {{ $subject->explnation }} </td>
                            <td> <a href="#" class="addQuestion" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#addQnaModal">Add Students</a> <br> <a href="#" class="seeQuestions" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#seeQnaModal">View Students</a></td>
                            <td> <a href="#" class="savepdf" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#addPdf">Add Pdf</a> <br>
                                <a href="{{ route('pdf.show', ['id' => $subject->id]) }}">View Pdf</a>
                            </td>
                            <td> <a href="#" class="addlink" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#addlink">Add Link</a> <br>
                                <a href="{{ route('link.show', ['id' => $subject->id]) }}">View Link</a>
                            </td>
                            <td>
                                <button class="btn btn-info editButton" data-id="{{$subject->id}}" data-subject="{{ $subject->subject }}" data-title="{{ $subject->titel }}" data-duration="{{ $subject->duration }}" data-startdate="{{ $subject->starting_date }}" data-enddate="{{ $subject->end_date }}" data-explanation="{{ $subject->explnation }}" data-toggle="modal" data-target="#editSubjectModel">Edit</button>
                            </td>
                            <td>
                                <button class="btn btn-danger deleteButton" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#deleteSubjectModel">Delete</button>
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
                <!-- Add student model -->
                <div class="modal fade" id="addQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Add Student</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addQna">
                                @csrf
                                <div class="modal-body form_section">
                                    <div class="form_wrapper">
                                        <input type="hidden" name="exam_id" id="addExamId">
                                        <!-- <input type="search" name="search" id="search" onkeyup="searchTable()" class="form-control w-100" placeholder="Search here"> -->
                                        <table class="table" id="questionsTable">
                                            <thead>
                                                <th>Select</th>
                                                <th>Email</th>
                                                <th>Name</th>
                                            </thead>
                                            <tbody class="addBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Student</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- See Student Modal -->
                <div class="modal fade" id="seeQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Students</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body form_section">
                                <div class="form_wrapper">
                                    <table class="table">
                                        <thead>
                                            <th>S.No</th>
                                            <th>Student</th>
                                            <th>Delete</th>
                                        </thead>
                                        <tbody class="seeQuestionTable">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Upload Pdf-->
                <div class="modal fade" id="addPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Add PDF</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form enctype="multipart/form-data" action="{{ route('addpdf', ['id' => $subject->id]) }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" id="addpdfs" name="subject_id" readonly>
                                    <input type="Text" name="topic" class="form-control w-100" placeholder="Please Enter Topic Name">
                                    <br> <br>
                                    <input type="file" name="pdf" class="form-control w-100">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload Pdf</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('.savepdf').click(function() {
                            var id = $(this).data('id'); // Use data('id') to get the attribute value
                            $('#addpdfs').val(id);
                        });
                    });
                </script>
                <!-- See Pdf Model -->
                <div class="modal fade" id="seePdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Pdf</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body form_section">
                                <div class="form_wrapper">
                                    <table class="table">
                                        <thead>
                                            <th>S.No</th>
                                            <th>Pdf</th>
                                            <th>Delete</th>
                                        </thead>
                                        <tbody class="seeQuestionTable">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Upload Video Link-->
                <div class="modal fade" id="addlink" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Add Video Link</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addVideolink">
                                @csrf
                                <div class="modal-body form_section">
                                    <div class="form_wrapper">
                                        <input type="hidden" name="exam_id" id="addlinks" readonly> <br><br>
                                        <input type="Text" name="topic" class="form-control w-100" placeholder="Please Enter Topic Name">
                                        <br> <br>
                                        <!-- <input type="search" name="search" id="search" onkeyup="searchTable()" class="form-control w-100" placeholder="Search here"> -->
                                        <input type="text" class="form-control w-100" placeholder="Enter Video Link" name="link">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <!-- <a href="#" class="seeVideo" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#seeVideo"><button class="btn btn-success">See Video</button></a> -->
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload Link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('.addlink').click(function() {
                var id = $(this).data('id'); // Use data('id') to get the attribute value
                $('#addlinks').val(id);
                $("#addVideolink").submit(function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('addlink') }}",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            if (data.success == true) {
                                location.reload();
                            } else {
                                alert(data.msg);
                            }
                        },
                    });
                });
                // 
            });
        });
    </script>
    <!-- See Video Link -->
    <!-- Modal -->
    <div class="modal fade" id="addSubjectModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="addSubject">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Batches</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body form_section">
                        <div class="form_wrapper">
                            <label>Batch Name:</label>
                            <input type="text" name="subject" class="form-control w-100" placeholder="Enter Subject Name" required>

                            <label>Subject:</label>
                            <select name="title" class="form-control w-100">
                                <option value="RfMP">PfMP</option>
                                <option value="PgMP">PgMP</option>
                                <option value="PMP">PMP</option>
                                <option value="PMI-ACP">PMI-ACP</option>
                                <option value="PMI-RMP">PMI-RMP</option>
                            </select>

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

    <!-- Edit Subject Modal -->
    <div class="modal fade" id="editSubjectModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editSubject">
                    @csrf
                    <div class="modal-body form_section">
                        <div class="form_wrapper">
                            <label>Batch Name:- </label>
                            <input type="text" name="subject" class="form-control w-100" placeholder="Enter Subject Name" id="edit_subject" required>
                            <input type="hidden" name="id" id="edit_subject_id">
                            <!-- edit subject name for code trough Ajax -->

                            <label>Subject Name:- </label>
                            <input type="hidden" name="id" id="edit_title_id">
                            <select name="title" id="edit_title" class="form-control w-100">
                                <option value="RfMP">PfMP</option>
                                <option value="PgMP">PgMP </option>
                                <option value="PMP">PMP</option>
                                <option value="PMI-ACP">PMI-ACP</option>
                                <option value="PMI-RMP">PMI-RMP</option>
                            </select>

                            <label>Duration:- </label>
                            <input type="text" name="duration" class="form-control w-100" placeholder="Enter Subject Name" id="edit_duration" required>
                            <input type="hidden" name="id" id="edit_duration_id">


                            <label>Start Date:- </label>
                            <input type="date" name="startdate" class="form-control w-100" placeholder="Enter Subject Name" id="edit_startdate" required>
                            <input type="hidden" name="id" id="edit_startdate_id">


                            <label>End Date:- </label>
                            <input type="date" name="enddate" class="form-control w-100" placeholder="Enter Subject Name" id="edit_enddate" required>
                            <input type="hidden" name="id" id="edit_enddate_id">
                            <!-- edit subject name for code trough Ajax -->

                            <label>Explanation:- </label>
                            <!-- <input type="date" name="enddate" placeholder="Enter Subject Name" id="edit_enddate" required> -->
                            <textarea name="explnation" id="edit_explanation" cols="30" rows="10" class="form-control w-100" style="height: 100px;"></textarea>
                            <input type="hidden" name="id" id="edit_explanation_id">
                            <!-- edit subject name for code trough Ajax -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Subject Modal -->
    <div class="modal fade" id="deleteSubjectModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteSubject">
                    @csrf
                    <div class="modal-body">
                        <p>Are you Sure you want to delete Subject?</p>
                        <input type="hidden" name="id" id="delete_subject_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
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
                $("#edit_subject").val(subject);
                $("#edit_subject_id").val(subject_id);
                var title_id = $(this).attr('data-id');
                var title = $(this).attr('data-title');
                $("#edit_title").val(title);
                $("#edit_title_id").val(title_id);
                var duration_id = $(this).attr('data-id');
                var duration = $(this).attr('data-duration');
                $("#edit_duration").val(duration);
                $("#edit_duration_id").val(duration_id);
                var startdate_id = $(this).attr('data-id');
                var startdate = $(this).attr('data-startdate');
                $("#edit_startdate").val(startdate);
                $("#edit_startdate_id").val(startdate_id);
                var enddate_id = $(this).attr('data-id');
                var enddate = $(this).attr('data-enddate');
                $("#edit_enddate").val(enddate);
                $("#edit_enddate_id").val(enddate_id);
                var explanation_id = $(this).attr('data-id');
                var explanation = $(this).attr('data-explanation');
                $("#edit_explanation").val(explanation);
                $("#edit_explanation_id").val(explanation_id);
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