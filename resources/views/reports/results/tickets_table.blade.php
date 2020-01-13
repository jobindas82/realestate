<div class="row clearfix">
    <input type="hidden" name="tickets_form_building_id" id="tickets_form_building_id">
    <input type="hidden" name="tickets_form_flat_id" id="tickets_form_flat_id">
    <input type="hidden" name="tickets_form_contract_id" id="tickets_form_contract_id">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-condensed table-hover" id="tickets_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Tenant</th>
                        <th>Contract #</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Details</th>
                        <th>Remarks</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group align-center">
            <button class="btn btn-danger" href="#" 
                onclick="window.open('/report/export/ticket?building=' + $('#summary_building_id').val() + '&flat=' + $('#summary_flat_id').val() + '&query=' + $('#flat_building_list').DataTable().search() , '_block');">
                Export
            </button>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#tickets_list').on("preXhr.dt", function(e, settings, data) {
            data.building_id = $('#tickets_form_building_id').val();
            data.flat_id = $('#tickets_form_flat_id').val();
            data.contract_id = $('#tickets_form_contract_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/report/response/tickets",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [1, "asc"]
            ]
        });
    });
</script>