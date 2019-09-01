@extends('layouts.login')

@section('content')
    <div class="body">
        <form id="forgot_password" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="msg">
                Enter your email address that you used to register. We'll send you an email with password reset link.
            </div>
            <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                <div class="form-line">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email') }}"  value="{{ old('email') }}" required autocomplete="email" autofocus>
                </div>
                @error('email')
                    <label id="email-error" class="error" for="email">{{ $message }}</label>
                @enderror
            </div>

            <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit"> {{ __('Send Password Reset Link') }} </button>

            <div class="row m-t-20 m-b--5 align-center">
                <a href="/login">Back to Login</a>
            </div>
        </form>
    </div>
@endsection
