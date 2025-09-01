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
                <h4 class="text-muted">Welcome, {{ auth()->user()->name }}!</h4>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-6"> {{-- Changed to col-lg-4 --}}
                <div class="card text-white bg-orange mb-3 h-100" style="transition: all .2s ease-in-out; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.125);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-header d-flex justify-content-center align-items-center text-dark fw-bold">Courses<i class="fas fa-book-open"></i></div>
                    <div class="card-body text-center">
                        <h5 class="card-title h3">{{ $courseCount }}</h5>
                        <p class="card-text">Number of Courses</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-light">More info</a> {{-- Link to courses index --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6"> {{-- Changed to col-lg-4 --}}
                <div class="card text-white bg-orange mb-3 h-100" style="transition: all .2s ease-in-out; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.125);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-header d-flex justify-content-center align-items-center text-dark fw-bold">Batches<i class="fas fa-layer-group"></i></div>
                    <div class="card-body text-center">
                        <h5 class="card-title h3">{{ $batchCount }}</h5>
                        <p class="card-text">Number of Batches</p>
                        <a href="{{ route('batches') }}" class="btn btn-light">More info</a> {{-- Link to batches index --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6"> {{-- Changed to col-lg-4 --}}
                <div class="card text-white bg-orange mb-3 h-100" style="transition: all .2s ease-in-out; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.125);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-header d-flex justify-content-center align-items-center text-dark fw-bold">Students<i class="fas fa-user-graduate"></i></div>
                    <div class="card-body text-center">
                        <h5 class="card-title h3">{{ $studentCount }}</h5>
                        <p class="card-text">Number of Students</p>
                        <a href="{{ route('admin.studentsDashboard') }}" class="btn btn-light">More info</a>
                    </div>
                </div>
            </div>
        </div>        
    </section>
</div>
@endsection