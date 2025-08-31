@extends('layout.student-layout')

@section('space-work')
<div class="test-info-header">
    <h3 class="test-title">{{ $mockTest->name }}</h3>
    @if($mockTest->course)
        <p class="batch-name">Batch: {{ $mockTest->course->name }}</p>
    @endif
</div>

<div class="container-fluid quiz-container">
    <div class="quiz-header">
        <div id="questionCount" class="question-count"></div>
        <div class="d-flex align-items-center"> <!-- New div to group timer and finish button -->
            <div id="timer-container" class="timer-container me-3"> <!-- Added me-3 for spacing -->
                <div id="remainingTime" class="remaining-time"></div>
            </div>
            <button id="finished" class="btn btn-danger quiz-btn">Finish Test</button>
        </div>
    </div>

    <div id="questionCard" class="question-card">
        <h5 id="questionText" class="question-text"></h5>
        <div id="questionImageContainer" class="question-image-container"></div>
    </div>

    <div id="answerOptions" class="answer-options">
        <!-- Answer options will be loaded here by JavaScript -->
    </div>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="quiz-navigation">
                <button id="prevButton" class="btn btn-secondary quiz-btn">Back</button>
                <button id="nextButton" class="btn btn-primary quiz-btn">Next</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var currentQuestionIndex = 0;
    var questions = @json($questions);
    var answers = @json($answers);
    var correctAnswers = @json($correctAnswers);
    var selectedAnswers = Array(questions.length).fill(null);
    var questionIds = [];
    var selectedOptions = [];
    var correctAnswerIds = [];

    function displayQuestionAndAnswer(index) {
        var question = questions[index];
        var questionCard = $('#questionCard');

        // Animate out current question
        questionCard.removeClass('fade-in').addClass('fade-out');

        setTimeout(() => {
            $('#questionText').html('Q No.' + (index + 1) + ' : ' + question.question);
            $('#questionImageContainer').empty();
            if (question.image) {
                $('#questionImageContainer').html('<img src="{{ asset('storage/') }}' + question.image + '" alt="Question Image" class="img-fluid question-image">');
            }

            $('#questionCount').text(`Question ${index + 1} of ${questions.length}`);

            $('#answerOptions').empty();
            answers[index].forEach(function(ans) {
                var optionDiv = $('<div class="answer-option"></div>');
                var input = $('<input type="radio" name="selected_answer" class="answer-radio"></div>');
                input.val(ans.id);
                input.attr('id', 'option-' + ans.id);

                var label = $('<label class="answer-label"></label>');
                label.attr('for', 'option-' + ans.id);
                label.html(ans.answer);

                if (selectedAnswers[index] !== null && selectedAnswers[index] == ans.id) {
                    input.prop('checked', true);
                    optionDiv.addClass('selected');
                }

                optionDiv.append(input).append(label);
                $('#answerOptions').append(optionDiv);

                optionDiv.on('click', function() {
                    $('.answer-option').removeClass('selected');
                    $(this).addClass('selected');
                    $(this).find('.answer-radio').prop('checked', true);
                    selectedAnswers[index] = ans.id;

                    questionIds[index] = question.id;
                    selectedOptions[index] = ans.id;
                    correctAnswerIds[index] = correctAnswers[index];
                });
            });

            // Animate in new question
            questionCard.removeClass('fade-out').addClass('fade-in');
        }, 300); // Match this duration with CSS transition duration
    }

    displayQuestionAndAnswer(currentQuestionIndex);

    $('#nextButton').on('click', function() {
        const selectedAnswer = $('input[name="selected_answer"]:checked');
        if (selectedAnswer.length > 0 || selectedAnswers[currentQuestionIndex] !== null) {
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                displayQuestionAndAnswer(currentQuestionIndex);
            } else {
                // Show custom confirmation modal
                var finishTestModal = new bootstrap.Modal(document.getElementById('finishTestModal'));
                finishTestModal.show();
            }
        } else {
            alert('Please select an answer before proceeding.');
        }
    });

    $('#prevButton').on('click', function() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            displayQuestionAndAnswer(currentQuestionIndex);
        }
    });

    function finishTest() {
        clearInterval(timerInterval);
        localStorage.removeItem("startTime");
        localStorage.removeItem("endTime");

        const totalQuestions = questions.length;
        let correctCount = 0;
        let incorrectCount = 0;
        let unattemptedCount = 0;

        for (let i = 0; i < totalQuestions; i++) {
            if (selectedAnswers[i] === null) {
                unattemptedCount++;
            } else if (correctAnswers[i].includes(parseInt(selectedAnswers[i]))) {
                correctCount++;
            } else {
                incorrectCount++;
            }
        }

        const percentage = ((correctCount / totalQuestions) * 100).toFixed(2);

        $.ajax({
            url: '{{ route('student.mock.test.submit.result') }}',
            type: 'POST',
            data: JSON.stringify({
                mock_test_id: {{ $mockTest->id }},
                correct_count: correctCount,
                incorrect_count: incorrectCount,
                unattempted_count: unattemptedCount,
                percentage: percentage
            }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is available
            },
            success: function(data) {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error saving results: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while submitting your test.');
            }
        });
    }

    const remainingTimeDisplay = $('#remainingTime');
    const startButton = $('#nextButton');
    const finishButton = $('#finished');

    let startTime = localStorage.getItem("startTime") || null;
    let endTime = localStorage.getItem("endTime") || null;
    let duration = 4 * 60 * 60; // 4 hours in seconds
    let timerInterval;

    function startTimer() {
        if (!startTime) {
            startTime = new Date().getTime() / 1000;
            endTime = startTime + duration;
            localStorage.setItem("startTime", startTime);
            localStorage.setItem("endTime", endTime);
        }
        timerInterval = setInterval(updateTimerDisplay, 1000);
    }

    function updateTimerDisplay() {
        const currentTime = new Date().getTime() / 1000;
        const elapsed = currentTime - startTime;
        const remaining = endTime - currentTime;

        const elapsedFormatted = formatTime(elapsed);
        const remainingFormatted = formatTime(remaining);

        remainingTimeDisplay.text(`Time Left: ${remainingFormatted}`);

        if (remaining <= 0) {
            clearInterval(timerInterval);
            alert("Time's up!"); // Keep this alert for time's up scenario
            finishTest();
        }
    }

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = Math.floor((seconds % 60));
        return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
    }

    startButton.on('click', () => {
        startTimer();
    });

    finishButton.on('click', () => {
        var finishTestModal = new bootstrap.Modal(document.getElementById('finishTestModal'));
        finishTestModal.show();
    });

    // Event listener for the "Yes, Finish" button inside the modal
    $('#confirmFinishTest').on('click', function() {
        var finishTestModal = bootstrap.Modal.getInstance(document.getElementById('finishTestModal'));
        if (finishTestModal) {
            finishTestModal.hide();
        }
        finishTest();
    });

    /* $(window).on('beforeunload', function (e) {
        const confirmationMessage = 'Your data will be reset. Are you sure you want to leave?';
        e.returnValue = confirmationMessage;
        return confirmationMessage;
    }); */

    $(window).on('unload', function () {
        localStorage.removeItem("startTime");
        localStorage.removeItem("endTime");
    });

    if (startTime && endTime) {
        const currentTime = new Date().getTime() / 1000;
        if (endTime > currentTime) {
            startTimer();
        } else {
            localStorage.removeItem("startTime");
            localStorage.removeItem("endTime");
        }
    } else {
        startTimer();
    }
});
</script>

