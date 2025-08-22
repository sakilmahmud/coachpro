@extends('layout/admin-layout')

@section('space-work')
<div class="container">
    <h1>Courses for Flash Cards</h1>
    <div class="row">
        @foreach($courses as $course)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->name }}</h5>
                    <p class="card-text">{{ $course->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="{{ route('flash-cards.show', $course->id) }}" class="btn btn-sm btn-outline-secondary">View Flash Cards</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
