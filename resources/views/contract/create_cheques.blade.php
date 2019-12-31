@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li>
                    <a href="/">
                        <i class="material-icons">home</i> Home
                    </a>
                </li>
                <li>
                    <a href="/contract/index">
                        <i class="material-icons">folder</i> Leasing & Contracts
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create Cheques
                </li>
            </ol>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Cheque Receipts for Contract # {{ $model->id  }}
                    </h2>
                </div>
                <div class="body have-mask">
                    {{ Form::open(['method' => 'post', 'id' => 'cheque-form']) }}
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('contract_id', $model->id) }}
                                    {{ Form::text('contract_amount', $model->gross_amount_wo_format(), [ 'class' => 'form-control', 'required' => true, 'id' => 'contract_amount' ]) }}
                                    <label class="form-label">Contract Amount</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover" id="cheque-items-table">
                            <thead>
                                <tr>
                                    <th style="width:2%">#</th>
                                    <th style="width:10%">Cheque Date</th>
                                    <th style="width:20%">Cheque No</th>
                                    <th style="width:30%">Bank</th>
                                    <th style="width:20%">Amount</th>
                                    <th style="width:2%"><a href="#" title="Add Row" onclick="addRow();"><i class="material-icons">add_circle</i></a></th>
                                </tr>
                            </thead>
                            <tbody id="receipt-tbody">
                                @foreach( [new \App\models\Entries()] as $i => $each )
                                <tr>
                                    <th><label>{{ $i+1 }}</label></th>
                                    <td>{{ Form::text('Entries['.$i.'][cheque_date]', '', [ 'class' => 'form-control datepicker', 'id' => 'Entries_'.$i.'_cheque_date', 'required' => true]) }}</td>
                                    <td>{{ Form::text('Entries['.$i.'][cheque_no]', '', [ 'class' => 'form-control', 'required' => true]) }}</td>
                                    <td>{{ Form::select('Entries['.$i.'][bank_id]', \App\models\Ledgers::childrenHaveClass(0,  \App\models\Ledgers::BANK_CHILD), 0, [ 'class' => 'form-control show-tick' ]) }}</td>
                                    <td>{{ Form::number('Entries['.$i.'][amount]', '', [ 'class' => 'form-control align-right', 'min' => 1, 'max' => 99999999, 'step'=> '.00001', 'onKeyup' => 'calculate(); checkMax(this.id);' , 'onBlur' => 'round_field(this.id)', 'id' => 'Entries_'.$i.'_amount', 'required' => true]) }}</td>
                                    <td><a href="#" title="Remove" id="Entries_{{ $i }}_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="warning">
                                    <th colspan="4" style="text-align:right">Total</th>
                                    <th><input type="text" name="total_value" id="total_value" class="form-control align-right" readonly="true" value="0.00"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="form-group align-right">
                        {{ Form::submit('Save', [ 'id' => 'basic_submit', 'class' => 'btn btn-danger'] )  }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#cheque-form').on('submit', function(e) {

            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/contract/cheques/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Receipts Saved!',
                            'success'
                        ).then(response => {
                            location.href = '/contract/index';
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

        });
    });

    function addRow() {
        last_field = $('#cheque-items-table').find('tbody').find('tr:last input').attr('id');
        last_id = last_field.match(/\d+/g);
        new_id = Number(last_id) + 1;
        newRow = $('#cheque-items-table').find('tbody').find('tr:last').clone();
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
            liveSearch: true,
            dropupAuto: false,
            size: 5
        });
        newRow.find(".datepicker").datepicker({
            autoclose: true,
            format: "dd/mm/yyyy"
        });
        $('#cheque-items-table').find('tbody').append(newRow);
    }

    function deleteRow(event) {
        var rowCount = $('#cheque-items-table').find('tr:gt(0)').length;
        if (rowCount > 2) {
            $(event).parents('tr').remove();
            var i = 0;
            $('#cheque-items-table').find('tr:gt(0)').each(function() {
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
        var total = 0;
        $("#cheque-items-table").find('tbody').find("tr").each(function() {
            total += +Number($(this).find("[id $=_amount]").val());
        });
        $("#total_value").val(roundNumber(total, 2).toFixed(2));
        return false;
    }

    function checkMax(fieldId) {
        var total = +$('#total_value').val();
        var max = +$('#contract_amount').val();
        if (total > max) {
            $('#' + fieldId).val('');
        }
        calculate();
    }
</script>

@endsection