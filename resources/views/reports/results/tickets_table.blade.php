<div class="row clearfix">
    <input type="hidden" name="tickets_form_building_id" id="tickets_form_building_id">
    <input type="hidden" name="tickets_form_flat_id" id="tickets_form_flat_id">
    <input type="hidden" name="tickets_form_contract_id" id="tickets_form_contract_id">
    <div class="col-sm-12">
        <table class="table table-condensed table-hover" id="tickets_list">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 5%">Date</th>
                    <th style="width: 10%">Tenant</th>
                    <th style="width: 8%">Contract #</th>
                    <th style="width: 8%">Category</th>
                    <th style="width: 10%">Priority</th>
                    <th style="width: 25%">Details</th>
                    <th style="width: 25%">Remarks</th>
                    <th style="width: 8%">Status</th>
                </tr>
            </thead>
        </table>
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