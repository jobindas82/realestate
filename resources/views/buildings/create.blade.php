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
                    <a href="/building/index">
                        <i class="material-icons">settings</i> Buildings
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
            </ol>
        </div>
    </div>

    @include('buildings.basic')

    @include('buildings.depreciation')

    <!-- Table -->
    <div class="row clearfix">
        @include('buildings.flat.index')
    </div>
    <!-- end -->

    <!-- Table -->
    <div class="row clearfix">
        @include('document.list')
    </div>
    <!-- end -->
</div>

@endsection