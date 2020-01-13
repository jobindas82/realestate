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
                    <i class="material-icons">folder</i> Leasing & Contracts
                </li>
            </ol>
            <div class="card">
                <div class="header">
                    <h2>
                        Leasing & Contracts
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li>
                            <a title="All Contracts" href="javascript:void(0);" onclick="$('#contact_status').val(''); reload_datatable('#contract_list');" title="Refresh">
                                <i class="material-icons">done_all</i>
                            </a>
                        </li>
                        <li>
                            <a title="Active Contracts" href="javascript:void(0);" onclick="$('#contact_status').val(1); reload_datatable('#contract_list');" title="Refresh">
                                <i class="material-icons">done</i>
                            </a>
                        </li>
                        <li>
                            <a title="Closed Contracts" href="javascript:void(0);" onclick="$('#contact_status').val(0); reload_datatable('#contract_list');" title="Refresh">
                                <i class="material-icons">close</i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <input type="hidden" name="contact_status" id="contact_status" value="1">
                                <li><a href="/contract/create"><i class="material-icons">add_circle</i> Create</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#contractExportModal"><i class="material-icons">archive</i> Export</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="contract_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tenant</th>
                                    <th>Building</th>
                                    <th>Flat</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Gross Amt.</th>
                                    <th>Status</th>
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



<div class="modal fade" id="contractExportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export</h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="contracts_from" id="contracts_from" class="form-control datepicker" value="{{ date('01/01/Y') }}">
                                <label class="form-label">Contracts From</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="contracts_to" id="contracts_to" class="form-control datepicker" value="{{ date('31/12/Y') }}">
                                <label class="form-label">Contracts To</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <select name="contract_status" id="contract_status" class="form-control">
                                    <option value="2">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Closed</option>
                                </select>
                                <label class="form-label">Status</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" onclick="window.open('/contract/pdf/?filter=' + $('#contract_status').val() + '&from=' + $('#contracts_from').val() + '&to=' + $('#contracts_to').val() , '_blank')">PDF</button>
                <button type="button" class="btn btn-link waves-effect" onclick="window.open('/contract/excel/?filter=' + $('#contract_status').val() + '&from=' + $('#contracts_from').val() + '&to=' + $('#contracts_to').val(), '_blank')">EXCEL</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {
        $('#contract_list').on("preXhr.dt", function(e, settings, data) {
            data.status = $('#contact_status').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/contract/list",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [1, "asc"]
            ],
        });
    });
</script>

@endsection