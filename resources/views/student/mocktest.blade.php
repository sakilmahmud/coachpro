@extends('layout/student-layout')

@section('space-work')

<div class="text-right"><img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>

<div class="container" style="height: 70px; width:auto; background-color:#2F89FC; display: flex; align-items: center; justify-content: space-between;">
    <div id="questionCount" style="color: white; font-size: 20px;">1/50</div>
    <div id="timer-container">
        <div id="elapsedTime" hidden style="color: white; font-size: 16px;">Elapsed Time: 00:00:00</div>
        <div id="remainingTime" style="color: white; font-size: 20px;">Remaining Time: 04:00:00</div>
    </div>
</div>

<br>
@php
$counter = 1;
@endphp
<div id="questionContainer" style="padding: 10px 10px 10px 10px; border:2px solid blue; width:100%;">
    Q No.{{ $counter++ }} : {{ $questions[0]->question }}
</div>
<br>
<div id="answerContainer" style="width: 100%;">
    <form id="answerForm">
        @foreach($answers[0] as $answer)
        <div style="padding: 10px;">
            <label>
                <input type="radio" name="selected_answer" value="{{ $answer->id }}">
               {{ $answer->answer }}
            </label>
        </div>
        @endforeach
    </form>
   <div id="buttonContainer" class="d-flex justify-content-between">
    <div>
        <button id="prevButton" class="btn btn-success">Back</button>
        <button id="nextButton" class="btn btn-success ml-2">Next</button>
    </div>
    <div>
        <button class="btn btn-danger" id="finished">Finish Test</button>
    </div>
</div>

</div>

<!-- <div id="resultContainer" style="margin-left: 20px; font-size: 20px;">Correct: 0 Incorrect: 0</div> -->

</div>

<!-- Question and answer fetch with matching ID code -->

<script>
    var currentQuestionIndex = 0;
    var questions = @json($questions);
    var answers = @json($answers);
    var correctAnswers = @json($correctAnswers);
    var answerForm = document.getElementById('answerForm');
    var answerContainer = document.getElementById('answerContainer');
    var questionCountDisplay = document.getElementById('questionCount');
    var selectedAnswers = Array(questions.length).fill(null);
    var correctCount = 0;
    var incorrectCount = 0;
    var displayCounter = 1;
    var questionIds = [];
    var selectedOptions = [];
    var correctAnswerIds = [];
    function displayQuestionAndAnswer(index) {
    var question = questions[index];
    var answer = answers[index];
    var questionContainer = document.getElementById('questionContainer');

    questionContainer.innerHTML = 'Q No.' + displayCounter + ' : ' + question.question;
    displayCounter++;

    questionCountDisplay.textContent = (index + 1) + '/' + questions.length; // Update question count

    answerForm.innerHTML = '';

    answer.forEach(function(ans) {
        var div = document.createElement('div');
        div.style.padding = '15px 10px 10px 10px';
        div.style.border = '1px solid blue';
        div.style.margin = '5px';

        var label = document.createElement('label');
        var input = document.createElement('input');
        input.type = 'radio';
        input.name = 'selected_answer';
        input.value = ans.id;
        input.style.marginRight = '5px';

        if (selectedAnswers[index] !== null && selectedAnswers[index] == ans.id) {
            input.checked = true;
        }

        label.appendChild(input);
        label.appendChild(document.createTextNode(ans.answer));

        div.appendChild(label);
        answerForm.appendChild(div);

        input.addEventListener('change', function() {
            selectedAnswers[index] = ans.id;

                // Store the question ID, selected option ID, and correct answer ID
                questionIds[index] = question.id;
                selectedOptions[index] = ans.id;
                correctAnswerIds[index] = correctAnswers[index].id;
            
            // Add this log statement to check the correct answer ID
            console.log('Correct Answer ID:', correctAnswers[index].id);
        });
    });
}


    displayQuestionAndAnswer(currentQuestionIndex);

    document.getElementById('nextButton').addEventListener('click', function() {
        const selectedAnswer = document.querySelector('input[name="selected_answer"]:checked');
        if (selectedAnswer !== null || selectedAnswers[currentQuestionIndex] !== null) {
            if (selectedAnswer !== null) {
                const selectedAnswerId = selectedAnswer.value;
                const currentCorrectAnswers = correctAnswers[currentQuestionIndex];

                if (currentCorrectAnswers.includes(parseInt(selectedAnswerId))) {
                    correctCount++;
                } else {
                    incorrectCount++;
                }
                selectedAnswers[currentQuestionIndex] = selectedAnswerId;
            }

            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                displayQuestionAndAnswer(currentQuestionIndex);
            } else {
                // Confirm before finishing the test
                if (window.confirm('Are you sure you want to finish the test?')) {
                    finishTest();
                }
            }
        } else {
            alert('Please select an answer before proceeding.');
        }
    });

    document.getElementById('prevButton').addEventListener('click', function() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;

            // Update counts when going back
            const selectedAnswerId = selectedAnswers[currentQuestionIndex];
            const currentCorrectAnswers = correctAnswers[currentQuestionIndex];

            if (selectedAnswerId !== null) {
                if (currentCorrectAnswers.includes(parseInt(selectedAnswerId))) {
                    correctCount--;
                } else {
                    incorrectCount--;
                }
            } else {
                // If there was no answer previously selected, decrement incorrectCount
                incorrectCount--;
            }

            displayQuestionAndAnswer(currentQuestionIndex);
        }
    });

    // Function to finish the test and show results for all questions
