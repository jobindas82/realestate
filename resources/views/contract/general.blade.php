<fieldset>
    <div class="row clearfix">
        <div class="col-sm-3">
            <div class="form-group form-float generated_date">
                <div class="form-line">
                    {{ Form::text('generated_date', $model->formated_generated_date(), [ 'class' => 'form-control datepicker', 'required']) }}
                    <label class="form-label">Contract Date</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float from_date">
                <div class="form-line tenant-set ">
                    {{ Form::text('from_date', $model->formated_from_date(), [ 'class' => 'form-control datepicker', 'required']) }}
                    <label class="form-label">Contract From</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float to_date">
                <div class="form-line tenant-set ">
                    {{ Form::text('to_date', $model->formated_to_date(), [ 'class' => 'form-control datepicker', 'required']) }}
                    <label class="form-label">Contract End</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::select('util_payment', [ 1 => 'Tenant', 2 => 'Landlord'], $model->util_payment, [ 'class' => 'form-control show-tick ajax-drop', 'id' => 'util_payment']) }}
                    <label class="form-label">Utility Payments</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="form-group form-float">
                <div class="form-line">
                    {{ Form::textarea('terms', $model->terms, [ 'class' => 'form-control no-resize auto-growth', 'rows' => 8]) }}
                    <label class="form-label">Terms & Conditions</label>
                </div>
            </div>
        </div>
    </div>

</fieldset>

<script>
    setTimeout(function() {
        $('#util_payment').selectpicker();
        $(".datepicker").datepicker({
            autoclose: true,
            format: "dd/mm/yyyy"
        });
    }, 500);
</script>