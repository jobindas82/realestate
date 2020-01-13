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
                    <a href="/ledger">
                        <i class="material-icons">view_list</i> Ledgers
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
            </ol>
        </div>
    </div>

    <!-- Input -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Basic Details
                    </h2>
                </div>
                <div class="body have-mask">
                    {{ Form::open(['method' => 'post', 'id' => 'basic-details']) }}
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('id', $model->id, [ 'id' => 'ledger_id' ]) }}
                                    {{ Form::text('name', $model->name, [ 'class' => 'form-control', 'required' => true ]) }}
                                    <label class="form-label">Name</label>
                                </div>
                                <label id="name-error" class="error" for="name"></label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('parent_id', \App\models\Ledgers::parents(0, true), $model->parent_id, [ 'class' => 'form-control show-tick', 'data-live-search' => true]) }}
                                    <label class="form-label">Group</label>
                                </div>
                                <label id="parent_id-error" class="error" for="parent_id"></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::select('is_contract_item', [ 'N' => 'No', 'Y' => 'Yes'], $model->is_contract_item, [ 'class' => 'form-control show-tick']) }}
                                    <label class="form-label">Use as Contract Item?</label>
                                </div>
                                <label id="is_contract_item-error" class="error" for="is_contract_item"></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group align-right">
                        {{ Form::submit('Save', [ 'id' => 'basic_submit', 'class' => 'btn btn-danger'] )  }}
                    </div>


                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Input -->

    <script>
        $(document).ready(function() {
            $('#basic-details').on('submit', function(e) {
                //Hide Error Fields
                $('.error').hide();
                e.preventDefault();
                $('.page-loader-wrapper').fadeIn();

                $.ajax({
                    type: "POST",
                    url: '/ledger/save',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.message == 'success') {

                            $('#ledger_id').val(response.ledger_id);

                            $('.page-loader-wrapper').fadeOut();
                            Swal.fire(
                                'SUCCESS',
                                'Ledger Saved!',
                                'success'
                            ).then((response) => {
                                location.href = "/ledger";
                            });

                        } else {
                            $('.page-loader-wrapper').fadeOut();
                            $.each(response, function(fieldName, fieldErrors) {
                                $('#' + fieldName + '-error').text(fieldErrors.toString());
                                $('#' + fieldName + '-error').show();
                            });
                        }
                    }
                });
            });
        });
    </script>

</div>

@endsection