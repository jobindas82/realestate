<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Ticket Details
                </h2>
            </div>
            <div class="body have-mask">
                {{ Form::open(['method' => 'post', 'id' => 'basic-details']) }}

                <div class="row clearfix">
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::hidden('id', $model->id, [ 'id' => 'ticket_id' ]) }}
                                {{ Form::select('tenant_id', $model->exists ? [ $model->tenant_id => $model->tenant->name ] : [], $model->tenant_id, [ 'class' => 'form-control show-tick ajax-drop', 'id' => 'tenant-drop-contract', 'name' => 'tenant_id']) }}
                                <label class="form-label">Tenant</label>
                            </div>
                            <label id="tenant_id-error" class="error" for="tenant_id"></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <div id="contract_list_div">
                                    {{ Form::select('contract_id', \App\models\Contracts::activeContractsTenant($model->tenant_id), $model->contract_id, [ 'class' => 'form-control show-tick simple-dropdown', 'onChange' => 'getDetails(this.value);']) }}
                                </div>
                                <label class="form-label">Contract #</label>
                            </div>
                            <label id="contract_id-error" class="error" for="contract_id"></label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line building_name">
                                {{ Form::text('building_name', $model->exists ? $model->contract->building->name : NULL, [ 'class' => 'form-control', 'readonly' => true, 'id' => 'building_name']) }}
                                <label class="form-label">Building</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-float">
                            <div class="form-line flat_name">
                                {{ Form::text('flat_name', $model->exists ?  $model->contract->flat->name :  NULL, [ 'class' => 'form-control', 'readonly' => true, 'id' => 'flat_name']) }}
                                <label class="form-label">Flat</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::text('date', $model->formated_date(), [ 'class' => 'form-control datepicker', 'required' => true]) }}
                                <label class="form-label">Date</label>
                            </div>
                            <label id="date-error" class="error" for="date"></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('priority', \App\models\Tickets::PRIORITIES , $model->priority, [ 'class' => 'form-control show-tick']) }}
                                <label class="form-label">Priority</label>
                            </div>
                            <label id="priority-error" class="error" for="priority"></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::textarea('details', $model->details, [ 'class' => 'form-control no-resize auto-growth', 'rows' => 1]) }}
                                <label class="form-label">Details</label>
                            </div>
                            <label id="details-error" class="error" for="details"></label>
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

<!-- Use the following Script to modify auto complete and other events -->
<script src="{{ asset('views/ticket.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#basic-details').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/fm/tickets/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {
                        $('#ticket_id').val(response.ticket_id);
                        $('#job_ticket_id').val(response.ticket_id);
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Ticket Saved!',
                            'success'
                        ).then(function() {
                            // when click ok then redirect back
                            @if(!$showJob)
                                location.href = "/fm/tickets";
                            @endif;
                            return;
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

    function getDetails(_contract_id) {
        $.ajax({
            method: 'POST',
            url: '/contract/details',
            data: {
                contract_id: _contract_id
            },
            success: function(response) {
                $('#building_name').val(response.building_name);
                $('#flat_name').val(response.flat_name);

                $('.building_name').addClass('focused');
                $('.flat_name').addClass('focused');
            }
        });
    }
</script>