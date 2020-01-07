@extends('layouts.app')

@section('content')
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Tax Report
                </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line tenant-set ">
                                {{ Form::text('from_date', date('01/01/Y'), [ 'class' => 'form-control datepicker', 'id' => 'entry_from']) }}
                                <label class="form-label">From</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line tenant-set ">
                                {{ Form::text('to_date', date('d/m/Y'), [ 'class' => 'form-control datepicker', 'id' => 'entry_to']) }}
                                <label class="form-label"> To</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button class="btn btn-danger" onclick="reload_datatable('#tax_report')">Load</button>
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
                            <table class="table table-condensed table-hover" id="tax_report">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Number</th>
                                        <th>Contract #</th>
                                        <th>Tenant</th>
                                        <th>Building</th>
                                        <th>Flat</th>
                                        <th>Tax Amount</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="warning">
                                        <td class="align-right font-bold" colspan="7">Tax on Sales</td>
                                        <td class="font-bold" id="tax_on_sales">0.00</td>
                                    </tr>
                                    <tr class="warning">
                                        <td class="align-right font-bold" colspan="7">Tax on Purchase</td>
                                        <td class="font-bold" id="tax_on_purchase">0.00</td>
                                    </tr>
                                    <tr class="warning">
                                        <td class="align-right font-bold" colspan="7">Tax on Expense</td>
                                        <td class="font-bold" id="tax_on_expense">0.00</td>
                                    </tr>
                                    <tr class="warning">
                                        <td class="align-right font-bold" colspan="7">Tax Payable</td>
                                        <td class="font-bold" id="tax_payable">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group align-center">
                            <button class="btn btn-danger" href="#" onclick="window.open('/report/export/tax?from=' + $('#entry_from').val() + '&to=' + $('#entry_to').val(), '_block');">
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
        $('#tax_report').on("preXhr.dt", function(e, settings, data) {
            data.from_date = $('#entry_from').val();
            data.to_date = $('#entry_to').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/finance/tax",
                type: "POST",
                cache: false
            },
            drawCallback: function(settings) {
                $('#tax_on_sales').text(settings.json.tax_sales);
                $('#tax_on_purchase').text(settings.json.tax_purchase);
                $('#tax_on_expense').text(settings.json.tax_expense);
                $('#tax_payable').text(settings.json.tax_payable);
            },
            serverSide: true,
            fixedColumns: true,
            processing: true
        });
    });
</script>

@endsection