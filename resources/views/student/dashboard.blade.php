@extends('layout/student-layout')

@section('space-work')
<div class="text-right"><img  src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>
<h2>Batches</h2>

    <table class="table table-bordered table-striped" id="enrolledBatchesTable">

        <thead>
            <th>#</th>
            <th>Batch Name</th>
            <th>Batch Duration</th>
            <th>Start Date</th>
            <th>End date</th>
            <th>batch Explanation</th>
        </thead>

        <tbody>
        @php
        $counter = 1;
        @endphp
        @foreach($exams as $subject)
                        <tr>
                            <td>{{$counter++}}</td>
                            <td>{{$subject->subject}}</td>
                            
                            <td>{{$subject->duration}}</td>
                            <td>{{$subject->starting_date}}</td>
                            <td>{{$subject->end_date}}</td>
                            <td>{{$subject->explnation}}</td>
                            
                   </tr>
         @endforeach
        </tbody>

    </table>

    @push('scripts')
<script>
    $(document).ready(function() {
        $('#enrolledBatchesTable').DataTable();
    });
</script>
@endpush

    

@endsection