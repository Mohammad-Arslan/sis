<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Transfer Request Form</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');

        body {
            padding: 0;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        .request-letter {

            width: 100%;
            border: 5px solid #233c84;
            padding: 0 5px !important;
            box-sizing: border-box;

            font-size: 14px;
            background: #fff;
        }

        .request-letter td h1 {
            color: #233c84;
            margin: 5px 0;
            text-align: center;
            text-transform: capitalize;
        }

        .request-letter td img {
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .request-letter td {
            padding: 5px 10px;
        }

        .request-letter td span {
            border-top: 2px solid;
            text-transform: capitalize;
            display: block;
            text-align: center;
        }

        .w-25 {
            width: 25%;
        }

        .w-50 {
            width: 50%;
        }

        .w-75 {
            width: 75%;
        }

        .w-100 {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .m-0 {
            margin: 0;
        }
    </style>
</head>

<body>

    <table class="request-letter">
        <tbody>
            <tr>
                <td colspan="3">
                    <img src="{{ asset('assets/img/1x/ucs_logo.png') }}" alt="" height="140px" width="100px">
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h1>student transfer request letter</h1>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    This is to certify that <b>{{ $studentInfo->first_name }} {{ $studentInfo->middle_name }}
                        {{ $studentInfo->last_name }} ({{ $studentInfo->registration_no }})</b> son of
                    <b>{{ $studentInfo->guardian->guardian_name }}</b> is a
                    student of
                    <b>{{ $transferInfo->from_branch_model->br_name }}({{ $transferInfo->from_branch_model->branch_code }})</b>
                    since {{ \Carbon\Carbon::parse($studentInfo->admission_wef)->format('d M Y') }}. He is studying in
                    <b>{{ $studentInfo->active_class->branch_class_sections->com_classes->class_name }}</b> section
                    <b>{{ $studentInfo->active_class->branch_class_sections->sections->section_name }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    He is seeking transfer to
                    <b>{{ $transferInfo->to_branch_model->br_name }}({{ $transferInfo->to_branch_model->branch_code }})</b>
                    and will join
                    on <b>{{ \Carbon\Carbon::parse($transferInfo->joining_date)->format('d M Y') }}</b> if the transfer
                    is confirmed.
                </td>
            </tr>
            <tr>
                <td class="w-25">
                    Transfer-from:
                </td>
                <td colspan="2" class="w-100">
                    <b>{{ $transferInfo->from_branch_model->br_name }}</b>
                </td>
            </tr>
            <tr>
                <td class="w-25">
                    Transfer-to:
                </td>
                <td colspan="2" class="w-100">
                    <b>{{ $transferInfo->to_branch_model->br_name }}</b>
                </td>
            </tr>
            <tr>
                <td class="w-25">
                    Date of Issuance:
                </td>
                <td colspan="2" class="w-100">
                    <b>{{ \Carbon\Carbon::parse($transferInfo->request_date)->format('d M Y') }}</b>
                </td>
            </tr>
            <tr>
                <td class="w-25">
                    Document Ref:
                </td>
                <td class="w-50">
                    <b>{{ $transferInfo->application_id }}</b>
                </td>
                <td class="w-25">
                    <b>
                        <span>
                            school administration
                        </span>
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <small class="text-center">
                        <b>
                            <i>
                                <p class="m-0">Note: This letter is not confirmation of your transfer. Seats are
                                    the subject to avaliability in the rescpective campus.</p>
                            </i>
                        </b>
                    </small>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top:0;">
                    <p class="text-center m-0">{{ $transferInfo->to_branch_model->br_name }}
                        ({{ $transferInfo->to_branch_model->branch_code }}) -
                        {{ $transferInfo->to_branch_model->region->region_name }} Region</p>
                </td>
            </tr>
        </tbody>
    </table>


</body>

</html>
