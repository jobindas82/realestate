<!-- <div class="row clearfix">
    <div class="col-sm-3">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" name="receipts_from_date" id="receipts_from_date" class="form-control datepicker" value="{{ date('01/01/Y') }}">
                <label class="form-label">From</label>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" name="receipts_to_date" id="receipts_to_date" class="form-control datepicker" value="{{ date('d/m/Y') }}">
                <label class="form-label">To</label>
            </div>
        </div>
    </div>
</div> -->
<div class="row clearfix">
    <input type="hidden" name="receipts_form_building_id" id="receipts_form_building_id">
    <input type="hidden" name="receipts_form_flat_id" id="receipts_form_flat_id">
    <input type="hidden" name="receipts_form_contract_id" id="receipts_form_contract_id">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-condensed table-hover" id="receipts_list">
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
                        <th style="width: 8%;">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group align-center">
            <button class="btn btn-danger" href="#" 
                onclick="window.open('/report/export/finance?type=1&building=' + $('#summary_building_id').val() + '&flat=' + $('#summary_flat_id').val() + '&query=' + $('#flat_building_list').DataTable().search() , '_block');">
                Export
            </button>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#receipts_list').on("preXhr.dt", function(e, settings, data) {
            data.building_id = $('#receipts_form_building_id').val();
            data.flat_id = $('#receipts_form_flat_id').val();
            data.contract_id = $('#receipts_form_contract_id').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 100,
            ajax: {
                url: "/report/response/finance/1",
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