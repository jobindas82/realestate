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
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    {{ Form::open(['method' => 'post', 'id' => 'basic-details']) }}
                    <div class="row clearfix">
                        <div class="col-sm-5">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('id', $model->id) }}
                                    {{ Form::text('name', $model->name, [ 'class' => 'form-control']) }}
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
                                <div class="form-line" id="datepicker_container">
                                    {{ Form::text('purchase_date', $model->formated_purchase_date(), [ 'class' => 'form-control']) }}
                                    <label class="form-label">Purchased on</label>
                                </div>
                                <label id="purchase_date-error" class="error" for="purchase_date"></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('owner_name', $model->owner_name, [ 'class' => 'form-control']) }}
                                    <label class="form-label">Owner Name</label>
                                </div>
                                <label id="owner_name-error" class="error" for="owner_name"></label>
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
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('country_id', \App\models\Countries::activeTypes( $model->country_id, true), $model->country_id, [ 'class' => 'form-control show-tick', 'onChange' => 'locationDropdown(this.value, this.name)']) }}
                                    <label class="form-label">Country</label>
                                </div>
                                <label id="country_id-error" class="error" for="country_id"></label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-float">
                                <div class="form-line" id="location_drop_down_div">
                                    
                                    @php
                                        $locations = [];
                                        if( $model->exists ) 
                                            $locations = \App\models\Location::activeLocations($model->country_id, $model->location_id);
                                    @endphp

                                    {{ Form::select('location_id', $locations, $model->location_id, [ 'class' => 'form-control show-tick', 'id' => 'location_drop']) }}
                                    <label class="form-label">Location</label>
                                </div>
                                <label id="location_id-error" class="error" for="location_id"></label>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Input -->
</div>


<script>
    $(document).ready(function() {
        $('#general-form').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/masters/country/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Country saved!',
                            'success'
                        ).then(function() {
                            // when click ok then redirect back
                            location.href = "/masters/country/index";
                        });
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


    function locationDropdown(country_id, name) {
        $.ajax({
            type: "GET",
            url: '/masters/locations/' + country_id,
            success: function(response) {
                var len = 0;
                if (response['data'] != null) {
                    len = response['data'].length;
                }
                if (len > 0) {
                    // Read data and create <option >
                    for (var i = 0; i < len; i++) {

                        var id = response['data'][i].id;
                        var name = response['data'][i].name;

                        var option = "<option value='" + id + "'>" + name + "</option>";
                       
                        $("#location_drop").append(option);
                    }
                }
            }
        });
    }
</script>
@endsection