@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li>
                    <a href="/">
                        <i class="material-icons">home</i> Home
                    </a>
                </li>
                <li>
                    <a href="/building/index">
                        <i class="material-icons">settings</i> Buildings
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
            </ol>
        </div>
    </div>
    <!-- Input -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Basic Details
                    </h2>
                </div>
                <div class="body">
                    {{ Form::open(['method' => 'post', 'id' => 'basic-details']) }}
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('id', $model->id, [ 'id' => 'building_id' ]) }}
                                    {{ Form::text('name', $model->name, [ 'class' => 'form-control', 'required' => true ]) }}
                                    <label class="form-label">Name</label>
                                </div>
                                <label id="name-error" class="error" for="name"></label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('ownership', [ 'O' => 'Owned', 'L' => 'Leased'], $model->ownership, [ 'class' => 'form-control show-tick']) }}
                                    <label class="form-label">Ownership</label>
                                </div>
                                <label id="ownership-error" class="error" for="ownership"></label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('floor_count', $model->floor_count, [ 'class' => 'form-control']) }}
                                    <label class="form-label">Floors</label>
                                </div>
                                <label id="floor_count-error" class="error" for="floor_count"></label>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('owner_name', $model->owner_name, [ 'class' => 'form-control']) }}
                                    <label class="form-label">Owner</label>
                                </div>
                                <label id="owner_name-error" class="error" for="owner_name"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('landlord_name', $model->landlord_name, [ 'class' => 'form-control']) }}
                                    <label class="form-label">Landlord</label>
                                </div>
                                <label id="landlord_name-error" class="error" for="landlord_name"></label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('country_id', \App\models\Countries::activeTypes( $model->country_id, true), $model->country_id, [ 'class' => 'form-control show-tick', 'onChange' => 'locationDropdown(this.value)']) }}
                                    <label class="form-label">Country</label>
                                </div>
                                <label id="country_id-error" class="error" for="country_id"></label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">

                                    @php
                                    $locations = [];
                                    if( $model->exists )
                                    $locations = \App\models\Location::activeLocations($model->country_id, $model->location_id);
                                    @endphp

                                    <div id="location_drop_down_div">
                                        {{ Form::select('location_id', $locations, $model->location_id, [ 'class' => 'form-control show-tick']) }}
                                    </div>

                                    <label class="form-label">Location</label>
                                </div>
                                <label id="location_id-error" class="error" for="location_id"></label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('is_available', [1 => 'Active', 2 => 'Maintenance', 3 => 'Blocked'], $model->is_available, [ 'class' => 'form-control show-tick']) }}
                                    <label class="form-label">Active</label>
                                </div>
                                <label id="country_id-error" class="error" for="country_id"></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::textarea('address', $model->address, [ 'class' => 'form-control no-resize auto-growth', 'rows' => 1]) }}
                                    <label class="form-label">Address</label>
                                </div>
                                <label id="address-error" class="error" for="address"></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group align-right">
                        {{ Form::submit('Save', [ 'id' => 'basic_submit', 'class' => 'btn btn-danger'] )  }}
                    </div>


                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Input -->

    <!-- Input -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Depreciation Details
                    </h2>
                </div>
                <div class="body">
                    {{ Form::open(['method' => 'post', 'id' => 'depreciation-details']) }}
                    <div class="row clearfix">
                        <div class="col-sm-6">

                            <div class="form-group form-float">
                                <div class="form-line" id="datepicker_container">
                                    {{ Form::text('purchase_date', $model->formated_purchase_date(), [ 'class' => 'form-control', 'required' => true]) }}
                                    <label class="form-label">Purchased on</label>
                                </div>
                                <label id="purchase_date-error" class="error" for="purchase_date"></label>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('id', $model->id, [ 'id' => 'dep_building_id' ]) }}
                                    {{ Form::text('depreciation_percentage', $model->depreciation_percentage, [ 'class' => 'form-control', 'required' => true ]) }}
                                    <label class="form-label">Depreciation(%)</label>
                                </div>
                                <label id="depreciation_percentage-error" class="error" for="depreciation_percentage"></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group align-right">
                        {{ Form::submit('Save', [ 'id' => 'basic_submit_dep', 'class' => 'btn btn-danger'] )  }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Input -->

    <!-- Table -->
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="card">
                <div class="header">
                    <h2>
                        Documents
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#" onclick="window.open('/document/building/?_ref=__create_building&__uuid={{ $model->encoded_key() }}', '_blank');"><i class="material-icons">add_circle</i> Add</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="building_document_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>File name</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="card">
                <div class="header">
                    <h2>
                        Flats
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#"><i class="material-icons">add_circle</i> Add</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="building_flat_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Flat #</th>
                                    <th>ft<sup>2</sup></th>
                                    <th>Type</th>
                                    <th>Occupancy</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->
</div>


<script>
    $(document).ready(function() {

        $('#building_document_list').DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/building/get_documents",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [0, "asc"]
            ],
        });

        $('#basic-details').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/building/save/basic',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {

                        $('#building_id').val(response.building_id);
                        $('#dep_building_id').val(response.building_id);

                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Building Saved!',
                            'success'
                        );

                    } else {
                        $('.page-loader-wrapper').fadeOut();
                        $.each(response, function(fieldName, fieldErrors) {
                            $('#' + fieldName + '-error').text(fieldErrors.toString());
                            $('#' + fieldName + '-error').show();
                        });
                    }
                }
            });
        });

        $('#depreciation-details').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/building/save/depreciation',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {

                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Depreciation Details Saved!',
                            'success'
                        );

                    } else {
                        $('.page-loader-wrapper').fadeOut();
                        $.each(response, function(fieldName, fieldErrors) {
                            $('#' + fieldName + '-error').text(fieldErrors.toString());
                            $('#' + fieldName + '-error').show();
                        });
                    }
                }
            });
        });
    });


    function locationDropdown(country_id) {
        $.ajax({
            type: 'GET',
            url: '/masters/locations/' + country_id,
            success: function(response) {
                $('#location_drop_down_div').html(response);
            }
        });
    }
</script>
@endsection