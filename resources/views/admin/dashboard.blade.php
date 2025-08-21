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
<div class="content-wrapper">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Dashboard</h2>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-6"> {{-- Changed to col-lg-4 --}}
                <div class="card text-white bg-orange mb-3">
                    <div class="card-header">Courses</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $courseCount }}</h5>
                        <p class="card-text">Number of Courses</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-light">More info</a> {{-- Link to courses index --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6"> {{-- Changed to col-lg-4 --}}
                <div class="card text-white bg-orange mb-3">
                    <div class="card-header">Batches</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $batchCount }}</h5>
                        <p class="card-text">Number of Batches</p>
                        <a href="{{ route('batches') }}" class="btn btn-light">More info</a> {{-- Link to batches index --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6"> {{-- Changed to col-lg-4 --}}
                <div class="card text-white bg-orange mb-3">
                    <div class="card-header">Students</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $studentCount }}</h5>
                        <p class="card-text">Number of Students</p>
                        <a href="{{ route('admin.studentsDashboard') }}" class="btn btn-light">More info</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection