@extends('layout.student-layout')

@section('space-work')
<div class="container-fluid text-center">
    @if($mockTest->course)
        <p class="batch-name mb-0 pb-0">Batch: {{ $mockTest->course->name }}</p>
    @endif
</div>

<div class="container-fluid results-container mt-0 pt-0">
    <h2 class="page-title">Your Latest Mock Test Result</h2>

    @if(!$latestResult)
        <div class="alert alert-info text-center" role="alert">
            You have not attempted this mock test yet.
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 mb-4"> <!-- Adjust column size for single card -->
                <div class="card result-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $mockTest->name ?? 'Mock Test Result' }}</h5>
                        <p class="card-text">Date: {{ $latestResult->created_at->format('M d, Y H:i A') }}</p>
                        <hr>
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <p class="stat-label">Correct</p>
                                <p class="stat-value correct">{{ $latestResult->correct_count }}</p>
                            </div>
                            <div class="col-4">
                                <p class="stat-label">Incorrect</p>
                                <p class="stat-value incorrect">{{ $latestResult->incorrect_count }}</p>
                            </div>
                            <div class="col-4">
                                <p class="stat-label">Unattempted</p>
                                <p class="stat-value unattempted">{{ $latestResult->unattempted_count }}</p>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <p class="percentage-value">{{ $latestResult->percentage }}%</p>
                            @if ($latestResult->percentage >= 70)
                                <p class="result-status pass">Result: Pass</p>
                            @else
                                <p class="result-status fail">Result: Fail</p>
                            @endif
                        </div>
                        <div class="chart-container">
                            <canvas id="percentageChart-{{$latestResult->id}}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('student.mock.tests.attempted') }}" class="btn btn-primary">View All Past Results</a>
        </div>
    @endif
</div>

<!-- JavaScript for Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    @if($latestResult) // Only initialize chart if a result exists
        var correctCount = {{ $latestResult->correct_count }};
        var incorrectCount = {{ $latestResult->incorrect_count }};
        var unattemptedCount = {{ $latestResult->unattempted_count }};

        var canvas = document.getElementById('percentageChart-{{$latestResult->id}}');

        if (canvas) {
            var ctx = canvas.getContext('2d');

            var data = {
                labels: ['Correct', 'Incorrect', 'Unattempted'],
                datasets: [{
                    data: [correctCount, incorrectCount, unattemptedCount],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107'], // Green, Red, Yellow
                    borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                    borderWidth: 1
                }]
            };

            var chart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.raw;
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    @endif
});
</script>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }

    .test-info-header {
        text-align: center;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .test-title {
        font-size: 2.2rem;
        color: #2c3e50;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .batch-name {
        font-size: 1.3rem;
        color: #7f8c8d;
        font-weight: 500;
    }

    .results-container {
        padding: 30px;
    }

    .page-title {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.5rem;
        color: #333;
        font-weight: 700;
    }

    .result-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background-color: #ffffff;
    }

    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 25px;
    }

    .card-title {
        font-size: 1.5rem;
        color: #000;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .card-text {
        font-size: 0.95rem;
        color: #777;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #888;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .stat-value.correct {
        color: #28a745;
    }

    .stat-value.incorrect {
        color: #dc3545;
    }

    .stat-value.unattempted {
        color: #ffc107;
    }

    .percentage-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 10px;
    }

    .result-status {
        font-size: 1.8rem;
        font-weight: 700;
    }

    .result-status.pass {
        color: #28a745;
    }

    .result-status.fail {
        color: #dc3545;
    }

    .chart-container {
        position: relative;
        height: 150px;
        width: 100%;
        margin-top: 20px;
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