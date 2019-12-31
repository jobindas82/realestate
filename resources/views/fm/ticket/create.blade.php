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
                    <a href="/fm/tickets">
                        <i class="material-icons">subject</i> Tickets
                    </a>
                </li>
                @if(!$showJob)
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
                @endif
                @if($showJob)
                <li class="active">
                    <i class="material-icons">directions_run</i> Job
                </li>
                @endif
            </ol>
        </div>
    </div>

</div>

@if($showTicket)
    @include('fm.ticket.ticket_details')
@endif

@if($showJob)
    @include('fm.ticket.job_details')
@endif

@endsection