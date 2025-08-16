
@extends('layout/admin-layout')

@section('space-work')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3 class="text-center">Edit And Update Flash Card Records</h3>
    <div class="container text-center mt-5">
       <form action="{{ route('update.question', ['id' => $record->id]) }}" method="POST">
       @csrf
       <label for="Subject">Subject</label>
       <select name="subject" id="subject" class="w-50">
        <option value="{{ $record->subject}}" selected>{{ $record->subject}}</option> 
                   <option value="PgMP">RfMP </option> 
                   <option value="PgMP">PgMP </option> 
                    <option value="PMP">PMP</option>
                    <option value="PMI-ACP">PMI-ACP</option>
                    <option value="PMI-RMP">PMI-RMP</option>
       </select><br><br>
    <!-- <input type="text" class="w-50" value="{{ $record->subject }}" name="subject"> <br><br> -->
    <label for="Subject">Question</label>
    <input type="text" class="w-50" value="{{ $record->question }}" name="question"> <br><br>
    <label for="Subject">Answer</label>
    <input type="text" class="w-50" value="{{ $record->answer }}" name="answer"> <br><br>
    <a href=""><button class="btn btn-success">Update</button></a>
       </form>
    </div>

</body>
</html> 


@endsection