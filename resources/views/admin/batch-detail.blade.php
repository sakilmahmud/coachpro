@extends('layout/admin-layout')

@section('space-work')
<style>
    .btn-theme-secondary:hover {
        background-color: orange !important;
        color: white !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="page-title">{{ $batch->subject }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row d-flex align-items-stretch mb-3">
            <div class="col-md-6">
                <div class="card custom-card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Batch Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><p><strong>Course:</strong> {{ $batch->course->name ?? 'N/A' }}</p></div>
                            <div class="col-md-6"><p><strong>Duration:</strong> {{ $batch->duration }}</p></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><p><strong>Start Date:</strong> {{ $batch->starting_date }}</p></div>
                            <div class="col-md-6"><p><strong>End Date:</strong> {{ $batch->end_date }}</p></div>
                        </div>
                        <p><strong>Explanation:</strong> {{ $batch->explaination }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card custom-card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                    </div>
                    <div class="card-body action-buttons">
                        <button type="button" class="btn theme-primary" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                            Enroll Student
                        </button>
                        <button type="button" class="btn theme-primary" data-bs-toggle="modal" data-bs-target="#uploadPdfModal">
                            Upload PDF
                        </button>
                        <button type="button" class="btn theme-primary" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">
                            Upload Video Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header p-2">
                <ul class="nav nav-pills custom-nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#enrolled_students" data-bs-toggle="tab">Enrolled Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pdfs" data-bs-toggle="tab">PDFs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#videos" data-bs-toggle="tab">Video Links</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="enrolled_students">
                        <table id="enrolledStudentsTable" class="table table-bordered table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($batch->getEnrolledStudents) > 0)
                                @foreach($batch->getEnrolledStudents as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->phone_no }}</td>
                                    <td>{{ $student->country }}</td>
                                    <td>{{ $student->address }}</td>
                                    <td>
                                        <form action="{{ route('unenrollStudent') }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <button type="submit" class="btn btn-theme-secondary btn-sm" onclick="return confirm('Are you sure you want to remove this student?')">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7">No students enrolled yet.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="pdfs">
                        <table class="table table-bordered table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Topic</th>
                                    <th>PDF</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($batch->pdfs) > 0)
                                @foreach($batch->pdfs as $pdf)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pdf->topic }}</td>
                                    <td><a href="{{ asset($pdf->pdf) }}" target="_blank">View PDF</a></td>
                                    <td>
                                        <form action="{{ route('deletePdf') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pdf->id }}">
                                            <button type="submit" class="btn btn-theme-secondary btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4">No PDFs uploaded yet.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="videos">
                        <table class="table table-bordered table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Topic</th>
                                    <th>Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($batch->videolinks) > 0)
                                @foreach($batch->videolinks as $video)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $video->topic }}</td>
                                    <td><a href="{{ $video->link }}" target="_blank">View Video</a></td>
                                    <td>
                                        <form action="{{ route('deleteVideo') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $video->id }}">
                                            <button type="submit" class="btn btn-theme-secondary btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4">No video links uploaded yet.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modals will go here -->

<!-- Enroll Student Modal -->
<div class="modal fade" id="enrollStudentModal" tabindex="-1" role="dialog" aria-labelledby="enrollStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="enrollStudentModalLabel">Enroll Student</h5>
                <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="unenrolledStudentsTable" class="table table-bordered table-striped custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($unenrolledStudents) > 0)
                        @foreach($unenrolledStudents as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone_no }}</td>
                            <td>{{ $student->country }}</td>
                            <td>{{ $student->address }}</td>
                            <td>
                                <form action="{{ route('enrollStudent') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button type="submit" class="btn theme-primary btn-sm">Enroll</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7">No students to enroll.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Upload PDF Modal -->
<div class="modal fade" id="uploadPdfModal" tabindex="-1" role="dialog" aria-labelledby="uploadPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="uploadPdfModalLabel">Upload PDF</h5>
                <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('uploadPdf') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                    <div class="form-group">
                        <label for="topic">Topic</label>
                        <input type="text" class="form-control" id="topic" name="topic" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="pdf">PDF File</label>
                        <input type="file" class="form-control-file" id="pdf" name="pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn theme-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Video Link Modal -->
<div class="modal fade" id="uploadVideoModal" tabindex="-1" role="dialog" aria-labelledby="uploadVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="uploadVideoModalLabel">Upload Video Link</h5>
                <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('uploadVideo') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                    <div class="form-group">
                        <label for="topic">Topic</label>
                        <input type="text" class="form-control" id="topic" name="topic" required>
                    </div>
                    <div class="form-group">
                        <label for="link">Video Link</label>
                        <input type="url" class="form-control" id="link" name="link" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn theme-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#enrolledStudentsTable').DataTable({"ordering": false});
        $('#unenrolledStudentsTable').DataTable({"ordering": false});

        // Check if there is a success message in the session
        @if(session('success'))
            // If there is a success message, activate the PDFs tab
            $('.nav-pills a[href="#pdfs"]').tab('show');
        @endif

        // Check file size before uploading
        $('#pdf').on('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size; // in bytes
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (fileSize > maxSize) {
                    alert('The selected file is too large. Please select a file smaller than 2MB.');
                    $(this).val(''); // Clear the file input
                }
            }
        });
    });
</script>
@endpush