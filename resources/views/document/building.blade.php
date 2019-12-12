@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ $title }}
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-sm-12">
                                <form action="/document/upload" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="from" value="{{ $from }}">
                                    <input type="hidden" name="parent" value="{{ $parent }}">

                                    <div class="dz-message">
                                        <div class="drag-icon-cph">
                                            <i class="material-icons">touch_app</i>
                                        </div>
                                        <h3>Drop files here or click to upload.</h3>
                                    </div>
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                </form>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group align-right">
                                <button class="btn btn-danger" onclick="window.close();"> Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var total_photos_counter = 0;
    Dropzone.options.frmFileUpload = {
        uploadMultiple: true,
        parallelUploads: 2,
        maxFilesize: 16,
        addRemoveLinks: true,
        dictRemoveFile: 'Remove',
        dictFileTooBig: 'Image is larger than 16MB',
        timeout: 10000,
        acceptedFiles: 'image/*, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword',

        init: function() {
            this.on("removedfile", function(file) {
                console.log(file);
                $.post({
                    url: '/document/remove',
                    data: {
                        id: file.name,
                        _token: $('[name="_token"]').val()
                    },
                    dataType: 'json',
                    success: function(data) {
                        total_photos_counter--;
                    }
                });
            });
        },
        success: function(file, done) {
            total_photos_counter++;
        }
    };
</script>
@endsection