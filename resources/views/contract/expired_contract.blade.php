@if( $model->securityDeposit(false) > 0 )
<div class="alert alert-warning">
    A Security Deposit of {{ $model->securityDeposit() }} have in this Contract! <a href="#" onclick="window.open('/contract/export/{{ $model->encoded_key() }}', '_blank')" class="alert-link">click here to view contract</a>.
</div>
@endif
<p class="font-bold font-underline col-cyan">Contract Details</p> <br>
{{ Form::open(['method' => 'post', 'id' => 'expired-settlement-form']) }}
@include('contract.contract_summary')
<p class="font-bold font-underline col-cyan">Damage & Others</p>
<table class="table table-condensed table-hover" id="expired-contract-table">
    <thead>
        <tr>
            <th style="width: 1%">#</th>
            <th style="width: 80%">Remarks</th>
            <th style="width: 10%">Amount</th>
            <th style="width:2%"><a href="#" title="Add Row" onclick="addRow();"><i class="material-icons">add_circle</i></a></th>
        </tr>
    </thead>
    <tbody>
        @foreach([new \App\models\Tenants()] as $i => $each )
        <tr>
            <th><label>{{ $i+1 }}</label></th>
            <td>{{ Form::text('ContractSettlement['.$i.'][remarks]', NULL, [ 'class' => 'form-control', 'id' => 'ContractSettlement_'.$i.'_remarks', 'required' => true]) }}</td>
            <td>{{ Form::number('ContractItems['.$i.'][amount]', NULL, [ 'class' => 'form-control',  'min' => 1, 'required' => true, 'id' => 'ContractSettlement_'.$i.'_amount', 'onKeyup' => 'calculate();']) }}</td>
            <td><a href="#" title="Remove" id="ContractItems_{{ $i }}_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="align-right font-bold"> Total</td>
            <td class="align-right font-bold" id="total_td">{{ number_format($total, 2, '.', ',')  }}</td>
        </tr>
    </tfoot>
</table>
<div class="form-group align-center">
    {{ Form::button('Close Contract', [ 'id' => 'exp-contract-submit', 'class' => 'btn btn-danger', 'onclick' => 'vacateFlat()'] )  }}
</div>
{{ Form::close() }}

<script>
    function vacateFlat() {

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
                    data: $('#expired-settlement-form').serialize(),
                    success: function(response) {
                        if (response.message == 'success') {
                            $('.page-loader-wrapper').fadeOut();
                            Swal.fire(
                                'SUCCESS',
                                'Contract Closed!',
                                'success'
                            ).then((result) => {
                                location.href = "/contract/index";
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
</script>