@extends('layout.student-layout')

@section('space-work')
<div class="container-fluid mock-tests-container">
    <h2 class="page-title">Available Mock Tests</h2>

    @if($mockTests->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            No mock tests available at the moment.
        </div>
    @else
        <div class="row">
            @foreach($mockTests as $mockTest)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card mock-test-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('uploads/courses/' . $mockTest->course->logo) }}" alt="{{ $mockTest->course->name }}" class="me-3" width="60">
                                <h5 class="card-title">{{ $mockTest->title }}</h5>
                            </div>
                            <p class="card-text description">{{ $mockTest->description }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="test-time"><i class="fa fa-clock-o"></i> {{ $mockTest->time }} minutes</span>
                            </div>
                            <a href="{{ route('student.mock.test.questions', $mockTest->id) }}" class="btn btn-primary view-questions-btn">Start Test</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Go Back</a>
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
        color: #000;
        margin-bottom: 0; /* Remove bottom margin to align better with image */
        font-weight: 600;
        white-space: nowrap; /* Prevent title from wrapping */
        overflow: hidden;
        text-overflow: ellipsis; /* Add ellipsis for long titles */
        max-width: calc(100% - 70px); /* Adjust based on image width + margin */
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
        color: #000;
    }

    .view-questions-btn {
        width: 100%;
        padding: 12px 0;
        font-size: 1.1rem;
        border-radius: 8px;
        font-weight: 600;
        background-color: #000;
        border-color: #000;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .view-questions-btn:hover {
        background-color: #000;
        border-color: #000;
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