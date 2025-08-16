@extends('layout/student-layout')

@section('space-work')
<div class="text-right"><img  src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>
@foreach($exams as $subject)
<h2>Flash Question of {{$subject->subject}}</h2>
<div class="container" style="height: 70px; width:auto; background-color:#2F89FC; display: flex; align-items: center; justify-content: space-between;">
    <div id="questionCount" style="color: white; font-size: 20px;"></div>
    <div id="timer-container">
        <div id="elapsedTime" hidden style="color: white; font-size: 16px;">Elapsed Time: 00:00:00</div>
        <div id="remainingTime" hidden style="color: white; font-size: 20px;">Remaining Time: 04:00:00</div>
    </div>
</div>

     @endforeach
    <table class="table">

        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Result</th>
                
            </tr>
        </thead>
        @php
        $counter = $exams->firstItem(); // Set the counter to the first item on the current page
        @endphp
        <tbody>
        @foreach($exams as $subject)
                    <tr>
                        <td>{{$counter++}}</td>
                        <td>{{$subject->question}}</td>
                        <td>
                            <a href="#" class="view-answer-link" data-toggle="modal" data-target="#answerModal{{$subject->id}}">View Answer</a>
                        </td>

                        <!-- Modal -->
                        <div class="modal fade" id="answerModal{{$subject->id}}" tabindex="-1" role="dialog" aria-labelledby="answerModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="answerModalLabel">{{$counter-1}}:   {{$subject->question}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-success">
                                        {{$subject->answer}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>   
                           
        @endforeach
        
                           
        </tbody>

    </table>

    <div class="pagination" style="display: flex; justify-content: space-between;">
        @if ($exams->previousPageUrl())
            <a href="{{ $exams->previousPageUrl() }}" class="btn btn-primary previous">Previous</a>
        @endif

        @if ($exams->nextPageUrl())
            <a href="{{ $exams->nextPageUrl() }}" class="btn btn-primary next">Next</a>
        @endif
    </div>

<script>
    var totalQuestions = {{ $exams->total() }};
    var currentQuestion = {{ $exams->firstItem() }};

    function updateQuestionCount() {
        document.getElementById('questionCount').textContent = currentQuestion + '/' + totalQuestions;
    }

    // Event listener for "Previous" button
    document.querySelector('.btn.btn-primary.previous').addEventListener('click', function() {
        if (currentQuestion > 1) {
            currentQuestion--;
            updateQuestionCount();
        }
    });

    // Event listener for "Next" button
    document.querySelector('.btn.btn-primary.next').addEventListener('click', function() {
        if (currentQuestion < totalQuestions) {
            currentQuestion++;
            updateQuestionCount();
        }
    });

    // Initial update of question count
    updateQuestionCount();
</script>
@endsection
