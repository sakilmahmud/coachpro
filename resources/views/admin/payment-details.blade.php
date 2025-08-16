@extends('layout/admin-layout')

@section('space-work')

    <h2 class="mb-4">Payment Details</h2>

    <table class="table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Exam Name</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @if(count($paymentDetails) > 0)
                @php $x=0; @endphp
                @foreach($paymentDetails as $payment)
                    <tr>
                        <td>{{ ++$x }}</td>
                        <td>{{ $payment->user[0]['name'] }}</td>
                        <td>{{ $payment->exam[0]['exam_name'] }}</td>
                        <td>
                            <a href="#" class="showDetails" data-details="{{ $payment->payment_details }}" data-toggle="modal" data-target="#showDetailsModal">Details</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">No payments found!</td>
                </tr>
            @endif
        </tbody>
    </table>

<!-- Modal -->
<div class="modal fade" id="showDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="paymentDetails"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        $('.showDetails').click(function(){
            var details = $(this).attr('data-details');
            details = JSON.parse(details);
            var html = '';
            Object.keys(details).map((key) => (
                html += '<p><b>'+key+':- </b>'+details[key]+'</p>'
            ));
            $('.paymentDetails').html(html);
        });
    });
</script>

@endsection