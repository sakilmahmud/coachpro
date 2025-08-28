@extends('layout.student-layout')

@section('content')
    <div class="container">
        <h2>My Profile</h2>
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $student->email }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="phone_no" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $student->phone_no }}">
                    </div>
                    <div class="mb-3">
                        <label for="altphone_no" class="form-label">Alternate Phone Number</label>
                        <input type="text" class_="form-control" id="altphone_no" name="altphone_no" value="{{ $student->altphone_no }}">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" value="{{ $student->country }}">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $student->address }}">
                    </div>
                    <div class="mb-3">
                        <label for="address_2" class="form-label">Address 2</label>
                        <input type="text" class="form-control" id="address_2" name="address_2" value="{{ $student->address_2 }}">
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ $student->city }}">
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ $student->state }}">
                    </div>
                    @if($student->image)
                        <div class="mb-3">
                            <img src="{{ asset('images/'.$student->image) }}" alt="Profile Picture" class="img-thumbnail" width="100">
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection