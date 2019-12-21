<!DOCTYPE html>
<html>
    
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>LEASE AGREEMENT</title>
        <style>
            * { font-family: DejaVu Sans, sans-serif; }
            p{
                text-align: justify;
                line-height: 20px;
                font: 13px arial;
            }
            .h6{
                font-size:12px;
                text-align:center;
                margin-top:10px;
            }
            .h4{
                padding-top:22px;
                text-align:center;   
                padding-bottom:2px;
            }
            .logo-img{
                text-align:center;
            }
            .top_border{
                border:1px solid #000;
                border-radius: 20px;
            }
            .date{
                padding-top:2px;
                text-align:center;
                padding-bottom:2px
            }
            .date1{
                margin-top:-10px;
                text-align:center;
                padding-bottom:5px;
                margin-left:15px;
            }
            .date2{
                margin-top:0px;
                text-align:center;
                padding-bottom:5px;
                margin-left:15px;
                float:right;
            }
            .circle{
                font-size:15px;
                font-weight:100;
            }
            .new p{
                padding-top:2px;
                text-align:left;
                padding-bottom:2px
            }
            .table1{
                border-collapse: collapse;
                text-align:left;
                padding:5px;
            }
            .table1 td, th {
                border: 1px solid black;
                text-align:left;
                padding:5px;
            }
            .head{
                background-color:#191970;
                height:10px;
                font-size:7px;
                text-transform:uppercase;
                color:#fff;
            }
            .box{
                border: 1px solid black;
                height:10px;
                width:80%;
                text-align:center;
                margin-left:20px;
            }
            .roundcircle {
                height: 25px;
                width: 25px;
            }
        </style>

    </head>

    <body>

        <div class="col-12">
            <div class="logo-img">
                <img src="images/logo/POS-Sales.png" alt="logo">
            </div> 
            <br>
            <?php
          
        ?>
            <div class="col-12 top_border">
                <div class="col-3 date">
                    <p>&nbsp;&nbsp;&nbsp;Date:....<?php //echo date("d/m/Y", strtotime($model->created_date)); ?>.... التاريخ</p>
                    <p>&nbsp;&nbsp;&nbsp;No:....<?php //echo $model->id; ?>........الرقم</p>
                </div>
                <div class="col-6 h4" style="margin-top: 0;margin-bottom: 0;">
                    <b><?php echo "عـــقـــد إيـــجـــــار"; ?></b><br>
                    <b><?php echo "TENANCY CONTRACT"; ?></b>
                </div>
                <div class="col-3 new">
                    <p>&nbsp;&nbsp;&nbsp;New <span class="circle"><img src="<?php //echo $lease_type_1; ?>"></span></p>
                    <p>Renew <span class="circle"><img src="<?php //echo $lease_type_2; ?>"></span></p>
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
                    <b><?php echo "عـــقـــد إيـــجـــــار"; ?></b><span class="roundcircle"><img src="<?php //echo $construct_type_3; ?>"></span><br>
                    <b><?php echo "Industrial"; ?></b>
                </div>
                <div class="col-2 h6" style="margin-top: 0;margin-bottom: 0;">
                    <br>
                    <b><?php echo "تجاري"; ?></b><span class="roundcircle"><img src="<?php //echo $construct_type_1; ?>"></span><br>
                    <b><?php echo "Commercial"; ?></b>
                </div>
                <div class="col-2 h6" style="margin-top: 0;margin-bottom: 0;">
                    <br>
                    <b><?php echo "سكني"; ?></b><span class="roundcircle"><img src="<?php //echo $construct_type_2; ?>"></span><br>
                    <b><?php echo "Residential"; ?></b>
                </div>
                <div class="col-2 date2" >
                    <p>&nbsp;&nbsp;&nbsp;استخدام الممتلكات</p>
                    <p>&nbsp;</p>
                </div>
            </div>
            <br>
            <table class="table1" border="1" style="width:100%;font-size:10px;margin-top:-20px;">
                <thead>
                    <tr> 
                        <th style="width:21.25%;"><b>Owner Name :</b></th>
                        <th style="width:64.25%;"><?php //echo $model->flat->owner_name; ?></th>
                        <th style="width:12%;text-align:right;">اسم المالك</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Landlord Name :</b></td>
                        <td><?php //echo $model->flat->landlord_name; ?></td>
                        <td style="text-align:right;">اسم المفجور</td>
                    </tr>
                    <tr>
                        <td><b>Tenant Name :</b></td>
                        <td><?php //echo $model->tenant->tenant_name; ?></td>
                        <td style="text-align:right;">اسم المستأجر</td>
                    </tr>
                    <tr>
                        <td><b>Landlord Email</b></td>
                        <td><?php //echo $modelCompany->genral_email; ?> </td>
                        <td style="text-align:right;">البريد التكرونى للمفجور</td>
                    </tr>
                    <tr>
                        <td><b>Tenant Email </b></td>
                        <td><?php //echo $model->tenant->email; ?></td>
                        <td style="text-align:right;">البريد الإلكتروني المستأجر</td>
                    </tr>
                </tbody>
            </table>
            <table class="table1" border="1" style="width:100%;font-size:10px;">
                <thead>
                    <tr>
                        <th style="width:21.25%;"><b>Tenant Phone </b></th>
                        <th><?php //echo $model->tenant->mobile; ?> </th>
                        <th>Landlord phone</th>
                        <th colspan="2" ><?php //echo $modelCompany->genral_phno; ?></th>
                        <th style="text-align:right;width:14.5%;">هاتف للمفوجر</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Building Name </b></td>
                        <td><?php //echo $model->building->building_name; ?></td>
                        <td>اسم المبني</td>
                        <td>Location</td>
                        <td><?php //echo $model->building->location; ?></td>
                        <td style="text-align:right;">المنطقة</td>
                    </tr>
                    <tr>
                        <td><b>Property size  </b></td>
                        <td><?php // echo $model->flat->squarefeet; ?> Sq.</td>
                        <td>مساحة الوحدة</td>
                        <td>Property Type</td>
                        <td><?php //echo $model->flat->flat_type_details->flat_type; ?></td>
                        <td style="text-align:right;">نوع الوحدة</td>
                    </tr>
                    <tr>
                        <td><b>Unit No   </b></td>
                        <td><?php //echo $model->flat->flat_no; ?></td>
                        <td>رقم العقار ديوة</td>
                        <td>Plot No </td>
                        <td><?php //echo $model->flat->plot_no; ?></td>
                        <td style="text-align:right;">رقم الارض</td>
                    </tr>	
                    <tr>
                        <td><b>Contract period To </b></td>
                        <td>من  From</td>
                        <td><?php //echo date("d/m/Y", strtotime($model->from_date)); ?></td>
                        <td><?php //echo date("d/m/Y", strtotime($model->to_date)); ?></td>
                        <td>إلى To</td>
                        <td style="text-align:right;">فترة الايجا</td>
                    </tr>
                    <tr>
                        <td><b>Annual rent </b></td>
                        <td colspan="4"><b>AED <?php //echo Yii::app()->numberFormatter->formatCurrency($rentAmount, "");?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php //echo ucfirst($this->widget('ext.NumtoWord.NumtoWord', array('num' => floatval($rentAmount)))->result) . ' Only/-'; ?></b></td>
                        <td style="text-align:right;">الايجار السنوي</td>
                    </tr>
                    <tr>
                        <td><b>Contract value </b></td>
                        <td colspan="4"><b>AED <?php //echo Yii::app()->numberFormatter->formatCurrency($rentAmount, "");?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php //echo ucfirst($this->widget('ext.NumtoWord.NumtoWord', array('num' => floatval($rentAmount)))->result) . ' Only/-'; ?> </b></td>
                        <td style="text-align:right;">قيمة العقد</td>
                    </tr>
                    <tr>
                        <td><b>Security Deposit </b></td>
                        <td><b><?php //echo Yii::app()->numberFormatter->formatCurrency($depositAmount, "");?> </b></td>
                        <td><b>مبلغ التامين </b></td>
                        <td><b>Mode of payment </b></td>
                        <td><b><?php //echo $trn;?>  </b></td>
                        <td style="text-align:right;"><b>طريقة الدفع</b></td>
                    </tr> 
                    <tr>
                        <td><b>FTA VAT (5%)</b></td>
                        <td><b><?php //echo Yii::app()->numberFormatter->formatCurrency($model->total_tax_amount, "");?></b></td>
                        <td colspan="4" style="text-align:right;"><b>سلطة الضرائب الفيدرالية ، ضريبة القيمة المضافة 5٪</b></td>
                    </tr> 					
                </tbody>
            </table>
            <br>
            <div class="col-12">
                <div class="col-6 head">
                    <p style="padding-left:20px;"><b>TERMS & CONDITIONS</b></p>
                </div>
                <div class="col-6 head">
                    <p style="text-align:center;"><b>الشروط و الأحكام</b></p>
                </div>
            </div>
            <div class="col-12">
                <div class="col-5">
                    <p style="text-align:left;font-size:9.5px;"><b>Returned cheques:</b></p>
                    <p style="text-align:justify;font-size:9.5px;line-height:18px;">In the case of a returned cheque by the band, the second party shall bear a penalty of AED 500/- for each retuned cheque, and for repeated retuned cheques of the rent value the contract shall be terminated automatically without serving notice, then the lessee is not entitled to deposit the rent value in the court, and the lesser has the right then to file a urgent plea to expel the lessee and register attachment without giving prior notice on all the belongings in the commercial store subject of this contract</p>	
                    
                </div>					 
                <div class="col-5" style="float:right">
                    <p style="text-align:right;font-size:9.5px;"><b>رجوع الشيكات من البنك صرف</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">
                        في حال رجوع شيك من البنك يدفع الطرف الثاني غرامة تقدر بمبلغ 500 درهم عن كل شيك مرتجع تسدد مع قيمة الشيك وفي حال تكرر استرجاع الشيكات الخاصة بالقيمة الايجارية يفسخ العقد فورا من تلقاء نفسه وبدون حاجة إلى التنبيه او إنذار ولا يحق له إيداع الايجار ويحق رفع دعوى مستعجلة بطرد المستأجر ويحق ايضا توقيع حجز تحفظي دون إنذار على جميع الاشياء الموجودة في المكان
                    </p>
                </div>	
            </div>


            <div class="col-12">
              <div class="col-5">
                <p style="text-align:center;font-size:9.5px;"><b>TENANT’S SIGNATURE</b></p>
                    <div class="box">
                    </div>
                  </div>
                   <div class="col-5" style="float:left;margin-left:120px;">
                   <p style="text-align:center;font-size:9.5px;"><b>LANDLORD’S SIGNATURE</b></p>
                    <div class="box">
                    </div>
                </div>
            </div>





            <div class="logo-img">
                <img src="images/logo/POS-Sales.png" alt="logo">
            </div> 
            <div class="col-12">
                <div class="col-5">
                    <p style="text-align:left;font-size:9.5px;"><b>Insurance:</b></p>
                    <p style="text-align:justify;font-size:9.5px;">The second party “the lessee” shall pay to the lesser an amount of AED (1000/-) upon signing this agreement as a warranty deposit, this deposit is only refundable at the end of the contract period after the payment of all utility services consumption and cleaning charges if not paid by the lessee.</p>	
                    <p style="text-align:left;font-size:9.5px;"><b>Waiver and sublease</b></p>
                    <p style="text-align:justify;font-size:9.5px;">Waiver and sublease; the lessee is not allowed to sublease or waive the leased shop or any part to it or changes its activity or the trade license or waives it or change sponsor or make any decoration without prior written consent from the lesser, and any violation to this condition shall deem to make this contract automatically nil and void, in case of a written consent of sublease by the lesser, the original lessee if lesser shall be responsible together the sub-lesser for the payment of the lease value and all article in this contract.</p>
                    <p style="text-align:left;font-size:9.5px;"><b>Vacating shop before end of contract:</b></p>	
                    <p style="text-align:justify;font-size:9.5px;">If the lessee desires to leave the leased shop before the end of the contract, he must inform the lesser by written notice to terminate the contract, he undertake to fulfill all his liabilities until the end of the commercial agreement as per this contract, the delivery of the shop must be done by virtue of a declaration signed by the lessee (the second party).<br>
                        In the case any of the two parties decide to renew the contract for another period or terminate it, he must inform the other party by written notice 90 days before the end of the contract.
                    </p>	
                    <p style="text-align:left;font-size:9.5px;"><b>Delivery of commercial store:</b></p>	
                    <p style="text-align:justify;font-size:9.5px;">The lesser undertake to deliver the commercial store to be used for the benefit lessee, and by signing this contract it is considered as a record of delivering the store.
                    </p>
                    <p style="text-align:left;font-size:9.5px;"><b>Alterations:</b></p>	
                    <p style="text-align:justify;font-size:9.5px;">The lesser undertake to make the necessary alterations, whereas the lessee shall make any lease changes, the lessee is not entitled to ask the lesser to make any changes beyond the limits of the laws and according accepted by the lesser without the lessee having to ask any compensation from the lesser. 
                    </p>							  

                </div>					 
                <div class="col-5" style="float:right">
                    <p style="text-align:right;font-size:9.5px;"><b>التأمين</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">دفع المستاجر مبلغ (1000 درهم) تأمين ولا يرد التأمين الا عند انتهاء عقد الايجار وتسليم العين المؤجرة محل هذا التعقد بالحالة التي كانت عليها وقت التعاقد فضلا عن الوفاء بالايجار كاملا وبعد سداد جميع الاستهلاكات ( كهرباء / مياه / صيانه ) وذلك إذا لم يفى بها المستاجر.</p>
                    <br>
                    <p style="text-align:right;font-size:9.5px;"><b>التنازل أو التأجير من الباطن</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">لا يحق للمستاجر أن يؤجر من الباطن او يتنازل عن كل المكان المؤجر أو جزء منه أو تغيير النشاط أو تغيير الرخصة التجارية أو التنازل  عنها أو تغيير الكفيل أو اجراء أي تعديل في الديكورات إلا بعد الحصول على موافقة المؤجر الكتابية من المالك وفي حال مخالفة هذا الشرط يعتبر العقد مفسوخا وفي حالة موافقة المؤجر الكتابية في التاجير من الباطن يكون المؤجر الاصلي ضامنا مع من اجر له في سداد الإيجار وتنفيذ جميع بنود هذا العقد.</p>
                    <br><br><br>
                    <p style="text-align:right;font-size:9.5px"><b>الاخلاء قبل الميعاد</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">إذا رغب المستاجر في ترد المكان قبل نهاية العقد عليه إخطار المؤجر بكتاب موصى بإنهاء العقد يلتزم بمقابل الانتفاع حتي تاريخ إنهاء العلاقة الايجارية المحددة بالعقد ويتم التسليم بموجب إقرار موقع من المستاجر (الطرف الثاني)</p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">وفي حالة اذا مارغب أي من الطرفين تجديد العقد مدة اخري او انهائه عليه اخطار الطرف الاخر برغبته في ذلك قبل انتهاء العقد بمدة 90 يوما بموجب اخطار كتابي.</p>
                    <br><br><br>
                    <p style="text-align:right;font-size:9.5px"><b>التسليم</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">يلتزم الموجر بتسليم المكان المؤجر للمستاجر للانتفاع به ويعتبر توقيع الطرف الاول على هذا العقد محضر بتسليم المستاجر المكان</p>
                    <br>
                    <p style="text-align:right;font-size:9.5px"><b>الترميمات</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">يلزم المؤجر بالترميمات الضرورية إما المستاجر فيلتزم بالترميمات التاجيرية فقط ولا يحق للمستاجر مطالبة المؤجر باجراء اية ترميمات الا في حدود القانون ووفقا للظروف التي يراها المؤجر دون أن يكون للمستاجر مطالبة المؤجر باي تعويض.</p>				  
                </div>	
            </div>	
            <br><br>				
            <div class="logo-img">
                <img src="images/logo/POS-Sales.png" alt="logo">
            </div> 
            <div class="col-12">
                <div class="col-5">
                    <p style="text-align:left;font-size:9.5px;"><b>Conditions of the leased store:</b></p>
                    <p style="text-align:justify;font-size:9.5px;">The lessee acknowledge and agree that he has inspected the commercial store and found it in a good and acceptable conditions, and also the accessories such as doors and windows, glass, electrical and sanitary ware and undertake to take care of the store as required by similar premises and shall not make any changes, demolition or construction otherwise the contract shall be considered terminated immediately and the lessee shall maintain the store in good conditions as it was before and compensate the lesser for any damages sustained accordingly.</p>	
                    <p style="text-align:left;font-size:9.5px;"><b>Sale of the property</b></p>
                    <p style="text-align:justify;font-size:9.5px;">In case the property is sold, the first party shall notify the second party, then the new buyer is not entitled to terminate this contract only after the end of its determined validity period, the buyer must inform the lessee by a written notice with a receipt of his intention to renew or terminate six months before the end of the period.</p>
                    <p style="text-align:left;font-size:9.5px;"><b>Address:</b></p>	
                    <p style="text-align:justify;font-size:9.5px;">The lessee declares it’s officially address to be used for my legal correspondences and notice;
                    </p>
                    <p style="text-align:justify;font-size:9.5px;">If the tenant is absent from the town at the expiry of the period of tenancy or leaves the property without Landlord’s consents or if he has not paid the balance of the rent due by him, the landlord shall have the right to open the property and lease it to others that he finds suitable and the tenant shall have no right to object or make any claims in the future.
                    </p>							  
                    <p style="text-align:left;font-size:9.5px;"><b>Article 11</b></p>	
                    <p style="text-align:justify;font-size:9.5px;">UAE laws shall govern any dispute that may arise out of this lease contract. 
                    </p>
                    <p style="text-align:left;font-size:9.5px;"><b>Article 12</b></p>	
                    <p style="text-align:justify;font-size:9.5px;">This contract is made in two original copies, one copy for each party to act upon accordingly. 
                    </p>							  
                    
                </div>					 
                <div class="col-5" style="float:right">
                    <p style="text-align:right;font-size:9.5px;"><b>حالة المكان</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">يقر المستاجر بانه قد عاين الوحدة بنفسة وقبلها بالحالة التي هي عليها مستوفيا كل لوازمها من ابواب ونوافذ زجاج وادوات صحية وكهربائية كما يتعهد بالمحافظة عليها وصيانتها بما يتفق والاستعمال للوحدات المماثلة يمتنع عن إجراء تغييرات أو بناء ما يضر بسلامة العقار الا اعتبر هذا العقد مفسوخا فورا مع الزام المستاجر اعادة المكان الى ما كان عليه فضلا عن الزامه بكافة تعويضات التي تترتب على ذلك.</p>
                    <br>
                    <p style="text-align:right;font-size:9.5px;"><b>بيع العقار</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">في حال بيع العقار يخطر الطرف الاول الطرف الثاني ذلك ولا يحق للمشتري فسخ العقد إلا بعد انتهاء المدة المحددة وعليه أن يخطر المستاجر برغبته في التجديدأو انتهاء وذلك قبل انتهاء المدة بمدة لا تقل عن ستة اشهر بانذار رسمي أو خطاب موصي عليه بعلم الوصول</p>
                    <br><br><br>
                    <p style="text-align:right;font-size:9.5px"><b>الموطن المختار</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">يقر المستاجر ان العين موضوع التعاقد موطنا مختار له وكل خطاب أو اعلان يرسل له فيه يعد قانونيا.</p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">اذا كان المستاجر غائبا عن المدينة عند انتهاء فترة السريان عقد الايجار أو في حالة ترك العقار من دون الموافقة المؤجر أو في حالة عدم دفع الرصيد الباقي من الايجار المستحق الاداء من قبله ، يحق للمؤجر الدخول العقار ويجاره للاخرين وفقا لما يراه ملائما ولا يحق للمستاجر الاعتراض أو القيام باية مطالبة أو دعوي في المستقبل.</p>
                    <br><br><br>
                    <p style="text-align:right;font-size:9.5px"><b>البند الحادي عشر</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">في حال مشوء أي خلاف لا قدر الله بخصوص هذا العقد تكون قوانين دولة الإمارات العربية المتحدة هي صاحبة الاختصاص.</p>
                    <br>
                    <p style="text-align:right;font-size:9.5px"><b>البند الثاني عشر</b></p>
                    <p style="text-align:right;line-height:18px;font-size:9.5px;">تحرر هذا العقد من نسختين بيد كل طرف نسخة للعمل بموجبها عند اللزوم</p>				  
                                    </div>					
            </div>

<div class="col-12">
              <div class="col-5">
                <p style="text-align:center;font-size:9.5px;"><b>TENANT’S SIGNATURE</b></p>
                    <div class="box">
                    </div>
                  </div>
                   <div class="col-5" style="float:left;margin-left:120px;">
                   <p style="text-align:center;font-size:9.5px;"><b>LANDLORD’S SIGNATURE</b></p>
                    <div class="box">
                    </div>
                </div>
            </div>

        </div>
    <htmlpagefooter name="myfooter">


        <div style="text-align:center;width:100%;font-size:10px"><?php // Yii::app()->user->footer_autogenerated; ?>
            <?php
           // date_default_timezone_set("Asia/Dubai");
            //echo date('d/m/Y') . " " . date("h:i:sa");
            ?>
        </div>


        <div style="border-top: 1px dotted #000000; font-size: 12px; text-align: center; padding-top: 10px; background-color: #d7faeb;">
            <div style="text-align:center;width:100%">
                <b><?php //echo Yii::app()->user->footer_line_1; ?></b><br>
                <?php //echo Yii::app()->user->footer_line_2; ?>
            </div>
            <div style="text-align:center;width:100%">Page {PAGENO} of {nb} </div>
        </div>
    </htmlpagefooter>
</body>

</html>