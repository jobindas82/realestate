@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
             <div class="col-sm-12">
                <div class="card">
                    <div class="body">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#profile_settings" aria-controls="settings" role="tab" data-toggle="tab">Profile Settings</a></li>
                                <li role="presentation"><a href="#change_password_settings" aria-controls="settings" role="tab" data-toggle="tab">Change Password</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                                    {{ Form::open(['method' => 'post', 'class' => 'form-horizontal', 'id' => 'user-general']) }}
                                        <div class="form-group">
                                            <label for="NameSurname" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    {{ Form::hidden('id', $userModel->id) }}
                                                    {{ Form::text('name', $userModel->name, [ 'class' => 'form-control', 'required' => true]) }}
                                                </div>
                                                <label style="dispaly:none" id="name-error" class="error" for="name"></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Email" class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    {{ Form::hidden('old_email', $userModel->email ) }}
                                                    {{ Form::email('email', $userModel->email, [ 'class' => 'form-control', 'required' => true ]) }}
                                                </div>
                                                <label style="dispaly:none" id="email-error" class="error" for="email"></label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                {{ Form::submit('Save', [ 'id' => 'general_submit', 'class' => 'btn btn-danger'] )  }}
                                            </div>
                                        </div>
                                    {{ Form::close() }}
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="change_password_settings">
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label for="OldPassword" class="col-sm-3 control-label">Old Password</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="OldPassword" name="OldPassword" placeholder="Old Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NewPassword" class="col-sm-3 control-label">New Password</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="NewPassword" name="NewPassword" placeholder="New Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NewPasswordConfirm" class="col-sm-3 control-label">New Password (Confirm)</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="NewPasswordConfirm" name="NewPasswordConfirm" placeholder="New Password (Confirm)" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger">SUBMIT</button>
                                            </div>
                                        </div>
                                    </form>
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

                $.ajax({
                    type: "POST",
                    url: '/users/update',
                    data: $(this).serialize(),
                    success: function( response ) {
                        if( response.message == 'success' ){
                            Swal.fire(
                                'SUCCESS',
                                'User detailed updated!',
                                'success'
                            ).then(function () {
                                // when click ok then redirect back
                                location.href = "/users/index";
                            });
                        }else{
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
