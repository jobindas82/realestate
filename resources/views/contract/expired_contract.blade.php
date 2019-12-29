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
            <td>{{ Form::number('ContractSettlement['.$i.'][amount]', NULL, [ 'class' => 'form-control',  'min' => 1, 'required' => true, 'id' => 'ContractSettlement_'.$i.'_amount', 'onKeyup' => 'calculateTotal();']) }}</td>
            <td><a href="#" title="Remove" id="ContractSettlement_{{ $i }}_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="warning">
            <td colspan="2" class="align-right font-bold"> Total</td>
            <td class="align-right font-bold" id="exp_total_td">{{ number_format($total, 2, '.', ',')  }}</td>
            <td></td>
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
                    url: '/contract/settlement/expired',
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

    function addRow() {
        last_field = $('#expired-contract-table').find('tbody').find('tr:last input').attr('id');
        last_id = last_field.match(/\d+/g);
        new_id = Number(last_id) + 1;
        newRow = $('#expired-contract-table').find('tbody').find('tr:last').clone();
        newRow.find('label:first').html(new_id + 1);
        newRow.attr('class', new_id);
        newRow.find('div,input,textarea,checkbox,td,select,a,button').each(function() {
            this.id = this.id.replace(/\d+/, new_id);
            if (!$(this).is(':checkbox'))
                this.value = '';
            else
                $(this).prop('checked', false);
            (this.name !== undefined) ? this.name = this.name.replace(/\d+/, new_id): this.style = '';
        });
        $('#expired-contract-table').find('tbody').append(newRow);
    }

    function deleteRow(event) {
        var rowCount = $('#expired-contract-table').find('tr:gt(0)').length;
        if (rowCount > 2) {
            $(event).parents('tr').remove();
            var i = 0;
            $('#expired-contract-table').find('tr:gt(0)').each(function() {
                $(this).find('div,input,textarea,checkbox,td,select,a,button').each(function() {
                    old_id = $(this).attr('id');
                    if (old_id) {
                        new_id = old_id.replace(/\d+/, i);
                        $(this).attr('id', new_id);
                        old_name = $(this).attr('name');
                        if (old_name !== undefined) {
                            new_name = old_name.replace(/\d+/, i);
                            $(this).attr('name', new_name);
                        }
                    }
                    old_data_id = $(this).attr('data-id');
                    if (old_data_id) {
                        new_data_id = old_data_id.replace(/\d+/, i);
                        $(this).attr('data-id', new_data_id);
                    }
                });
                $(this).find('label:first').html(++i);
                calculate();
            });


        } else {
            Swal.fire('At least one item needed here!!');
        }
    }

    function calculateTotal() {
        var total = 0;
        $("#expired-contract-table").find('tbody').find("tr").each(function() {
            total += +$(this).find('[id $=_amount]').val();
        });
        $('#exp_total_td').text(total.toFixed(2));
    }
</script>