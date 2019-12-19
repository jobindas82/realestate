<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <style>

            @page { margin: 15px; }

            body {
                font-size: 11px;
                font-family: <?=$this->font?>;
            }


            h1, h2, h3 ,h4 {
                color: #3c3c3c;
                margin: 5px 0;
            }

            h1 {
                font-size: 15px;
            }
            
            h1.status {
                font-size: 40px;
                margin-top: 20px;
            }
            
            .status.unpaid {
                color: red;
            }
            
            .status.paid {
                color: green;
            }
            
            .status.cancelled {
                color: grey;
            }

            h2 {
                font-size: 12px;
            }

            ul {
                list-style-type: none;
            }

            .header_wrapper {
                text-align: right;
            }


            table {
                width: 95%;
                margin: 0 auto;
                padding: 5px;
            }

            table td {
                padding: 5px;
            }

            .logo {
                padding-top: 10%;
            }

            .grey, .invoice_items {
                background-color: #f6f6f6;
            }

            .invoice_items {
                border: #ccc 1px solid;
                margin-top: 50px;
                margin-bottom: 50px;
            }

            .invoice_items .items td {
                background-color: #fff;
                padding: 5px;
                border: #ccc 1px solid;

            }

            .center {
                text-align: center;
            }

            .left {
                text-align: left;
            }

            .right {
                text-align: right;
            }

            .header {
                font-weight: bold;
            }

            .price {
                text-align: center;
            }

            .footer_notes {
                position: relative;
                bottom: 30px;
                left: 15px;
                padding: 5px;
                page-break-inside:auto;
            }

        </style>

        <?php
        $count = count($this->invoices);
        $loopCount = 1;
        foreach ($this->invoices as $invoice):?>

            <div <?php if ($count > $loopCount): ?>style="page-break-after: always"<?php endif; ?>>

                <table align="center" class="pdf_data">
                    
                    <tr>
                        <td width="50%">
                            <?php if (!empty($invoice->business['logo']) && is_file(LOGO_DIR.DIRECTORY_SEPARATOR.$invoice->business['logo'])):?>
                                <img width="200px" src="http://<?= DOMAIN . LOGO_PATH . '/' . $invoice->business['logo'] ?>" />
                            <?php endif; ?>
                        </td>
                        <td class="header_wrapper">
                            <ul class="business">
                                <?php if (!empty($invoice->business['business_name'])): ?><li><h1><?= $invoice->business['business_name'] ?></h1></li><?php endif;?>
                                <?php if (!empty($invoice->business['telephone'])): ?><li>Phone: <?= $invoice->business['telephone'] ?></li><?php endif;?>
                                <?php if (!empty($invoice->business['company_registration'])): ?><li>Company Registration: <?= $invoice->business['company_registration'] ?></li><?php endif; ?>
                                <?php if (!empty($invoice->business['tax_number'])): ?><li>Vat Ref: <?= $invoice->business['tax_number'] ?></li><?php endif; ?>
                            </ul>
                            <ul class="business_address">
                                <?php if (strlen($invoice->business['address_line_1'])): ?><li><?= $invoice->business['address_line_1'] ?></li><?php endif; ?>
                                <?php if (strlen($invoice->business['address_line_2'])): ?><li><?= $invoice->business['address_line_2'] ?></li><?php endif; ?>
                                <?php if (strlen($invoice->business['address_line_3'])): ?><li><?= $invoice->business['address_line_3'] ?></li><?php endif; ?>
                                <?php if (strlen($invoice->business['city'])): ?><li><?= $invoice->business['city'] ?></li><?php endif; ?>
                                <?php if (strlen($invoice->business['postcode'])): ?><li><?= $invoice->business['postcode'] ?></li><?php endif; ?>
                                <?php if (strlen($invoice->business['state'])): ?><li><?= $invoice->business['state'] ?></li><?php endif; ?>
                                <?php if (strlen($invoice->business['country'])): ?><li><?= $invoice->business['country'] ?></li><?php endif; ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="grey">
                            <h2>Invoice #<?= $invoice->invoice->number ?></h2>
                            Invoice Date: <?= $this->date($invoice->invoice->date) ?><br />
                            Invoice Due Date: <?= $this->date($invoice->invoice->due_date) ?><br />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2>Invoiced to</h2>
                            <?= $invoice->client->business_name ?><br />
                            ATTN: <?= $invoice->client->first_name ?> <?= $invoice->client->last_name ?><br />
                            <?php if (strlen($invoice->client->address_line_1)): ?><?= $invoice->client->address_line_1 ?><br /><?php endif; ?>
                            <?php if (strlen($invoice->client->address_line_2)): ?><?= $invoice->client->address_line_2 ?><br /><?php endif; ?>
                            <?php if (strlen($invoice->client->address_line_3)): ?><?= $invoice->client->address_line_3 ?><br /><?php endif; ?>
                            <?php if (strlen($invoice->client->city)): ?><?= $invoice->client->city ?><br /><?php endif; ?>
                            <?php if (strlen($invoice->client->postcode)): ?><?= $invoice->client->postcode ?><br /><?php endif; ?>
                            <?php if (strlen($invoice->client->state)): ?><?= $invoice->client->state ?><br /><?php endif; ?>
                            <?php if (strlen($invoice->client->country)): ?><?= $invoice->client->country ?><br /><?php endif; ?>
                        </td>
                        <td style="text-align: right">
                            <h1 class="status <?=$invoice->invoice->status?>"><?=  strtoupper($invoice->invoice->status)?></h1>
                        </td>
                    </tr> 

                </table>


                <table class="invoice_items">
                    <tr class="center header">
                        <td>Description</td>
                        <td>Total</td>
                    </tr>
                    <?php foreach ($invoice->items as $item) : ?>
                        <tr class="items">
                            <td width="85%"><?= $item->description ?></td>
                            <td class="price"><?= $this->currency($item->amount) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="header right">Sub Total</td>
                        <td class="price"><?= $this->currency($invoice->totals->subtotal) ?><br /></td>
                    </tr>
                    <?php if ($invoice->invoice->tax_rate > 0): ?>
                    <tr>
                        <td class="header right"><?= $invoice->invoice->tax_rate ?>% Vat</td>
                        <td class="price"><?= $this->currency($invoice->totals->vat) ?><br /></td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <td class="header right">Total</td>
                        <td class="price"><?= $this->currency($invoice->totals->total) ?><br /></td>
                    </tr>
                </table>


                <?php if (!empty($invoice->business['invoice_footer_message'])): ?>
                    <div class="footer_notes">
                        <h4>Invoice Notes</h4>
                        <?= $invoice->business['invoice_footer_message'] ?>
                    </div>
                <?php endif; ?>  

            </div>

            <?php
            $loopCount++;
        endforeach;
        ?>

    </body>
</html>