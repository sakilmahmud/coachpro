@extends('layout.student-layout')

@section('space-work')

<div class="container-fluid mock-tests-container">
    <h2 class="page-title">Attempted Mock Tests</h2>

    @if($allAttempts->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            You have not attempted any mock tests yet.
        </div>
    @else
        <div class="row">
            @foreach($allAttempts as $attempt)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card mock-test-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $attempt->mockTest->name ?? 'N/A' }}</h5> <!-- Use mockTest relationship -->
                            <p class="card-text description">
                                Attempted on: {{ $attempt->created_at->format('M d, Y H:i A') }}<br>
                                Score: {{ $attempt->percentage }}%<br>
                                Correct: {{ $attempt->correct_count }}, Incorrect: {{ $attempt->incorrect_count }}, Unattempted: {{ $attempt->unattempted_count }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="test-time"><i class="fa fa-clock-o"></i> {{ $attempt->mockTest->time ?? 'N/A' }} minutes</span>
                            </div>
                            <a href="{{ route('student.mock.test.result', $attempt->mock_test_id) }}" class="btn btn-primary view-results-btn">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }

    .mock-tests-container {
        padding: 30px;
    }

    .page-title {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.5rem;
        color: #333;
        font-weight: 700;
    }

    .mock-test-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .mock-test-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 25px;
    }

    .card-title {
        font-size: 1.8rem;
        color: #007bff;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .card-text.description {
        font-size: 1rem;
        color: #555;
        margin-bottom: 20px;
        line-height: 1.6;
        min-height: 60px; /* Ensure consistent height for descriptions */
    }

    .test-time {
        font-size: 0.95rem;
        color: #777;
        font-weight: 500;
    }

    .test-time i {
        margin-right: 8px;
        color: #007bff;
    }

    .view-results-btn {
        width: 100%;
        padding: 12px 0;
        font-size: 1.1rem;
        border-radius: 8px;
        font-weight: 600;
        background-color: #28a745; /* Green for results */
        border-color: #28a745;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .view-results-btn:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-2px);
    }

    .alert-info {
        background-color: #e0f7fa;
        border-color: #b2ebf2;
        color: #00796b;
        font-size: 1.1rem;
        padding: 20px;
        border-radius: 10px;
    }
</style>
@endsection