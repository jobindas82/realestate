<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Job Details
                </h2>
            </div>
            <div class="body">
                {{ Form::open(['method' => 'post', 'id' => 'job-details']) }}
                <div class="row clearfix">
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::hidden('id', $model->id, [ 'id' => 'job_ticket_id' ]) }}
                                {{ Form::select('job_category', \App\models\Tickets::JOB_CATEGORIES , $model->job_category, [ 'class' => 'form-control show-tick']) }}
                                <label class="form-label">Category</label>
                            </div>
                            <label id="id-error" class="error" for="id"></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::number('amount', $model->amount, [ 'class' => 'form-control', 'required' => true]) }}
                                <label class="form-label">Amount</label>
                            </div>
                            <label id="amount-error" class="error" for="amount"></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::textarea('remarks', $model->remarks, [ 'class' => 'form-control no-resize auto-growth', 'rows' => 1]) }}
                                <label class="form-label">Remarks</label>
                            </div>
                            <label id="remarks-error" class="error" for="remarks"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group align-right">
                    {{ Form::submit('Save', [ 'id' => 'jobs_submit', 'class' => 'btn btn-danger'] )  }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<!-- #END# Input -->

<script>
    $(document).ready(function() {
        $('#job-details').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/fm/jobs/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {
                        $('#ticket_id').val(response.ticket_id);
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Job Saved!',
                            'success'
                        ).then((response) => {
                            location.href = "/fm/tickets";
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