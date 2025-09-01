@extends('layout.student-layout')

@section('space-work')
<div class="container-fluid flashcard-container border border-dark rounded border-2">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 mb-4 d-flex justify-content-between border-bottom border-2">
            <h2 class="page-title m-0">Flashcards</h2>
            <div class="flashcard-counter">
                Card <span id="currentFlashcard">{{ $exams->firstItem() }}</span> of {{ $exams->total() }}
            </div>
        </div>
    </div>

    @if($exams->isEmpty())
    <div class="alert alert-info text-center" role="alert">
        No flashcards available at the moment.
    </div>
    @else
    <div class="row justify-content-center">
        @foreach($exams as $flashcard)
        <div class="col-md-12 col-lg-12 mb-4">
            <div class="flashcard-card" data-id="{{ $flashcard->id }}">
                <div class="flashcard-inner">
                    <div class="flashcard-front">
                        <h5 class="flashcard-question">Q: {!! $flashcard->question !!}</h5>
                    </div>
                    <div class="flashcard-back">
                        <h5 class="flashcard-answer">A: {!! $flashcard->answer !!}</h5>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 mb-4">
            <div class="">
                <div class="d-flex justify-content-between">
                    @if ($exams->previousPageUrl())
                    <a href="{{ $exams->previousPageUrl() }}" class="btn btn-primary pagination-btn prev-btn">Previous</a>
                    @else
                    <button class="btn btn-secondary pagination-btn prev-btn" disabled>Previous</button>
                    @endif

                    @if ($exams->nextPageUrl())
                    <a href="{{ $exams->nextPageUrl() }}" class="btn btn-primary pagination-btn next-btn">Next</a>
                    @else
                    <button class="btn btn-secondary pagination-btn next-btn" disabled>Next</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        $('.flashcard-card').on('click', function() {
            $(this).toggleClass('flipped');
        });

        // Update current flashcard number on page load
        var currentPage = {{ $exams->currentPage() }};
        var perPage = {{ $exams->perPage() }};
        var firstItem = {{$exams->firstItem() }};
        var lastItem = {{ $exams->lastItem() }};

        // This script assumes only one flashcard is displayed per page for the counter to be accurate
        // If multiple flashcards are displayed per page, this counter logic needs adjustment.
        // For now, it will show the number of the first item on the current page.
        $('#currentFlashcard').text(firstItem);
    });
</script>

