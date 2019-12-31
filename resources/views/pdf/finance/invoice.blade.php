<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice</title>
    @include('pdf.style.default')
</head>

<body>
    <div>
        <div class="col-6">
            <div class="h3" style="margin-top: 0;margin-bottom: 0;">TAX INVOICE/ فاتورة ضريبية</div>
            <div class="input-block"><b>{{ $model->number }}</b></div><br>
            @if( $model->tenant_id > 0 )
            <b>{{ strtoupper($model->tenant->name) }}</b><br>
            <small>{{ $model->tenant->email }}</small><br>
            <small>{{ $model->tenant->mobile }}</small><br>
            <small><b>TRN : {{ $model->tenant->trn_no }}</b></small><br>
            @endif
        </div>
        <div class="col-5 offset-1" style="color:#101010;">
            <div class="text-right"><img src="{{ public_path('images/logo.png') }}" class="logo"></div> <br>
            <table class="table" style="width:100%;">
                <tr>
                    <td>Invoice Date</td>
                    <td>{{ $model->formated_date() }}</td>
                </tr>
                <tr>
                    <td>Our TRN</td>
                    <td>10000000254XXXX</td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:1%">#</th>
                    <th style="width:80%">Description<br> تفاصيل</th>
                    <th style="width:19%">Amount<br>الإجمالي</th>
            </thead>
            <tbody>
                @foreach ($model->entries()->where('amount', '<', 0)->get() as $i => $eachItem) {
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $eachItem->ledger->name }}</td>
                        <td class="text-right">{{ Akaunting\Money\Money::AED(abs($eachItem->amount), true)->format() }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-right" colspan="2"><b>VAT/ ضريبة القيمة المضافة </b></td>
                    <td class="text-right"><b>{{ $model->totalTax() }}</b></td>
                </tr>
                <tr>
                    <td class="text-right total bc-bl" colspan="2"><b>Gross Amount/ الإجمالي</b></td>
                    <td class="text-right total bc-br"><b>{{ $model->totalAmount() }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br> <br><br>
    <div style="position:relative">
        <div style="float:left; width: 40%;text-align: center;">
            ___________________________________________ <br>
            <span style="text-align: center;"><small>Received By / Signature</small></span>
        </div>
        <div style="width: 20%;"></div>
        <div style="width: 40%;float: right;text-align: center;">
            ___________________________________________ <br>
            <span style="text-align: center;"><small>Prepared By / Signature</small></span>
        </div>
    </div>
    <htmlpagefooter name="myfooter">
        <div style="border-top: 1px dotted #000000; font-size: 10px; text-align: center; padding: 10px;">
            <div style="text-align:center;width:100%">Page {PAGENO} of {nb} </div>
        </div>
    </htmlpagefooter>
</body>

</html>