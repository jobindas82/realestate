@extends('layouts.app')

@section('content')
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Cheque Report
                </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line tenant-set ">
                                {{ Form::text('from_date', date('01/01/Y'), [ 'class' => 'form-control datepicker', 'id' => 'entry_from']) }}
                                <label class="form-label">From</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line tenant-set ">
                                {{ Form::text('to_date', date('d/m/Y'), [ 'class' => 'form-control datepicker', 'id' => 'entry_to']) }}
                                <label class="form-label"> To</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('type', [ 1 => 'Receivable', 2 => 'Payable'], 0, [ 'class' => 'form-control show-tick', 'id' => 'type' ]) }}
                                <label class="form-label">Type</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('status', [ 0 => 'All', 1 => 'Cleared', 2 => 'Returned' ], 0, [ 'class' => 'form-control show-tick', 'id' => 'status' ]) }}
                                <label class="form-label">Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button class="btn btn-danger" onclick="reload_datatable('#cheque_table_list')">Load</button>
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
                            <table class="table table-condensed table-hover" id="cheque_table_list">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cheque Date</th>
                                        <th>Cheque No.</th>
                                        <th>Contract #</th>
                                        <th>Tenant</th>
                                        <th>Narration</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group align-center">
                            <button class="btn btn-danger" href="#" onclick="window.open('/report/export/cheque?from=' + $('#entry_from').val() + '&to=' + $('#entry_to').val() + '&status=' + $('#status').val() + '&type=' + $('#type').val(), '_block');">
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
        $('#cheque_table_list').on("preXhr.dt", function(e, settings, data) {
            data.from_date = $('#entry_from').val();
            data.to_date = $('#entry_to').val();
            data.status = $('#status').val();
            data.type = $('#type').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/finance/cheque",
                type: "POST",
                cache: false
            },
            serverSide: true,
            fixedColumns: true,
            processing: true
        });
    });
</script>

@endsection