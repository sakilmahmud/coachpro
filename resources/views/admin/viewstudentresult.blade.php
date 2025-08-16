@extends('layout/admin-layout')

@section('space-work')

    <h2 class="mb-4"><h1>{{ $students }}</h1></h2>
    <!-- Button trigger modal -->
<div class="container" style="height: 50px; background-color:#2F89FC; display: flex; align-items: center; justify-content: space-between;">
    <div id="questionCount" style="color: white; font-size: 20px;"></div>
    <div id="timer-container">
        <div id="elapsedTime" hidden style="color: white; font-size: 16px;">Elapsed Time: 00:00:00</div>
        <div id="remainingTime" hidden style="color: white; font-size: 20px;">Remaining Time: 04:00:00</div>
    </div>
</div>
<br>

<!-- Display Test History -->
@php
    $dataExists = false;
    foreach ($results as $attempt) {
        $dataExists = true;
        break;
    }
@endphp

<div class="container">
    <div class="test-attempts">
        @if ($dataExists)
            @forelse ($results as $attempt)
                
       
            <a href="{{ route('mock.review', ['attempt_id' => $attempt->attempt_id, 'title' => $attempt->title, 'subject' => $attempt->subject]) }}" class="test-attempt-link" style=" border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; text-decoration: none; background-color:#ffffff; width: 100%;">
                <div class="progress-container" style="flex: 1; order: 1; padding: 10px;">
                <!-- Replace the progress bar with a canvas element for the pie chart -->
                <div class="row">
                    <div class="col-6">
                       
                        <canvas hidden id="attemptChart-{{$attempt->attempt_id}}" class="ca nvas" width="50" height="50"></canvas>
                        <p class="text-center mt-5" style="color:black;">Marks:{{ $attempt->percentage}}% </p> 
                         <p class="text-center" style="color:black;">
    Test - {{ preg_replace("/[^0-9]/", "", $attempt->title) }}
</p> 
                    </div>
                    <div class="col-6">
                        <canvas  id="percentageChart-{{$attempt->attempt_id}}" class="canvas" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
            <div class="statistics" style="flex: 1; order: 2; padding: 10px; text-align: center;">
                <p style="color: #20a520; font-size: 16px; margin-bottom: 5px;"> Correct&nbsp;{{ $attempt->correct_count }}</p>
                <p style="color: #ff2929; font-size: 16px; margin-bottom: 5px;">Incorrect&nbsp;{{ $attempt->incorrect_count }} </p>
                <p style="color:  #EED202; font-size: 16px; margin-bottom: 5px;">Unattempted&nbsp;{{ $attempt->unattempted_count }}</p>
            </div>
            <div class="labels" style="flex: 1; order: 3; padding: 10px; text-align: center;">
                <p style="color: #000000; font-size: 14px; margin-bottom: 5px;">Attempt: {{ $attempt->attempt_id }}</p>
                <p style="color: #000000; font-size: 14px; margin-bottom: 5px;">Date: {{ $attempt->created_at }}</p>
                @if ($attempt->percentage >= 70)
                <p class="text-center" style="font-size: 26px; color: #20a520; font-weight: bold;">Result: Pass</p>
                @else
                <p class="text-center" style="font-size: 26px; color: #ff2929; font-weight: bold;">Result: Fail</p>
                @endif
            </div>
            </a>
            @empty
            <p style="text-align: center;">This student has not attempted any mock tests yet.</p>
            @endforelse
        @else
            <p style="text-align: center;">This student has not attempted any mock tests yet.</p>
        @endif
    </div>
</div>


<!-- JavaScript for Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@foreach($results as $attempt)
    var percentageData{{$attempt->attempt_id}} = {{ $attempt->percentage }};
    var correctCount{{$attempt->attempt_id}} = {{ $attempt->correct_count }};
    var incorrectCount{{$attempt->attempt_id}} = {{ $attempt->incorrect_count }};
    var unattemptedCount{{$attempt->attempt_id}} = {{ $attempt->unattempted_count }};

    var canvas{{$attempt->attempt_id}} = document.getElementById('percentageChart-{{$attempt->attempt_id}}');
    var canvas2{{$attempt->attempt_id}} = document.getElementById('attemptChart-{{$attempt->attempt_id}}');

    if (canvas{{$attempt->attempt_id}} && canvas2{{$attempt->attempt_id}}) {
        var ctx{{$attempt->attempt_id}} = canvas{{$attempt->attempt_id}}.getContext('2d');
        var ctx2{{$attempt->attempt_id}} = canvas2{{$attempt->attempt_id}}.getContext('2d');

        var colors{{$attempt->attempt_id}} = ['#20a520', '#ff2929', ' #EED202'];
        var data{{$attempt->attempt_id}} = {
            // labels: ['Correct Answers', 'Incorrect Answers', 'Unattempted'],
            datasets: [{
                data: [correctCount{{$attempt->attempt_id}}, incorrectCount{{$attempt->attempt_id}}, unattemptedCount{{$attempt->attempt_id}}],
                backgroundColor: colors{{$attempt->attempt_id}}
            }]
        };

        var chart{{$attempt->attempt_id}} = new Chart(ctx{{$attempt->attempt_id}}, {
            type: 'pie',
            data: data{{$attempt->attempt_id}},
            options: {
                legend: {
                    display: false
                }
            }
        });

        var data2{{$attempt->attempt_id}} = {
            // labels: ['Pass', 'Fail'],
            datasets: [{
                data: [percentageData{{$attempt->attempt_id}}, 100 - percentageData{{$attempt->attempt_id}}],
                backgroundColor: colors{{$attempt->attempt_id}}
            }]
        };

        var chart2{{$attempt->attempt_id}} = new Chart(ctx2{{$attempt->attempt_id}}, {
            type: 'pie',
            data: data2{{$attempt->attempt_id}},
            options: {
                legend: {
                    display: false
                }
            }
        });
    }
@endforeach
</script>

<style>
.container {
    max-width: 100%;
}

.test-attempt-link {
    border: 1px solid #2F89FC;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    text-decoration: none;
    background-color: #f0f8ff;
    width: 100%;
    margin: 0 auto;
}

.attempt-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.progress-container {
    flex: 1;
    order: 1;
    padding: 10px;
    text-align: block;
}

.statistics {
    flex: 1;
    order: 2;
    padding: 10px;
    text-align: center;
}

.labels {
    flex: 1;
    order: 3;
    padding: 10px;
    text-align: center;
}

p {
    font-size: 16px;
    margin: 0;
}

.canvas {
    height: 50px;
    width: 50px;
}

@media (max-width: 768px) {
    .attempt-info {
        flex-direction: column;
        text-align: center;
    }

    .labels, .statistics, .progress-container {
        flex: 1;
        order: unset;
        padding: 10px;
    }
}
</style>


       
   

       
  </tbody>
</table>












<!-- Delete Subject Modal -->
<div class="modal fade" id="deleteSubjectModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Delete Subject</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="deleteSubject" >
        @csrf
            <div class="modal-body">
                <p>Are you Sure you want to delete Subject?</p>
                <input type="hidden" name="id" id="delete_subject_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
        </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        $(".deleteButton").click(function () {
            var subject_id = $(this).data('id');
            $("#delete_subject_id").val(subject_id);
        });

        $("#deleteSubject").submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('deleteqery') }}",
                type: "POST",
                data: formData,
                success: function (data) {
                    if (data.success == true) {
                        // Show a success message in a popup
                        alert('Your data has been deleted successfully');
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        });
    });
</script>


           

     
    


    
</script>
      

@endsection