@extends('layouts.portal.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Tickets
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-hover" id="ticket_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Contract #</th>
                                    <th>Details</th>
                                    <th>Status</th>
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
        $('#ticket_list').DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/portal/tickets/list",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [1, "asc"]
            ]
        });
    });
</script>
@endsection