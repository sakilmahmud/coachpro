@extends('layout/admin-layout')

@section('space-work')
<div class="d-flex justify-content-between">
    <div>
        @include('admin-s-button')
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
<h2 class="mb-4" style="text-align: center;">PMP Test 1 Question & Answer</h2>
@if($questions->count() > 0)
    <h3 class="mb-4">Label: {{ $questions[0]->label }}</h3>
    <h3 class="mb-4">Description: {{ $questions[0]->explanation }}</h3>
@endif

<style>
    .total-questions {
        background-color: #f0f0f0; /* Set your desired background color */
        padding: 10px; /* Add padding for better visibility */
        text-align: center; /* Center align the content */
    }
</style>

<!-- Styling for total questions count -->
<div class="total-questions">
    <p style="margin: 0;">Total Questions: {{ $questions->count() }}</p>
</div>

<table class="table">
    <thead>
        <th>#</th>
        <th>Question</th>
        <th>Answers</th>
        <th>Edit</th>
        <th>Delete</th>
    </thead>
    <tbody>
        @if($questions->count() > 0)
            @php
            $counter = 1;
            @endphp
            @foreach($questions as $question)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $question->question }}</td>
                    <td>
                        <a href="#" class="ansButton" data-id="{{ $question->id }}" data-toggle="modal" data-target="#showAnsModal">See Answers</a>
                    </td>
                    <td>
                        <button class="btn btn-info editButton" data-id="{{ $question->id }}" data-toggle="modal" data-target="#editQnaModal">Edit</button>
                    </td>
                    <td>
                        <button class="btn btn-danger deleteButton" data-id="{{ $question->id }}" data-toggle="modal" data-target="#deleteQnaModal">Delete</button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">Questions & Answers not Found!</td>
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
        <form id="addQna">
            @csrf
            <div class="modal-body addModalAnswers">
            <div class="row">
                    <div class="col">
                   
                        <input type="hidden" class="w-100" id="subjectInput" name="subject" readonly >
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col">
                 
                        <input type="hidden" class="w-100" id="testTitleInput" name="title" readonly>
                    </div>
                </div>
                <div class="row">
                  
                    <div class="col">
                   
                        <input type="hidden" class="w-100" id="levelInput" name="label" readonly>
                    </div>
                </div> 
                <div class="row">
                    <div class="col">
                    
                        <input type="hidden" class="w-100" id="explanationInput" name="explanation" readonly>
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
                    <label for="Subject">Q. Explanation(optional)</label>
                        <textarea name="explaination" class="w-100" placeholder="Enter your explaination(Optional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="error" style="color:red;"></span>
                <button id="addAnswer" class="ml-5 btn btn-info">Add Answer</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Question</button>
            </div>
        </form>
        </div>
  </div>
</div>


<!-- Edit Q&A Modal -->
<div class="modal fade" id="editQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Update Q&A</h5>

            <button id="addEditAnswer" class="ml-5 btn btn-info">Add Answer</button>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="editQna">
            @csrf
            <div class="modal-body editModalAnswers">
                <div class="row">
                    <div class="col">
                        <input type="hidden" name="question_id" id="question_id">
                        <input type="text" class="w-100" name="question" id="question" placeholder="Enter Question" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <textarea name="explaination" id="explaination" class="w-100" placeholder="Enter your explaination(Optional)"></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <span class="editError" style="color:red;"></span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Q&A</button>
            </div>
        </form>
        </div>
  </div>
</div>

<!--Show answer Modal -->
<div class="modal fade" id="showAnsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Show Answers</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <th>#</th>
                        <th>Answer</th>
                        <th>Is Correct</th>
                    </thead>
                    <tbody class="showAnswers">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <span class="error" style="color:red;"></span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
  </div>
</div>


