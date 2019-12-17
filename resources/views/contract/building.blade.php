<fieldset>
    <div class="row clearfix">
        <div class="col-sm-3">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::select('building_id', \App\models\Buildings::activeBuildings((int) $model->building_id ), $model->building_id, [ 'class' => 'form-control show-tick ajax-drop', 'required', 'id' => 'building_drop', 'onchange' => 'populateFlats(this.value)' , 'min' => '1']) }}
                    <label class="form-label">Building</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float flat_id">
                <div class="form-line">
                    <div id="contract_flat_div">
                        {{ Form::select('flat_id', \App\models\Flats::activeFlats($model->building_id, (int) $model->flat_id ), $model->flat_id, [ 'class' => 'form-control show-tick ajax-drop', 'required', 'id' => 'flat_drop', 'min' => '1', 'onchange' => 'populate_flat_details(this.value)' ]) }}
                    </div>
                    <label class="form-label">Flat</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float">
                <div class="form-line building-set ">
                    {{ Form::text('premise_id', $model->exists ? $model->flat->premise_id : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_premise' ]) }}
                    <label class="form-label">Premise ID</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float">
                <div class="form-line building-set ">
                    {{ Form::text('floor', $model->exists ? $model->flat->floor : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_floor' ]) }}
                    <label class="form-label">Floor</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-sm-3">
            <div class="form-group form-float">
                <div class="form-line building-set ">
                    {{ Form::text('square_feet', $model->exists ? $model->flat->square_feet : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_square_feet' ]) }}
                    <label class="form-label">ft<sup>2</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float">
                <div class="form-line building-set ">
                    {{ Form::text('construction_type', $model->exists ? $model->flat->construction->name : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_construction_type' ]) }}
                    <label class="form-label">Construction</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float">
                <div class="form-line building-set ">
                    {{ Form::text('flat_type', $model->exists ? $model->flat->flat_type->name : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_flat_type' ]) }}
                    <label class="form-label">Type</label>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<script>
    setTimeout(function() {
        $('#building_drop').selectpicker({  liveSearch: true });
        $('#flat_drop').selectpicker({  liveSearch: true });
    }, 500);

    function populateFlats(building_id) {
        $.ajax({
            type: 'GET',
            url: '/building/flats/' + building_id,
            success: function(response) {
                $('#contract_flat_div').html(response);
                $('#flat_drop').selectpicker({  liveSearch: true });

                $('#contract_premise').val('');
                $('#contract_floor').val('');
                $('#contract_square_feet').val('');
                $('#contract_construction_type').val('');
                $('#contract_flat_type').val('');

                $('.building-set').removeClass('focused');
            }
        });
    }

    function populate_flat_details(flat_id){
        $.ajax({
            type: 'POST',
            url: '/flat/fetch',
            data : { _ref : flat_id },
            success: function(response) {
               if( response.status == 'success' ){
    
                $('#contract_premise').val(response.premise);
                $('#contract_floor').val(response.floor);
                $('#contract_square_feet').val(response.square_feet);
                $('#contract_construction_type').val(response.construction_type);
                $('#contract_flat_type').val(response.flat_type);

                $('.building-set').addClass('focused');
               }else{
                    $('#contract_premise').val('');
                    $('#contract_floor').val('');
                    $('#contract_square_feet').val('');
                    $('#contract_construction_type').val('');
                    $('#contract_flat_type').val('');

                    $('.building-set').removeClass('focused');
               }
            }
        });
    }
</script>