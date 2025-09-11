@extends('layout/admin-layout')

@section('space-work')

@if(session('success'))    
    <div class="alert alert-success" role="alert"> 
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">
    {{ session('error') }}
</div>
@endif
    
<h2 class="mb-4">Student Query</h2>
<!-- Button trigger modal -->

<table class="table table-bordered table-striped" id="studentQueryTable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Student Name</th>
            <th scope="col">Student Mail</th>
            <th scope="col">Country</th>
            <th scope="col">Phone No</th>
            <th scope="col">Student Query</th>
            <th scope="col">Attachment</th>
            <th scope="col">Date & Time</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        @php
        $counter = 1;
        @endphp
        @if(count($studentQueries) > 0)

        @foreach($studentQueries as $subject)
        <tr>
            <td> {{ $counter ++ }} </td>
            <td> {{ $subject->student_name }} </td>
            <td> {{ $subject->student_mail }} </td>
            <td> {{ $subject->contry }} </td>
            <td> {{ $subject->student_number }} </td>
            <td> {{ $subject->student_querys }} </td>
            <td>
                @if($subject->attachment)
                    <a href="{{ asset('uploads/student_queries/' . $subject->attachment) }}" target="_blank">View Attachment</a>
                @else
                    N/A
                @endif
            </td>
            <td> {{ $subject->created_at }} </td>
            <td>                
                <form action="{{ route('deleteqery') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this query?');">
                    @csrf
                    <input type="hidden" name="id" value="{{ $subject->id }}">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach

        @else
        <tr>
            <td colspan="9">Student Query not Found!</td>
        </tr>
        @endif

    </tbody>
</table>

</script>


@endsection