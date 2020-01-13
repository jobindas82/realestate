<div class="row clearfix">
    <input type="hidden" name="contracts_form_building_id" id="contracts_form_building_id">
    <input type="hidden" name="contract_form_flat_id" id="contract_form_flat_id">
    <input type="hidden" name="contract_form_contract_id" id="contract_form_contract_id">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-condensed table-hover" id="contracts_list">
                <thead>
                    <tr>
                        <th>Con. #</th>
                        <th>Tenant</th>
                        <th>Flat</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Renewed</th>
                        <th>Prev. Con.</th>
                        <th>Tax</th>
                        <th>Gross Amt.</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group align-center">
            <button class="btn btn-danger" href="#" 
                onclick="window.open('/report/export/contract?building=' + $('#summary_building_id').val() + '&flat=' + $('#summary_flat_id').val() + '&query=' + $('#contracts_list').DataTable().search() , '_block');">
                Export
            </button>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#contracts_list').on("preXhr.dt", function(e, settings, data) {
            data.building_id = $('#contracts_form_building_id').val();
            data.flat_id = $('#contract_form_flat_id').val();
            data.contract_id = $('#contract_form_contract_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/response/contracts",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [0, "desc"]
            ],
            columnDefs: [{
                targets: [9],
                visible: false
            }],
            rowCallback: function(row, data) {
                if (data[9] == 0) {
                    $(row).addClass("danger");
                }
            },
        });
    }); 
</script>