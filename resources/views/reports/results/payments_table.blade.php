<div class="row clearfix">
    <input type="hidden" name="payments_form_building_id" id="payments_form_building_id">
    <input type="hidden" name="payments_form_flat_id" id="payments_form_flat_id">
    <input type="hidden" name="payments_form_contract_id" id="payments_form_contract_id">
    <div class="col-sm-12">
        <table class="table table-condensed table-hover" id="payments_list">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 5%;">Date</th>
                    <th style="width: 7%;">Contract #</th>
                    <th style="width: 8%;">Method</th>
                    <th style="width: 6%;">Cheque #</th>
                    <th style="width: 8%;">Cheque Date</th>
                    <th style="width: 15%;">Tenant</th>
                    <th style="width: 17%;">Narration</th>
                    <th style="width: 12%;">Amount</th>
                    <th style="width: 7%;">Status</th>
                </tr>
            </thead>
        </table>
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