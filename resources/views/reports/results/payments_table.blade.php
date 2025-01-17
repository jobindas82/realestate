<div class="row clearfix">
    <input type="hidden" name="payments_form_building_id" id="payments_form_building_id">
    <input type="hidden" name="payments_form_flat_id" id="payments_form_flat_id">
    <input type="hidden" name="payments_form_contract_id" id="payments_form_contract_id">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-condensed table-hover" id="payments_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Contract #</th>
                        <th>Method</th>
                        <th>Cheque #</th>
                        <th>Cheque Date</th>
                        <th>Tenant</th>
                        <th>Narration</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group align-center">
            <button class="btn btn-danger" href="#" 
                onclick="window.open('/report/export/finance?type=2&building=' + $('#summary_building_id').val() + '&flat=' + $('#summary_flat_id').val() + '&query=' + $('#flat_building_list').DataTable().search() , '_block');">
                Export
            </button>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#payments_list').on("preXhr.dt", function(e, settings, data) {
            data.building_id = $('#payments_form_building_id').val();
            data.flat_id = $('#payments_form_flat_id').val();
            data.contract_id = $('#payments_form_contract_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/response/finance/2",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [1, "desc"]
            ]
        });
    });
</script>