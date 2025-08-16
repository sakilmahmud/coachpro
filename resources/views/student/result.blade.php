@extends('layout/student-layout')

@section('space-work')
<div class="text-right"><img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>
<div class="container" style="background-color:#ec7725; padding: 20px; border-radius: 10px;">

    <h1 class="text-center">Test Results </h1>
  
    <p class="text-center text-white" style="font-size: large;">Correct: {{ $correctCount }}</p>
    <p class="text-center text-white" style="font-size: large;">Incorrect: {{ $incorrectCount }}</p>
    <p class="text-center text-white" style="font-size: large;">Unattempt: {{ $unattemptedCount }}</p>
    <p class="text-center text-white" style="font-size: large;">
        Percentage: {{ round($percentage) }}%
        <div style="background-color: #2F89FC; height: 20px; border-radius: 100px; width: {{ round($percentage) }}%;"></div>
    </p>

    @if ($percentage >= 70)
    <p class="text-center" style="font-size: 30px; color: green;">Result: Pass</p>

    @else
    <p class="text-center" style="font-size: 30px;  color:red;">Result: Fail</p>
    @endif

   

</div>


@endsection
