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
                    <i class="material-icons">add_circle</i> Settlement
                </li>
            </ol>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Contract # {{ $model->id }} | Settlement
                    </h2>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#early_settlement" data-toggle="tab">
                                <i class="material-icons">access_time</i> Early Settlement
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#expired_contract" data-toggle="tab">
                                <i class="material-icons">warning</i> Expired Contract
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="early_settlement">
                            @include('contract.early_settlement')
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="expired_contract">
                            @include('contract.expired_contract')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection