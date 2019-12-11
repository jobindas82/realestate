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
                                <div class="form-line">
                                    {{ Form::text('purchase_date', $model->formated_purchase_date(), [ 'class' => 'form-control datepicker', 'required' => true]) }}
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

    <script>
        $(document).ready(function() {

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
    </script>