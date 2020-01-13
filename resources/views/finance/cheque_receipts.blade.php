<table class="table table-condensed table-hover" id="receipts_cheque_list">
    <thead>
        <tr>
            <th>#</th>
            <th>Receipt Date</th>
            <th>Cheque Date</th>
            <th>Cheque No.</th>
            <th>Bank</th>
            <th>Narration</th>
            <th>Amount</th>
            <th>Actions</th>
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