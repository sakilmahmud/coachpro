@extends('layout/student-layout')

@section('space-work')
<div class="text-right">
    <img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%">
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Test Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your additional CSS styles or local CSS file -->
    <style>
        /* Add any custom styles here */
        body {
            margin: 20px;
        }
        .test-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .test-details h3 {
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="text-right">
            <!--<img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%">-->
        </div>

        <div class="test-details">
            @if($titles->count() > 0)
                <h3>Subject: PMP Certification</h3>
                <h3>Title: Test -  {{ preg_replace("/[^0-9]/", "", $titles[0]->title) }}</h3>

                <h3>Level: Intermediate</h3>
                <h3>Time: 4 Hours</h3>
                <h3>Total Questions: 170</h3>
                <!--<h3>Description: {{ $titles[0]->explanation }}</h3>-->

                <a href="{{ route('mock.test', ['testsubject' => $titles[0]->subject, 'title' => $titles[0]->title]) }}">
                    <button class="btn btn-success" id="nextButton">Start Test</button>
                </a>
            @else
                <p>Questions not available.</p>
            @endif
        </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Additional scripts if needed -->
</body>
</html>


   <script>
                            // JavaScript in questions.blade.php

                    // Elements
                    const elapsedTimeDisplay = document.getElementById("elapsedTime");
                    const remainingTimeDisplay = document.getElementById("remainingTime");
                    const startButton = document.getElementById("nextButton");
                    const finishButton = document.getElementById("finished");

                    // Variables to store timer state
                    let startTime = localStorage.getItem("startTime") || null;
                    let endTime = localStorage.getItem("endTime") || null;
                    let duration = 4 * 60 * 60; // 4 hours in seconds
                    let timerInterval;

                    // Function to update the timer display
                    function updateTimerDisplay() {
                        const currentTime = new Date().getTime() / 1000;
                        const elapsed = currentTime - startTime;
                        const remaining = endTime - currentTime;

                        const elapsedFormatted = formatTime(elapsed);
                        const remainingFormatted = formatTime(remaining);

                        elapsedTimeDisplay.textContent = `Elapsed Time: ${elapsedFormatted}`;
                        remainingTimeDisplay.textContent = `Remaining Time: ${remainingFormatted}`;

                        if (remaining <= 0) {
                            clearInterval(timerInterval);
                            alert("Time's up!");
                        }
                    }

                    // Function to format time in HH:MM:SS format
                    function formatTime(seconds) {
                        const hours = Math.floor(seconds / 3600);
                        const minutes = Math.floor((seconds % 3600) / 60);
                        const secs = Math.floor(seconds % 60);
                        return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
                    }

                    // Event listener for the "Start Test" button
                    startButton.addEventListener("click", () => {
                        if (!startTime) {
                            startTime = new Date().getTime() / 1000;
                            endTime = startTime + duration;
                            localStorage.setItem("startTime", startTime);
                            localStorage.setItem("endTime", endTime);
                            timerInterval = setInterval(updateTimerDisplay, 1000);
                        }
                    });

                    // Event listener for the "Finish Test" button
                    finishButton.addEventListener("click", () => {
                        clearInterval(timerInterval);
                        alert("Do you want to test finished!");
                        localStorage.removeItem("startTime");
                        localStorage.removeItem("endTime");
                    });

                    // Check if the timer was previously started and resume if necessary
                    if (startTime && endTime) {
                        const currentTime = new Date().getTime() / 1000;
                        if (endTime > currentTime) {
                            timerInterval = setInterval(updateTimerDisplay, 1000);
                        } else {
                            localStorage.removeItem("startTime");
                            localStorage.removeItem("endTime");
                        }
                    }

                    </script>
                    
@endsection
