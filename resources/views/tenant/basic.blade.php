<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Basic Details
                </h2>
            </div>
            <div class="body have-mask">
                {{ Form::open(['method' => 'post', 'id' => 'basic-details']) }}
                <div class="row clearfix">
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::hidden('id', $model->id, [ 'id' => 'tenant_id' ]) }}
                                {{ Form::text('name', $model->name, [ 'class' => 'form-control', 'required' => true ]) }}
                                <label class="form-label">Name</label>
                            </div>
                            <label id="name-error" class="error" for="name"></label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('email', $model->email, [ 'class' => 'form-control email']) }}
                                <label class="form-label">e-mail</label>
                            </div>
                            <label id="email-error" class="error" for="email"></label>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('emirates_id', $model->emirates_id, [ 'class' => 'form-control emirates-id' , 'required' => true]) }}
                                <label class="form-label">Emirates ID</label>
                            </div>
                            <label id="emirates_id-error" class="error" for="emirates_id"></label>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('land_phone', $model->land_phone, [ 'class' => 'form-control']) }}
                                <label class="form-label">Phone</label>
                            </div>
                            <label id="land_phone-error" class="error" for="land_phone"></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('mobile', $model->mobile, [ 'class' => 'form-control mobile-phone-number', 'required' => true]) }}
                                <label class="form-label">Mobile</label>
                            </div>
                            <label id="mobile-error" class="error" for="mobile"></label>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('passport_number', $model->passport_number, [ 'class' => 'form-control']) }}
                                <label class="form-label">Passport Number</label>
                            </div>
                            <label id="passport_number-error" class="error" for="passport_number"></label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('trn_number', $model->trn_number, [ 'class' => 'form-control trn-no']) }}
                                <label class="form-label">TRN No.</label>
                            </div>
                            <label id="trn_number-error" class="error" for="trn_number"></label>
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
                url: '/tenant/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {

                        $('#tenant_id').val(response.tenant_id);
                        $('#doc_parent_id').val(response.tenant_id);
                        $('#parent_key').val(response.tenant_id_encrypted);

                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Tenant Saved!',
                            'success'
                        ).then((response) => {
                            location.href = "/tenant/index";
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
</script>