<!-- Delete Q&a Modal -->
<div class="modal fade" id="deleteQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Delete Q&A</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="deleteQna">
        @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="delete_qna_id">
                <p>Are your Sure you want to Delete Q&A?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Delete</button>
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
            <div class="modal-body">
            <label for="Lavel">Test Title<span style="color: red;">*</span></label>
                <select name="" id="testTitle" class="w-100">

                     <option value="test 1">Test 1</option> 
                    <option value="test 2">Test 2 </option> 
                    <option value="test 3">Test 3</option>
                    <option value="test 4">Test 4</option>
                    <option value="test 5">Test 5</option>
                    <option value="test 6">Test 6</option>
                    <option value="test 7">Test 7</option>
                </select>
            </div>
            
            <div class="modal-body">
        <label for="Lavel">Lavel<span style="color: red;">*</span></label>
        <select name="lavel"  class="w-100" id="level" style="border-radius: 5px;">
            <option value="Beginner">Beginner</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Expert">Expert</option>
            <option value="All Lavel">All Levels</option>
        </select>
        </div>
        
        <div class="modal-body">
            <label for="Lavel">Explanation<span style="color: red;">*</span></label>
                <textarea name="" id="explanation" cols="30" rows="10" class="w-100"></textarea>
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
    sessionStorage.setItem('testTitle', document.querySelector('#testTitle').value);
    sessionStorage.setItem('level', document.querySelector('#level').value);
    sessionStorage.setItem('explanation', document.querySelector('#explanation').value);

    // Open the Add Qna Modal
    $('#addQnaModal').modal('show');
});

// JavaScript code to populate data from session when Add Qna Modal is shown
$('#addQnaModal').on('show.bs.modal', function () {
    // Display data 
    document.querySelector('#subjectInput').value = sessionStorage.getItem('subject');
    document.querySelector('#testTitleInput').value = sessionStorage.getItem('testTitle');
    document.querySelector('#levelInput').value = sessionStorage.getItem('level');
    document.querySelector('#explanationInput').value = sessionStorage.getItem('explanation');
});

                </script>






           
        <!-- </form> -->
        </div>
  </div>
</div>

