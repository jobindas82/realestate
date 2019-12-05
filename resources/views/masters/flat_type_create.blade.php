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
                        <a href="/masters/flat/type">
                            <i class="material-icons">settings</i> Flat types
                        </a>
                    </li>
                    <li class="active">
                        <i class="material-icons">add_circle</i> New Type
                    </li>
                </ol>
                <div class="card">
                    <div class="body">
                        <div>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                                    {{ Form::open(['method' => 'post', 'class' => 'form-horizontal', 'id' => 'flat-type-general']) }}
                                        <div class="form-group">
                                            <label for="NameSurname" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    {{ Form::hidden('id', $model->id) }}
                                                    {{ Form::text('name', $model->name, [ 'class' => 'form-control']) }}
                                                </div>
                                                <label style="dispaly:none" id="name-error" class="error" for="name"></label>
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
        $( document ).ready( function() {
            $( '#flat-type-general' ).on( 'submit', function(e) {
                //Hide Error Fields
                $('.error').hide();
                e.preventDefault();
                $('.page-loader-wrapper').fadeIn();

                $.ajax({
                    type: "POST",
                    url: '/masters/flat/save',
                    data: $(this).serialize(),
                    success: function( response ) {
                        if( response.message == 'success' ){
                            $('.page-loader-wrapper').fadeOut();
                            Swal.fire(
                                'SUCCESS',
                                'Flat type saved!',
                                'success'
                            ).then(function () {
                                // when click ok then redirect back
                                location.href = "/masters/flat/type";
                            });
                        }else{
                            $('.page-loader-wrapper').fadeOut();
                            $.each(response, function(fieldName, fieldErrors) {
                                $('#'+fieldName+'-error').text(fieldErrors.toString());
                                $('#'+fieldName+'-error').show();
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
