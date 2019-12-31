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
                                    {{ Form::select('location_id', $locations, $model->location_id, [ 'class' => 'form-control show-tick simple-dropdown']) }}
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

<script>
    $(document).ready(function() {
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
                        $('#flat_building_id').val(response.building_id);
                        $('#doc_parent_id').val(response.building_id);
                        $('#flat_key').val(response.building_id_encrypted);
                        $('#parent_key').val(response.building_id_encrypted);

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