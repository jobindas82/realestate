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
                    <a href="/contract/index">
                        <i class="material-icons">folder</i> Leasing & Contracts
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
            </ol>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>@if( $model->exists ) {{ 'Contract # '.$model->id }} @else New Contract @endif</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="javascript:void(0);">New Tenant</a></li>
                                <li><a href="javascript:void(0);">New Building</a></li>
                                <li><a href="javascript:void(0);">New Flat</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="wizard_with_validation" method="POST">
                        <h3>Tenant</h3>
                        @include('contract.tenant')

                        <h3>Building</h3>
                        @include('contract.building')

                        <h3>Payments</h3>
                        @include('contract.payments')

                        <h3>Terms</h3>
                        @include('contract.terms')
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(function() {

                //Advanced form with validation
                var form = $('#wizard_with_validation').show();
                form.steps({
                    headerTag: 'h3',
                    bodyTag: 'fieldset',
                    transitionEffect: 'slideLeft',
                    onInit: function(event, currentIndex) {
                        $.AdminBSB.input.activate();

                        //Set tab width
                        var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
                        var tabCount = $tab.length;
                        $tab.css('width', (100 / tabCount) + '%');

                        //set button waves effect
                        setButtonWavesEffect(event);
                    },
                    onStepChanging: function(event, currentIndex, newIndex) {
                        if (currentIndex > newIndex) {
                            return true;
                        }

                        if (currentIndex < newIndex) {
                            form.find('.body:eq(' + newIndex + ') label.error').remove();
                            form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                        }

                        form.validate().settings.ignore = ':disabled,:hidden';
                        return form.valid();
                    },
                    onStepChanged: function(event, currentIndex, priorIndex) {
                        setButtonWavesEffect(event);
                    },
                    onFinishing: function(event, currentIndex) {
                        form.validate().settings.ignore = ':disabled';
                        return form.valid();
                    },
                    onFinished: function(event, currentIndex) {
                        swal("Good job!", "Submitted!", "success");
                    }
                });

                form.validate({
                    highlight: function(input) {
                        $(input).parents('.form-line').addClass('error');
                    },
                    unhighlight: function(input) {
                        $(input).parents('.form-line').removeClass('error');
                    },
                    errorPlacement: function(error, element) {
                        $(element).parents('.form-group').append(error);
                    },
                    rules: {
                        'confirm': {
                            equalTo: '#password'
                        }
                    }
                });
            });

            function setButtonWavesEffect(event) {
                $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
                $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
            }
        </script>

    </div>

    @endsection