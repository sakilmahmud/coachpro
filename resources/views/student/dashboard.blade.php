@extends('layout.student-layout')
@section('space-work')

<div class="container-fluid dashboard-container">
    <h2 class="page-title">Student Dashboard</h2>

    <!-- Enrolled Batches Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card dashboard-section-card">
                <div class="card-header dashboard-section-header">
                    <h4 class="mb-0">Enrolled Batches</h4>
                </div>
                <div class="card-body">
                    @if($enrolledBatches->isEmpty())
                        <p class="text-center text-muted">You are not enrolled in any batches yet. Please contact admin to enroll a batch for a course.</p>
                    @else
                        <div class="row">
                            @foreach($enrolledBatches as $batch)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card course-card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $batch->titel }}</h5>
                                            <p class="card-text">Course: {{ $batch->course_name }}</p>
                                            <a href="{{ route('student.mock.tests', ['course_id' => $batch->course_id]) }}" class="btn btn-sm btn-outline-primary">View Mock Tests</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Enrolled Batches Count Card -->        <div class="col-md-3">
            <div class="card dashboard-kpi-card">
                <div class="card-body text-center">
                    <i class="fa fa-users kpi-icon"></i>
                    <h5 class="card-title kpi-title">Enrolled Batches</h5>
                    <p class="kpi-value">{{ $enrolledBatches->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Mock Tests Card -->
        <div class="col-md-3">
            <div class="card dashboard-kpi-card">
                <div class="card-body text-center">
                    <i class="fa fa-file-text-o kpi-icon"></i>
                    <h5 class="card-title kpi-title">Total Mock Tests</h5>
                    <p class="kpi-value">{{ $totalMockTests ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Attempted Mock Tests Card -->
        <div class="col-md-3">
            <div class="card dashboard-kpi-card">
                <div class="card-body text-center">
                    <i class="fa fa-check-square-o kpi-icon"></i>
                    <h5 class="card-title kpi-title">Attempted Tests</h5>
                    <p class="kpi-value">{{ $attemptedMockTestCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Average Score Card -->
        <div class="col-md-3">
            <div class="card dashboard-kpi-card">
                <div class="card-body text-center">
                    <i class="fa fa-percent kpi-icon"></i>
                    <h5 class="card-title kpi-title">Average Score</h5>
                    <p class="kpi-value">{{ $averageScore ?? 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-md-12">
            <div class="card dashboard-section-card">
                <div class="card-header dashboard-section-header">
                    <h4 class="mb-0">Quick Actions</h4>
                </div>
                <div class="card-body d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ $enrolledBatches->isEmpty() ? '#' : route('student.mock.tests') }}" class="btn btn-lg btn-success quick-action-btn {{ $enrolledBatches->isEmpty() ? 'disabled' : '' }}">
                        <i class="fa fa-play-circle"></i> Start New Test
                    </a>
                    <a href="{{ $enrolledBatches->isEmpty() ? '#' : route('student.mock.tests.attempted') }}" class="btn btn-lg btn-info quick-action-btn {{ $enrolledBatches->isEmpty() ? 'disabled' : '' }}">
                        <i class="fa fa-history"></i> Review Past Results
                    </a>
                    <a href="{{ $enrolledBatches->isEmpty() ? '#' : route('flash.card') }}" class="btn btn-lg btn-warning quick-action-btn {{ $enrolledBatches->isEmpty() ? 'disabled' : '' }}">
                        <i class="fa fa-lightbulb-o"></i> Practice Flashcards
                    </a>
                    <a href="{{ $enrolledBatches->isEmpty() ? '#' : route('query.text') }}" class="btn btn-lg btn-danger quick-action-btn {{ $enrolledBatches->isEmpty() ? 'disabled' : '' }}">
                        <i class="fa fa-question-circle"></i> Submit Query
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-container {
        padding: 30px;
    }

    .page-title {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.8rem;
        color: #2c3e50;
        font-weight: 700;
    }

    .dashboard-kpi-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        height: 100%;
    }

    .dashboard-kpi-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
    }

    .kpi-icon {
        font-size: 3.5rem;
        color: #007bff;
        margin-bottom: 15px;
    }

    .kpi-title {
        font-size: 1.3rem;
        color: #555;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .kpi-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
    }

    .dashboard-section-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        background-color: #ffffff;
    }

    .dashboard-section-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 25px;
        font-size: 1.4rem;
        font-weight: 600;
        color: #333;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .list-group-item {
        padding: 15px 25px;
        border-color: #eee;
    }

    .list-group-item:last-child {
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.6em 0.9em;
    }

    .quick-action-btn {
        padding: 15px 30px;
        font-size: 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .quick-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .quick-action-btn i {
        font-size: 1.5rem;
    }

    /* Custom colors for quick action buttons */
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529; /* Dark text for contrast */
    }
    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* Course Card Specific Styles */
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .course-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .course-card .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #007bff;
    }

    .course-card .card-text {
        font-size: 0.9rem;
        color: #666;
        min-height: 40px; /* Ensure consistent height */
    }
</style>
@endsection