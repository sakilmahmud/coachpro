@extends('layout/admin-layout')

@section('space-work')

<style>
    .bg-orange {
        background-color: #f2761e !important;
        color: #fff !important;
    }

    .small-box .icon {
        color: rgba(255, 255, 255, 0.5) !important;
        /* Make icon slightly transparent white */
    }
</style>
<div class="container">
    <h1>Flash Cards</h1>
    <div class="row">
        @foreach($courses as $course)

        <div class="col-lg-4 col-6 mb-3"> {{-- Changed to col-lg-4 --}}
            <div class="card text-white bg-orange mb-3 h-100" style="transition: all .2s ease-in-out; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.125);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div class="card-header d-flex justify-content-center align-items-center text-dark fw-bold">Courses<i class="fas fa-book-open"></i></div>
                <div class="card-body text-center">
                    <h5 class="card-title h3">{{ $course->name }}</h5>
                    <p class="card-text">{{ $course->description }}</p>
                    <a href="{{ route('flash-cards.show', $course->id) }}" class="btn btn-sm btn-dark">View Flash Cards</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
