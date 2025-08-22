@extends('layout/admin-layout')

@section('space-work')

<h2 class="mb-4">Student Query</h2>
<!-- Button trigger modal -->

<table class="table table-bordered table-striped" id="studentQueryTable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Student Name</th>
            <th scope="col">Student Mail</th>
            <th scope="col">Country</th>
            <th scope="col">Phone No</th>
            <th scope="col">Student Query</th>
            <th scope="col">Date & Time</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        @php
        $counter = 1;
        @endphp
        @if(count($studentQueries) > 0)

        @foreach($studentQueries as $subject)
        <tr>
            <td> {{ $counter ++ }} </td>
            <td> {{ $subject->student_name }} </td>
            <td> {{ $subject->student_mail }} </td>
            <td> {{ $subject->contry }} </td>
            <td> {{ $subject->student_number }} </td>
            <td> {{ $subject->student_querys }} </td>
            <td> {{ $subject->created_at }} </td>
            <td>
                <button class="btn btn-danger deleteButton" data-id="{{ $subject->id }}" data-toggle="modal" data-target="#deleteSubjectModel">Delete</button>
            </td>
        </tr>
        @endforeach

        @else
        <tr>
            <td colspan="8">Student Query not Found!</td>
        </tr>
        @endif

    </tbody>
</table>

<!-- Delete Subject Modal -->
<div class="modal fade" id="deleteSubjectModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
        $('#studentQueryTable').DataTable();
        $(".deleteButton").click(function() {
            var subject_id = $(this).data('id');
            $("#delete_subject_id").val(subject_id);
        });

        $("#deleteSubject").submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('deleteqery') }}",
                type: "POST",
                data: formData,
                success: function(data) {
                    if (data.success == true) {
                        // Show a success message in a popup
                        alert('Your data has been deleted successfully');
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        });
    });
</script>









</script>


@endsection