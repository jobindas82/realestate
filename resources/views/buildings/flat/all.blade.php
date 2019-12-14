@extends('layouts.simple')

@section('content')
<div class="container-fluid">
    <!-- Table -->
    <div class="row clearfix">
        @include('buildings.flat.index')
    </div>
    <!-- end -->

</div>

@endsection