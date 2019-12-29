<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $model->entry_type->name }}</title>
    @include('pdf.style.default')
</head>

<body>

    <div class="col-12">
        <div class="logo-img">
            <img src="{{ public_path('images/logo.png') }}" alt="logo">
        </div>
    </div>
    <htmlpagefooter name="myfooter">
        <div style="border-top: 1px dotted #000000; font-size: 10px; text-align: center; padding: 10px;">
            <div style="text-align:center;width:100%">Page {PAGENO} of {nb} </div>
        </div>
    </htmlpagefooter>
</body>

</html>