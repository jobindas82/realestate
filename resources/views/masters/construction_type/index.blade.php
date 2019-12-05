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
                    <i class="material-icons">settings</i> Construction Types
                </li>
            </ol>
            <div class="card">
                <div class="header">
                    <h2>
                        Construction Types
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="/masters/construction/create"><i class="material-icons">add_circle</i> Create</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="construction_type_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Tax Percentage</th>
                                    <th>Actions</th>
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
        $('#construction_type_list').on("preXhr.dt", function(e, settings, data) {
            data.filter_type = $('#showType').val();
            return data;
        }).DataTable({
            responsive: true,
            // scrollY         : "500px",
            pageLength: 50,
            ajax: {
                url: "/masters/construction/getlist",
                type: "POST",
                cache: false,
            },
            columns: [{
                    className: "nw sl col-max-1"
                },
                {
                    className: "nw sl col-max-2"
                },
                {
                    className: "nw sl col-max-2"
                },
                {
                    className: "nw sl col-max-1"
                }
            ],
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [0, "asc"]
            ],
        });
    });
</script>

@endsection