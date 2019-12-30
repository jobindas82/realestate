<table class="table table-condensed table-hover" id="jobs_list">
    <thead>
        <tr>
            <th style="width: 1%">#</th>
            <th style="width: 5%">Date</th>
            <th style="width: 15%">Tenant</th>
            <th style="width: 10%">Contract #</th>
            <th style="width: 40%">Details</th>
            <th style="width: 10%">Actions</th>
        </tr>
    </thead>
</table>

<script>
    $(function() {
        $('#jobs_list').on("preXhr.dt", function(e, settings, data) {
            data.job_type = 2;
            data.status = 1;
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/fm/tickets/list",
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