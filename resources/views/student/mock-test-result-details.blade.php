@extends('layout.student-layout')

@section('space-work')
<div class="container-fluid">
    
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('student.mock.test.result', $mockTest->id) }}" class="btn btn-secondary mb-3">Back to Result</a>
                    </div>    
                    <h2 class="text-center mb-4">Mock Test Details: {{ $mockTest->name }}</h2>
                </div>
            </div>
        </div>
    @if(!$latestResult)
        <div class="alert alert-info text-center" role="alert">
            You have not attempted this mock test yet.
        </div>
    @else
        <div class="row">
            <div class="col-md-10 mx-auto">
                @foreach($mockTest->questions as $question)
                    <div class="card mb-4 question-detail-card">
                        <div class="card-body">
                            <h5 class="card-title">Q{{ $loop->iteration }}: {!! $question->question !!}</h5>
                            @if($question->image)
                                <div class="question-image-container mb-3">
                                    <img src="{{ asset('storage/' . $question->image) }}" alt="Question Image" class="img-fluid">
                                </div>
                            @endif

                            <div class="answers-section">
                                @php
                                    $userAnswer = $userAnswers->get($question->id);
                                    $userSelectedAnswerIds = $userAnswer ? json_decode($userAnswer->selected_answer_ids, true) : [];
                                    $isCorrect = $userAnswer ? $userAnswer->is_correct : false;
                                @endphp

                                <p class="result-status {{ $isCorrect ? 'correct' : 'incorrect' }}">
                                    Your Answer: {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                </p>

                                <ul class="list-group mb-3">
                                    @foreach($question->answers as $answer)
                                        @php
                                            $isUserSelected = in_array($answer->id, $userSelectedAnswerIds);
                                            $isCorrectAnswer = $answer->is_correct;
                                            $answerClass = '';

                                            if ($isUserSelected && $isCorrectAnswer) {
                                                $answerClass = 'list-group-item-success'; // User selected correct
                                            } elseif ($isUserSelected && !$isCorrectAnswer) {
                                                $answerClass = 'list-group-item-danger'; // User selected incorrect
                                            } elseif (!$isUserSelected && $isCorrectAnswer) {
                                                $answerClass = 'list-group-item-info'; // Correct answer not selected by user
                                            }
                                        @endphp
                                        <li class="list-group-item {{ $answerClass }}">
                                            {!! $answer->answer !!}
                                            @if($isCorrectAnswer)
                                                <i class="fa fa-check text-success float-end"></i>
                                            @endif
                                            @if($isUserSelected && !$isCorrectAnswer)
                                                <i class="fa fa-times text-danger float-end"></i>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($question->explanation)
                                <div class="explanation-section mt-3">
                                    <h6>Explanation:</h6>
                                    <p>{!! $question->explanation !!}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }
    .container-fluid {
        padding-top: 30px;
        padding-bottom: 30px;
    }
    .question-detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }
    .card-title {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .question-image-container {
        text-align: center;
    }
    .question-image-container img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }
    .answers-section .list-group-item {
        border-radius: 8px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1rem;
        color: #555;
    }
    .list-group-item-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .list-group-item-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .list-group-item-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }
    .result-status {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 15px;
    }
    .result-status.correct {
        color: #28a745;
    }
    .result-status.incorrect {
        color: #dc3545;
    }
    .explanation-section {
        background-color: #f2f2f2;
        border-left: 5px solid #007bff;
        padding: 15px;
        border-radius: 8px;
    }
    .explanation-section h6 {
        color: #007bff;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .explanation-section p {
        font-size: 0.95rem;
        color: #444;
    }
</style>
@endsection