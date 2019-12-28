<br>
<p class="font-bold font-underline col-cyan">Contract Details</p> <br>
{{ Form::open(['method' => 'post', 'id' => 'early-settlement-form']) }}
@include('contract.contract_summary')
<table class="table table-condensed table-hover" id="ese_cheque_list">
    <thead>
        <tr>
            <th style="width: 1%"></th>
            <th style="width: 10%">Receipt Date</th>
            <th style="width: 10%">Cheque Date</th>
            <th style="width: 20%">Cheque No.</th>
            <th style="width: 30%">Narration</th>
            <th class="align-right" style="width: 10%">Amount</th>
        </tr>
    </thead>
    <tbody>
        @if( $model->chequeReceipts()->count() > 0 )
        @foreach( $model->chequeReceipts() as $i => $eachReceipt )
        @php
        $statusClass = '';
        if( $eachReceipt->cheque_status == 1 ) ///Cleared
        $statusClass = 'success';
        if( $eachReceipt->cheque_status == 2 ) ///Returned
        $statusClass = 'danger';
        @endphp
        <tr class="{{ $statusClass }}">
            <td>{{ $eachReceipt->cheque_status == 0 ? Form::checkbox('Receipts[]', $eachReceipt->id, true, [ 'id'=>'Receipts_'.$i.'_checked', 'class' => 'no-style-checkbox', 'onchange' => 'calculateSum();' ]) : NULL }}</td>
            <td>{{ $eachReceipt->formated_date() }}</td>
            <td>{{ $eachReceipt->formated_cheque_date() }}</td>
            <td>{{ $eachReceipt->cheque_no }}</td>
            <td>{{ $eachReceipt->narration }}</td>
            <td class="align-right" id="Receipts_{{$i}}_amount">{{ number_format($eachReceipt->debitSum(false), 2, '.', '') }}</td>
        </tr>
        @php
        $total += $eachReceipt->cheque_status == 0 ? $eachReceipt->debitSum(false) : 0;
        @endphp
        @endforeach
        @else
        <tr>
            <td colspan="5" class="align-center">No Receipts Available</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="align-right font-bold"> Total</td>
            <td class="align-right font-bold" id="total_td">{{ number_format($total, 2, '.', ',')  }}</td>
        </tr>
    </tfoot>
</table>
<div class="form-group align-center">
    {{ Form::button('Close Contract', [ 'id' => 'basic_submit', 'class' => 'btn btn-danger', 'onclick' => 'save()'] )  }}
</div>
{{ Form::close() }}

<script>
    $(function() {
        $('#ese_cheque_list').DataTable({
            paging: false,
            info: false,
            dom: '<"toolbar">frtip',
            order: [
                [1, "asc"]
            ],
        });
        $("div.toolbar").html('<p class="font-bold font-underline col-cyan">Cheques Available</p>');
    });

    function save() {

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Close Contract!'
        }).then((result) => {
            if (result.value) {
                $('.error').hide();
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    type: "POST",
                    url: '/contract/settlement/early',
                    data: $('#early-settlement-form').serialize(),
                    success: function(response) {
                        if (response.message == 'success') {
                            $('.page-loader-wrapper').fadeOut();
                            Swal.fire(
                                'SUCCESS',
                                'Contract Closed!',
                                'success'
                            ).then((result) => {
                                location.href="/contract/index";
                            });
                        } else {
                            $('.page-loader-wrapper').fadeOut();
                            $.each(response, function(fieldName, fieldErrors) {
                                $('#' + fieldName + '-error').text(fieldErrors.toString());
                                $('#' + fieldName + '-error').show();
                            });
                        }
                    }
                });
            }
        });
    }

    function calculateSum() {
        var total = 0;
        $("#ese_cheque_list").find('tbody').find("tr").each(function() {
            if( $(this).find('[id $=_checked]').length > 0 )
                total += $(this).find('[id $=_checked]').prop('checked') ? +$(this).find('[id $=_amount]').text() : 0;
        });
        $('#total_td').text(total.toFixed(2));
    }
</script>