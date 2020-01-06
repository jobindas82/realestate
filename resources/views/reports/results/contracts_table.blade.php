<div class="row clearfix">
    <input type="hidden" name="contracts_form_building_id" id="contracts_form_building_id">
    <input type="hidden" name="contract_form_flat_id" id="contract_form_flat_id">
    <input type="hidden" name="contract_form_contract_id" id="contract_form_contract_id">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-condensed table-hover" id="contracts_list">
                <thead>
                    <tr>
                        <th style="width: 5%">Con. #</th>
                        <th style="width: 25%">Tenant</th>
                        <th style="width: 10%">Flat</th>
                        <th style="width: 10%">From Date</th>
                        <th style="width: 10%">To Date</th>
                        <th style="width: 5%">Renewed</th>
                        <th style="width: 10%">Prev. Con.</th>
                        <th style="width: 10%">Tax</th>
                        <th style="width: 15%">Gross Amt.</th>
                        <th style="width: 0%">Status</th>
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