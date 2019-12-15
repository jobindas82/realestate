@extends('layouts.simple')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>{{ strtoupper($model->name) }}</h2>
    </div>
    <!-- Table -->
    <div class="row clearfix">
        @include('document.list')
    </div>
    <!-- end -->

</div>

@endsection