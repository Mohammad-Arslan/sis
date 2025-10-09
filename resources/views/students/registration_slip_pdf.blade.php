<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Slip</title>
    @if ($type == 'pdf')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    @endif
</head>
<style>
    /*  Style for Payment Slip */
    .payment-slip h6 {
        text-transform: capitalize;
        font-weight: 700;
        background-color: #00a78d;
        color: #fff;
        padding: 5px 2px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        height: 26px;
        margin-bottom: 1px;
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
        font-size: 14px;
    }

    .payment-slip th span {
        border-bottom: 1px solid #000;
    }

    .payment-slip .t-amount {
        border-top: 1px solid #000;
        border-bottom: 2px double #000;
    }

    .payment-slip td {
        font-size: 13px;
    }

    .payment-slip td b {
        font-size: 12px;
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
        font-size: 14px;
        font-weight: normal;
        margin-top: 10px;
        margin-bottom: 0;
    }

    .slip-footer h4 span {
        text-decoration: underline;
        text-transform: capitalize;
        font-size: 17px;
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
        font-size: 14px;
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
        background: transparent -webkit-gradient(linear, right top, left top, from(#008f78), to(#00a78d)) 0% 0% no-repeat padding-box;
        background: transparent linear-gradient(270deg, #008f78 0%, #00a78d 100%) 0% 0% no-repeat padding-box;
        color: #fff;
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
        background-color: #d2d7df;
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

    .form-date,
    .form-receipt {
        font-size: 13px;
        text-transform: capitalize;
        color: #000000;
        font-weight: 400;
        text-align: right;
        white-space: nowrap;
    }

    .form-date span,
    .form-receipt span {
        font-weight: 600;
        font-size: 14px;
        width: 120px;
        white-space: nowrap;
        text-align: left
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
        padding-bottom: 8px !important;
        font-size: 14px;
        height: 12px;
        line-height: 12px;
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

    .btn-info-new {
        color: #fff;
        background-color: #007AFF;
        ;
        border-color: #007AFF;
        ;
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

    @page {
        margin: 0 0 0 0;
        size: A4 portrait;
    }

    .text-white-new {
        color: rgb(0, 0, 0);
    }
</style>

<body>

    <div>
        <div class="ucs-form-header">
            <table class="border-0 px-0 mx-0 w-100">
                <tbody>
                    <tr>
                        <td class="w-25">
                            <img src="{{ asset('ucs-icon-description.png') }}" height="" width="270"
                                alt="img">
                        </td>
                        <td class="w-50">
                            <div class="ucs-title text-center">
                                <h4 class="text-white-new">
                                    {{ $student['branch'] ? $student['branch']['br_name'] : 'N/A' }}
                                </h4>
                                <h6 class="text-white-new">registration fee slip</h6>
                            </div>
                        </td>
                        <td class="w-25">
                            <table class="border-0 px-0 mx-0 my-0 w-100">
                                <tbody>
                                    <tr class="form-date">
                                        <td>
                                            date: <span>{{ \Carbon\Carbon::now()->format('d-m-Y g:i A') }}</span>
                                        </td>
                                    </tr>
                                    <tr class="form-receipt">
                                        <td>
                                            receipt no: <span>{{ sprintf('%06u', (int) $student['id']) }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- <div class="d-flex align-items-start justify-content-between">
                <div class="ucs-logo get-center">
                    <img src="{{ asset('registration_slip_logo.png') }}" class="img-fluid" alt="img">
                    <img src="{{ asset('theme/icons/awesome-code-branch.svg') }}" class="img-fluid" alt="img">
                </div>
                <div class="">
                    <div class="form-date">
                        <span>date:</span> {{ \Carbon\Carbon::today()->format('d-m-Y') }}
                    </div>
                    <div class="form-receipt">
                        <span>receipt no:</span> 989898
                    </div>
                </div>
            </div> --}}

        </div>
        <div class="ucs-form-body pt-3 pb-5">
            <form class="">
                <table class="table mb-5">
                    <tbody>
                        <tr>
                            <td scope="row">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">student name:</label>
                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ $student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name'] }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                            <td>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">parent/guardian</label>
                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ $student['guardian'] ? $student['guardian']['guardian_name'] : 'N/A' }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">class:</label>
                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ $student['active_class']['branch_class_sections']['com_classes']['class_name'] }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                            <td>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">section:</label>
                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ $student['active_class']['branch_class_sections']['sections']['section_name'] }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="pe-0">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">address:</label>
                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control w-100 pe-0" id=""
                                                    value="{{ $student['student_address'] ? $student['student_address']['street_address'] : 'N/A' }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">registration fee:</label>

                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ 'Five Hundred Only -' }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                            <td>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">RS. (1000):</label>
                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control text-center" id=""
                                                    value="Cost of Prospectus Inclusive" placeholder="">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">registration date:</label>
                                            </td>
                                            <td class="w-100" style="margin-top: -20px">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ \Carbon\Carbon::parse($student->registration_date)->format('d-m-Y') }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                            <td scope="row">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">test date & time:</label>
                                            </td>
                                            <td class="w-100">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ \Carbon\Carbon::parse($student->test_date_time)->format('d-m-Y g:i A') }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="" class="">interview date & time:</label>
                                            </td>
                                            <td class="w-100" style="margin-top: -20px">
                                                <input type="text" class="form-control" id=""
                                                    value="{{ \Carbon\Carbon::parse($student->interview_date_time)->format('d-m-Y g:i A') }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="ucs-footer" style="margin-top: 4rem!important">
                    <div class="text-center mb-5">
                        <h5>Signatures</h5>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="" class="">Parent Relations
                                                        Officer:</label>
                                                </td>
                                                <td class="w-100">
                                                    <input type="text" class="form-control" id=""
                                                        value="">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="" class="">parent/guardian:</label>
                                                </td>
                                                <td class="w-100">
                                                    <input type="text" height="30px" class="form-control"
                                                        id="" value="">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="ucs-footer mt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="" class="">accountant:</label>
                                                </td>
                                                <td class="w-100">
                                                    <input type="text" class="form-control" id=""
                                                        value="">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="" class="">school head:</label>
                                                </td>
                                                <td class="w-100">
                                                    <input type="text" height="30px" class="form-control"
                                                        id="" value="">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        @if ($type == 'modal')
            <div class="hstack gap-2 justify-content-end d-print-none mt-4 p-4">
                <a href="" id="print_registration_slip" class="btn btn-info-new"><i
                        class="ri-printer-line align-bottom me-1"></i> Print</a>
                <a href="{{ route('students.create_registration_slip', [$student['id'], 'pdf']) }}"
                    class="btn btn-info-new"><i class="ri-download-2-line align-bottom me-1"></i> Download</a>
            </div>
        @endif
    </div>
    @if ($type == 'pdf')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
        </script>
    @endif
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#print_registration_slip', function(e) {
                var newstr = $('.registration_slip_modal').html();
                var oldstr = document.body.innerHTML;
                document.body.innerHTML = newstr;
                // $("body").css("margin", "4%");
                $("body").css("background-color", "#ffffff");
                window.print();
                document.body.innerHTML = oldstr;
                // $("body").removeAttr("style");
                return false;
            });
        });
    </script>

</body>

</html>
