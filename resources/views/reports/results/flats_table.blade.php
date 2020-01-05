<div class="row clearfix">
    <input type="hidden" name="flat_form_building_id" id="flat_form_building_id">
    <input type="hidden" name="flat_form_flat_id" id="flat_form_flat_id">
    <div class="col-sm-12">
        <table class="table table-condensed table-hover" id="flat_building_list">
            <thead>
                <tr>
                    <th style="width: 5%">Flat</th>
                    <th style="width: 10%">Premise #</th>
                    <th style="width: 10%">Plot #</th>
                    <th style="width: 5%">Floor</th>
                    <th style="width: 5%">f<sup>2</sup></th>
                    <th style="width: 5%">Min Value</th>
                    <th style="width: 15%">Owner</th>
                    <th style="width: 15%">Landlord</th>
                    <th style="width: 10%">Type</th>
                    <th style="width: 10%">Spacing</th>
                    <th style="width: 0%">Status</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-sm-12">
        <div class="form-group align-center">
            <button class="btn btn-danger" href="#" 
                onclick="window.open('/report/export/flat?building=' + $('#summary_building_id').val() + '&flat=' + $('#summary_flat_id').val() + '&query=' + $('#flat_building_list').DataTable().search() , '_block');">
                Export
            </button>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#flat_building_list').on("preXhr.dt", function(e, settings, data) {
            data.building_id = $('#flat_form_building_id').val();
            data.flat_id = $('#flat_form_flat_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/response/flats",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [0, "asc"]
            ],
            columnDefs: [{
                targets: [10],
                visible: false
            }],
            rowCallback: function(row, data) {
                if (data[10] == 1) {
                    $(row).addClass("success");
                }
            },
        });
    });
</script>