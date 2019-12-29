<table class="table table-condensed table-hover" id="receipts_cheque_list">
    <thead>
        <tr>
            <th style="width: 1%">#</th>
            <th style="width: 10%">Receipt Date</th>
            <th style="width: 10%">Cheque Date</th>
            <th style="width: 15%">Cheque No.</th>
            <th style="width: 20%">Bank</th>
            <th style="width: 25%">Narration</th>
            <th class="align-right" style="width: 10%">Amount</th>
            <th style="width: 15%">Actions</th>
        </tr>
    </thead>
</table>

<script>
    $(function() {
        $('#receipts_cheque_list').on("preXhr.dt", function(e, settings, data) {
            data.type = 1;
            data.history = 0;
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/finance/cheques/list",
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