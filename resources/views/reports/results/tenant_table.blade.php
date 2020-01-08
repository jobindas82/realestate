<div class="row clearfix">
    <input type="hidden" name="tenant_form_building_id" id="tenant_form_building_id">
    <input type="hidden" name="tenant_form_flat_id" id="tenant_form_flat_id">
    <input type="hidden" name="tenant_form_contract_id" id="tenant_form_contract_id">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-condensed table-hover" id="tenant_list">
                <thead>
                    <tr>
                        <th style="width: 2%;">#</th>
                        <th style="width: 15%;">Contract #</th>
                        <th style="width: 15%;">Name</th>
                        <th style="width: 10%;">Mobile</th>
                        <th style="width: 10%;">Phone</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%;">Emirates ID</th>
                        <th style="width: 10%;">Passport</th>
                        <th style="width: 15%;">TRN No</th>
                        <th style="width: 2%;">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group align-center">
            <button class="btn btn-danger" href="#" 
                onclick="window.open('/report/export/tenant?building=' + $('#summary_building_id').val() + '&flat=' + $('#summary_flat_id').val() + '&query=' + $('#tenant_list').DataTable().search() , '_block');">
                Export
            </button>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#tenant_list').on("preXhr.dt", function(e, settings, data) {
            data.building_id = $('#tenant_form_building_id').val();
            data.flat_id = $('#tenant_form_flat_id').val();
            data.contract_id = $('#tenant_form_contract_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/response/tenants",
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
            // rowCallback: function(row, data) {
            //     if (data[9] == 2) {
            //         $(row).addClass("warning");
            //     }
            //     if (data[9] == 3) {
            //         $(row).addClass("danger");
            //     }
            // },
        });
    });
</script>