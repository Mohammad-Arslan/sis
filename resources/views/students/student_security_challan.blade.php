<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Challan
    </title>
    <link rel="stylesheet" href="assets/scss/main.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
</head>

<style>
    * {
        line-height: 13px;
    }

    .invisible {
        visibility: hidden
    }

    td,
    th {
        padding: 0;
        margin: 0;
        white-space: nowrap;
        text-align: left
    }

    .payment-slip {
        padding-top: 9px;
    }

    /* .payment-slip {
        background-image: url("{{ asset('registration_slip_logo.png') }}");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        height: max-content;
    } */


    /*  Style for Payment Slip */
    .payment-slip h6 {
        text-transform: capitalize;
        font-weight: 700;
	    font-size: 12px;
        color: black;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin: 0;
    }

    .payment-slip h6 span {
        text-transform: uppercase;
    }

    .payment-slip table {
        margin-bottom: 5px;
    }

    .payment-slip table,
    .payment-slip tbody,
    .payment-slip td,
    .payment-slip tfoot,
    .payment-slip th,
    .payment-slip thead,
    .payment-slip tr {
        border: none;
        text-transform: capitalize;
    }

    .payment-slip .table> :not(caption)>*>* {
        padding: 0;
        vertical-align: middle;
    }

    .payment-slip th {
        font-size: 10px;
    }

    .payment-slip th span {
        border-bottom: 1px solid #000;
    }

    .payment-slip .t-amount {
        border-top: 1px solid #000;
        border-bottom: 2px double #000;
    }

    .payment-slip td {
        font-size: 10px;
    }

    .payment-slip td b {
        font-size: 10px;
    }

    .slip-footer {
        border-top: 1px dotted #000;
    }

    .slip-footer table {
        margin-bottom: 5px;
    }

    .slip-footer table,
    .slip-footer tbody,
    .slip-footer td,
    .slip-footer tfoot,
    .slip-footer th,
    .slip-footer thead,
    .slip-footer tr {
        border: none;
        text-transform: capitalize;
    }

    .slip-footer .table> :not(caption)>*>* {
        padding: 0;
        vertical-align: middle;
    }

    .slip-footer h4 {
        font-size: 9px;
        font-weight: normal;
        margin-top: 10px;
        margin-bottom: 0;
    }

    .slip-footer h4 span {
        text-transform: capitalize;
        font-size: 10px;
        font-weight: 700;
    }

    .slip-footer .form-control {
        border: none;
        border-bottom: 1px solid #000;
        padding: 0 5px;
        border-radius: 0;
        width: 180px;
    }

    .slip-footer .form-control:focus {
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .slip-footer tr td {
        font-size: 8px;
    }

    .slip-footer tr td:first-child {
        max-width: 500px;
    }

    .slip-footer .phn-no {
        padding: 0 10px !important;
    }

    /* Style for Main Form */
    .form-bg {
        background-color: #fff !important;
        border-radius: 10px;
        -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
        padding: 30px;
        max-width: 820px;
        width: 100%;
        margin: 0 auto !important;
    }

    @media (max-width: 575px) {
        .form-bg {
            padding: 20px;
        }
    }

    .form-img {
        max-width: 300px;
        width: 100%;
        margin: 0 auto;
    }

    .common-form p {
        font-size: 18px;
        color: #666;
    }

    .common-form .form-input label {
        font-size: 18px;
        color: #000;
        text-transform: capitalize;
    }

    .common-form .form-input .form-control {
        height: 50px;
        border-radius: 5px;
        border: 1px solid #b1b1b1;
    }

    .common-form .form-input .form-control:focus {
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .common-form .form-btn {
        color: black;
        text-transform: uppercase;
        font-size: 16px;
        font-weight: 500;
        border-radius: 5px;
        padding: 8px 20px;
        margin-top: 20px;
        border: none;
        margin-left: auto;
        width: 100%;
        text-align: center;
    }

    @media (max-width: 575px) {
        .common-form .form-btn {
            font-size: 14px;
            padding: 8px 10px;
        }
    }

    @media (max-width: 325px) {
        .common-form .form-btn {
            font-size: 12px;
        }
    }

    .common-form .form-btn:focus {
        outline: none;
        border: none;
    }

    /*  Style for UCS Form */
    .ucs-form {
        background-color: #fff;
        max-width: 630px;
        width: 100%;
    }

    .ucs-form-header {
        color: black;
        padding: 20px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .ucs-logo {
        min-width: 170px;
        max-width: 170px;
        height: 44px;
        overflow: hidden;
    }

    .ucs-logo img {
        image-rendering: -webkit-optimize-contrast;
    }

    .ucs-title {
        text-transform: capitalize;
        margin-top: 10px;
        color: #fff;
    }

    .ucs-title h4 {
        font-weight: 700;
    }

    .ucs-title h6 {
        font-weight: 600;
    }



    .ucs-form-body {
        padding: 0 15px;
    }

    .ucs-form-body .table,
    .ucs-form-body th,
    .ucs-form-body td {
        border: none;
    }

    .ucs-form-body tr td {
        padding: 2px 4px;
    }

    .ucs-form-body .form-control {
        border-radius: 0;
        border: none;
        border-bottom: 1px solid #999;
        padding-bottom: 2px !important;
        font-size: 14px;
    }

    .ucs-form-body .form-control:focus {
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        border-bottom: 1px solid #999;
    }

    .ucs-form-body label {
        white-space: nowrap;
        text-transform: capitalize;
        color: #999;
        font-size: 14px;
    }

    .ucs-footer label {
        color: #666 !important;
        font-weight: 400 !important;
    }

    body {
        padding: 0;
        margin: 0;
        height: auto;
        width: 100%;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .get-center {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
    }

    .wrap-bg {
        background-color: #f3f4f6;
        height: 100vh;
    }

    @media (max-width: 575px) {
        .wrap-bg {
            height: auto;
            padding: 30px 15px;
        }
    }

    .small-img {
        width: 30px;
        height: 30px;
        over-flow: hidden;
        /* margin-right:20px; */
        display: flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
    }

    .page {
       page-break-after: always;
    }
    .page:last-child {
       page-break-after: unset;
    }
    /*# sourceMappingURL=main.css.map */
</style>

<body>
    @foreach ($challan as $key => $invoice)
        <div class="{{ (count($challan) > 1 ? ($key != count($challan) - 1 ? 'page' : '') : '') }}">
            <div class="payment-slip mb-0 mt-3">
                <table>
                    <tbody>
                        <tr>
                            <td class="get-center">
                                <div class="small-img get-center">
                                    <img src="{{ asset('ucs-icon.png') }}" class="img-fluid" width="25px" alt="logo">
                                </div>
                            </td>
                            <td>
                                <h6 class="m-0 p-0">
                                    Branch: {{ $invoice['securityChallan']['branch']['br_name'] }}
                                    <span>
                                        <small>
                                            <b>(Challan Type: Security Challan)</b>
                                        </small>
                                    </span>
                                </h6>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="multi-tables border-0" cellspacing="1">
                    <tbody>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="none">
                                    <tbody>
                                        <tr>
                                            <th scope="col" colspan="2"><span>Student Information</span></th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span>name:</span></th>
                                            <th style="padding-left: 30px">
                                                <span>{{ $invoice['securityChallan']['first_name'] . ' ' . $invoice['securityChallan']['middle_name'] . ' ' . $invoice['securityChallan']['last_name'] }}</span>
                                            </th>

                                        </tr>
                                        <tr>
                                            @if(!empty($invoice['securityChallan']['roll_no']))
                                                <td scope="row">student ID :</td>
                                                <td style="padding-left: 30px">{{ $invoice['securityChallan']['roll_no'] }}</td>
                                            @else
                                                <td scope="row">registration ID :</td>
                                                <td style="padding-left: 30px">{{ $invoice['securityChallan']['registration_no'] }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td scope="row">class:</td>
                                            <td style="padding-left: 30px">
                                                {{ $invoice['securityChallan']['active_class']['branch_class_sections']['com_classes']['class_name'] . ' ' . $invoice['securityChallan']['active_class']['branch_class_sections']['sections']['section_name'] }}
                                            </td>

                                        </tr>
                                        {{-- <tr>
                                            <td scope="row">programme:</td>
                                            <td style="padding-left: 30px">
                                                {{ $invoice['securityChallan']['branch']['class_group']['name'] . ' (' . $invoice['securityChallan']['student_fee_package']['academic_year']['title'] . ')' }}

                                        </tr> --}}
                                        <tr>
                                            <th scope="row"><span>challan information:</span></th>
                                            <td style="padding-left: 30px"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row"><b>serial number/buyer code:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>
                                                    {{ $invoice['securityChallan']['security_number'] }}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="row">issue date:</td>
                                            <td style="padding-left: 30px">
                                                {{ \Carbon\Carbon::parse(now())->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td scope="row"><b>due date:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ \Carbon\Carbon::parse($invoice['securityChallan']['due_date'])->format('d-m-Y') }}</b>
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <td scope="row"><b>validity date:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ \Carbon\Carbon::parse(now()->addDays(10))->format('d-m-Y') }}</b>
                                            </td>
                                        </tr>
                                        @if (isset($invoice['securityChallan']['branch']['default_bank_account']))
                                            <tr>
                                                <td scope="row">
                                                    {{-- {{ ucwords($invoice['securityChallan']['branch']['bank_accounts'][0]['bank_name']) }} --}}
                                                    {{ucwords($invoice['securityChallan']['branch']['default_bank_account']['bank_name'])}}
                                                </td>
                                                <td style="padding-left: 30px">
                                                    {{ $invoice['securityChallan']['branch']['default_bank_account']['account_no']}}
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- <tr>
                                            <td scope="row" class="text-center"><b>for payment</b></td>
                                            <td><b>use serial number/buyer code</b></td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </td>
                            <td style="visibility: hidden">
                                for space
                            </td>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="none">
                                    <tbody>
                                        <tr>

                                            <th><span>charges</span></th>
                                            <th style="padding-left: 30px"></th>
                                        </tr>

                                        <tr>
                                            <td>Security Challan</td>
                                            <td style="padding-left: 30px">
                                            {{ number_format($invoice['securityChallan']['amount']) }}
                                        </tr>

                                        <tr>
                                            <td>Arrears</td>
                                            <td style="padding-left: 30px">
                                                0
                                            </td>
                                        </tr>
                                        <tr class="invisible">

                                            <td>
                                                for space
                                            </td>
                                            <td>
                                                for space
                                            </td>
                                        </tr>

                                        <tr>

                                            <td>total:</td>
                                            <td style="padding-left: 30px"><span
                                                    class="t-amount">{{ number_format($invoice['securityChallan']['amount']) }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td><b>payable by
                                                    '{{ \Carbon\Carbon::parse($invoice['securityChallan']['due_date'])->format('d-m-Y') }}'</b>
                                            </td>
                                            <td style="padding-left: 30px">
                                                <b>{{ number_format($invoice['securityChallan']['amount']) }}</b>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td><b>payable after due date</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ number_format($invoice['securityChallan']['amount']) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>For Payments: Use Serial Number / Buyer Code &nbsp; | &nbsp;</b></td>
                                            <td><b>School copy</b></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="payment-slip mt-5 mb-0" style="padding-top: 130px">
                <table>
                    <tbody>
                        <tr>
                            <td class="get-center">
                                <div class="small-img get-center">
                                    <img src="{{ asset('ucs-icon.png') }}" class="img-fluid" width="25px" alt="logo">
                                </div>
                            </td>
                            <td>
                                <h6 class="m-0 p-0">
                                    Branch: {{ $invoice['securityChallan']['branch']['br_name'] }}
                                    <span>
                                        <small>
                                            <b>(Challan Type: Security Challan)</b>
                                        </small>
                                    </span>
                                </h6>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="multi-tables border-0" cellspacing="1">
                    <tbody>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="none">
                                    <tbody>
                                        <tr>
                                            <th scope="col" colspan="2"><span>Student Information</span></th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span>name:</span></th>
                                            <th style="padding-left: 30px">
                                                <span>{{ $invoice['securityChallan']['first_name'] . ' ' . $invoice['securityChallan']['middle_name'] . ' ' . $invoice['securityChallan']['last_name'] }}</span>
                                            </th>

                                        </tr>
                                        <tr>
                                            @if(!empty($invoice['securityChallan']['roll_no']))
                                                <td scope="row">student ID :</td>
                                                <td style="padding-left: 30px">{{ $invoice['securityChallan']['roll_no'] }}</td>
                                            @else
                                                <td scope="row">registration ID :</td>
                                                <td style="padding-left: 30px">{{ $invoice['securityChallan']['registration_no'] }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td scope="row">class:</td>
                                            <td style="padding-left: 30px">
                                                {{ $invoice['securityChallan']['active_class']['branch_class_sections']['com_classes']['class_name'] . ' ' . $invoice['securityChallan']['active_class']['branch_class_sections']['sections']['section_name'] }}
                                            </td>

                                        </tr>
                                        {{-- <tr>
                                            <td scope="row">programme:</td>
                                            <td style="padding-left: 30px">
                                                {{ $invoice['securityChallan']['branch']['class_group']['name'] . ' (' . $invoice['securityChallan']['student_fee_package']['academic_year']['title'] . ')' }}

                                        </tr> --}}
                                        <tr>
                                            <th scope="row"><span>challan information:</span></th>
                                            <td style="padding-left: 30px"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row"><b>serial number/buyer code:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>
                                                    {{ $invoice['securityChallan']['security_number'] }}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="row">issue date:</td>
                                            <td style="padding-left: 30px">
                                                {{ \Carbon\Carbon::parse(now())->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td scope="row"><b>due date:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ \Carbon\Carbon::parse($invoice['securityChallan']['due_date'])->format('d-m-Y') }}</b>
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <td scope="row"><b>validity date:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ \Carbon\Carbon::parse(now()->addDays(10))->format('d-m-Y') }}</b>
                                            </td>
                                        </tr>
                                        @if (isset($invoice['securityChallan']['branch']['default_bank_account']))
                                            <tr>
                                                <td scope="row">
                                                    {{-- {{ ucwords($invoice['securityChallan']['branch']['bank_accounts'][0]['bank_name']) }} --}}
                                                    {{ucwords($invoice['securityChallan']['branch']['default_bank_account']['bank_name'])}}
                                                </td>
                                                <td style="padding-left: 30px">
                                                    {{ $invoice['securityChallan']['branch']['default_bank_account']['account_no']}}
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- <tr>
                                            <td scope="row" class="text-center"><b>for payment</b></td>
                                            <td><b>use serial number/buyer code</b></td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </td>
                            <td style="visibility: hidden">
                                for space
                            </td>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="none">
                                    <tbody>
                                        <tr>

                                            <th><span>charges</span></th>
                                            <th style="padding-left: 30px"></th>
                                        </tr>

                                        <tr>
                                            <td>Security Challan</td>
                                            <td style="padding-left: 30px">
                                            {{ number_format($invoice['securityChallan']['amount']) }}
                                        </tr>

                                        <tr>
                                            <td>Arrears</td>
                                            <td style="padding-left: 30px">
                                                0
                                            </td>
                                        </tr>
                                        <tr class="invisible">

                                            <td>
                                                for space
                                            </td>
                                            <td>
                                                for space
                                            </td>
                                        </tr>

                                        <tr>

                                            <td>total:</td>
                                            <td style="padding-left: 30px"><span
                                                    class="t-amount">{{ number_format($invoice['securityChallan']['amount']) }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td><b>payable by
                                                    '{{ \Carbon\Carbon::parse($invoice['securityChallan']['due_date'])->format('d-m-Y') }}'</b>
                                            </td>
                                            <td style="padding-left: 30px">
                                                <b>{{ number_format($invoice['securityChallan']['amount']) }}</b>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td><b>payable after due date</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ number_format($invoice['securityChallan']['amount']) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>For Payments: Use Serial Number / Buyer Code &nbsp; | &nbsp;</b></td>
                                            <td><b>School copy</b></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="payment-slip mt-5 mb-0" style="padding-top: 135px">
                <table>
                    <tbody>
                        <tr>
                            <td class="get-center">
                                <div class="small-img get-center">
                                    <img src="{{ asset('ucs-icon.png') }}" class="img-fluid" width="25px" alt="logo">
                                </div>
                            </td>
                            <td>
                                <h6 class="m-0 p-0">
                                    Branch: {{ $invoice['securityChallan']['branch']['br_name'] }}
                                    <span>
                                        <small>
                                            <b>(Challan Type: Security Challan)</b>
                                        </small>
                                    </span>
                                </h6>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="multi-tables border-0">
                    <tbody>
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="none">
                                    <tbody>
                                        <tr>
                                            <th scope="col" colspan="2"><span>Student Information</span></th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span>name:</span></th>
                                            <th style="padding-left: 30px">
                                                <span>{{ $invoice['securityChallan']['first_name'] . ' ' . $invoice['securityChallan']['middle_name'] . ' ' . $invoice['securityChallan']['last_name'] }}</span>
                                            </th>

                                        </tr>
                                        <tr>
                                            @if(!empty($invoice['securityChallan']['roll_no']))
                                                <td scope="row">student ID :</td>
                                                <td style="padding-left: 30px">{{ $invoice['securityChallan']['roll_no'] }}</td>
                                            @else
                                                <td scope="row">registration ID :</td>
                                                <td style="padding-left: 30px">{{ $invoice['securityChallan']['registration_no'] }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td scope="row">class:</td>
                                            <td style="padding-left: 30px">
                                                {{ $invoice['securityChallan']['active_class']['branch_class_sections']['com_classes']['class_name'] . ' ' . $invoice['securityChallan']['active_class']['branch_class_sections']['sections']['section_name'] }}
                                            </td>

                                        </tr>
                                        {{-- <tr>
                                            <td scope="row">programme:</td>
                                            <td style="padding-left: 30px">
                                                {{ $invoice['securityChallan']['branch']['class_group']['name'] . ' (' . $invoice['securityChallan']['student_fee_package']['academic_year']['title'] . ')' }}

                                        </tr> --}}
                                        <tr>
                                            <th scope="row"><span>challan information:</span></th>
                                            <td style="padding-left: 30px"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row"><b>serial number/buyer code:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>
                                                    {{ $invoice['securityChallan']['security_number'] }}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="row">issue date:</td>
                                            <td style="padding-left: 30px">
                                                {{ \Carbon\Carbon::parse(now())->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td scope="row"><b>due date:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ \Carbon\Carbon::parse($invoice['securityChallan']['due_date'])->format('d-m-Y') }}</b>
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <td scope="row"><b>validity date:</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ \Carbon\Carbon::parse(now()->addDays(10))->format('d-m-Y') }}</b>
                                            </td>
                                        </tr>
                                        @if (isset($invoice['securityChallan']['branch']['default_bank_account']))
                                            <tr>
                                                <td scope="row">
                                                    {{-- {{ ucwords($invoice['securityChallan']['branch']['bank_accounts'][0]['bank_name']) }} --}}
                                                    {{ucwords($invoice['securityChallan']['branch']['default_bank_account']['bank_name'])}}
                                                </td>
                                                <td style="padding-left: 30px">
                                                    {{ $invoice['securityChallan']['branch']['default_bank_account']['account_no']}}
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- <tr>
                                            <td scope="row" class="text-center"><b>for payment</b></td>
                                            <td><b>use serial number/buyer code</b></td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </td>
                            <td style="visibility: hidden">
                                for space
                            </td>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="none">
                                    <tbody>
                                        <tr>

                                            <th><span>charges</span></th>
                                            <th style="padding-left: 30px"></th>
                                        </tr>

                                        <tr>
                                            <td>Security Challan</td>
                                            <td style="padding-left: 30px">
                                            {{ number_format($invoice['securityChallan']['amount']) }}
                                        </tr>

                                        <tr>
                                            <td>Arrears</td>
                                            <td style="padding-left: 30px">
                                                0
                                            </td>
                                        </tr>
                                        <tr class="invisible">

                                            <td>
                                                for space
                                            </td>
                                            <td>
                                                for space
                                            </td>
                                        </tr>

                                        <tr>

                                            <td>total:</td>
                                            <td style="padding-left: 30px"><span
                                                    class="t-amount">{{ number_format($invoice['securityChallan']['amount']) }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td><b>payable by
                                                    '{{ \Carbon\Carbon::parse($invoice['securityChallan']['due_date'])->format('d-m-Y') }}'</b>
                                            </td>
                                            <td style="padding-left: 30px">
                                                <b>{{ number_format($invoice['securityChallan']['amount']) }}</b>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td><b>payable after due date</b></td>
                                            <td style="padding-left: 30px">
                                                <b>{{ number_format($invoice['securityChallan']['amount']) }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>For Payments: Use Serial Number / Buyer Code &nbsp; | &nbsp;</b></td>
                                            <td><b>School copy</b></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="slip-footer">
                <h4>
                    <span>contact information:</span> If there is any change, please
                    update below and submit a copy in the school office.
                </h4>
                <table cellspacing="0" cellpadding="0" border="none">
                    <tbody>
                        <tr>
                            <td><b>home number (for emergancy contact): </b> {{ $invoice['securityChallan']['emergency_phone_number'] }}</td>
                            <td class="invisible">For Space</td>
                            <td><b>mobile number (for SMS alerts): </b> {{ isset($invoice['securityChallan']['student_address']) ? $invoice['securityChallan']['student_address']['res_sms_number'] : 'N/A' }}</td>
                            <td class="invisible">For Space</td>
                            <td><b>email address (for school reports): </b> {{ $invoice['securityChallan']['email'] }}</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <label for="inputPassword6">new:</label>
                                    <input type="text" id="inputPassword6" class="form-control" />
                                    <b>parent copy</b>
                                </div>
                            </td>
                            <td class="invisible">For Space</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <label for="inputPassword6">new:</label>
                                    <input type="text" id="inputPassword6" class="form-control" />
                                    <b>parent copy</b>
                                </div>
                            </td>
                            <td class="invisible">For Space</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <label for="inputPassword6">new:</label>
                                    <input type="text" id="inputPassword6" class="form-control" />
                                    <b>parent copy</b>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
</body>

</html>
