@extends('layout.student-layout')

@section('space-work')
<div class="text-right"><img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>

<h2>Mock Test Results</h2>

<div class="container" style="height: 50px; background-color:#2F89FC; display: flex; align-items: center; justify-content: space-between;">
    <div id="questionCount" style="color: white; font-size: 20px;"></div>
    <div id="timer-container">
        <div id="elapsedTime" hidden style="color: white; font-size: 16px;">Elapsed Time: 00:00:00</div>
        <div id="remainingTime" hidden style="color: white; font-size: 20px;">Remaining Time: 04:00:00</div>
    </div>
</div>
<br>

<!-- Display Test History -->
@php
    $dataExists = false;
    if (!$results->isEmpty()) {
        $dataExists = true;
    }
@endphp

<div class="container">
    <div class="test-attempts">
        @if ($dataExists)
            @forelse ($results as $attempt)
            <div class="test-attempt-link" style=" border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; text-decoration: none; background-color:#ffffff; width: 100%;">
                <div class="progress-container" style="flex: 1; order: 1; padding: 10px;">
                <div class="row">
                    <div class="col-6">
                       
                        <canvas hidden id="attemptChart-{{$attempt->id}}" class="ca nvas" width="50" height="50"></canvas>
                        <p class="text-center mt-5" style="color:black;">Marks:{{ $attempt->percentage}}% </p> 
                    </div>
                    <div class="col-6">
                        <canvas  id="percentageChart-{{$attempt->id}}" class="canvas" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
            <div class="statistics" style="flex: 1; order: 2; padding: 10px; text-align: center;">
                <p style="color: #20a520; font-size: 16px; margin-bottom: 5px;"> Correct&nbsp;{{ $attempt->correct_count }}</p>
                <p style="color: #ff2929; font-size: 16px; margin-bottom: 5px;">Incorrect&nbsp;{{ $attempt->incorrect_count }} </p>
                <p style="color:  #EED202; font-size: 16px; margin-bottom: 5px;">Unattempted&nbsp;{{ $attempt->unattempted_count }}</p>
            </div>
            <div class="labels" style="flex: 1; order: 3; padding: 10px; text-align: center;">
                <p style="color: #000000; font-size: 14px; margin-bottom: 5px;">Attempt ID: {{ $attempt->id }}</p>
                <p style="color: #000000; font-size: 14px; margin-bottom: 5px;">Date: {{ $attempt->created_at }}</p>
                @if ($attempt->percentage >= 70)
                <p class="text-center" style="font-size: 26px; color: #20a520; font-weight: bold;">Result: Pass</p>
                @else
                <p class="text-center" style="font-size: 26px; color: #ff2929; font-weight: bold;">Result: Fail</p>
                @endif
            </div>
            </div>
            @empty
            <p style="text-align: center;">You have not attempted any mock tests yet.</p>
            @endforelse
        @else
            <p style="text-align: center;">You have not attempted any mock tests yet.</p>
        @endif
    </div>
</div>


<!-- JavaScript for Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@foreach($results as $attempt)
    var percentageData{{$attempt->id}} = {{ $attempt->percentage }};
    var correctCount{{$attempt->id}} = {{ $attempt->correct_count }};
    var incorrectCount{{$attempt->id}} = {{ $attempt->incorrect_count }};
    var unattemptedCount{{$attempt->id}} = {{ $attempt->unattempted_count }};

    var canvas{{$attempt->id}} = document.getElementById('percentageChart-{{$attempt->id}}');
    var canvas2{{$attempt->id}} = document.getElementById('attemptChart-{{$attempt->id}}');

    if (canvas{{$attempt->id}}) {
        var ctx{{$attempt->id}} = canvas{{$attempt->id}}.getContext('2d');

        var colors{{$attempt->id}} = ['#20a520', '#ff2929', ' #EED202'];
        var data{{$attempt->id}} = {
            datasets: [{
                data: [correctCount{{$attempt->id}}, incorrectCount{{$attempt->id}}, unattemptedCount{{$attempt->id}}],
                backgroundColor: colors{{$attempt->id}}
            }]
        };

        var chart{{$attempt->id}} = new Chart(ctx{{$attempt->id}}, {
            type: 'pie',
            data: data{{$attempt->id}},
            options: {
                legend: {
                    display: false
                }
            }
        });
    }
@endforeach
</script>

<style>
.container {
    max-width: 100%;
}

.test-attempt-link {
    border: 1px solid #2F89FC;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    text-decoration: none;
    background-color: #f0f8ff;
    width: 100%;
    margin: 0 auto;
}

.attempt-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.progress-container {
    flex: 1;
    order: 1;
    padding: 10px;
    text-align: block;
}

.statistics {
    flex: 1;
    order: 2;
    padding: 10px;
    text-align: center;
}

.labels {
    flex: 1;
    order: 3;
    padding: 10px;
    text-align: center;
}

p {
    font-size: 16px;
    margin: 0;
}

.canvas {
    height: 50px;
    width: 50px;
}

@media (max-width: 768px) {
    .attempt-info {
        flex-direction: column;
        text-align: center;
    }

    .labels, .statistics, .progress-container {
        flex: 1;
        order: unset;
        padding: 10px;
    }
}
</style>
@endsection