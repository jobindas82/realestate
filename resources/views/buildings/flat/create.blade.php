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
                @if( isset($modelBuilding->id) && $modelBuilding->id > 0 )
                <li>
                    <a href="/building/create/{{ $modelBuilding->encoded_key() }}">
                        <i class="material-icons">home</i> {{ $modelBuilding->name }}
                    </a>
                </li>
                @endif
                <li class="active">
                    <i class="material-icons">add_circle</i> Flat
                </li>
            </ol>
        </div>
    </div>

    @include('buildings.flat.basic')

    <!-- Table -->
    <div class="row clearfix">
        @include('document.list')
    </div>
    <!-- end -->

</div>

@endsection