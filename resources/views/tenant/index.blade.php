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
                    <i class="material-icons">record_voice_over</i> Tenants
                </li>
            </ol>
            <div class="card">
                <div class="header">
                    <h2>
                        Tenants
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="/tenant/create"><i class="material-icons">add_circle</i> Create</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="tenant_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Emirates ID</th>
                                    <th>Passport</th>
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

<script>
    $(function() {
        $('#tenant_list').DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/tenant/list",
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

    function block_tenant(tenant_id, status) {
        var msg = status == 3 ? 'Block Tenant!' : 'Unblock Tenant!';
        Swal.fire({
            title: 'Are you sure?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '/tenant/status',
                    data: {
                        _ref: tenant_id,
                        status: status
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            Swal.fire(
                                'Updated!',
                                'Tenant updated!',
                                'success'
                            );
                            reload_datatable('#tenant_list');
                        } else {
                            Swal.fire(
                                'Failed!',
                                'Failed to update Status!',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    }
</script>

@endsection