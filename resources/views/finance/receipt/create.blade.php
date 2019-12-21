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
                    <a href="/finance/receipt">
                        <i class="material-icons">euro_symbol</i> Receipts
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
                        Receipt @if( $model->exists ) {{ '# '.$model->number  }} @endif
                    </h2>
                </div>
                <div class="body have-mask">
                    {{ Form::open(['method' => 'post', 'id' => 'receipt-form']) }}
                    <div class="row clearfix">
                        <div class="col-sm-1">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('id', $model->id, [ 'id' => 'receipt_id' ]) }}
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
                        <div class="col-sm-1">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('contract_value', $model->exists ? $model->contract->grossAmount() : '0.00', [ 'class' => 'form-control align-right' , 'readonly' => true]) }}
                                    <label class="form-label">Contract Amount</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('tenant_id', $model->tenant_id, [ 'id' => 'tenant_id' ]) }}
                                    {{ Form::text('tenant_name', $model->exists ? $model->tenant->name : '', [ 'class' => 'form-control' , 'readonly' => true]) }}
                                    <label class="form-label">Tenant</label>
                                </div>
                                <label id="tenant_id-error" class="error" for="tenant_id"></label>

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::number('amount', $model->amount() , [ 'class' => 'form-control align-right', 'min' => 0, 'max' => 999999999]) }}
                                    <label class="form-label">Receipt Amount</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('method', \App\models\Head::METHOD, $model->method, [ 'class' => 'form-control show-tick', 'onchange' => 'method_visibility(this.value)']) }}
                                    <label class="form-label">Method</label>
                                </div>
                                <label id="method-error" class="error" for="method"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="cash_method_div" style="@if( $model->method == 1 || !$model->exists ) display: block; @else display: none; @endif">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('cash_account_id', \App\models\Ledgers::childrenHaveClass($model->cash_account(),  \App\models\Ledgers::CASH_CHILD), $model->cash_account(), [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
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
                                    {{ Form::select('cheque_account_id', \App\models\Ledgers::childrenHaveClass($model->cheque_account(),  \App\models\Ledgers::BANK_CHILD), $model->cheque_account(), [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
                                    <label class="form-label">Bank A/C</label>
                                </div>
                                <label id="bank_account_id-error" class="error" for="bank_account_id"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="bank_method_div" style="@if( $model->method == 3 ) display: block; @else display: none; @endif">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('bank_account_id', \App\models\Ledgers::childrenHaveClass($model->bank_account(),  \App\models\Ledgers::BANK_CHILD), $model->bank_account(), [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
                                    <label class="form-label">Bank A/C</label>
                                </div>
                                <label id="bank_account_id-error" class="error" for="bank_account_id"></label>
                            </div>
                        </div>
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
        $('#receipt-form').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/tenant/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {

                        $('#receipt_id').val(response.receipt_id);
                        $('#doc_parent_id').val(response.receipt_id);
                        $('#parent_key').val(response.receipt_id_encrypted);

                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Tenant Saved!',
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
</script>

@endsection