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
                    <a href="/finance/payment">
                        <i class="material-icons">euro_symbol</i> Payments
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
            </ol>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Payment @if( $model->exists ) {{ '# '.$model->number  }} @endif
                    </h2>
                </div>
                <div class="body have-mask">
                    {{ Form::open(['method' => 'post', 'id' => 'payment-form']) }}
                    <div class="row clearfix">
                        <div class="col-sm-1">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('id', $model->id, [ 'id' => 'payment_id' ]) }}
                                    {{ Form::text('date', $model->exists ? $model->formated_date() : date('d/m/Y'), [ 'class' => 'form-control datepicker', 'required' => true ]) }}
                                    <label class="form-label">Entry Date</label>
                                </div>
                                <label id="date-error" class="error" for="date"></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('contract_id', \App\models\Contracts::activeContracts($model->contract_id, true), $model->contract_id, [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
                                    <label class="form-label">Contract #</label>
                                </div>
                                <label id="contract_id-error" class="error" for="contract_id"></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('method', \App\models\Head::METHOD, $model->method, [ 'class' => 'form-control show-tick', 'onchange' => 'method_visibility(this.value)']) }}
                                    <label class="form-label">Method</label>
                                </div>
                                <label id="method-error" class="error" for="method"></label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::textarea('narration', $model->narration, [ 'class' => 'form-control no-resize auto-growth', 'rows' => 1]) }}
                                    <label class="form-label">Narration</label>
                                </div>
                                <label id="narration-error" class="error" for="narration"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="cash_method_div" style="@if( $model->method == 1 || !$model->exists ) display: block; @else display: none; @endif">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    @php
                                        $creditLedger = $model->exists ? $model->entries()->where('amount', '<', 0)->first()->ledger_id : 0;
                                    @endphp
                                    {{ Form::select('cash_account_id', \App\models\Ledgers::childrenHaveClass(0,  \App\models\Ledgers::CASH_CHILD), $creditLedger, [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
                                    <label class="form-label">Cash A/C</label>
                                </div>
                                <label id="cash_account_id-error" class="error" for="cash_account_id"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="cheque_method_div" style="@if( $model->method == 2 ) display: block; @else display: none; @endif">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('cheque_no', $model->cheque_no, [ 'class' => 'form-control' ]) }}
                                    <label class="form-label">Cheque No</label>
                                </div>
                                <label id="cheque_no-error" class="error" for="cheque_no"></label>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('cheque_date', $model->formated_cheque_date() , [ 'class' => 'form-control datepicker' ]) }}
                                    <label class="form-label">Cheque Date</label>
                                </div>
                                <label id="cheque_date-error" class="error" for="cheque_date"></label>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('cheque_account_id', \App\models\Ledgers::childrenHaveClass(0,  \App\models\Ledgers::BANK_CHILD), $creditLedger, [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
                                    <label class="form-label">Bank A/C</label>
                                </div>
                                <label id="cheque_account_id-error" class="error" for="cheque_account_id"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="bank_method_div" style="@if( $model->method == 3 ) display: block; @else display: none; @endif">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('bank_account_id', \App\models\Ledgers::childrenHaveClass(0,  \App\models\Ledgers::BANK_CHILD), $creditLedger, [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
                                    <label class="form-label">Bank A/C</label>
                                </div>
                                <label id="bank_account_id-error" class="error" for="bank_account_id"></label>
                            </div>
                        </div>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover" id="payment-items-table">
                            <thead>
                                <tr>
                                    <th style="width:2%">#</th>
                                    <th style="width:30%">Ledger</th>
                                    <th style="width:30%">Amount</th>
                                    <th style="width:2%"><a href="#" title="Add Row" onclick="addRow();"><i class="material-icons">add_circle</i></a></th>
                                </tr>
                            </thead>
                            <tbody id="receipt-tbody">
                                @php
                                $totalAmount = 0;
                                @endphp
                                @foreach( $model->exists ? $model->entries()->where('amount', '>', 0)->get() : [new \App\models\Entries()] as $i => $each )
                                @php
                                $amount = number_format((float) $each->amount, 6, '.', '');
                                $totalAmount += $amount;
                                @endphp
                                <tr>
                                    <th><label>{{ $i+1 }}</label></th>
                                    <td>{{ Form::select('Entries['.$i.'][ledger_id]', \App\models\Ledgers::children($each->ledger_id), $each->ledger_id, [ 'class' => 'form-control show-tick', 'required' => true, 'id' => 'Entries_'.$i.'_ledger_id' ]) }}</td>
                                    <td>{{ Form::number('Entries['.$i.'][amount]', $amount, [ 'class' => 'form-control align-right', 'required' => true, 'id' => 'Entries_'.$i.'_amount', 'min' => 1, 'max' => 999999999999999, 'step'=> '.0000001', 'onKeyup' => 'calculate();' , 'onBlur' => 'round_field(this.id)']) }}</td>
                                    <td><a href="#" title="Remove" id="Entries_{{ $i }}_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="warning">
                                    <th colspan="2" style="text-align:right">Total</th>
                                    <th><input type="text" name="total_value" id="total_value" class="form-control align-right" readonly="true" value="{{ number_format(round($totalAmount,  2), 2, '.', '') }}"></th>
                                    <th></th>
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
        $('#payment-form').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/finance/payment/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {

                        $('#payment_id').val(response.payment_id);
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Payment Saved!',
                            'success'
                        );

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

    function method_visibility(value) {
        $('#cheque_method_div').hide();
        $('#bank_method_div').hide();
        $('#cash_method_div').hide();
        if (value == 1) {
            $('#cash_method_div').show();
        } else if (value == 2) {
            $('#cheque_method_div').show();
        } else {
            $('#bank_method_div').show();
        }
    }

    function addRow() {
        last_field = $('#payment-items-table').find('tbody').find('tr:last select').attr('id');
        last_id = last_field.match(/\d+/g);
        new_id = Number(last_id) + 1;
        newRow = $('#payment-items-table').find('tbody').find('tr:last').clone();
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
        $('#payment-items-table').find('tbody').append(newRow);
    }

    function deleteRow(event) {
        var rowCount = $('#payment-items-table').find('tr:gt(0)').length;
        if (rowCount > 2) {
            $(event).parents('tr').remove();
            var i = 0;
            $('#payment-items-table').find('tr:gt(0)').each(function() {
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

        var totalAmount = 0;

        $("#payment-items-table").find('tbody').find("tr").each(function() {
            totalAmount += +Number($(this).find("[id $=_amount]").val());
        });


        $("#total_value").val(roundNumber(totalAmount, 2).toFixed(2));
        return false;
    }
</script>

@endsection