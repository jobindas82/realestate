<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LEASE AGREEMENT</title>
    @include('pdf.style.default')
    @include('pdf.style.contract')
</head>

<body>

    <div class="col-12">
        <div class="logo-img">
            <img src="{{ public_path('images/logo.png') }}" alt="logo">
        </div>
        <br>
        <?php

        ?>
        <div class="col-12 top_border">
            <div class="col-3 date">
                <p>&nbsp;&nbsp;&nbsp;Date: <u>{{ $model->formated_generated_date() }}&nbsp;&nbsp;&nbsp;&nbsp;</u> التاريخ</p>
                <p>&nbsp;&nbsp;&nbsp;No: <u> {{ $model->id }}&nbsp;&nbsp;&nbsp;&nbsp;</u>الرقم</p>
            </div>
            <div class="col-6 h4" style="margin-top: 0;margin-bottom: 0;">
                <b>عـــقـــد إيـــجـــــار"</b><br>
                <b>TENANCY CONTRACT</b>
            </div>
            <div class="col-3 new">
                <p>&nbsp;&nbsp;&nbsp;New @if(!$model->isRenewed()) &#11044; @else &#9711; @endif </p>
                <p>Renew @if($model->isRenewed()) &#11044; @else &#9711; @endif </p>
            </div>

        </div>
        <br>
        <div class="col-12">
            <div class="col-2 date1">
                <p><b>&nbsp;&nbsp;&nbsp;Property Usage</b></p>
                <p>&nbsp;</p>
            </div>
            <div class="col-3 h6" style="margin-top: 0;margin-bottom: 0;">
                <br>
                <b>عـــقـــد إيـــجـــــار </b> &#9711;<br>
                <b>Industrial</b>
            </div>
            <div class="col-2 h6" style="margin-top: 0;margin-bottom: 0;">
                <br>
                <b>تجاري </b> @if( $model->flat->isCommercial() ) &#11044; @else &#9711; @endif<br>
                <b>Commercial</b>
            </div>
            <div class="col-2 h6" style="margin-top: 0;margin-bottom: 0;">
                <br>
                <b>سكني </b> @if( $model->flat->isResidential() ) &#11044; @else &#9711; @endif<br>
                <b>Residential</b>
            </div>
            <div class="col-2 date2">
                <p>&nbsp;&nbsp;&nbsp;استخدام الممتلكات</p>
                <p>&nbsp;</p>
            </div>
        </div>
        <br>
        <table class="table1" border="1" style="width:100%;font-size:10px;margin-top:-20px;">
            <thead>
                <tr>
                    <th style="width:21.25%;"><b>Owner Name :</b></th>
                    <th style="width:64.25%;">{{ $model->flat->owner_name }}</th>
                    <th style="width:12%;text-align:right;">اسم المالك</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Landlord Name :</b></td>
                    <td>{{ $model->flat->landlord_name }}</td>
                    <td style="text-align:right;">اسم المفجور</td>
                </tr>
                <tr>
                    <td><b>Tenant Name :</b></td>
                    <td>{{ $model->tenant->name }}</td>
                    <td style="text-align:right;">اسم المستأجر</td>
                </tr>
                <tr>
                    <td><b>Landlord Email</b></td>
                    <td>-</td>
                    <td style="text-align:right;">البريد التكرونى للمفجور</td>
                </tr>
                <tr>
                    <td><b>Tenant Email </b></td>
                    <td>{{ $model->tenant->email }}</td>
                    <td style="text-align:right;">البريد الإلكتروني المستأجر</td>
                </tr>
            </tbody>
        </table>
        <table class="table1" border="1" style="width:100%;font-size:10px;">
            <thead>
                <tr>
                    <th style="width:21.25%;"><b>Tenant Phone </b></th>
                    <th>{{ $model->tenant->mobile }}</th>
                    <th>Landlord phone</th>
                    <th colspan="2">-</th>
                    <th style="text-align:right;width:14.5%;">هاتف للمفوجر</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Building Name </b></td>
                    <td>{{ $model->building->name }}</td>
                    <td>اسم المبني</td>
                    <td>Location</td>
                    <td>{{ $model->building->location->name }}</td>
                    <td style="text-align:right;">المنطقة</td>
                </tr>
                <tr>
                    <td><b>Property size </b></td>
                    <td>{{ $model->flat->square_feet }} ft<sup>2</td>
                    <td>مساحة الوحدة</td>
                    <td>Property Type</td>
                    <td>{{ $model->flat->flat_type->name }}</td>
                    <td style="text-align:right;">نوع الوحدة</td>
                </tr>
                <tr>
                    <td><b>Unit No </b></td>
                    <td>{{ $model->flat->name }}</td>
                    <td>رقم العقار ديوة</td>
                    <td>Plot No </td>
                    <td>{{ $model->flat->plot_no }}</td>
                    <td style="text-align:right;">رقم الارض</td>
                </tr>
                <tr>
                    <td><b>Contract period To </b></td>
                    <td>من From</td>
                    <td>{{ $model->formated_from_date() }}</td>
                    <td>{{ $model->formated_to_date() }}</td>
                    <td>إلى To</td>
                    <td style="text-align:right;">فترة الايجا</td>
                </tr>
                <tr>
                    <td><b>Annual rent </b></td>
                    <td colspan="4"><b>0.00&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                    <td style="text-align:right;">الايجار السنوي</td>
                </tr>
                <tr>
                    <td><b>Contract value </b></td>
                    <td colspan="4"><b>{{ $model->grossAmount() }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                    <td style="text-align:right;">قيمة العقد</td>
                </tr>
                <tr>
                    <td><b>Security Deposit </b></td>
                    <td><b>0.00</b></td>
                    <td><b>مبلغ التامين </b></td>
                    <td><b>Mode of payment </b></td>
                    <td><b></b></td>
                    <td style="text-align:right;"><b>طريقة الدفع</b></td>
                </tr>
                @if( $model->flat->isCommercial() )
                    <tr>
                        <td><b>FTA VAT (5%)</b></td>
                        <td><b>{{ $model->taxAmount() }}</b></td>
                        <td colspan="4" style="text-align:right;"><b>سلطة الضرائب الفيدرالية ، ضريبة القيمة المضافة 5٪</b></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
        @if( $model->flat->isCommercial() )
            @include('pdf.contract.commercial')
        @endif

        @if( $model->flat->isResidential() )
            @include('pdf.contract.residential')
        @endif
    </div>
    <htmlpagefooter name="myfooter">
        <div style="border-top: 1px dotted #000000; font-size: 10px; text-align: center; padding: 10px;">
            <div style="text-align:center;width:100%">Page {PAGENO} of {nb} </div>
        </div>
    </htmlpagefooter>
</body>

</html>