@extends('layouts.app')

@section('content')
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    All-in-One Summary
                </h2>
            </div>
            <div class="body have-mask">
                <div class="row clearfix">
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{ Form::select('building_id', \App\models\Buildings::allBuildings(), '', [ 'class' => 'form-control show-tick', 'onchange' => 'updateBuildingFilter(this.value)', 'id' => 'summary_building_id' ]) }}
                                <label class="form-label">Building</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <div id="flat_div_drop">
                                    {{ Form::select('flat_id',[], '', [ 'class' => 'form-control show-tick', 'onchange' => 'updateFlatFilter(this.value)', 'id' => 'summary_flat_id' ]) }}
                                </div>
                                <label class="form-label">Flat</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <div id="contract_div_drop">
                                    {{ Form::select('contract_id',[], '', [ 'class' => 'form-control show-tick', 'onchange' => 'updateContractFilter(this.value)', 'id' => 'summary_contract_id' ]) }}
                                </div>
                                <label class="form-label">Contracts</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Input -->

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs tab-col-orange" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#flatsLink" data-toggle="tab">
                            <i class="material-icons">home</i> Flats
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#contractsLink" data-toggle="tab">
                            <i class="material-icons">assignment</i> Contracts
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tenantsLink" data-toggle="tab">
                            <i class="material-icons">supervisor_account</i> Tenants
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#receiptsLink" data-toggle="tab">
                            <i class="material-icons">trending_up</i> Receipts
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#paymentsLink" data-toggle="tab">
                            <i class="material-icons">trending_down</i> Payments
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#ticketsLink" data-toggle="tab">
                            <i class="material-icons">build</i> Tickets
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#ledgersLink" data-toggle="tab">
                            <i class="material-icons">playlist_add_check</i> Ledgers
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="flatsLink">
                        @include('reports.results.flats_table')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="contractsLink">
                        @include('reports.results.contracts_table')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tenantsLink">
                        @include('reports.results.tenant_table')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="receiptsLink">
                        @include('reports.results.receipts_table')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="paymentsLink">
                        @include('reports.results.payments_table')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="ticketsLink">
                        @include('reports.results.tickets_table')
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="ledgersLink">
                        @include('reports.results.ledgers_table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateBuildingFilter(value) {
        $('#flat_form_building_id').val(value);
        $('#contracts_form_building_id').val(value);
        $('#tenant_form_building_id').val(value);
        $('#receipts_form_building_id').val(value);
        $('#payments_form_building_id').val(value);
        $('#tickets_form_building_id').val(value);
        $('#ledgers_form_building_id').val(value);

        populateFlats(value);
        populateContracts(0);
        reloadData_tables();

        //reset
        resetFlats();
        resetContracts();
    }

    function updateFlatFilter(value) {
        $('#flat_form_flat_id').val(value);
        $('#contract_form_flat_id').val(value);
        $('#tenant_form_flat_id').val(value);
        $('#receipts_form_flat_id').val(value);
        $('#payments_form_flat_id').val(value);
        $('#tickets_form_flat_id').val(value);
        $('#ledgers_form_flat_id').val(value);

        reloadData_tables();

        populateContracts(value);

        //reset
        resetContracts();
    }

    function updateContractFilter(value) {
        $('#contract_form_contract_id').val(value);
        $('#tenant_form_contract_id').val(value);
        $('#receipts_form_contract_id').val(value);
        $('#payments_form_contract_id').val(value);
        $('#tickets_form_contract_id').val(value);
        $('#ledgers_form_contract_id').val(value);

        reloadData_tables();
    }

    function populateFlats(building_id = 0) {
        $.ajax({
            type: 'GET',
            url: '/report/drop/flat/' + building_id,
            success: function(response) {
                $('#flat_div_drop').html(response);
                $('#flat_div_drop').find('select').selectpicker({
                    liveSearch: true,
                    dropupAuto: false,
                    size: 5
                });
            }
        });
    }

    function populateContracts(flat_id = 0) {
        $.ajax({
            type: 'GET',
            url: '/report/drop/contracts/' + flat_id,
            success: function(response) {
                $('#contract_div_drop').html(response);
                $('#contract_div_drop').find('select').selectpicker({
                    liveSearch: true,
                    dropupAuto: false,
                    size: 5
                });
            }
        });
    }

    function reloadData_tables() {
        reload_datatable('#flat_building_list');
        reload_datatable('#contracts_list');
        reload_datatable('#tenant_list');
        reload_datatable('#receipts_list');
        reload_datatable('#payments_list');
        reload_datatable('#tickets_list');
        reload_datatable('#ledgers_list');
    }

    function resetFlats(){
        $('#flat_form_flat_id').val(0);
        $('#contract_form_flat_id').val(0);
        $('#tenant_form_flat_id').val(0);
        $('#receipts_form_flat_id').val(0);
        $('#payments_form_flat_id').val(0);
        $('#tickets_form_flat_id').val(0);
        $('#ledgers_form_flat_id').val(0);
    }

    function resetContracts(){
        $('#contract_form_contract_id').val(0);
        $('#tenant_form_contract_id').val(0);
        $('#receipts_form_contract_id').val(0);
        $('#payments_form_contract_id').val(0);
        $('#tickets_form_contract_id').val(0);
        $('#ledgers_form_contract_id').val(0);
    }
</script>

@endsection