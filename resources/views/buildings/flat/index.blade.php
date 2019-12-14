<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>
                Flats
            </h2>
            <ul class="header-dropdown m-r--5">
                <li>
                    <a href="javascript:void(0);" onclick="reload_datatable('#building_flat_list');" title="Refresh">
                        <i class="material-icons">loop</i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <input type="hidden" name="flat_building_id" id="flat_building_id" value="{{ $model->id }}">
                        <input type="hidden" name="flat_key" id="flat_key" value="{{ $model->encoded_key() }}">
                        <li><a href="#" onclick="goto_flat()"><i class="material-icons">add_circle</i> Add</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover dataTable" id="building_flat_list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Building</th>
                            <th>Flat #</th>
                            <th>ft<sup>2</sup></th>
                            <th>Type</th>
                            <th>Spacing</th>
                            <th>Occupancy</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#building_flat_list').on("preXhr.dt", function(e, settings, data) {
            data.parent = $('#flat_building_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            "columnDefs": [{
                "targets": [ {{ $hiddenRow }}],
                "visible": false
            }],
            ajax: {
                url: "/building/flat/list",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [0, "asc"]
            ],
        });

    });

    function goto_flat(){
        var flat_key = $('#flat_key').val();
        window.open('/building/flat/?_ref=' + flat_key, '_blank');
    }

    function block_flat(flat_id, status){
        var msg = status == 3 ? 'Block this Flat?' : 'Unblock this Flat?';
        Swal.fire({
            title: 'Are you sure?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Block'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '/building/flat/status',
                    data: {
                        _ref: flat_id,
                        status : status
                    },
                    success: function(response) {
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'Updated!',
                            'Flat Status updated!',
                            'success'
                        );
                        reload_datatable('#building_flat_list');
                    }
                });
            }
        });
    }
</script>