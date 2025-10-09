<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <h1>school leaving certificate</h1>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-center">

                    This is to certify that <b>{{ $studentInfo->first_name }} {{ $studentInfo->middle_name }}
                        {{ $studentInfo->last_name }} ({{ $studentInfo->roll_no }})</b> @if($studentInfo->gender=='male')
                            son
                        @else
                            daughter
                        @endif of
                    <b>{{ $studentInfo->guardian->guardian_name }}</b> was
                    enrolled on
                    <b>{{ \Carbon\Carbon::parse($studentInfo->admission_wef)->format('d M Y') }}</b> in
                    <b>{{ $studentInfo->active_class->branch_class_sections->com_classes->class_name }}</b> and left on
                    <b>{{ \Carbon\Carbon::parse($studentInfo->student_withdrawals->withdrawal_wef)->format('d M Y') }}</b>.<br>
                    @if($studentInfo->gender=='male')
                        He
                    @else
                        She
                    @endif
                         was not promoted to the next class due to early withdrawal.<br>
                    @if($studentInfo->gender=='male')
                         His
                     @else
                         Her
                     @endif
                      date of birth according to the school records is <b>{{ \Carbon\Carbon::parse($studentInfo->date_of_birth)->format('d M Y') }}</b>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="table">
                        <tr>
                            <td>
                                Branch:
                                <b>{{ $studentInfo->branch->br_name }}({{ $studentInfo->branch->branch_code }})</b><br>
                                Phone:
                                <b>{{ $studentInfo->branch->branch_phone_number }}</b><br>
                                Date of Issuance:
                                <b>{{ \Carbon\Carbon::parse(now())->format('d M Y') }}</b><br>
                                Certificate No.
                                <b>{{ $studentInfo->certificate_number }}</b>
                            </td>
                            <td class="text-center">
                                {!! QrCode::size(100)->generate('https://oms.ucs.edu.pk/student-info?cn='.$studentInfo->certificate_number) !!}<br>
                                Scan me to verify
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <small class="text-center">
                        <b>
                            <i>
                                <p class="m-0">This is system generated letter and does not require signatures.</p>
                            </i>
                        </b>
                    </small>
                </td>
            </tr>
        </tbody>
    </table>


</body>

</html>
