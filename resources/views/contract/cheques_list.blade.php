@extends('layouts.simple')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Receipts for Contract # {{ $model->id }} | {{ $model->tenant->name }}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="receipt_list">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 10%">Date</th>
                                    <th style="width: 10%">Contract #</th>
                                    <th style="width: 10%">Cheque #</th>
                                    <th style="width: 10%">Cheque Date</th>
                                    <th style="width: 30%">Tenant</th>
                                    <th style="width: 10%;">Amount</th>
                                    <th style="width: 10%">Actions</th>
                                    <th>Posted</th>
                                    <th>Cancelled</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
</div>

<script>
    $(function() {
        $('#receipt_list').on("preXhr.dt", function(e, settings, data) {
            data.type = 1;
            data.contract = "{{ $model->id }}";
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/finance/receipt/list",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [1, "desc"]
            ],
            columnDefs: [{
                "targets": [2, 5, 8, 9],
                "visible": false,
            }],
            rowCallback: function(row, data) {
                if (data[8] == 0) {
                    $("td", row).css("text-decoration", "line-through");
                }
                if (data[9] == 1) {
                    $(row).addClass("danger");
                }
            },
        });
    });
</script>

@endsection