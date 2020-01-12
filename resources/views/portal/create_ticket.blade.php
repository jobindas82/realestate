@extends('layouts.portal.app')

@section('content')
<div class="container-fluid">
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
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line building_name">
                                    {{ Form::hidden('tenant_id', request()->tenantModel->id) }}
                                    {{ Form::hidden('contract_id', $contractModel->id) }}
                                    {{ Form::text('building_name', $contractModel->building->name, [ 'class' => 'form-control', 'readonly' => true, 'id' => 'building_name']) }}
                                    <label class="form-label">Building</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line flat_name">
                                    {{ Form::text('flat_name', $contractModel->flat->name, [ 'class' => 'form-control', 'readonly' => true, 'id' => 'flat_name']) }}
                                    <label class="form-label">Flat</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::text('date', $model->formated_date(), [ 'class' => 'form-control', 'required' => true, 'readonly' => true]) }}
                                    <label class="form-label">Date</label>
                                </div>
                                <label id="date-error" class="error" for="date"></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('priority', \App\models\Tickets::PRIORITIES , $model->priority, [ 'class' => 'form-control show-tick']) }}
                                    <label class="form-label">Priority</label>
                                </div>
                                <label id="priority-error" class="error" for="priority"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::textarea('details', $model->details, [ 'class' => 'form-control no-resize auto-growth', 'rows' => 4, 'required' => true]) }}
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
</div>

<script>
    $(document).ready(function() {
        $('#basic-details').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/portal/ticket/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Ticket Saved!',
                            'success'
                        ).then((response)=>{
                            location.href="/portal/tickets";
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


@endsection