@extends('layout/admin-layout')

@section('space-work')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $batch->subject }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Batch Details</h3>
            </div>
            <div class="card-body">
                <p><strong>Course:</strong> {{ $batch->course->name ?? 'N/A' }}</p>
                <p><strong>Duration:</strong> {{ $batch->duration }}</p>
                <p><strong>Start Date:</strong> {{ $batch->starting_date }}</p>
                <p><strong>End Date:</strong> {{ $batch->end_date }}</p>
                <p><strong>Explanation:</strong> {{ $batch->explnation }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Actions</h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                    Enroll Student
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadPdfModal">
                    Upload PDF
                </button>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">
                    Upload Video Link
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#enrolled_students" data-bs-toggle="tab">Enrolled Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pdfs" data-bs-toggle="tab">PDFs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#videos" data-bs-toggle="tab">Video Links</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="enrolled_students">
                        <table id="enrolledStudentsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
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
                                    <td>
                                        <button class="btn btn-danger btn-sm">Remove</button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4">No students enrolled yet.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="pdfs">
                        <table class="table table-bordered table-striped">
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
                                    <td><a href="{{ asset('storage/'.$pdf->pdf) }}" target="_blank">View PDF</a></td>
                                    <td>
                                        <form action="{{ route('deletePdf') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pdf->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
                        <table class="table table-bordered table-striped">
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
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="enrollStudentModalLabel">Enroll Student</h5>
                <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="unenrolledStudentsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Email</th>
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
                            <td>
                                <form action="{{ route('enrollStudent') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm">Enroll</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4">No students to enroll.</td>
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
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="uploadPdfModalLabel">Upload PDF</h5>
                <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Video Link Modal -->
<div class="modal fade" id="uploadVideoModal" tabindex="-1" role="dialog" aria-labelledby="uploadVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="uploadVideoModalLabel">Upload Video Link</h5>
                <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#enrolledStudentsTable').DataTable();
        $('#unenrolledStudentsTable').DataTable();
    });
</script>
@endpush