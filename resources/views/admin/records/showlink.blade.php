@extends('layout/admin-layout')

@section('space-work')

 <?php
//@dd($cardquestion);
?> 
<!-- Button trigger modal -->

    <h2 class="mb-4" style="text-align: center;">Pdf Documents</h2>
    
        
    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

    <table class="table">
        <thead>
            <th>S no.</th>
            <th>Topic</th>
            <th>Link</th>
           
           
            <th>Delete</th>
        </thead>
        <tbody>
                    @php
                    $counter = 1;
                    @endphp
        @foreach($chal as $key => $value)        
    <tr>
        <td>{{ $counter++ }}</td>
        <td>{{ $value->topic }}</td>
        <td>{{ $value->link }}</td>
       
       
        <td>
        <a href="{{ route('delete.link', ['id' => $value->id]) }}" onclick="return confirm('Are you sure you want to delete this record?')"><button class="btn btn-danger">Delete</button></a>
        </td>
    </tr>
@endforeach
          
                
               
        </tbody>
    </table>




@endsection