<script>
    $(document).ready(function(){

        //form submittion
        $("#addQna").submit(function(e){
            e.preventDefault();

            if($(".answers").length < 2){
                $(".error").text("Please add minimum two answers.")
                setTimeout(function(){
                    $(".error").text("");
                },2000);
            }
            else{

                var checkIsCorrect = false;

                for(let i = 0; i < $(".is_correct").length; i++ ){
                    if( $(".is_correct:eq("+i+")").prop('checked') == true )
                    {
                        checkIsCorrect = true;
                        $(".is_correct:eq("+i+")").val( $(".is_correct:eq("+i+")").next().find('input').val() );
                    }
                }

                if(checkIsCorrect){

                    var formData = $(this).serialize();

                    $.ajax({
                        url:"{{ route('addQna') }}",
                        type:"POST",
                        data:formData,
                        success:function(data){
                            console.log(data);
                            if(data.success == true){
                                location.reload();
                            }
                            else{
                                alert(data.msg);
                            }

                        }
                    });

                }
                else{
                    $(".error").text("Please select anyone correct answer.")
                    setTimeout(function(){
                        $(".error").text("");
                    },2000);
                }

            }

        });

        //add answers
        $("#addAnswer").click(function(){

            if($(".answers").length >= 6){
                $(".error").text("You can add Maximum 6 answers.")
                setTimeout(function(){
                    $(".error").text("");
                },2000);
            }
            else{
                    var html = `
                    <div class="row mt-2 answers">
                        <input type="radio" name="is_correct" class="is_correct">
                        <div class="col">
                            <input type="text" class="w-100" name="answers[]" placeholder="Enter Enswer" required>
                        </div>
                        <button class="btn btn-danger removeButton">Remove</button>
                    </div>
                `;

                $(".addModalAnswers").append(html);
            }

        });

        $(document).on("click",".removeButton",function(){
            $(this).parent().remove();
        });


        //show answers code

        $(".ansButton").click(function(){

            var questions = @json($questions);
            var qid = $(this).attr('data-id');
            var html = '';

            for(let i=0;i < questions.length; i++){

                if(questions[i]['id'] == qid){
                    
                    var answersLength = questions[i]['answers'].length;
                    for(let j=0; j< answersLength; j++){
                        let is_correct = 'No';
                        if(questions[i]['answers'][j]['is_correct'] == 1){
                            is_correct = 'Yes';
                        }

                        html += `
                            <tr>
                                <td>`+(j+1)+`</td>
                                <td>`+questions[i]['answers'][j]['answer']+`</td>
                                <td>`+is_correct+`</td>
                            </tr>
                        `;
                    }
                    break;
                }

            }

            $('.showAnswers').html(html);

        });


        //edit or update Q&a
        $("#addEditAnswer").click(function(){

            if($(".editAnswers").length >= 6){
                $(".editError").text("You can add Maximum 6 answers.")
                setTimeout(function(){
                    $(".editError").text("");
                },2000);
            }
            else{
                    var html = `
                        <div class="row mt-2 editAnswers">
                            <input type="radio" name="is_correct" class="edit_is_correct">
                            <div class="col">
                                <input type="text" class="w-100" name="new_answers[]" placeholder="Enter Answer" required>
                            </div>
                            <button class="btn btn-danger removeButton">Remove</button>
                        </div>
                    `;

                $(".editModalAnswers").append(html);
            }

        });

        $(".editButton").click(function(){

            var qid = $(this).attr('data-id');

            $.ajax({
                url:"{{ route('getQnaDetails') }}",
                type:"GET",
                data:{qid:qid},
                success:function(data){
                    console.log(data);

                    var qna = data.data[0];
                    $("#question_id").val(qna['id']);                    
                    $("#question").val(qna['question']);  
                    $("#explaination").val(qna['explaination']);  
                    $(".editAnswers").remove();
                    var html = '';
                    
                    for(let i = 0; i < qna['answers'].length; i++){

                        var checked = '';
                        if(qna['answers'][i]['is_correct'] == 1){
                            checked = 'checked';
                        }

                        html += `
                            <div class="row mt-2 editAnswers">
                                <input type="radio" name="is_correct" class="edit_is_correct" `+checked+`>
                                <div class="col">
                                    <input type="text" class="w-100" name="answers[`+qna['answers'][i]['id']+`]" 
                                    placeholder="Enter Answer" value="`+qna['answers'][i]['answer']+`" required>
                                </div>
                                <button class="btn btn-danger removeButton removeAnswer" data-id="`+qna['answers'][i]['id']+`">Remove</button>
                            </div>
                        `;

                    }
                    $(".editModalAnswers").append(html);

                }
            });

        });

        //updaate Qna submittion
        $("#editQna").submit(function(e){
            e.preventDefault();

            if($(".editAnswers").length < 2){
                $(".editError").text("Please add minimum two answers.")
                setTimeout(function(){
                    $(".editError").text("");
                },2000);
            }
            else{

                var checkIsCorrect = false;

                for(let i = 0; i < $(".edit_is_correct").length; i++ ){
                    if( $(".edit_is_correct:eq("+i+")").prop('checked') == true )
                    {
                        checkIsCorrect = true;
                        $(".edit_is_correct:eq("+i+")").val( $(".edit_is_correct:eq("+i+")").next().find('input').val() );
                    }
                }

                if(checkIsCorrect){

                    var formData = $(this).serialize();

                    $.ajax({
                        url:"{{ route('updateQna') }}",
                        type:"POST",
                        data:formData,
                        success:function(data){
                            if(data.success == true){
                                location.reload();
                            }
                            else{
                                alert(data.msg);
                            }
                        }
                    });
                }
                else{
                    $(".editError").text("Please select anyone correct answer.")
                    setTimeout(function(){
                        $(".editError").text("");
                    },2000);
                }

            }

        });

        //remove Answers
        $(document).on('click','.removeAnswer',function(){
            
            var ansId = $(this).attr('data-id');

            $.ajax({
                url:"{{ route('deleteAns') }}",
                type:"GET",
                data:{ id:ansId },
                success:function(data){
                    if(data.success == true){
                        console.log(data.msg);
                    }
                    else{
                        alert(data.msg);
                    }
                }
            });

        });


        //delete Q&A

        $('.deleteButton').click(function(){
            var id = $(this).attr('data-id');
            $('#delete_qna_id').val(id);
        });

        $('#deleteQna').submit(function(e){
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url:"{{ route('deleteQna') }}",
                type:"POST",
                data:formData,
                success:function(data){
                    if(data.success == true){
                        location.reload();
                    }
                    else{
                        alert(data.msg);
                    }
                }
            });

        })

        //import Q&A

        $('#importQna').submit(function(e){
            e.preventDefault();

            
            let formData = new FormData();

            formData.append("file",fileupload.files[0]);

            $.ajaxSetup({
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token() }}"
                }
            });

            $.ajax({
                url:"{{ route('importQna') }}",
                type:"POST",
                data:formData,
                processData:false,
                contentType:false,
                success:function(data){
                    if(data.success == true){
                        location.reload();
                    }
                    else{
                        alert(data.msg);
                    }
                }
            });

        })

    });
</script>

@endsection