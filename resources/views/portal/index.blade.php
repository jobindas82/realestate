@extends('layouts.portal')

@section('content')
<div class="body have-mask">
    <form id="sign_in" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group">
            <span class="input-group-addon">
                Emirates ID
            </span>
            <div class="form-line">
                <input id="emirates_id" type="emirates_id" class="form-control emirates-id" name="emirates_id" required>
            </div>
            <label id="emirates_id-error" class="error" for="emirates_id"></label>
        </div>
        <div class="input-group">
            <span class="input-group-addon">
                Mobile No
            </span>
            <div class="form-line">
                <input id="mobile_no" type="mobile_no" class="mobile-phone-number form-control" name="mobile_no" required>
            </div>
            <label id="mobile_no-error" class="error" for="mobile_no"></label>
        </div>
        <div class="row">
            <div class="col-xs-12 align-center">
                <button class="btn btn-block bg-pink waves-effect" type="submit">Open</button>
            </div>
        </div>
    </form>
</div>
@endsection