<style>
    :root {
        --primary-color: #f2761e; /* Orange */
        --secondary-color: #34495e; /* Dark Blue/Grey */
        --text-color: #333;
        --light-grey: #f0f2f5;
        --dark-grey: #555;
        --white: #fff;
    }

    body {
        background-color: var(--light-grey);
        font-family: 'Poppins', sans-serif;
        line-height: 1.6;
        color: var(--text-color);
    }

    .flashcard-container {
        background-color: var(--white);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
        margin-bottom: 30px;
        max-width: 1000px; /* Increased max-width for a more spacious feel */
        margin-left: auto;
        margin-right: auto;
    }

    .page-title {
        font-size: 2.8rem; /* Slightly larger */
        color: var(--secondary-color); /* Use secondary color for heading */
        font-weight: 800; /* Bolder */
        margin-bottom: 10px;
    }

    .flashcard-counter {
        font-size: 1.6rem; /* Slightly larger */
        font-weight: 700; /* Bolder */
        color: var(--primary-color); /* Highlight with primary color */
    }

    .flashcard-card {
        background-color: transparent;
        width: 100%;
        height: 400px; /* Increased height for more content space */
        perspective: 1000px;
        cursor: pointer;
        border-radius: 20px; /* Slightly more rounded */
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15); /* Stronger shadow */
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .flashcard-card:hover {
        transform: translateY(-8px); /* More pronounced lift */
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); /* Even stronger shadow on hover */
    }

    .flashcard-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.7s cubic-bezier(0.4, 0.2, 0.2, 1); /* Smoother transition */
        transform-style: preserve-3d;
        border-radius: 20px;
    }

    .flashcard-card.flipped .flashcard-inner {
        transform: rotateY(180deg);
    }

    .flashcard-front,
    .flashcard-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px; /* More padding */
        border-radius: 20px;
        box-sizing: border-box;
        font-size: 1.8rem; /* Larger font size for questions/answers */
        font-weight: 600;
        line-height: 1.4;
    }

    .flashcard-front {
        background-color: var(--white);
        color: var(--text-color);
        border: 2px solid var(--light-grey); /* Slightly more prominent border */
    }

    .flashcard-back {
        background-color: var(--primary-color);
        color: var(--white);
        transform: rotateY(180deg);
        border: 2px solid var(--primary-color);
    }

    .flashcard-question,
    .flashcard-answer {
        margin: 0;
        max-height: 100%; /* Ensure content fits */
        overflow-y: auto; /* Add scroll for long content */
    }

    .flashcard-answer {
        font-size: 1.6rem; /* Slightly smaller than question, but still large */
    }

    .alert-info {
        background-color: #e0f7fa; /* Light blue */
        border-color: #80deea; /* Slightly darker blue */
        color: #00796b; /* Dark teal */
        font-size: 1.2rem; /* Larger font */
        padding: 25px; /* More padding */
        border-radius: 12px; /* More rounded corners */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08); /* Subtle shadow */
        margin-top: 30px;
    }

    /* Pagination styling */
    .pagination-controls {
        display: flex;
        justify-content: space-between;
        margin-top: 20px; /* Reduced margin-top as wrapper will have padding */
        padding: 0;
    }

    .pagination-wrapper {
        background-color: var(--white);
        padding: 20px 30px; /* Added padding to the wrapper */
        border-radius: 15px; /* Rounded corners for the wrapper */
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); /* Subtle shadow for the wrapper */
        margin-top: 30px; /* Space above the wrapper */
        margin-bottom: 20px; /* Space below the wrapper */
    }

    .pagination-btn {
        padding: 12px 25px; /* Larger buttons */
        font-size: 1.2rem; /* Larger font */
        border-radius: 30px; /* Pill-shaped buttons */
        font-weight: 700; /* Bolder text */
        transition: all 0.3s ease;
        text-transform: uppercase; /* Uppercase text */
        letter-spacing: 0.5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .pagination-btn.prev-btn {
        background-color: var(--secondary-color); /* Dark blue/grey */
        border-color: var(--secondary-color);
        color: var(--white);
    }

    .pagination-btn.prev-btn:hover {
        background-color: #2c3e50; /* Slightly darker secondary */
        border-color: #2c3e50;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .pagination-btn.next-btn {
        background-color: var(--primary-color); /* Orange */
        border-color: var(--primary-color);
        color: var(--white);
    }

    .pagination-btn.next-btn:hover {
        background-color: #e66a15; /* Slightly darker orange */
        border-color: #e66a15;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .pagination-btn:disabled {
        background-color: #e0e0e0; /* Lighter grey for disabled */
        border-color: #e0e0e0;
        color: #a0a0a0; /* Darker text for disabled */
        opacity: 1; /* No opacity change */
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .flashcard-container {
            padding: 20px;
        }

        .page-title {
            font-size: 2.2rem;
        }

        .flashcard-counter {
            font-size: 1.4rem;
        }

        .flashcard-card {
            height: 300px;
        }

        .flashcard-front,
        .flashcard-back {
            font-size: 1.5rem;
            padding: 20px;
        }

        .flashcard-answer {
            font-size: 1.3rem;
        }

        .pagination-btn {
            padding: 10px 20px;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .flashcard-container {
            padding: 15px;
            border-radius: 10px;
        }

        .page-title {
            font-size: 1.8rem;
            text-align: center;
            width: 100%;
        }

        .flashcard-counter {
            font-size: 1.2rem;
            text-align: center;
            width: 100%;
            margin-top: 10px;
        }

        .flashcard-card {
            height: 250px;
            border-radius: 15px;
        }

        .flashcard-front,
        .flashcard-back {
            font-size: 1.2rem;
            padding: 15px;
        }

        .flashcard-answer {
            font-size: 1rem;
        }

        .pagination-controls {
            flex-direction: column;
            gap: 15px;
            margin-top: 25px;
        }

        .pagination-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection