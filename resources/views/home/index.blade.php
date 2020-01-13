@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Widgets -->
    <div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">playlist_add_check</i>
                </div>
                <div class="content">
                    <div class="text">ACTIVE CONTRACTS</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Contracts::activeContractsCount() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">help</i>
                </div>
                <div class="content">
                    <div class="text">ACTIVE TICKETS</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Tickets::activeTicketsCount() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">euro_symbol</i>
                </div>
                <div class="content">
                    <div class="text">INCOME</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Ledgers::findClass(\App\models\Ledgers::INCOME_PARENT)->currentYearBalance() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">poll</i>
                </div>
                <div class="content">
                    <div class="text">EXPENSE</div>
                    <div class="number count-to" data-from="0" data-to="{{ \App\models\Ledgers::findClass(\App\models\Ledgers::EXPENSE_PARENT)->currentYearBalance() }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Widgets -->

    <div class="row clearfix">
        <!-- Task Info -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <div id='calendar'></div>
                </div>
            </div>

        </div>
        <!-- #END# Task Info -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list'],
            // header: {
            //     left: 'prevYear,prev,next,nextYear today',
            //     center: 'title',
            //     right: 'dayGridMonth,dayGridWeek,dayGridDay'
            // },
            navLinks: true,
            editable: true,
            eventLimit: true,
            eventSources: [{
                    url: '/expiring_contracts',
                    color: '#FF6F5E', //Green 
                    textColor: 'white'
                },
                {
                    url: '/uncleared_payments',
                    color: '#6969E2',
                    textColor: 'white'
                },
                {
                    url: '/uncleared_receipts',
                    color: '#FF6F5E',
                    textColor: 'white'
                }
            ],
            eventClick: function(info) {
                info.jsEvent.preventDefault(); // don't let the browser navigate
                if (info.event.url) {
                    window.open(info.event.url, '_blank');
                }
            }
        });

        calendar.render();
    });
</script>
@endsection