<fieldset>
    <div class="row clearfix">
        <div class="col-sm-4">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::select('country_id', [], '', [ 'class' => 'form-control show-tick ajax-drop', 'id' => 'tenant-drop-contract', 'required']) }}
                    <label class="form-label">Tenant</label>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::hidden('id', $model->id, [ 'id' => 'contract_id' ]) }}
                    {{ Form::text('emirates_id', $model->exists ? $model->tenant()->emirates_id : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_emirates_id' ]) }}
                    <label class="form-label">Emirates ID</label>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::text('email', $model->exists ? $model->tenant()->email : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_email' ]) }}
                    <label class="form-label">e-mail</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-4">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::text('passport_no', $model->exists ? $model->tenant()->passport_number : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_passport' ]) }}
                    <label class="form-label">Passport No.</label>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::text('phone_no', $model->exists ? $model->tenant()->land_phone : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_phone' ]) }}
                    <label class="form-label">Phone No.</label>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::text('mobile_no', $model->exists ? $model->tenant()->mobile : '', [ 'class' => 'form-control', 'readonly' => true, 'id' => 'contract_mobile' ]) }}
                    <label class="form-label">Mobile No</label>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<!-- Use the following Script to modify auto complete and other events -->
<script src="{{ asset('views/contract.js') }}"></script>