@extends('layouts.app')

@section('content')
<div class="row">
    @foreach ( \App\models\Buildings::all()->sortBy('is_available') as $i => $each )
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
            <div id="building_header_{{ $each->id }}" class="header @if ($each->is_available == 1) bg-light-green @elseif ($each->is_available == 2) bg-amber @else bg-red @endif">
                <h2>
                    <b>{{ $each->name }}<b> <small> {{{ $each->address }}}</small>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                                    <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="timer" data-loading-color="lightBlue">
                                        <span class="badge">{{ $each->flats_available() }} Flats Available</span>
                                    </a>
                                </li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="/building/create">Create</a></li>
                            <li><a href="/building/create/{{ $each->encoded_key() }}">Edit</a></li>
                            <li><a href="javascript:void(0);">Add Flats</a></li>
                            <li><a href="#" onclick="window.open('/document/building/?_ref=__index_building&__uuid={{ $each->encoded_key() }}', '_blank');">Add Documents</a></li>
                            @if( $each->is_available == 1 )
                                <li><a href="javascript:void(0);">Block</a></li>
                                <li><a href="javascript:void(0);">Under Maintenance</a></li>
                            @elseif ( $each->is_available == 2 )
                                <li><a href="javascript:void(0);">Active</a></li>
                                <li><a href="javascript:void(0);">Block</a></li>
                            @else
                                <li><a href="javascript:void(0);">Active</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <ul style="list-style : circle;">
                    <li><span class="font-bold col-teal">Ownership</span> : @if( $each->ownership == 'O') Owned @else Leased @endif</li>
                    <li><span class="font-bold col-teal">Owner</span> : {{ $each->owner_name }}</li>
                    <li><span class="font-bold col-teal">Floors</span> : {{ $each->floor_count }}</li>
                    <li><span class="font-bold col-teal">Location</span> : @if ( isset($each->location->name) ) {{ $each->location->name }} @endif</li>
                    <li><span class="font-bold col-teal">Country</span> : @if ( isset($each->country->name) ) {{ $each->country->name }} @endif</li>
                </ul>
                <br>
                <a href="javascript(0);"><i class="material-icons">settings</i> All Flats</a><br>
                <a href="javascript(0);"><i class="material-icons">settings</i> Active Contracts</a><br>
                <a href="javascript(0);"><i class="material-icons">settings</i> Contracts</a><br>
                <a href="javascript(0);"><i class="material-icons">settings</i> View Documents</a><br>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection