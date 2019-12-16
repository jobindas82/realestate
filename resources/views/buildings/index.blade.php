@extends('layouts.app')

@section('content')
<div class="row">
    @php
    $buildings = \App\models\Buildings::all()->sortBy('is_available');
    @endphp

    @if( $buildings->count() > 0 )
    @foreach ( $buildings as $i => $each )
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
            <div id="building_header_{{ $each->id }}">
                <div class="header @if ($each->is_available == 1) bg-light-green @elseif ($each->is_available == 2) bg-amber @else bg-red @endif">
                    <h2>
                        <b>{{ $each->name }}<b> <small> {{{ $each->address }}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li>
                            <a href="javascript:void(0);" onclick="window.open('/building/flat/all/1/{{ $each->encoded_key() }}', '_blank', 'location=yes,height=0,width=0,scrollbars=yes,status=yes');" data-toggle="cardloading" data-loading-effect="timer" data-loading-color="lightBlue">
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
                                <li><a href="#" onclick="window.open('/building/flat/?_ref={{ $each->encoded_key()  }}', '_blank');">Add Flat</a></li>
                                <li><a href="#" onclick="window.open('/document/create/?__uuid={{ $each->encoded_key() }}&__from=1', '_blank');">Add Documents</a></li>
                                @if( $each->is_available == 1 )
                                <li><a href="#" onclick="building_status({{ $each->id }}, 3);">Block</a></li>
                                <li><a href="#" onclick="building_status({{ $each->id }}, 2);">Under Maintenance</a></li>
                                @elseif ( $each->is_available == 2 )
                                <li><a href="#" onclick="building_status({{ $each->id }}, 1);">Active</a></li>
                                <li><a href="#" onclick="building_status({{ $each->id }}, 3);">Block</a></li>
                                @else
                                <li><a href="#" onclick="building_status({{ $each->id }}, 1);">Active</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
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
                <a href="#" onclick="window.open('/building/flat/all/{{ $each->encoded_key() }}', '_blank', 'location=yes,height=0,width=0,scrollbars=yes,status=yes');"><i class="material-icons">settings</i> All Flats</a><br>
                <a href="javascript(0);"><i class="material-icons">settings</i> Active Contracts</a><br>
                <a href="javascript(0);"><i class="material-icons">settings</i> Contracts</a><br>
                <a href="#" onclick="window.open('/document/all/1/{{ $each->encoded_key() }}', '_blank', 'location=yes,height=0,width=0,scrollbars=yes,status=yes');"><i class="material-icons">settings</i> View Documents</a><br>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
            <div class="header bg-light-green">
                <h2>
                    <b>Lets Start :)</small>
                </h2>
            </div>
            <div class="body">
                <a href="/building/create" class="btn btn-primary btn-block waves-effect"> New Building </a>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function building_status(building_id, status) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Update Building Status?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Update'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '/building/status',
                    data: {
                        _ref: building_id,
                        status: status
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            $('#building_header_' + building_id).html(response.content);
                            Swal.fire(
                                'Updated!',
                                'Building Status updated!',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Failed!',
                                'Failed to update Status!',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    }
</script>
@endsection