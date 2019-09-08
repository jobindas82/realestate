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
                        <a href="/users/index">
                            <i class="material-icons">group</i> Users
                        </a>
                    </li>
                    <li class="active">
                        <i class="material-icons">group_add</i> New user
                    </li>
                </ol>
                <div class="card">
                    <div class="body">
                        <div>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                                    {{ Form::open(['method' => 'post', 'class' => 'form-horizontal', 'id' => 'user-general']) }}
                                        <div class="form-group">
                                            <label for="NameSurname" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    {{ Form::text('name', '', [ 'class' => 'form-control', 'required' => true]) }}
                                                </div>
                                                <label style="dispaly:none" id="name-error" class="error" for="name"></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Email" class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    {{ Form::email('email', '', [ 'class' => 'form-control', 'required' => true ]) }}
                                                </div>
                                                <label style="dispaly:none" id="email-error" class="error" for="email"></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="NewPassword" class="col-sm-2 control-label">New Password</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => 'New Password', 'required' => true ]) }}
                                                    </div>
                                                    <label style="dispaly:none" id="new_password-error" class="error" for="new_password"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="NewPasswordConfirm" class="col-sm-2 control-label">New Password (Confirm)</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        {{ Form::password('new_confirm_password', ['class' => 'form-control', 'placeholder' => 'New Password (Confirm)', 'required' => true ]) }}
                                                    </div>
                                                    <label style="dispaly:none" id="new_confirm_password-error" class="error" for="new_confirm_password"></label>
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
            $( '#user-general' ).on( 'submit', function(e) {
                //Hide Error Fields
                $('.error').hide();
                e.preventDefault();
                $('.page-loader-wrapper').fadeIn();

                $.ajax({
                    type: "POST",
                    url: '/users/store',
                    data: $(this).serialize(),
                    success: function( response ) {
                        if( response.message == 'success' ){
                            $('.page-loader-wrapper').fadeOut();
                            Swal.fire(
                                'SUCCESS',
                                'User saved!',
                                'success'
                            ).then(function () {
                                // when click ok then redirect back
                                location.href = "/users/index";
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
