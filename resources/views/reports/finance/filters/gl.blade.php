@extends('layouts.app')

@section('content')
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    General Ledger
                </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('ledger_id', \App\models\Ledgers::allChildren(), '', [ 'class' => 'form-control show-tick', 'id' => 'ledger_id' ]) }}
                                <label class="form-label">Ledger</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line tenant-set ">
                                {{ Form::text('from_date', date('01/m/Y'), [ 'class' => 'form-control datepicker', 'id' => 'entry_from']) }}
                                <label class="form-label">From</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line tenant-set ">
                                {{ Form::text('to_date', date('d/m/Y'), [ 'class' => 'form-control datepicker', 'id' => 'entry_to']) }}
                                <input type="hidden" name="contract_id" id="contract_id" value="0">
                                <label class="form-label"> To</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button class="btn btn-danger" onclick="reload_datatable('#general_ledger_details')">Load</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover" id="general_ledger_details">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Number</th>
                                        <th>Cheque #</th>
                                        <th>Cheque Date</th>
                                        <th>Narration</th>
                                        <th>Dr.</th>
                                        <th>Cr.</th>
                                        <th>Running Balance</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="warning">
                                        <td colspan="6" class="align-right font-bold">Total</td>
                                        <td class="font-bold" id="debit_sum">0.00</td>
                                        <td class="font-bold" id="credit_sum">0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr class="warning">
                                        <td colspan="8" class="align-right font-bold">Closing Balance</td>
                                        <td class="font-bold" id="closing_balance">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group align-center">
                            <button class="btn btn-danger" href="#" onclick="window.open('/report/export/gl?from=' + $('#entry_from').val() + '&to=' + $('#entry_to').val() + '&ledger=' + $('#ledger_id').val() + '&contract=' + $('#contract_id').val(), '_block');">
                                Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Input -->

<script>
    $(function() {
        $('#general_ledger_details').on("preXhr.dt", function(e, settings, data) {
            data.ledger_id = $('#ledger_id').val();
            data.from_date = $('#entry_from').val();
            data.to_date = $('#entry_to').val();
            data.contract_id = $('#contract_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/finance/gl",
                type: "POST",
                cache: false
            },
            drawCallback: function(settings) {
                $('#closing_balance').text(settings.json.closing_balance);
                $('#debit_sum').text(settings.json.debit_sum);
                $('#credit_sum').text(settings.json.credit_sum);
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            bSort: false,
            searching: false
        });
    });
</script>

@endsection