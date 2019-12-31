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
            <div class="h3" style="margin-top: 0;margin-bottom: 0;">{{ ucfirst($model->entry_type->name) }} </div>
            <div class="input-block"><b>{{ $model->number }}</b></div><br>
            @if( $model->tenant_id > 0 )
            <small>Received From :</small><br>
            <b>{{ strtoupper($model->tenant->name) }}</b><br>
            <small>{{ $model->tenant->email }}</small><br>
            <small>{{ $model->tenant->mobile }}</small><br>
            @endif
        </div>
        <div class="col-5 offset-1" style="color:#101010;">
            <div class="text-right"><img src="{{ public_path('images/logo.png') }}" class="logo"></div> <br>
            <table class="table" style="width:100%;">
                <tr>
                    <td>Receipt Date</td>
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
                    <th class="text-center" style="width:80%">Details</th>
                    <th class="text-center" style="width:20%">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-bottom:1px solid #fff;"><b>Account :</b></td>
                    <td class="text-right" style="border-bottom:none;border-top:none;"><b>&nbsp;</b></td>
                </tr>
                @foreach( $model->entries()->where('amount', '<', 0)->get() as $each )
                    <tr>
                        <td style="border-bottom:none;border-top:none;">&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $each->ledger->name }}</b><br><br></td>
                        <td class="text-right" style="border-bottom:none;border-top:none;">
                            <b>{{ Akaunting\Money\Money::AED(abs($each->amount), true)->format() }}</b>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="border-bottom:none;border-top:none;"><b>Through :</b></td>
                        <td class="text-right" style="border-bottom:none;border-top:none;"><b>&nbsp;</b></td>
                    </tr>

                    @foreach( $model->entries()->where('amount', '>', 0)->get() as $each )
                    <tr>
                        <td style="border-bottom:none;border-top:none;"> &nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $each->ledger->name }}</b><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<small><b>Amount : </b>{{ Akaunting\Money\Money::AED(abs($each->amount), true)->format() }}</small>
                        </td>
                        <td class="text-right" style="border-bottom:none;border-top:none;"><b>&nbsp;</b></td>
                    </tr>
                    @endforeach

                    <tr>
                        <td style="border-bottom:none;border-right:none; padding: 5px;">
                            Method : <b>{{ $model->paymentMethod() }}</b><br>
                            <small><?php echo nl2br($model->paymentMethodDetails()) ?></small><br>
                        </td>
                        <td class="text-right" style="border-bottom:none;border-left:none;"><b>&nbsp;</b></td>
                    </tr>

                    @if( $model->narration != NULL )
                    <tr>
                        <td style="border-bottom:none;border-right:none;">
                            <b>Narration :</b> <br><small>{{ $model->narration }}</small>
                        </td>
                        <td class="text-right" style="border-bottom:none;border-left:none;"><b>&nbsp;</b></td>
                    </tr>
                    @endif

                    <tr>
                        <td class="text-right"><b>Total</b></td>
                        <td class="text-right"><b>{{ $model->totalAmount() }}</b></td>
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