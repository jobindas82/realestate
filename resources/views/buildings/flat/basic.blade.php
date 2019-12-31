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
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::hidden('id', $model->id, ['id' => 'flat_id']) }}
                                {{ Form::text('name', $model->name, [ 'class' => 'form-control', 'required' => true ]) }}
                                <label class="form-label">Flat #</label>
                            </div>
                            <label id="name-error" class="error" for="name"></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('premise_id', $model->premise_id, [ 'class' => 'form-control' , 'required' => true]) }}
                                <label class="form-label">Premise #</label>
                            </div>
                            <label id="premise_id-error" class="error" for="premise_id"></label>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('plot_no', $model->plot_no, [ 'class' => 'form-control']) }}
                                <label class="form-label">Plot #</label>
                            </div>
                            <label id="plot_no-error" class="error" for="plot_no"></label>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('floor', $model->floor, [ 'class' => 'form-control']) }}
                                <label class="form-label">Floor</label>
                            </div>
                            <label id="floor-error" class="error" for="floor"></label>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('square_feet', $model->square_feet, [ 'class' => 'form-control']) }}
                                <label class="form-label">ft<sup>2</label>
                            </div>
                            <label id="square_feet-error" class="error" for="square_feet"></label>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::number('minimum_value', $model->minimum_value, [ 'class' => 'form-control']) }}
                                <label class="form-label">Min value</label>
                            </div>
                            <label id="minimum_value-error" class="error" for="minimum_value"></label>

                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('owner_name', $model->owner_name, [ 'class' => 'form-control']) }}
                                <label class="form-label">Owner</label>
                            </div>
                            <label id="owner_name-error" class="error" for="owner_name"></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('landlord_name', $model->landlord_name, [ 'class' => 'form-control']) }}
                                <label class="form-label">Landlord</label>
                            </div>
                            <label id="landlord_name-error" class="error" for="landlord_name"></label>
                        </div>
                    </div>
                    @if( $building_id > 0  )
                        {{ Form::hidden('building_id', $building_id) }}
                    @else
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('building_id', \App\models\Buildings::allBuildings(), $model->building_id, [ 'class' => 'form-control show-tick']) }}
                                <label class="form-label">Building</label>
                            </div>
                            <label id="building_id-error" class="error" for="building_id"></label>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('construction_type_id', \App\models\ConstructionTypes::activeConstruction( $model->construction_type_id), $model->construction_type_id, [ 'class' => 'form-control show-tick']) }}
                                <label class="form-label">Construction type</label>
                            </div>
                            <label id="construction_type_id-error" class="error" for="construction_type_id"></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('flat_type_id', \App\models\FlatTypes::activeTypes( $model->flat_type_id), $model->flat_type_id, [ 'class' => 'form-control show-tick']) }}
                                <label class="form-label">Flat type</label>
                            </div>
                            <label id="flat_type_id-error" class="error" for="flat_type_id"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group align-right">
                    <button class="btn btn-info" onclick="window.close();"> Close</button>
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
                url: '/building/flat/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {
                        
                        $('#flat_id').val(response.flat_id);
                        $('#doc_parent_id').val(response.flat_id);
                        $('#parent_key').val(response.flat_id_encoded);

                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Flat Saved!',
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
</script>