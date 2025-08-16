

@extends('layout/student-layout')

@section('space-work')
<div class="text-right"><img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>

<h2>Review Your Mock Test</h2>

<!-- Rest of your HTML structure... -->

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Question</th>
            <th>Your Result</th>
        </tr>
    </thead>
    <tbody>
    @php
    $counter = 1; // Initialize the counter here
    @endphp
    @foreach ($allQuestions as $question)
    @php
    $attemptedQuestion = $result->where('question_id', $question['id'])->first();
   
   
    @endphp
    <tr>
        <td>{{ $counter++ }}</td>
        <td>{{ $question['question'] }}</td>
        <td>
            @if ($attemptedQuestion)
                <!-- Display the button for attempted questions -->
                <button class="result-button {{ $attemptedQuestion->selected_option === $attemptedQuestion->is_correct ? 'btn btn-success' : 'btn btn-danger' }}"
                    data-selected="{{ $attemptedQuestion->selected_option }}"
                    data-correct="{{ $attemptedQuestion->is_correct }}"
                    data-question="{{ $attemptedQuestion->question_name }}"
                    data-explanation="{{ isset($explanation[$attemptedQuestion->question_name]) ? json_encode($explanation[$attemptedQuestion->question_name]) : '{}' }}"
                    data-options="{{ isset($options[$attemptedQuestion->question->id]) ? json_encode($options[$attemptedQuestion->question->id]) : '{}' }}">
                    Check Result
                </button>
            @else
                <!-- Display unattempted question details here -->
                <!-- This button is for unattempted questions -->
                <button class="not-attempted-button btn btn-warning"
            data-question-id="{{ $question['id'] }}"
            data-explanation="{{ isset($explanation[$question['question']]) ? json_encode($explanation[$question['question']]) : '{}' }}"
            data-options="{{ isset($options[$question['id']]) ? json_encode($options[$question['id']]) : '{}' }}"
            data-correct="{{ $unattemptedcorrectanswer}}">
            <span class="text-muted">Not Attempted</span>
        </button>



            @endif
        </td>
    </tr>
@endforeach


<!-- Display unattempted questions at the end -->



</tbody>

</table>



<!-- Bootstrap Modal -->
<!-- Bootstrap Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Result Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Question:</strong> <span id="questionText"></span></p>
                <p><strong>Options:</strong></p>
                <ul id="optionsList"></ul>
                <p><strong>Explanation:</strong> <span id="explanationText"></span></p>
                <p><strong>Selected Answer:</strong> <span id="selectedOption"></span></p>
                <p><strong>Correct Answer:</strong> <span id="correctAnswer"></span></p>
            </div>
        </div>
    </div>
</div>

<script>
    // Add click event listener to all result buttons
const resultButtons = document.querySelectorAll(".result-button");
resultButtons.forEach(button => {
    button.addEventListener("click", () => {
        const selectedOption = button.getAttribute("data-selected");
        const correctAnswer = button.getAttribute("data-correct");
        const questionText = button.getAttribute("data-question");
        const explanation = button.getAttribute("data-explanation"); // Get the explanation attribute
        const options = JSON.parse(button.getAttribute("data-options"));
           
        // Update the modal content
        const questionTextElement = document.getElementById("questionText");
        const optionsListElement = document.getElementById("optionsList");
        const selectedOptionText = document.getElementById("selectedOption");
        const correctAnswerText = document.getElementById("correctAnswer");
        const explanationTextElement = document.getElementById("explanationText");

        questionTextElement.textContent = questionText;
        selectedOptionText.textContent = selectedOption;
        correctAnswerText.textContent = correctAnswer;

        // Clear previous options and populate the options list
        optionsListElement.innerHTML = "";
        options.forEach((option, index) => {
            const listItem = document.createElement("li");
            listItem.textContent = `Option ${index + 1}: ${option.answer}`;
            optionsListElement.appendChild(listItem);
        });

        // Display the explanation
        if (explanation !== '{}') {
            explanationTextElement.textContent = explanation;
        } else {
            explanationTextElement.textContent = "Explanation not available for this question.";
        }

        // Show the Bootstrap modal
        $('#resultModal').modal('show');
    });
});

// Add click event listener to "Not Attempted" buttons
const notAttemptedButtons = document.querySelectorAll(".not-attempted-button");
notAttemptedButtons.forEach(button => {
    button.addEventListener("click", () => {
        const questionText = button.parentNode.previousElementSibling.textContent.trim(); // Get the question text from the table cell
        const explanation = button.getAttribute("data-explanation"); // Get the explanation attribute
        const options = JSON.parse(button.getAttribute("data-options"));
        const correctAnswerForUnattemptedQuestion = button.getAttribute("data-correct"); // Get the correct answer from the data-correct attribute

        // Update the modal content
        const questionTextElement = document.getElementById("questionText");
        const optionsListElement = document.getElementById("optionsList");
        const selectedOptionText = document.getElementById("selectedOption");
        const correctAnswerText = document.getElementById("correctAnswer");
        const explanationTextElement = document.getElementById("explanationText");

        questionTextElement.textContent = "Question: " + questionText; // Display the question text

        // Display the explanation
        if (explanation !== '{}') {
            explanationTextElement.textContent = "Explanation: " + explanation;
        } else {
            explanationTextElement.textContent = "Explanation not available for this question.";
        }

        // Display the correct answer for unattempted questions
        correctAnswerText.textContent = "Correct Answer: " + correctAnswerForUnattemptedQuestion;

        // Clear previous options and populate the options list
        optionsListElement.innerHTML = "";
        options.forEach((option, index) => {
            const listItem = document.createElement("li");
            listItem.textContent = `Option ${index + 1}: ${option.answer}`;
            optionsListElement.appendChild(listItem);
        });

        // Show the Bootstrap modal
        $('#resultModal').modal('show');
    });
});


</script>


@endsection
