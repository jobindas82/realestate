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
                <li class="active">
                    <i class="material-icons">add_circle</i> Cheque Management
                </li>
            </ol>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Cheque Management
                    </h2>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#receipts" data-toggle="tab">
                                <i class="material-icons">call_received</i> Receipts
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#payments" data-toggle="tab">
                                <i class="material-icons">call_made</i> Payments
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#history" data-toggle="tab">
                                <i class="material-icons">history</i> Returned Cheques
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="receipts">
                            @include('finance.cheque_receipts')
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="payments">
                            @include('finance.cheque_payments')
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="history">
                            @include('finance.cheque_history')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCheque(id, isCleared, type) {
        $.ajax({
            method: 'POST',
            url: '/finance/cheques/save',
            data: {
                id: id,
                cheque_date: $('#Cheque_date_' + id).val(),
                cheque_no: $('#Cheque_no_' + id).val(),
                bank: $('#Bank_' + id).val(),
                operation: isCleared,
                type : type
            },
            success: function(response) {
                Swal.fire(
                    'SUCCESS',
                    'Cheque Updated!',
                    'success'
                );
                reload_datatable('#receipts_cheque_list');
                reload_datatable('#payments_cheque_list');
                reload_datatable('#returned_cheque_list');
            }
        });
    }

    function revertCheque(id){
        $.ajax({
            method: 'POST',
            url: '/finance/cheques/revert',
            data: {
                id: id
            },
            success: function(response) {
                Swal.fire(
                    'SUCCESS',
                    'Cheque Reverted!',
                    'success'
                );
                reload_datatable('#receipts_cheque_list');
                reload_datatable('#payments_cheque_list');
                reload_datatable('#returned_cheque_list');
            }
        });
    }
</script>

@endsection