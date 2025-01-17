<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>
                Documents
            </h2>
            <ul class="header-dropdown m-r--5">
                <li>
                    <a href="javascript:void(0);" onclick="reload_datatable('#document_list');" title="Refresh">
                        <i class="material-icons">loop</i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <input type="hidden" name="doc_parent_id" id="doc_parent_id" value="{{ $model->id }}">
                        <input type="hidden" name="doc_from" id="doc_from" value="{{ $from }}">
                        <input type="hidden" name="parent_key" id="parent_key" value="{{ $model->encoded_key() }}">
                        <li><a href="#" onclick="goto_doc();"><i class="material-icons">add_circle</i> Add</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover dataTable" id="document_list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Expiry Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#document_list').on("preXhr.dt", function(e, settings, data) {
            data.parent = $('#doc_parent_id').val();
            data.from=  $('#doc_from').val();
            return data;
        }).DataTable({
            responsive: true,
            pageLength: 50,
            ajax: {
                url: "/document/get_documents",
                type: "POST",
                cache: false,
            },
            serverSide: true,
            fixedColumns: true,
            processing: true,
            order: [
                [0, "asc"]
            ],
        });

    });

    function save_doc(document_id) {
        var title = $('#doc_title_' + document_id).val();
        var expiry_date = $('#doc_exp_' + document_id).val();

        $.ajax({
            type: "POST",
            url: '/document/update_document',
            data: {
                _ref: document_id,
                title: title,
                expiry_date: expiry_date
            },
            success: function(response) {
                if (response.message == 'success') {

                    $('.page-loader-wrapper').fadeOut();
                    Swal.fire(
                        'SUCCESS',
                        'Document Updated!',
                        'success'
                    );

                } else {
                    $('.page-loader-wrapper').fadeOut();
                    $.each(response, function(fieldName, fieldErrors) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: 'Is your date valid?'
                        });
                    });
                }
            }
        });
    }


    function remove_doc(document_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '/document/remove_with_ref',
                    data: {
                        _ref: document_id
                    },
                    success: function(response) {
                        $('.page-loader-wrapper').fadeOut();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        reload_datatable('#document_list');
                    }
                });
            }
        });
    }

    function goto_doc(){
        var parent_key = $('#parent_key').val();
        window.open('/document/create/?__uuid=' + parent_key + '&__from={{ $from }}', '_blank');
    }
</script>