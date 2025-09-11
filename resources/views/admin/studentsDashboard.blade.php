@extends('layout/admin-layout')

@section('space-work')

<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid d-flex justify-content-between align-items-center my-2">
			<h2>Students</h2>
            <div>
			<button type="button" class="btn btn-primary mr-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                Add Student
            </button>
			<a href="{{ route('exportStudents') }}" class="btn btn-dark">Export Students</a>
            </div>
		</div>
	</section>
	<section class="content">
		<table class="table table-bordered table-striped" id="studentTable">
			<thead>
				<th>#</th>
				<th>Name</th>
				<th>Email</th>
				<th>Country</th>
				<th>Phone No</th>
				<th>Enrolled Course</th>
				<th width="15%">Action</th>
			</thead>
			<tbody>
				@php
				$counter = 1;
				@endphp
				@if(count($students) > 0)
				@foreach($students as $student)
				<tr>
					<td>{{$counter++}}</td>
					<td><a href="{{ route('student.details', ['id' => $student->id]) }}">{{ $student->name }}</a></td>
					<td>{{ $student->email }}</td>
					<td>{{ $student->country }}</td>
					<td>{{ $student->phone_no }}</td>
					<td>
                        @php
                            $courseNames = [];
                            foreach ($student->accessSubjects as $access) {
                                if ($access->subject && $access->subject->course) {
                                    $courseNames[] = $access->subject->course->name;
                                }
                            }
                            $courseNames = array_unique($courseNames);
                        @endphp
                        @if(count($courseNames) > 0)
                            {{ implode(', ', $courseNames) }}
                        @else
                            N/A
                        @endif
                    </td>
					
					<td>
						<button type="button" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-email="{{ $student->email }}" data-address="{{ $student->address }}" data-address_2="{{ $student->address_2 }}" data-city="{{ $student->city }}" data-state="{{ $student->state }}" data-country="{{ $student->country }}" data-phone="{{ $student->phone_no }}" data-alterphone="{{ $student->altphone_no }}" class="btn btn-info mr-3 editButton" data-bs-toggle="modal" data-bs-target="#editStudentModal">
							Edit
						</button>					
						<button type="button" data-id="{{ $student->id }}" class="btn btn-danger deleteButton" data-bs-toggle="modal" data-bs-target="#deleteStudentModal">
							Delete
						</button>
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="3">Students not Found!</td>
				</tr>
				@endif
			</tbody>
		</table>
	</section>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStudent">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Student Name" required>
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Student Email" required>
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address 1 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address 1" required>
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_2" class="form-label">Address 2</label>
                                <input type="text" class="form-control" id="address_2" name="address_2" placeholder="Enter Address 2">
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" required>
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="state" name="state" placeholder="Enter State" required>
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country" required>
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone_no" class="form-label">Phone No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Enter Phone No" required>
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="altphone_no" class="form-label">Alt Phone</label>
                                <input type="text" class="form-control" id="altphone_no" name="altphone_no" placeholder="Enter Alternate Phone No">
                                <span class="text-danger error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStudent">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter Student Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="edit_email" name="email" placeholder="Enter Student Email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_address" class="form-label">Address 1 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_address" name="address" placeholder="Enter Address 1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_address_2" class="form-label">Address 2</label>
                                <input type="text" class="form-control" id="edit_address_2" name="address_2" placeholder="Enter Address 2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_city" name="city" placeholder="Enter City" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_state" class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_state" name="state" placeholder="Enter State" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_country" class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_country" name="country" placeholder="Enter Country" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_phone_no" class="form-label">Phone No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_phone_no" name="phone_no" placeholder="Enter Phone No" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_altphone_no" class="form-label">Alt Phone</label>
                                <input type="text" class="form-control" id="edit_altphone_no" name="altphone_no" placeholder="Enter Alternate Phone No">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary updateButton">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete student Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Delete Student</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="deleteStudent">
				@csrf
				<div class="modal-body">
					<p>Are your sure you want to delete Student?</p>
					<input type="hidden" name="id" id="student_id">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-danger">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>

@push('scripts')
<script>
	$(document).ready(function() {
		$("#addStudent").submit(function(e) {
            e.preventDefault();

            var form = $(this);
            var formData = new FormData(this);
            var submitBtn = form.find("button[type=submit]");

            // Disable button
            submitBtn.prop("disabled", true).text("Please wait...");

            // Clear previous errors
            form.find(".error-message").text("");
            form.find(".form-error-box").remove();

            $.ajax({
                url: "{{ route('addStudent') }}",
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        location.reload();
                        // ✅ Reset form & close modal
                        form[0].reset();
                        $("#addStudentModal").modal("hide");
                    }
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;

                    if (xhr.status === 422 && response && response.errors) {
                        // Validation errors
                        $.each(response.errors, function(field, messages) {
                            let input = form.find("[name=" + field + "]");
                            input.closest(".mb-3").find(".error-message").text(messages[0]);
                        });
                    } else if (response && response.msg) {
                        // Show backend custom error
                        form.prepend('<div class="text-danger mb-2 form-error-box">' + response.msg + '</div>');
                    } else {
                        // Fallback
                        form.prepend('<div class="text-danger mb-2 form-error-box">Something went wrong. Please try again.</div>');
                    }

                    submitBtn.prop("disabled", false).text("Add Student");
                }

            });
        });




		//edit button click and show values
		$(".editButton").click(function() {

			$("#edit_id").val($(this).attr('data-id'));
			$("#edit_name").val($(this).attr('data-name'));
			$("#edit_email").val($(this).attr('data-email'));
			$("#edit_address").val($(this).attr('data-address'));
			$("#edit_address_2").val($(this).data('address_2'));
			$("#edit_city").val($(this).data('city'));
			$("#edit_state").val($(this).data('state'));
			$("#edit_country").val($(this).attr('data-country'));
			$("#edit_phone_no").val($(this).attr('data-phone'));
			$("#edit_altphone_no").val($(this).attr('data-alterphone'));

		});

		$("#editStudent").submit(function(e) {
			e.preventDefault();
			$('.updateButton').prop('disabled', true);

			var formData = new FormData(this);

			$.ajax({
				url: "{{ route('editStudent') }}",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function(data) {
					if (data.success == true) {
						location.reload();
					} else {
						alert(data.msg);
					}
				}
			});

		});

		$(".deleteButton").click(function() {
			var id = $(this).attr('data-id');
			$("#student_id").val(id);
		});

		$("#deleteStudent").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "{{ route('deleteStudent') }}",
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
</script>
<script>
    $(document).ready(function () {
        $('#studentTable').DataTable();
    });
</script>
@endpush


@endsection