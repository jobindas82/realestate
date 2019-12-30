<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ strtoupper($model->entry_type->name) }}</title>
    @include('pdf.style.default')
</head>

<body>
    <div>
        <div class="col-6">
            <br>
            <div class="h3" style="margin-top: 0;margin-bottom: 0;">{{ ucfirst($model->entry_type->name) }} </div>
            <div class="input-block"><b>{{ $model->number }}</b></div><br><br><br>
        </div>
        <div class="col-5 offset-1" style="color:#101010;">
            <div class="text-right"><img src="{{ public_path('images/logo.png') }}" class="logo"></div> <br>
            <table class="table" style="width:100%;">
                <tr>
                    <td>Entry Date</td>
                    <td>{{ $model->formated_date() }}</td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" style="width:1%">#</th>
                    <th class="text-center" style="width:40%">Ledger</th>
                    <th class="text-center" style="width:30%">Dr.</th>
                    <th class="text-center" style="width:30%">Cr.</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $debitTotal = 0;
                    $creditTotal = 0;
                @endphp
                @foreach( $model->entries as $i => $each )

                @php
                   $debit = $each->amount > 0 ? number_format(round($each->amount, 2), 2, '.', '') : '';
                   $credit = $each->amount < 0 ? number_format(abs(round($each->amount, 2)), 2, '.', '') : '';

                   $debitTotal += (float) $debit;
                   $creditTotal += (float) $credit;
                   
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $each->ledger->name }}</td>
                    <td class="text-right">{{ $debit }}</td>
                    <td class="text-right">{{ $credit }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-right" colspan="2"><b>Total</b></td>
                    <td class="text-right"><b>{{ Akaunting\Money\Money::AED($debitTotal, true)->format() }}</b></td>
                    <td class="text-right"><b>{{ Akaunting\Money\Money::AED($creditTotal, true)->format() }}</b></td>
                </tr>
            </tbody>
        </table>
        <br>
    </div>
    <br><br> <br><br>
    <div style="position:relative">
        <div style="float:left; width: 40%;text-align: center;">
            ___________________________________________ <br>
            <span style="text-align: center;"><small>Prepared By / Signature</small></span>
        </div>
        <div style="width: 20%;"></div>
        <div style="width: 40%;float: right;text-align: center;">
            ___________________________________________ <br>
            <span style="text-align: center;"><small>Verified By / Signature</small></span>
        </div>
    </div>
    <htmlpagefooter name="myfooter">
        <div style="border-top: 1px dotted #000000; font-size: 10px; text-align: center; padding: 10px;">
            <div style="text-align:center;width:100%">Page {PAGENO} of {nb} </div>
        </div>
    </htmlpagefooter>
</body>

</html>