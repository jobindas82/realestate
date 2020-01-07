@extends('layouts.app')

@section('content')
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Balance Sheet
                </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line tenant-set ">
                                {{ Form::text('to_date', date('d/m/Y'), [ 'class' => 'form-control datepicker', 'id' => 'entry_to']) }}
                                <label class="form-label">From</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button class="btn btn-danger" onclick="reload_datatable('#asset_table_list');reload_datatable('#liability_table_list');">Load</button>
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
                    <div class="col-sm-6">
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover" id="asset_table_list">
                                <thead>
                                    <tr>
                                        <td colspan="2" class="align-center font-bold success">Total Assets</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 60%;">Ledger</th>
                                        <th style="width: 40%;">Amount</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="warning">
                                        <td class="align-right font-bold">Total</td>
                                        <td class="font-bold" id="asset_total">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover" id="liability_table_list">
                                <thead>
                                    <tr>
                                        <td colspan="2" class="align-center font-bold success">Total Equity & Liabilities</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 60%;">Ledger</th>
                                        <th style="width: 40%;">Amount</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="warning">
                                        <td class="align-right font-bold">Total</td>
                                        <td class="font-bold" id="liability_total">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group align-center">
                            <button class="btn btn-danger" href="#" onclick="window.open('/report/export/bs?to=' + $('#entry_to').val(), '_block');">
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
        $('#asset_table_list').on("preXhr.dt", function(e, settings, data) {
            data.to_date = $('#entry_to').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/finance/bs_asset",
                type: "POST",
                cache: false
            },
            drawCallback: function(settings) {
                $('#asset_total').text(settings.json.asset_total);
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            bSort: false,
            paging: false,
            searching: false,
            info:false
        });

        $('#liability_table_list').on("preXhr.dt", function(e, settings, data) {
            data.to_date = $('#entry_to').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/finance/bs_liability",
                type: "POST",
                cache: false
            },
            drawCallback: function(settings) {
                $('#liability_total').text(settings.json.liability_total);
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            bSort: false,
            paging: false,
            searching: false,
            info:false
        });
    });
</script>

@endsection