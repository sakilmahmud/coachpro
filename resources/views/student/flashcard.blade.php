@extends('layout.student-layout')

@section('space-work')
<div class="container-fluid flashcard-container w-75 border border-dark rounded border-2">
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
        <div class="col-md-8 col-lg-6 mb-4">
            <div class="d-flex justify-content-between mt-4 pagination-controls">
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
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }

    #currentFlashcard {
        color: #f2761e;
    }

    .flashcard-container {
        padding: 30px;
    }

    .page-title {
        font-size: 2.5rem;
        color: #333;
        font-weight: 700;
    }

    .flashcard-counter {
        font-size: 1.5rem;
        font-weight: 600;
        color: #555;
    }

    .flashcard-card {
        background-color: transparent;
        width: 100%;
        height: 350px;
        /* Increased height */
        perspective: 1000px;
        cursor: pointer;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .flashcard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .flashcard-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s;
        transform-style: preserve-3d;
        border-radius: 15px;
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
        /* Safari */
        backface-visibility: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border-radius: 15px;
        box-sizing: border-box;
    }

    .flashcard-front {
        background-color: #fff;
        color: #333;
        border: 1px solid #e0e0e0;
    }

    .flashcard-back {
        background-color: #f2761e;
        /* Updated background color */
        color: #fff;
        /* Updated font color */
        transform: rotateY(180deg);
        border: 1px solid #f2761e;
    }

    .flashcard-question,
    .flashcard-answer {
        font-size: 1.5rem;
        font-weight: 600;
        line-height: 1.5;
        margin: 0;
    }

    .flashcard-answer {
        font-size: 1.3rem;
    }

    .alert-info {
        background-color: #e0f7fa;
        border-color: #b2ebf2;
        color: #00796b;
        font-size: 1.1rem;
        padding: 20px;
        border-radius: 10px;
    }

    /* Pagination styling */
    .pagination-controls {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .pagination-btn {
        padding: 10px 20px;
        font-size: 1.1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .pagination-btn.prev-btn {
        background-color: #6c757d;
        /* Grey for previous */
        border-color: #6c757d;
    }

    .pagination-btn.prev-btn:hover {
        background-color: #5a6268;
        border-color: #545b62;
        transform: translateY(-2px);
    }

    .pagination-btn.next-btn {
        background-color: #007bff;
        /* Blue for next */
        border-color: #007bff;
    }

    .pagination-btn.next-btn:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
    }

    .pagination-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endsection