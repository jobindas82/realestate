@extends('layouts.portal.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Leasing & Contracts
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="contract_list">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 10%">Building</th>
                                    <th style="width: 10%">Flat</th>
                                    <th style="width: 10%">From</th>
                                    <th style="width: 10%">To</th>
                                    <th style="width: 10%">Gross Amt.</th>
                                    <th style="width: 10%">Actions</th>
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
        $('#contract_list').on("preXhr.dt", function(e, settings, data) {
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/portal/list",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            paging: false,
            ordering: false,
            info: false,
            bFilter: false
        });
    });
</script>

@endsection