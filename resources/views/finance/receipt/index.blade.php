@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ol class="breadcrumb">
                <li>
                    <a href="/">
                        <i class="material-icons">home</i> Home
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">euro_symbol</i> Receipts
                </li>
            </ol>
            <div class="card">
                <div class="header">
                    <h2>
                        Receipts
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="/finance/receipt/create"><i class="material-icons">add_circle</i> Create</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="receipt_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Contract #</th>
                                    <th>Cheque #</th>
                                    <th>Cheque Date</th>
                                    <th>Tenant</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
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
            data.contract =0;
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
                "targets": [8, 9],
                "visible": false,
            }],
            rowCallback: function(row, data) {
                if (data[8] == 0) {
                    $("td", row).css("text-decoration", "line-through");
                }
                if (data[9] == 1) {
                    $(row).addClass("danger");
                }
                if (data[9] == 100) {
                    $(row).addClass("warning");
                }
                if (data[9] == 99) {
                    $(row).addClass("success");
                }
            },
        });
    });
</script>

@endsection