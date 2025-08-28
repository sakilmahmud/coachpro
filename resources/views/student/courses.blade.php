@extends('layout.student-layout')

@section('space-work')
    <div class="container course-page-container">
        <h2 class="section-title text-center mb-4">All Available Courses</h2>
        <div class="row">
            @forelse($allCourses as $course)
                <div class="col-md-4 mb-4">
                    <div class="card course-card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-white">{{ $course->name }}</h5>
                            <p class="card-text text-white-50 flex-grow-1">{{ Str::limit($course->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                @if($enrolledBatches->pluck('course_id')->contains($course->id))
                                    <span class="badge bg-success course-badge">Enrolled</span>
                                @else
                                    <span class="badge bg-info course-badge">Available</span>
                                @endif
                                <button type="button" class="btn btn-sm btn-light view-details-btn"
                                        data-bs-toggle="modal" data-bs-target="#courseDetailsModal"
                                        data-course-name="{{ $course->name }}"
                                        data-course-description="{{ $course->description }}">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="alert alert-info text-center">No courses available at the moment.</p>
                </div>
            @endforelse
        </div>

        <h2 class="section-title text-center mt-5 mb-4">My Enrolled Batches</h2>
        @if($enrolledBatches->isEmpty())
            <div class="alert alert-warning text-center">
                You are not enrolled in any batches yet. Please contact admin to enroll a batch for a course.
            </div>
        @else
            <div class="list-group enrolled-courses-list">
                @foreach($enrolledBatches as $batch)
                    <a href="{{ count($batch->mockTests) > 0 ? route('student.mock.tests', ['course_id' => $batch->course_id]) : '#' }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ count($batch->mockTests) > 0 ? '' : 'disabled' }}">
                        <div>
                            <h5 class="mb-1">{{ $batch->titel }}</h5>
                            <small class="text-muted">Course: {{ $batch->course_name }}</small>
                        </div>
                        @if(count($batch->mockTests) == 0)
                            <span class="badge bg-warning text-dark no-mock-tests-badge">No Mock Tests Available</span>
                        @else
                            <span class="badge bg-primary mock-tests-count-badge">{{ count($batch->mockTests) }} Mock Tests</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
        <div class="text-center mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
        </div>
    </div>

    <!-- Course Details Modal -->
    <div class="modal fade" id="courseDetailsModal" tabindex="-1" aria-labelledby="courseDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="courseDetailsModalLabel">Course Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 id="modalCourseName" class="text-primary"></h6>
                    <p id="modalCourseDescription"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .course-page-container {
            padding: 30px 0;
        }
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }
        .course-card {
            border: none; /* Remove default border */
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: linear-gradient(45deg, #FF8C00, #000000); /* Orange to Black gradient */
            color: white; /* Text color for gradient cards */
        }
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }
        .course-card .card-body {
            padding: 25px;
        }
        .course-card .card-title {
            font-size: 1.6rem;
            margin-bottom: 10px;
            color: white !important; /* Ensure title is white */
        }
        .course-card .card-text {
            font-size: 1rem;
            line-height: 1.6;
            min-height: 80px; /* Adjusted height for description */
            color: rgba(255, 255, 255, 0.8); /* Lighter text for description */
        }
        .course-badge {
            font-size: 0.9rem;
            padding: 0.6em 1em;
            border-radius: 5px;
            font-weight: 600;
        }
        .enrolled-courses-list .list-group-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background-color 0.2s ease, transform 0.2s ease;
            padding: 15px 20px;
        }
        .enrolled-courses-list .list-group-item:hover:not(.disabled) {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        .enrolled-courses-list .list-group-item.disabled {
            opacity: 0.7;
            cursor: not-allowed;
            background-color: #f2f2f2;
        }
        .enrolled-courses-list .list-group-item h5 {
            font-size: 1.2rem;
            color: #333;
        }
        .enrolled-courses-list .list-group-item small {
            font-size: 0.85rem;
        }
        .no-mock-tests-badge, .mock-tests-count-badge {
            font-size: 0.8rem;
            padding: 0.4em 0.7em;
            border-radius: 5px;
        }
        .view-details-btn {
            background-color: white;
            color: #007bff;
            border: none;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .view-details-btn:hover {
            background-color: #e9ecef;
            color: #0056b3;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#courseDetailsModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var courseName = button.data('course-name'); // Extract info from data-* attributes
                var courseDescription = button.data('course-description');

                var modal = $(this);
                modal.find('#modalCourseName').text(courseName);
                modal.find('#modalCourseDescription').text(courseDescription);
            });
        });
    </script>
@endsection