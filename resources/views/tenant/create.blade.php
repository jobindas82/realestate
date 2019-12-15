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
                    <a href="/tenant/index">
                        <i class="material-icons">record_voice_over</i> Tenants
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
            </ol>
        </div>
    </div>

    @include('tenant.basic')

    <!-- Table -->
    <div class="row clearfix">
        @include('document.list')
    </div>
    <!-- end -->
</div>

@endsection