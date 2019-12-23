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
                                <li><a href="javascript:void(0);"><i class="material-icons">add_circle</i> Tenant</a></li>
                                <li><a href="javascript:void(0);"><i class="material-icons">add_circle</i> Building</a></li>
                                <li><a href="javascript:void(0);"><i class="material-icons">add_circle</i> Flat</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    {{ Form::open(['method' => 'post', 'id' => 'contract_form']) }}
                    {{ Form::hidden('id', $model->id, [ 'id' => 'contract_id' ]) }}
                    <h3>Tenant</h3>
                    @include('contract.tenant')

                    <h3>Building</h3>
                    @include('contract.building')

                    <h3>Contract</h3>
                    @include('contract.general')

                    <h3>Particulars</h3>
                    @include('contract.particulars')

                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <script>
            $(function() {

                //Advanced form with validation
                var form = $('#contract_form').show();
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
                        //Hide Error Fields
                        $('.error').hide();
                        event.preventDefault();
                        $('.page-loader-wrapper').fadeIn();

                        $.ajax({
                            type: "POST",
                            url: '/contract/save',
                            data: $(this).serialize(),
                            success: function(response) {
                                if (response.message == 'success') {

                                    $('#contract_id').val(response.contract_id);

                                    $('.page-loader-wrapper').fadeOut();
                                    Swal.fire(
                                        'SUCCESS',
                                        'Contract Saved!',
                                        'success'
                                    );

                                } else {
                                    $('.page-loader-wrapper').fadeOut();
                                    $.each(response, function(fieldName, fieldErrors) {
                                        $('.' + fieldName ).append(' <label class="error" for="' + fieldName + '">' +fieldErrors.toString() + '</label>');
                                    });
                                }
                            }
                        });
                    }
                });

                form.validate({
                    highlight: function(input) {
                        $(input).parents('.form-line').addClass('error');
                        $(input).parents('td').addClass('error');
                    },
                    unhighlight: function(input) {
                        $(input).parents('.form-line').removeClass('error');
                        $(input).parents('td').removeClass('error');
                    },
                    errorPlacement: function(error, element) {
                        $(element).parents('.form-group').append(error);
                        $(element).parents('td').append(error);
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

            @if( $model->exists )
                setTimeout(function(){
                    $('[role="tab"]').each(function () {
                        $(this).removeClass('disabled').addClass('done');
                    });
                    $('#contract_form-t-3').click();
                }, 500);
            @endif;

        </script>

    </div>

    @endsection