// Function to finish the test and show results for all questions
function finishTest() {
    clearInterval(timerInterval);
    localStorage.removeItem("startTime");
    localStorage.removeItem("endTime");

    // Create a result message
    let resultMessage = "Results:\n\n";
    const totalQuestions = questions.length;
    let correctCount = 0;
    let incorrectCount = 0;
    let unattemptedCount = 0;

    for (let i = 0; i < totalQuestions; i++) {
        resultMessage += `Question ${i + 1}: `;
        if (selectedAnswers[i] === null) {
            resultMessage += "Not attempted\n";
            unattemptedCount++;
        } else if (correctAnswers[i].includes(parseInt(selectedAnswers[i]))) {
            resultMessage += "Correct\n";
            correctCount++;
        } else {
            resultMessage += "Incorrect\n";
            incorrectCount++;
        }
    }

    // Display the result message
    alert(resultMessage);

    // Redirect to the result page with the overall percentage and counts
    const percentage = ((correctCount / totalQuestions) * 100).toFixed(2);
    const queryParams = `?correctCount=${correctCount}&incorrectCount=${incorrectCount}&unattemptedCount=${unattemptedCount}&percentage=${percentage}&questionIds=${JSON.stringify(questionIds)}&selectedOptions=${JSON.stringify(selectedOptions)}&correctAnswerIds=${JSON.stringify(correctAnswerIds)}`;


    window.location.href = '/result' + queryParams;
}

</script>

<script>
const elapsedTimeDisplay = document.getElementById("elapsedTime");
const remainingTimeDisplay = document.getElementById("remainingTime");
const startButton = document.getElementById("nextButton");
const finishButton = document.getElementById("finished");

let startTime = localStorage.getItem("startTime") || null;
let endTime = localStorage.getItem("endTime") || null;
let duration = 4 * 60 * 60; // 4 hours in seconds
let timerInterval;

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
        finishTest();
    }
}

function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = Math.floor(seconds % 60);
    return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
}

startButton.addEventListener("click", () => {
    if (!startTime) {
        startTime = new Date().getTime() / 1000;
        endTime = startTime + duration;
        localStorage.setItem("startTime", startTime);
        localStorage.setItem("endTime", endTime);
        timerInterval = setInterval(updateTimerDisplay, 1000);
    }
});

finishButton.addEventListener("click", () => {
    clearInterval(timerInterval);
    alert("Do you want to finish the test?");
    localStorage.removeItem("startTime");
    localStorage.removeItem("endTime");
    finishTest();
});

window.addEventListener('beforeunload', function (e) {
    const confirmationMessage = 'Your data will be reset. Are you sure you want to leave?';
    e.returnValue = confirmationMessage;
    return confirmationMessage;
});

window.addEventListener('unload', function () {
    localStorage.removeItem("startTime");
    localStorage.removeItem("endTime");
});

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
