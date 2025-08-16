@extends('layout/admin-layout')

@section('space-work')

    <h2 class="mb-4">Packages</h2>
    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPackageModel">
  Add Package
</button>

<table class="table">
    <thead>
        <tr>
            <th>Package Name</th>
            <th>Exams</th>
            <th>Prices</th>
            <th>Expiry Date</th>
            <th>Delete</th>
            <th>Edit</th>
        </tr>
    </thead>
    @if(count($packages) > 0)

    @foreach($packages as $package)
        <tr>
            <td>{{ $package->name }}</td>
            <td>
                @foreach($package->exam_id as $exam)
                    {{ $exam->exam_name }}, 
                @endforeach
            </td>
            <td>
                @php $prices = json_decode($package->price); @endphp
                @foreach($prices as $key => $price)
                    {{$key}} {{ $price }},
                @endforeach
            </td>
            <td>
                {{ $package->expire }}
            </td>
            <td>
                <a href="#" class="btn btn-danger deletePackage" data-id="{{ $package->id }}">Delete</a>
            </td>
            <td>
                <a href="#" class="btn btn-primary editPackage" data-obj="{{ $package }}" data-toggle="modal" data-target="#editPackageModel">Edit</a>
            </td>
        </tr>
    @endforeach

    @else

        <tr>
            <td colspan="4">No Packages Found!</td>
        </tr>

    @endif
</table>


<!-- Modal -->
<div class="modal fade" id="addPackageModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Package</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="addPackage" method="POST" action="{{ route('addPackage') }}">
        @csrf
            <div class="modal-body">
                <input type="text" name="package_name" placeholder="Enter Package Name" required class="w-100 mb-2">
                
                @if(count($exams) > 0)
                    @foreach($exams as $exam)
                        @php $id = $exam->id; @endphp
                        @if($exam->check_in_exists_package == false)
                            <input type="checkbox" name="exams[]" value="{{ $id }}" class="exams">&nbsp;&nbsp;{{ $exam->exam_name }}<br>
                        @endif
                    @endforeach
                @endif

                <input type="date" name="expire" min="{{ date('Y-m-d') }}" required class="w-100 mb-2">
                <input type="number" name="price_inr" min="0" required placeholder="Price(INR)" class="w-45 mb-2">              
                <input type="number" name="price_usd" min="0" required placeholder="Price(USD)" class="w-45 mb-2">  
                <p class="error m-0" style="color:red;"></p>            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
            </div>
        </form>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editPackageModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Edit Package</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="editPackage" method="POST" action="{{ route('editPackage') }}">
        @csrf
            <div class="modal-body">
                <input type="hidden" name="package_id" id="package_id">
                <input type="text" name="package_name" id="package_name" placeholder="Enter Package Name" required class="w-100 mb-2">
                
                @if(count($exams) > 0)
                    @foreach($exams as $exam)
                        @php $id = $exam->id; @endphp
                        @if($exam->check_in_exists_package == false)
                            <input type="checkbox" name="exams[]" value="{{ $id }}" class="editexams">&nbsp;&nbsp;{{ $exam->exam_name }}<br>
                        @endif
                    @endforeach
                @endif
                <div class="package_exams">

                </div>

                <input type="date" name="expire" id="expire" min="{{ date('Y-m-d') }}" required class="w-100 mb-2">
                <input type="number" name="price_inr" id="price_inr" min="0" required placeholder="Price(INR)" class="w-45 mb-2">              
                <input type="number" name="price_usd" id="price_usd" min="0" required placeholder="Price(USD)" class="w-45 mb-2">  
                <p class="error m-0" style="color:red;"></p>            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
            </div>
        </form>
  </div>
</div>

<script>
    $(document).ready(function(){

        $('#addPackage').submit(function(event){

            var checked =  false;
            var lgt = $('.exams').length;

            for(let i = 0; i < lgt; i++){

                if($('.exams:eq('+i+')').prop('checked') == true){
                    checked = true;
                    break;
                }

            }

            if(checked == false){
                event.preventDefault();
                $('.error').text('Please select any one exam!');
            }

        });

        $('.deletePackage').click(function(){

            var obj = $(this);
            var id = $(this).attr('data-id');

            $.ajax({
                url:"{{ route('deletePackage') }}",
                type:"GET",
                data:{id:id},
                success:function(response) {
                    if(response){
                        $(obj).parent().parent().remove();
                        alert(response.msg);
                        location.reload();
                    }
                    else{
                        alert(response.msg);
                    }
                }
            });

        });

        $('.editPackage').click(function(){

            var obj = JSON.parse($(this).attr('data-obj'));

            $('#package_id').val(obj.id);
            $('#package_name').val(obj.name);

            var exams = obj.exam_id;
            var html = '';
            for(let i = 0; i< exams.length;i++){
                html +=`
                <input type="checkbox" name="exams[]" checked value="`+exams[i]['id']+`" class="editexams">&nbsp;&nbsp;`+exams[i]['exam_name']+`<br>
                `;
            }

            $('.package_exams').html(html);

            var price = JSON.parse(obj.price);

            $('#price_inr').val(price['INR']);
            $('#price_usd').val(price['USD']);
            $('#expire').val(obj.expire); min="{{ date('Y-m-d') }}"
        });


        $('#editPackage').submit(function(event){

            var checked =  false;
            var lgt = $('.editexams').length;

            for(let i = 0; i < lgt; i++){

                if($('.editexams:eq('+i+')').prop('checked') == true){
                    checked = true;
                    break;
                }

            }

            if(checked == false){
                event.preventDefault();
                $('.error').text('Please select any one exam!');
            }

        });

    });
</script>
      

@endsection