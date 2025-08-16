@extends('layout/student-layout')

@section('space-work')
<div class="text-right"><img src="{{ asset('image/logost.png') }}" alt="Image" width="20%" height="20%"></div>
<h2>Pdfs</h2>

<table class="table">
    <thead>
        <th>#</th>
        <th>Topic</th>
        <th>Pdf</th>
        <th>Download</th>
    </thead>
    <tbody>
        @php
        $counter = 1;
        @endphp

        @foreach($subjects as $subject)
        <tr>
            <td>{{$counter++}}</td>
            <td>{{$subject->topic}}</td>
            <td>{{$subject->pdf}}</td>
            <td>
                <!-- Generate a unique download link for each row -->
               <a href="{{ asset('storage/' . $subject->pdf) }}" download="{{ $subject->pdf }}">Download</a>



            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
