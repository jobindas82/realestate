@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li>
                    <a href="/">
                        <i class="material-icons">home</i> Home
                    </a>
                </li>
                <li>
                    <a href="/masters/tax/index">
                        <i class="material-icons">settings</i> Tax Code
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Add/Edit Code
                </li>
            </ol>
            <div class="card">
                <div class="body">
                    <div>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                                {{ Form::open(['method' => 'post', 'class' => 'form-horizontal', 'id' => 'flat-type-general']) }}

                                <div class="row clearfix">
                                    <label for="code" class="col-sm-1 control-label">Name</label>
                                    <div class="col-md-4">
                                        <div class="input-group">

                                            <div class="form-line">
                                                {{ Form::hidden('id', $model->id) }}
                                                {{ Form::text('code', $model->code, [ 'class' => 'form-control']) }}
                                            </div>
                                            <label style="dispaly:none" id="code-error" class="error" for="code"></label>
                                        </div>
                                    </div>
                                    <label for="percentage" class="col-sm-1 control-label">Percentage</label>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <div class="form-line">
                                                {{ Form::number('percentage', $model->percentage, [ 'class' => 'form-control', 'min' => 0, 'max' => 100]) }}
                                            </div>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        {{ Form::submit('Save', [ 'id' => 'general_submit', 'class' => 'btn btn-danger'] )  }}
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#flat-type-general').on('submit', function(e) {
            //Hide Error Fields
            $('.error').hide();
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                type: "POST",
                url: '/masters/tax/save',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.message == 'success') {
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'SUCCESS',
                            'Tax code saved!',
                            'success'
                        ).then(function() {
                            // when click ok then redirect back
                            location.href = "/masters/tax/index";
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
@endsection