<!-- Finish Test Confirmation Modal -->
<div class="modal fade" id="finishTestModal" tabindex="-1" aria-labelledby="finishTestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="finishTestModalLabel">Confirm Finish Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to finish the test?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmFinishTest">Yes, Finish</button>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }

    .quiz-container {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-top: 30px;
        width: 75%; /* Changed from max-width */
        margin-left: auto;
        margin-right: auto;
    }

    .quiz-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .question-count {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }

    .timer-container {
        font-size: 1.2rem;
        font-weight: 600;
        color: #e44d26; /* A vibrant orange-red for urgency */
    }

    .question-card {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease-in-out;
    }

    .question-text {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .question-image-container {
        text-align: center;
        margin-top: 15px;
    }

    .question-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .answer-options {
        margin-bottom: 30px;
    }

    .answer-option {
        background-color: #fff;
        border: 1px solid #dcdcdc;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
    }

    .answer-option:hover {
        border-color: #000;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);;
        transform: translateY(-2px);
    }

    .answer-option.selected {
        background-color: #e7f0ff;
        border-color: #000;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
    }

    .answer-radio {
        margin-right: 15px;
        transform: scale(1.2);
    }

    .answer-label {
        font-size: 1.1rem;
        color: #555;
        margin-bottom: 0;
        cursor: pointer;
        flex-grow: 1;
    }

    .quiz-navigation {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }

    .quiz-btn {
        padding: 12px 25px;
        font-size: 1.1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    .quiz-navigation .quiz-btn {
        flex-grow: 1;
    }

    .quiz-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    .fade-out {
        animation: fadeOut 0.3s ease-in forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    .test-info-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15px; /* Reduced margin-top */
        margin-bottom: 20px; /* Reduced margin-bottom */
    }

    .test-title {
        font-size: 1.8rem; /* Reduced font size */
        color: #2c3e50;
        margin-bottom: 0; /* Remove bottom margin */
        margin-right: 10px; /* Reduced space between title and batch name */
        font-weight: 700;
    }

    .batch-name {
        font-size: 1rem; /* Reduced font size */
        color: #7f8c8d;
        font-weight: 500;
        margin-bottom: 0; /* Remove bottom margin */
    }
</style>

@endsection