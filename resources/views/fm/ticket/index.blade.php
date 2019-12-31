@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">view_week</i>
                </div>
                <div class="content">
                    <div class="text">THIS WEEK</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Tickets::ticketsThisWeekCount() }}" data-speed="15" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">check</i>
                </div>
                <div class="content">
                    <div class="text">ACTIVE</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Tickets::activeTicketsCount() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">report</i>
                </div>
                <div class="content">
                    <div class="text">CRITICAL</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Tickets::criticalTicketsCount() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">fast_forward</i>
                </div>
                <div class="content">
                    <div class="text">RUNNING JOBS</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Tickets::runningJobsCount() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Ticket Management
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="/fm/ticket/create"><i class="material-icons">add_circle</i> Create</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tickets" data-toggle="tab">
                                <i class="material-icons">warning</i> Tickets
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#jobs" data-toggle="tab">
                                <i class="material-icons">directions_run</i> Jobs
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tickets">
                            @include('fm.ticket.tickets')
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="jobs">
                            @include('fm.ticket.jobs')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection