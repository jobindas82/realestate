<div class="row clearfix">
    <input type="hidden" name="ledgers_form_building_id" id="ledgers_form_building_id">
    <input type="hidden" name="ledgers_form_flat_id" id="ledgers_form_flat_id">
    <input type="hidden" name="ledgers_form_contract_id" id="ledgers_form_contract_id">
    <div class="col-sm-12">
        <table class="table table-condensed table-hover" id="ledgers_list">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 70%;">Ledger</th>
                    <th style="width: 25%;">Balance</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    $(function() {
        $('#ledgers_list').on("preXhr.dt", function(e, settings, data) {
            data.building_id = $('#ledgers_form_building_id').val();
            data.flat_id = $('#ledgers_form_flat_id').val();
            data.contract_id = $('#ledgers_form_contract_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/response/ledgers",
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