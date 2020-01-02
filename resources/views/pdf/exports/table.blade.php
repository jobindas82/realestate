<!DOCTYPE html>
<html>

<head>
    <title>{{ $props['title'] }}</title>
    @include('pdf.style.default')
</head>

<body>
    <div>
        <div class="col-6"><br>
            <div class="h3" style="margin-top: 0;margin-bottom: 0;">{{ $props['title'] }}</div>
            <br><br> <br><br>
            <table class="table" style="width:100%">
                @if( isset($props['customField']) )
                <tr>
                    <td><b>{{ $props['customField']['name'] }}</b></td>
                    <td><b>{{ $props['customField']['value'] }}</b></td>
                </tr>
                @endif
                @if( isset($props['date']) )
                <tr>
                    <td><b>From</b></td>
                    <td><b>{{ date('d/m/Y', strtotime($props['date'])) }}</b></td>
                </tr>
                @endif
                @if( isset($props['from_date']) )
                <tr>
                    <td><b>To</b></td>
                    <td><b>{{ date('d/m/Y', strtotime($props['from_date'])) }}</b></td>
                </tr>
                @endif
                @if( isset($props['to_date']) )
                <tr>
                    <td><b>Date</b></td>
                    <td><b>{{ date('d/m/Y', strtotime($props['to_date'])) }}</b></td>
                </tr>
                @endif
            </table>
        </div>
        <div class="col-5 offset-1" style="color:#101010;">
            <div class="text-right">
                <img src="{{ public_path('images/logo.png') }}" class="logo">
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="clearfix"></div>
    <div class="panel">
        <div class="panel-body text-bold">
            <?php echo $props['table'] ?>
        </div>
    </div>
    <htmlpagefooter name="myfooter">
        <div style="border-top: 1px dotted #000000; font-size: 10px; text-align: center; padding: 10px;">
            <div style="text-align:center;width:100%">Page {PAGENO} of {nb} </div>
        </div>
    </htmlpagefooter>
</body>

</html>