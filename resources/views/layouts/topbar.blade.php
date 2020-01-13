<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="/">{{ config('app.name', 'Laravel') }}</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- Notifications -->
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons">receipt</i>
                        <span class="label-count">{{ \App\models\VoucherNotification::count() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Cheques Today</li>
                        <li class="body">
                            <ul class="menu">
                                @foreach( \App\models\VoucherNotification::all() as $each )
                                <li>
                                    <a href="#" onclick="window.open('/finance/export/{{ $each->encoded_key() }}', '_blank')">
                                        <div class="icon-circle {{ $each->background  }}">
                                            <i class="material-icons">{{ $each->icon  }}</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>{{ $each->msg  }}</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 12.00 AM
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <!-- <li class="footer">
                                <a href="javascript:void(0);">View All Notifications</a>
                            </li> -->
                    </ul>
                </li>
                <!-- #END# Notifications -->
                <!-- Contracts -->
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons">report_problem</i>
                        <span class="label-count">{{ \App\models\ContractNotification::count() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Contracts Expiring Soon</li>
                        <li class="body">
                            <ul class="menu tasks">
                                @foreach( \App\models\ContractNotification::orderBy('end_date', 'DESC')->get() as $each )
                                <li>
                                    <a href="#" onclick="window.open('/contract/export/{{ $each->encoded_key() }}', '_blank')">
                                        <div class="icon-circle bg-cyan">
                                            <i class="material-icons">folder</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>Contract # {{ $each->contract_id }} will <br>Expire on {{ $each->formated_to_date() }}</h4>
                                            <!-- <p>
                                                <i class="material-icons">access_time</i> 12.00 AM
                                            </p> -->
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <!-- <li class="footer">
                            <a href="javascript:void(0);">View All Tasks</a>
                        </li> -->
                    </ul>
                </li>
                <!-- #END# Contracts -->
                <!-- Tickets -->
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons">sms_failed</i>
                        <span class="label-count">{{ \App\models\Tickets::activeTicketsCount() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">New Tickets</li>
                        <li class="body">
                            <ul class="menu tasks">
                                @foreach( \App\models\Tickets::activeTickets() as $each )
                                <li>
                                    <a href="#" onclick="window.open('/fm/ticket/create/{{ $each->encoded_key() }}', '_blank')">
                                        <div class="icon-circle bg-red">
                                            <i class="material-icons">subject</i>
                                        </div>
                                        <div class="menu-info">
                                            <small>{{ $each->contract_id }} : {{ mb_strimwidth($each->details, 0, 25, "...") }}</small>
                                            <p>
                                                <i class="material-icons">access_time</i> {{ date('d/m/Y h:i A', strtotime($each->created_at)) }}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <!-- <li class="footer">
                            <a href="javascript:void(0);">View All Tasks</a>
                        </li> -->
                    </ul>
                </li>
                <!-- #END# Tickets -->
                <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
            </ul>
        </div>
    </div>
</nav>