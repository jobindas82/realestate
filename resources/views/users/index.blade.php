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
                        <i class="material-icons">group</i> Users
                    </li>
                </ol>
                <div class="card">
                    <div class="header">
                        <h2>
                            USERS
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="/users/create"><i class="material-icons" >add_circle</i> Create</a></li>
                                    <li><a href="#" onclick="$('#showType').val('AC'); reload_datatable('#user_list');"><i class="material-icons" >done</i> Active</a></li>
                                    <li><a href="#" onclick="$('#showType').val('BL'); reload_datatable('#user_list');"><i class="material-icons" >report</i> Blocked</a></li>
                                    <li><a href="#" onclick="$('#showType').val('ALL'); reload_datatable('#user_list');"><i class="material-icons" >group</i> All</a></li>

                                    <input type="hidden" id="showType" value="AC" />
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" id="user_list">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Belongs to</th>
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
        $(function () {
            $('#user_list').on("preXhr.dt", function (e, settings, data) {
                data.filter_type = $('#showType').val();
                return data;
            }).DataTable({
                responsive: true,
                // scrollY         : "500px",
                pageLength      : 50,
                ajax: {
                    url: "/users/getlist",
                    type: "POST",
                    cache : false,
                },
                serverSide:     true,
                fixedColumns:   true,
                processing: true,
                order: [[ 1, "desc" ]],
            });
        });
    </script>

@endsection
