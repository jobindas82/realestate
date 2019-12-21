<fieldset>
    <div class="body table-responsive">
        <table class="table table-hover" id="contract-items-table">
            <thead>
                <tr>
                    <th style="width:2%">#</th>
                    <th style="width:30%">Particular</th>
                    <th style="width:15%">Amount</th>
                    <th style="width:10%">Tax(%)</th>
                    <th style="width:10%">Tax</th>
                    <th style="width:15%">Net Amount</th>
                    <th style="width:2%"><a href="#" title="Add Row" onclick="addRow();"><i class="material-icons">add_circle</i></a></th>
                </tr>
            </thead>
            <tbody>
                @foreach( $model->exists ? $model->items : [new \App\models\Tenants()] as $i => $each )
                <tr>
                    <th><label>{{ $i+1 }}</label></th>
                    <td>{{ Form::select('ContractItems['.$i.'][ledger_id]', \App\models\Ledgers::contractItems($each->ledger_id), $each->ledger_id, [ 'class' => 'contract-item form-control show-tick ajax-drop', 'required', 'id' => 'ContractItems_'.$i.'_ledger_id' ]) }}</td>
                    <td>{{ Form::number('ContractItems['.$i.'][amount]', $each->amount, [ 'class' => 'form-control', 'required', 'id' => 'ContractItems_'.$i.'_amount', 'min' => 0, 'max' => 999999999999999, 'onKeyup' => 'calculate();' , 'onBlur' => 'round_field(this.id)']) }}</td>
                    <td>
                        {{ Form::select('ContractItems['.$i.'][tax_id]', \App\models\TaxCode::activeCodes((int) $each->tax_id, true), $each->tax_id, [ 'class' => 'contract-item form-control show-tick ajax-drop', 'required', 'id' => 'ContractItems_'.$i.'_tax_id', 'min' => '1', 'onChange' => 'populatePercentage(this.id, this.value)' ]) }}
                        {{ Form::hidden('ContractItems['.$i.'][tax_percentage]', $each->tax_percentage, [ 'id' => 'ContractItems_'.$i.'_tax_percentage' ]) }}
                    </td>
                    <td>{{ Form::text('ContractItems['.$i.'][tax_amount]', $each->tax_amount, [ 'class' => 'form-control align-right', 'readonly' => true, 'id' => 'ContractItems_'.$i.'_tax_amount']) }}</td>
                    <td>{{ Form::text('ContractItems['.$i.'][net_amount]', $each->net_amount, [ 'class' => 'form-control align-right', 'readonly' => true, 'id' => 'ContractItems_'.$i.'_net_amount']) }}</td>
                    <td><a href="#" title="Remove" id="ContractItems_{{ $i }}_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="warning">
                    <th colspan="5" style="text-align:right">Tax Amount</th>
                    <th><input type="text" name="total_tax" id="total_tax" class="form-control align-right" readonly="true"></th>
                    <th></th>
                </tr>
                <tr class="warning">
                    <th colspan="5" style="text-align:right">Gross Amount</th>
                    <th><input type="text" name="gross_amount" id="gross_amount" class="form-control align-right" readonly="true"></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

</fieldset>

<script>
    $(function() {
        $('#contract-items-table').DataTable({
            paging: false,
            ordering: false,
            info: false,
            bFilter: false
        });
    });

    function addRow() {
        last_field = $('#contract-items-table').find('tbody').find('tr:last select').attr('id');
        last_id = last_field.match(/\d+/g);
        new_id = Number(last_id) + 1;
        newRow = $('#contract-items-table').find('tbody').find('tr:last').clone();
        newRow.find('label:first').html(new_id + 1);
        newRow.attr('class', new_id);
        newRow.find('.bootstrap-select').replaceWith(function() {
            return $('select', this);
        });
        newRow.find('div,input,textarea,checkbox,td,select,a,button').each(function() {
            this.id = this.id.replace(/\d+/, new_id);
            if (!$(this).is(':checkbox'))
                this.value = '';
            else
                $(this).prop('checked', false);
            (this.name !== undefined) ? this.name = this.name.replace(/\d+/, new_id): this.style = '';
        });
        newRow.find('select').selectpicker({
            liveSearch: true
        });
        $('#contract-items-table').find('tbody').append(newRow);
    }

    function deleteRow(event) {
        var rowCount = $('#contract-items-table').find('tr:gt(0)').length;
        if (rowCount > 3) {
            $(event).parents('tr').remove();
            var i = 0;
            $('#contract-items-table').find('tr:gt(0)').each(function() {
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

    function calculate() {


        var tax_percentage = 0;
        var amount = 0;
        var tax_amount = 0;
        var total_tax = 0;
        var net_amount = 0;
        var gross_amount = 0;

        $("#contract-items-table").find('tbody').find("tr").each(function() {

            amount = Number($(this).find("[id $=_amount]").val());
            tax_percentage = Number($(this).find("[id $=_tax_percentage]").val());

            amount = roundNumber(amount, 6);
            console.log(amount);
            tax_amount = (amount * tax_percentage) / 100;
            tax_amount = roundNumber(tax_amount, 6);

            net_amount = amount + tax_amount;

            $(this).find("[id $=_tax_amount]").val(tax_amount.toFixed(6));
            $(this).find("[id $=_net_amount]").val(net_amount.toFixed(6));

            total_tax += tax_amount;
            gross_amount += net_amount;
        });



        $("#total_tax").val(roundNumber(total_tax, 2).toFixed(2));
        $("#gross_amount").val(roundNumber(gross_amount, 2).toFixed(2));

        return false;
    }

    function populatePercentage(field_id, value) {
        var arr_field_id = field_id.split('_');
        var i = arr_field_id[1];
        $.ajax({
            type: "POST",
            data: {
                _ref: value
            },
            url: '/masters/tax/fetch',
            success: function(response) {
                $('#ContractItems_' + i + '_tax_percentage').val(response.value);
                calculate();
            }
        });
    }

    function round_field(field_id)
    {
        if (!isNaN($('#' + field_id).val())) {
            var value = Number($('#' + field_id).val());
            value = value >= 0 ? roundNumber(value, 6) : 0;
            $('#' + field_id).val(value.toFixed(6));
        }

    }

    setTimeout(function() {
        $('.contract-item').selectpicker({
            liveSearch: true
        });
        calculate();
    }, 600);
</script>