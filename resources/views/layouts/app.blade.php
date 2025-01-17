<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon-->
    <!-- <link rel="icon" href="favicon.ico" type="image/x-icon"> -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- Morris Chart Css-->
    <link href="{{ asset('plugins/morrisjs/morris.css') }}" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="{{ asset('plugins/dropzone/dropzone.css') }}" rel="stylesheet">

    <!-- Custom Css -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />

    <!-- Sweetalert Css -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet" />

    <!-- ajax select -->
    <link href="{{ asset('plugins/ajaxSelect/css/ajax-bootstrap-select.min.css') }}" rel="stylesheet" />

    <!-- Full calandar -->
    <link href="{{ asset('plugins/fullcalendar/core/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('plugins/fullcalendar/daygrid/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('plugins/fullcalendar/bootstrap/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('plugins/fullcalendar/list/main.css') }}" rel='stylesheet' />
    <link href="{{ asset('plugins/fullcalendar/timegrid/main.css') }}" rel='stylesheet' />

    <!-- Jquery Core Js -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>

    <!-- Select Plugin Js -->
    <script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('plugins/node-waves/waves.js') }}"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/dataTables.responsive.js') }}"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset('plugins/jquery-countto/jquery.countTo.js') }}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('plugins/morrisjs/morris.js') }}"></script>

    <!-- ChartJs -->
    <script src="{{ asset('plugins/chartjs/Chart.bundle.js') }}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset('plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>

    <!-- SweetAlert Plugin Js -->
    <script src="{{ asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>


    <!-- Custom Js -->
    <script src="{{ asset('js/admin.js') }}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('js/skin.js') }}"></script>

    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>

    <!-- Autosize Plugin Js -->
    <script src="{{ asset('plugins/autosize/autosize.js') }}"></script>

    <!-- Dropzone Plugin Js -->
    <script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>

    <!-- Input Mask Plugin Js -->
    <script src="{{ asset('plugins/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>

    <!-- JQuery Steps Plugin Js -->
    <script src="{{ asset('plugins/jquery-steps/jquery.steps.js') }}"></script>

    <!--  Ajax Select -->
    <script src="{{ asset('plugins/ajaxSelect/js/ajax-bootstrap-select.min.js') }}"></script>

    <!-- full Calendar -->
    <script src="{{ asset('plugins/fullcalendar/core/main.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/daygrid/main.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/bootstrap/main.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/google-calendar/main.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/interaction/main.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/list/main.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/timegrid/main.js') }}"></script>

    <script>
        function roundNumber(num, delimiter) {
            return +(Math.round(num + "e+" + delimiter) + "e-" + delimiter);
        }

        function round_field(field_id) {
            if (!isNaN($('#' + field_id).val())) {
                var value = Number($('#' + field_id).val());
                value = value >= 0 ? roundNumber(value, 6) : 0;
                if (!$('#' + field_id).attr('readonly'))
                    $('#' + field_id).val(value.toFixed(6));
            }

        }

        function updateStatus(entry_id, status, type) {
            var label = status == 0 ? 'Un-Post' : 'Post';
            if(type == 1)
                label = 'Cancel';
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want '+ label + ' this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + label +' it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: '/finance/status',
                        data: {
                            _ref: entry_id,
                            status : status,
                            type : type
                        },
                        success: function(response) {
                            $('.page-loader-wrapper').fadeOut();
                            Swal.fire(
                                'Updated!',
                                'Entry '+ label +'ed!',
                                'success'
                            );
                            reload_datatable('#receipt_list');
                            reload_datatable('#journal_list');
                            reload_datatable('#payment_list');
                        }
                    });
                }
            });
        }
    </script>



    <!-- Pusher -->
    <!-- <script src="https://js.pusher.com/5.0/pusher.min.js"></script> -->
    <!-- <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('320c8a9cd2bfcc60e682', {
            cluster: 'ap2',
            forceTLS: true
        });

        var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script> -->

</head>

@php
$themeName = Auth::user()->theme;
@endphp

<body class="{{ 'theme-'.$themeName }}">

    <!-- Page Loader -->
    @include('layouts.loader')
    <!-- #END# Page Loader -->

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

    <!-- Top Bar -->
    @include('layouts.topbar')
    <!-- #Top Bar -->

    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="{{ asset('images/user.png') }}" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div>
                    <div class="email">{{ Auth::user()->email }}</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="/users/create/{{ Auth::user()->encoded_key() }}"><i class="material-icons">person</i>Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">input</i>{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->

            <!-- Menu -->
            @include('layouts.menu')
            <!-- #Menu -->

            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; <a href="https://bluesky.ae">{{ config('app.dev', 'Bluesky Technologies') }}</a>.
                </div>
                <!-- <div class="version">
                    <b>Version: </b> {{ config('app.dev', '1.0') }}
                </div> -->
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->

        <!-- Right Sidebar -->
        @include('layouts.rightbar')
        <!-- #END# Right Sidebar -->

    </section>

    <section class="content" id="main-render-section">
        @yield('content')
    </section>

    <!-- AJAX TOKEN -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Reload Datatable
        function reload_datatable(table_id) {
            $(table_id).DataTable().ajax.reload();
        }
    </script>
    <!-- END-->

</body>


</html>