@extends('layout/student-layout')

@section('space-work')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Query</h2>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="savequery">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ Auth::user()->country }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="number">Phone No</label>
                            <input type="text" class="form-control" id="number" name="number" value="{{ Auth::user()->phone_no }}" readonly>
                        </div>

                        <div class="form-group">
    <label for="query">Your Query</label>
    <textarea name="query" id="query" class="form-control" rows="4" placeholder="Enter Your Query" style="background-color: #f0f8ff;"></textarea>
</div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#successModal">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Data saved successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
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
                        $('#successModal').modal('show');
                        document.getElementById("savequery").reset();
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
@endsection
