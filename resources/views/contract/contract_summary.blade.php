<div class="row clearfix">
    <div class="col-sm-4">
        <div class="form-group form-float">
            <div class="form-line">
                {{ Form::hidden('id', $model->id, [ 'id' => 'ese_contract_id' ]) }}
                {{ Form::text('tenant', $model->tenant->name, [ 'class' => 'form-control', 'readonly' => true ]) }}
                <label class="form-label">Tenant</label>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group form-float">
            <div class="form-line">
                {{ Form::text('building', $model->building->name, [ 'class' => 'form-control', 'readonly' => true ]) }}
                <label class="form-label">Building</label>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group form-float">
            <div class="form-line">
                {{ Form::text('flat', $model->flat->name, [ 'class' => 'form-control', 'readonly' => true ]) }}
                <label class="form-label">Flat</label>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-sm-3">
        <div class="form-group form-float">
            <div class="form-line">
                {{ Form::text('contract_no', $model->id, [ 'class' => 'form-control', 'readonly' => true ]) }}
                <label class="form-label">Contract #</label>
            </div>
            <label id="contract_no-error" class="error" for="contract_no"></label>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group form-float">
            <div class="form-line">
                {{ Form::text('from_date', $model->formated_from_date(), [ 'class' => 'form-control', 'readonly' => true ]) }}
                <label class="form-label">Contract From</label>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group form-float">
            <div class="form-line">
                {{ Form::text('to_date', $model->formated_to_date(), [ 'class' => 'form-control', 'readonly' => true ]) }}
                <label class="form-label">Contract To</label>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group form-float">
            <div class="form-line">
                {{ Form::text('gross_amount', $model->grossAmount(), [ 'class' => 'form-control', 'readonly' => true ]) }}
                <label class="form-label">Gross Amount</label>
            </div>
        </div>
    </div>
</div>