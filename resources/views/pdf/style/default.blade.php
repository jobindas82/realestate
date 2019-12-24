<style>
    @page {
        margin-top: 1cm;
        margin-bottom: 3.6cm;
        margin-left: 1cm;
        margin-right: 1cm;
        /* header: html_myHeader; */
        footer: myfooter;
        font-family: tahoma;
    }

    body {
        font-family: tahoma;
        color: #101010;
        font-size: 14px;
    }

    * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .col-1,
    .col-2,
    .col-3,
    .col-4,
    .col-5,
    .col-6,
    .col-7,
    .col-8,
    .col-9,
    .col-10,
    .col-11,
    .col-12 {
        float: left;
    }

    .col-12 {
        width: 100%;
    }

    .col-11 {
        width: 91.66666667%;
    }

    .col-10 {
        width: 83.33333333%;
    }

    .col-9 {
        width: 75%;
    }

    .col-8 {
        width: 66.66666667%;
    }

    .col-7 {
        width: 58.33333333%;
    }

    .col-6 {
        width: 50%;
    }

    .col-5 {
        width: 41.66666667%;
    }

    .col-4 {
        width: 33.33333333%;
    }

    .col-3 {
        width: 25%;
    }

    .col-2 {
        width: 16.66666667%;
    }

    .col-1 {
        width: 8.33333333%;
    }

    .offset-1 {
        margin-left: 8.33333%;
    }

    .offset-2 {
        margin-left: 16.66667%;
    }

    .offset-3 {
        margin-left: 25%;
    }

    .offset-4 {
        margin-left: 33.33333%;
    }

    .offset-5 {
        margin-left: 41.66667%;
    }

    .offset-6 {
        margin-left: 50%;
    }

    .offset-7 {
        margin-left: 58.33333%;
    }

    .offset-8 {
        margin-left: 66.66667%;
    }

    .offset-9 {
        margin-left: 75%;
    }

    .offset-10 {
        margin-left: 83.33333%;
    }

    .offset-11 {
        margin-left: 91.66667%;
    }

    .pad-15,
    .pad15 {
        padding: 0 15px;
        box-sizing: border-box;
    }

    .row {
        margin-left: -15px;
        margin-right: -15px;
    }

    .clearfix {
        display: table;
        clear: both;
        width: 100%;
    }

    .text-left {
        text-align: left;
        text-align: left !important;
    }

    .text-right {
        text-align: right;
        text-align: right !important;
    }

    .text-center {
        text-align: center;
        text-align: center !important;
    }

    .heading {
        padding-bottom: 15px;
        border-bottom: 1px solid #1f67b2;
    }

    .companay_name {
        padding-top: 15px;
        color: #101010;
    }

    .heading .companay_name h3 {
        margin: 0;
        padding: 0;
    }

    .heading .companay_name p {
        margin: 0;
        padding: 0;
    }

    .title h1 {
        color: #101010;
        font-weight: normal;
        font-size: 22px;
    }

    .table {
        border-collapse: collapse !important;
        font-size: 12px;
        color: #101010;
        width: 100%;
    }

    .table thead th {
        background: #1f67b2;
        color: #Fff;
    }

    .table th,
    .table td {
        border: 1px solid #ccc !important;
        text-align: left;
        padding: 3px 6px;
    }

    .table th.text-left,
    .table td.text-left {
        text-align: left;
        text-align: left !important;
    }

    .table th.text-right,
    .table td.text-right {
        text-align: right;
        text-align: right !important;
    }

    .table th.text-center,
    .table td.text-center {
        text-align: center;
        text-align: center !important;
    }

    .table tbody tr:nth-child(even) {
        background: #f4fef3;
    }

    .table th.Table-Title,
    .table td.Table-Title {
        padding: 7px 6px !important;
        font-size: 1.15rem;
        color: green;
        font-weight: 500;
        text-transform: uppercase;
    }

    .table th.total,
    .table td.total {
        background: #1f67b2;
        color: #Fff;
        font-size: 14px;
    }

    h5 {
        margin-top: 10px;
        margin-bottom: 3px;
    }

    .h5sub-details {
        font-size: 12px;
        white-space: pre-line;
    }

    .input-block {
        display: inline-block;
        padding: 5px 10px;
        background: #d4e9ff;
        border: 1px solid #1f67b2;
        border-radius: 4px;
        font-size: 14px;
        margin-top: 4px;
        margin-bottom: 8px;
        width: 200px;
    }

    .h3 {
        font-size: 16px;
        font-weight: bold;
    }

    .bc-tl {
        border-radius: 5px 0 0 0;
    }

    .bc-tr {
        border-radius: 0 5px 0 0;
    }

    .bc-bl {
        border-radius: 0 0 5px 0;
    }

    .bc-br {
        border-radius: 0 0 0 5px;
    }

    .box {
        padding: 10px;
        border: 1px solid #1f67b2;
        margin-top: 15px;
        border-radius: 5px;
        width: 50%;
    }

    .box h5 {
        margin-top: 0px;
        color: #00366e;
    }

    small {
        margin: 0;
        padding: 0;
    }

    .first {
        padding: 50px 20px 20px 20px;
    }

    .heading-center {
        text-align: center;
    }

    .section-box {
        text-align: center;
        margin-bottom: 20px;
    }

    .headingpdf {
        margin-top: 20px;
    }

    .container-fluid {
        width: 100%;
    }

    .first-box {
        width: 39%;
        padding: 20px;
        float: left;
    }

    .second-box {
        width: 47%;
        padding: 20px;
        float: right;
    }

    .row-box {
        width: 25%;
    }

    .headingpdf h4 {
        font-size: 14px;
        margin: 0px;
        font-weight: bold;
    }

    .second-parograph p {
        font-size: 15px;
        margin-top: 20px;
        margin-bottom: 5px;
    }

    .section-box b {
        font-size: 16px;
    }

    .parograph p {
        font-size: 12px;
        margin-top: 20px;
        margin-bottom: 5px;
    }

    @media screen and (max-width: 990px) {
        .headingpdf h4 {
            font-size: 10px;
        }

        .second-parograph p {
            font-size: 8px;
        }

        .second-parograph {
            margin-bottom: 30px;
        }

        .section-box b {
            font-size: 10px;
        }

        .first-box {
            width: 35%;
            padding: 20px;
            float: left;
        }

        .second-box {
            width: 40%;
            padding: 20px;
            float: right;
        }

        .parograph p {
            font-size: 8px;
        }
    }

    @media screen and (max-width: 320px) {
        .headingpdf h4 {
            font-size: 8px;
        }

        .second-parograph p {
            font-size: 7px;
        }

        .parograph p {
            font-size: 7px;
        }

        .second-parograph {
            margin-bottom: 30px;
        }

        .section-box b {
            font-size: 8px;
        }
    }
</style>