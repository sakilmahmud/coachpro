@extends('layout/admin-layout')

@section('space-work')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-graduate"></i> Student Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.studentsDashboard') }}">Students</a></li>
                        <li class="breadcrumb-item active">{{ $student->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-info-circle"></i> Personal Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fa fa-user"></i> Name:</strong> {{ $student->name }}</p>
                                    <p><strong><i class="fa fa-envelope"></i> Email:</strong> {{ $student->email }}</p>
                                    <p><strong><i class="fa fa-phone"></i> Phone No:</strong> {{ $student->phone_no }}</p>
                                    <p><strong><i class="fa fa-phone-alt"></i> Alt Phone:</strong> {{ $student->altphone_no ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fa fa-map-marker-alt"></i> Address:</strong> {{ $student->address ?? 'N/A' }}</p>
                                    <p><strong><i class="fa fa-map-marked-alt"></i> Address 2:</strong> {{ $student->address_2 ?? 'N/A' }}</p>
                                    <p><strong><i class="fa fa-city"></i> City:</strong> {{ $student->city ?? 'N/A' }}</p>
                                    <p><strong><i class="fa fa-globe-americas"></i> State:</strong> {{ $student->state ?? 'N/A' }}</p>
                                    <p><strong><i class="fa fa-flag"></i> Country:</strong> {{ $student->country ?? 'N/A' }}</p>
                                    <p><strong><i class="fa fa-calendar-alt"></i> Registered On:</strong> {{ $student->created_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-book-open"></i> Enrolled Courses</h3>
                        </div>
                        <div class="card-body">
                            @if($student->accessSubjects->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($student->accessSubjects as $access)
                                        @if($access->subject && $access->subject->course)
                                            <li class="list-group-item">
                                                <i class="fa fa-check-circle text-success"></i>
                                                <strong>{{ $access->subject->course->name }}</strong>
                                                <span class="badge badge-primary ml-2">Batch: {{ $access->subject->subject }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-exclamation-triangle"></i> No courses enrolled.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection