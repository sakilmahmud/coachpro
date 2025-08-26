@extends('layout.student-layout')

@section('space-work')
<div class="container-fluid query-container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card query-card">
                <div class="card-header query-card-header">
                    <h2 class="query-title">Submit Your Query</h2>
                </div>
                <div class="card-body query-card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="savequery">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ Auth::user()->country }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label for="number" class="form-label">Phone No</label>
                            <input type="text" class="form-control" id="number" name="number" value="{{ Auth::user()->phone_no }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label for="query" class="form-label">Your Query</label>
                            <textarea name="query" id="query" class="form-control query-textarea" rows="5" placeholder="Enter Your Query"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary submit-query-btn">Submit Query</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Success!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fa fa-check-circle fa-4x text-success mb-3"></i>
                <p class="lead">Your query has been submitted successfully!</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#savequery').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('studentQuery') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                        $('#savequery')[0].reset(); // Reset the form
                    } else {
                        alert('Data not saved. Please try again.');
                    }
                },
                error: function () {
                    alert('An error occurred. Please try again later.');
                }
            });
        });
    });
</script>

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }

    .query-container {
        padding: 30px;
    }

    .query-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .query-card-header {
        background-color: #007bff;
        color: white;
        padding: 20px;
        border-bottom: none;
        text-align: center;
    }

    .query-title {
        margin-bottom: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .query-card-body {
        padding: 30px;
    }

    .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        box-shadow: none;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    .query-textarea {
        resize: vertical;
    }

    .submit-query-btn {
        background-color: #28a745;
        border-color: #28a745;
        padding: 12px 25px;
        font-size: 1.1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .submit-query-btn:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-2px);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-title {
        font-weight: 700;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    .modal-body .fa-check-circle {
        color: #28a745;
    }

    .modal-footer {
        border-top: none;
    }
</style>
@endsection