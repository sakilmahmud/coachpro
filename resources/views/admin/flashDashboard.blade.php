@extends('layout/admin-layout')

@section('space-work')

 <?php
//@dd($cardquestion);
?> 
<!-- Button trigger modal -->
<div class="d-flex justify-content-between">
            <div>
                @include('admin-flash-button')
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importQnaModal">
                    Add Subject
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQnaModal">
                    Add Questions
                </button>
            </div>
        </div>
<br><br>
    <h2 class="mb-4" style="text-align: center;">PfMP Flash Question & Answer </h2>
    
        
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
            <th>Subject</th>
            <th>Question</th>
            <th>Answers</th>
            <th>Edit</th>
            <th>Delete</th>
        </thead>
        <tbody>
        @if(count($chal) > 0)  
        @foreach($chal as $key => $value)        
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $value->subject }}</td>
        <td>{{ $value->question }}</td>
        <td>
            {{$value->answer}}
        </td>
        <td>
            <a href="{{ route('edit.flash', ['id' => $value->id]) }}"onclick="return confirm('Are you sure you want to Edit this record?')"><button class="btn btn-info editButton">Edit</button></a>
        </td>
        <td>
        <a href="{{ route('delete.flash', ['id' => $value->id]) }}" onclick="return confirm('Are you sure you want to delete this record?')"><button class="btn btn-danger">Delete</button></a>
        </td>
    </tr>
@endforeach
            @else
                <tr>
                    <td colspan="3">Questions & Answers not Found!</td>
                </tr>
            @endif
                
               
        </tbody>
    </table>

    <!-- Modal -->
<div class="modal fade" id="addQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Question & Answer</h5>

          

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="" method="post" action="{{ route('addflash') }}">
            @csrf
           
            <div class="modal-body addModalAnswers">
            <div class="row">
                    <div class="col">
                   
                        <input type="text" class="w-100" id="subjectInput" name="subject" readonly >
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                    <label for="Subject">Question<span style="color: red;">*</span></label>
                        <input type="text" class="w-100" name="question" placeholder="Enter Question" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <label for="Subject">Answer<span style="color: red;">*</span></label>
                        <input type="text" class="w-100" name="answer" placeholder="Enter Answer" required>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <span class="error" style="color:red;"></span>
                
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Question</button>
            </div>
        </form>
        </div>
  </div>
</div>

<!-- Import Q&a Modal -->
<div class="modal fade" id="importQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Subject</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- <form id="importQna" enctype="multipart/form-data"> -->
        @csrf
            <div class="modal-body">
            <label for="Lavel">Subject<span style="color: red;">*</span></label>
                <select name="" id="subject" class="w-100">
                <option value="RfMP">PfMP</option> 
                    <option value="PgMP">PgMP </option> 
                    <option value="PMP">PMP</option>
                    <option value="PMI-ACP">PMI-ACP</option>
                    <option value="PMI-RMP">PMI-RMP</option>
                </select>
            </div>
            
            
        
        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" id="addQnaButton" data-target="#addQnaModal">
    Save & Next
    </button>
            </div>
            <script>
               
                    // JavaScript code to open Add Qna Modal and focus on it
document.querySelector('#addQnaButton').addEventListener('click', function () {
    ////Store in session
    sessionStorage.setItem('subject', document.querySelector('#subject').value);
   

    // Open the Add Qna Modal
    $('#addQnaModal').modal('show');
});

// JavaScript code to populate data from session when Add Qna Modal is shown
$('#addQnaModal').on('show.bs.modal', function () {
    // Display data 
    document.querySelector('#subjectInput').value = sessionStorage.getItem('subject');
   
});

                </script>






           
        <!-- </form> -->
        </div>
  </div>
</div>



@endsection