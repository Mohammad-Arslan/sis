<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Lower Primary Progress Report</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        .scroll-table {
            max-width: 575px;
            overflow: hidden;
            overflow-x: auto;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .for-renove-padding td {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }

        .table-into-flex {
            display: flex;
            flex-direction: column;
        }

        .single-border,
        .single-border td,
        .single-border th {
            border: 1px solid grey;
            border-collapse: collapse;

        }

        .custom-h5 {
            margin: 0px;
        }

        textarea:focus,
        input:focus {
            outline: none;
            border: none;
        }

        .montser-font {
            font-family: 'Montserrat', sans-serif;
            font-weight: lighter;


        }

        .roboto-f {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            font-size: 14px;
        }

        .single-border td,
        .single-border th {
            padding: 10px;
            text-transform: capitalize;
            vertical-align: baseline;
            text-align: left;
            /* overflow-wrap: anywhere; */
            font-size: 10px;
            font-family: 'Roboto', sans-serif;
        }


        .use-flex {
            display: flex;
            padding: 0 20px;
            gap: 30px;
            width: 100%;
        }

        .modal-body.student_progress_report_modal {
            z-index: 9;

        }

        .white-space-nowrap {
            white-space: nowrap !important;
        }

        .info-headings {
            color: azure;
            background-color: #1e398d;
            padding: 3px 33px 3px 33px;
            margin: 5px auto !important;
            border-radius: 25px;
            white-space: nowrap;
            display: block;
            width: fit-content;
        }

        .text-left {
            text-align: left !important;
        }


        .text-right {
            text-align: right !important;
        }

        .blue-clr {
            background-color: #cdede8;
        }

        .green-clr {
            background-color: #00A78D;
            color: #fff;
        }

        .tbl-grading {
            margin-bottom: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #dcdddf;
        }

        .drk-blue-clr {
            background-color: #1e398d;
            color: #fff;
        }

        .grey-clr {
            background-color: #dcdddf;
            color: #000;
        }

        .grey1-clr {
            background-color: #edeeef;
            color: #000;
        }

        .tbl-inputs {
            border: 2px solid #00A88E;
            margin-right: 50px;
            margin-bottom: 60px;
            padding-top: 30px;
            padding-right: 40px;
            padding-bottom: 120px;
            padding-left: 40px;
            width: 60%;
            border-radius: 5px;
            background-color: #fff;
        }

        .name-inputs {
            color: #76a7ff;

        }

        .cover-table label {
            white-space: nowrap;
            margin-bottom: 0;
            margin-right: 5px;
            align-self: center;
        }

        .small-text {
            color: blue;
            font-size: small;
        }

        .input-spc {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .table-img tr td input {
            border: none !important;
            border-bottom: 1px solid #000 !important;
            background: transparent;
            width: 100%;
            font-size: 12px;
        }

        .table-img {
            position: absolute;
            width: 70%;
            height: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            left: 50%;
        }

        .table-img3 {
            position: absolute;
            width: 70%;
            height: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            left: 50%;
        }

        .all-text {
            font-size: 12px;
        }

        #lined {
            line-height: 31px;
            background-image: -webkit-linear-gradient(left, #ffffff00 0, #ffffff00 0),
                -webkit-linear-gradient(right, #ffffff00 0, #ffffff00 0),
                -webkit-linear-gradient(#fff 30px, #000 30px, #000 31px, #fff 31px);
            background-repeat: repeat-y;
            background-size: 100% 100%, 100% 100%, 100% 31px;
            background-attachment: local;
            border: none;
            overflow-y: auto;
            height: 128px;
            resize: none;
            overflow: hidden;
        }

        #lined::-webkit-scrollbar {
            width: 5px;
            height: 5px;
            background-color: #ececec;
            /* or add it to the track */
        }

        /* Add a thumb */
        #lined::-webkit-scrollbar-thumb {
            background: #aaa;
        }

        .d-flex {
            display: flex !important;
        }

        .input-border {
            border: none !important;
            border-bottom: 1px solid #000 !important;
            background: transparent;
        }

        input[type='number']::-webkit-outer-spin-button,
        input[type='number']::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* .border-none{
        border: none !important;
      } */

        @media print {
            footer {
                page-break-after: always;
            }
        }

        .set-bottom-img {
            position: relative;
            border: 2px solid #00A88E;
            padding: 20px 30px;

        }

        .b-img {
            position: absolute;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            max-width: 260px;
            width: 100%;
        }

        .w-100 {
            width: 100% !important;
        }

        .w-50 {
            width: 50% !important;
        }

        .small-text {
            margin-bottom: 0;
        }

        .logo-country {
            vertical-align: middle;
            text-align: center;
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            padding-top: 80px;
        }

        .logo-country .table-small-img img {
            max-width: 100%;
            height: auto;
        }

        .logo-country .table-small-img {
            height: 25px;
            max-width: 60px;
            width: 100%;
            /* margin: 0 auto; */
            display: flex;
            justify-content: center;
            overflow: hidden;
        }

        .logo-country .text-inline-p {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-country .text-inline-p p {
            border-right: 1px solid blue;
            margin-top: 8px;
            padding-right: 5px;
            margin-right: 5px;
            line-height: 10px;
        }

        .logo-country .text-inline-p p:last-child {
            border-right: none;
        }

        .table-spacing {
            border-collapse: separate;
            border-spacing: 0;
        }

        .group-icons {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .add-flex-equal {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 25px;
            margin: 0 auto;
            gap: 20px;
        }

        .add-flex-equal .flex-td {
            width: 100%;
        }

        .wrap-table td,
        .wrap-table th {
            padding-left: 5px !important;
            padding-right: 5px !important;
            white-space: nowrap;
        }

        .big-table-flex1 {
            width: 50%;
        }

        .big-table-flex {
            width: 100%;
        }

        .main-bg-img {
            top: 0;
            left: 50%;
            transform: translateX(-50%)
        }

        textarea,
        input[type] {
            pointer-events: none;
        }

        /* *:read-write{
            pointer-events: none;
        } */



        @media print {
            .page-break {
                page-break-before: always;
            }

        }

        @media (max-width:375px) {

            .wrap-table-scroll-cover .single-border td {
                white-space: wrap !important;
            }

            .table-img.cover-table,
            .mob-width {
                width: 100% !important;
                max-width: 100%
            }

            .set-bottom-img {
                padding: 20px 10px;
            }

            .mob-flex {
                justify-content: center !important;
                align-items: center !important;
                flex-direction: column;
            }

            .mob-p {
                align-items: baseline !important;
                text-align: left;
            }
        }
    </style>
</head>


<body class="A4">

    <section style="width:100%;" class="page-break" style="position: relative;">
        <img style="width:100%; height:100vh; position:absolute; z-index:-1;" class="main-bg-img"
            src="{{ asset('/assets/img/1x/img-cld-1.png') }}">
        <div class="container-fluid">
            <div class="row" id="add-flex-equal flex-td">
                <div class="col-lg-6 py-2 order-1">
                    <table class="tbl-inputs w-100">
                        <tr>
                            <td class="set-bottom-img">
                                <p style="font-size: 12px;" class="all-text">
                                    Class Teacher's Comments:</p>
                                <textarea style="" name="" id="lined" class="w-100" cols="50" rows="4">{{ $student_behaviour_skill['student_behaviour_skill_remark']['teacher_comments'] }}</textarea>
                                <p style="font-size: 12px;" class="all-text">School Head's
                                    Comments: </p>
                                <textarea style="" name="" id="lined" class="w-100" cols="50" rows="2">{{ $student_behaviour_skill['student_behaviour_skill_remark']['schoolhead_comments'] }}</textarea>
                                <br><br>
                                <p style="font-size: 12px;" class="all-text">Class
                                    Teacher's Signature: <input class="input-border" type="text"
                                        value="{{ $class_teacher_name ? $class_teacher_name : 'N/A' }}"
                                        style="width: 40%;">
                                </p>
                                <p style="font-size: 12px;" class="all-text">School
                                    Head's Signature: <input class="input-border" type="text" style="width: 40%;">
                                </p>
                                <p style="font-size: 12px;" class="all-text">
                                    Parent's/Guardian's Signature: <input class="input-border" type="text"
                                        style="width: 40%;">
                                </p>
                                @if (str_contains($student_behaviour_skill['term']['name'], '2'))
                                    <p style="margin-left:20px; margin-bottom:10px;"
                                        style="font-weight:bolder; font-size: 12px;" class="all-text">
                                        {{ $student_behaviour_skill->student_behaviour_skill_remark->is_promoted ? 'Promoted' : 'Not Promoted' }}
                                    </p>
                                @endif
                                <p style="font-size: 12px;" class="all-text">Dated:
                                    <input class="input-border" type="text"
                                        value="{{ Carbon\Carbon::now()->format('d-m-Y') }}">
                                </p>
                                <img class="b-img" src="{{ asset('/assets/img/1x/animate.png') }}">

                            </td>
                        </tr>
                    </table>
                    <table class="w-100 d-none d-md-table">
                        <tr>
                            <td>
                                <div class="logo-country">
                                    <div class="group-icons">
                                        <div class="table-small-img">
                                            <img src="{{ asset('/assets/img/1x/bss_logo.png') }}">
                                        </div>
                                        <div class="table-small-img">
                                            <img src="{{ asset('/assets/img/1x/montserri_acad.png') }}">
                                        </div>

                                        <div class="table-small-img">
                                            <img src="{{ asset('/assets/img/1x/educators.png') }}">
                                        </div>


                                        <div class="table-small-img">
                                            <img src="{{ asset('/assets/img/1x/concordia_logo.png') }}">
                                        </div>


                                        <div class="table-small-img">
                                            <img src="{{ asset('/assets/img/1x/remierdlc_logo.png') }}">
                                        </div>
                                    </div>
                                    <div class="text-inline-p">
                                        <p class="small-text" style="color: blue; font-size: small;">
                                            Belgium
                                        </p>
                                        <p class="small-text" style="color: blue; font-size: small;">
                                            Malaysia
                                        </p>
                                        <p class="small-text" style="color: blue; font-size: small;">
                                            Oman
                                        </p>
                                        <p class="small-text" style="color: blue; font-size: small;">
                                            Pakistan
                                        </p>
                                        <p class="small-text" style="color: blue; font-size: small;">
                                            Phillipines
                                        </p>
                                        <p class="small-text" style="color: blue; font-size: small;">
                                            Thailand
                                        </p>
                                        <p class="small-text" style="color: blue; font-size: small;">
                                            UAE
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr
                            style=" display:flex !important; margin-top: 03px;margin-bottom: 0px;justify-content: center;">
                            <td style="display: flex;align-items:center">
                                <img src="{{ asset('/assets/img/1x/fb_icon.png') }}" height="10px" width="10px"
                                    alt="">
                                <img src="{{ asset('/assets/img/1x/insta_icon.png') }}" height="10px"
                                    width="10px" alt="">
                                <img src="{{ asset('/assets/img/1x/youtube_icon.png') }}" height="10px"
                                    width="10px" alt="">
                                <img src="{{ asset('/assets/img/1x/linked_in.png') }}" height="10px" width="10px"
                                    alt="">

                                <p class="small-text" style="color: blue; font-size: small;margin-left:5px">
                                    United
                                    Charter Schools</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6 py-2 order-0">
                    {{-- <p
                        style="color: #1e398d; font-family: 'Roboto', sans-serif; font-weight: 800; font-size:16px; margin-right:15px; text-align:right; ">
                        {{ $student->state->state_name }}
                    </p> --}}
                    <table class="w-100">
                        <tr>
                            <td style="margin:0 auto;text-align:center;">
                                <img style="" src="{{ asset('/assets/img/1x/ucs_logo.png') }}" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; position:relative;">

                                <img src="{{ asset('/assets/img/1x/title_bg.png') }}" class="img-fluid"
                                    style="margin-top:-100px;" alt="">
                                <table class="table-img cover-table" style="width:40%;">
                                    <tr>
                                        <td>
                                            <p
                                                style="color:#1e398d; font-family: 'Roboto', sans-serif; font-size:19px; font-weight:900; text-align:center; margin-left:10px; margin-top:-25px; margin-bottom:-8px; line-height: 0.5px;">
                                                PROGRESS</p>
                                            <p
                                                style="color:#1e398d; font-family:'Roboto', sans-serif; font-size:26px; font-weight:900; text-align:center; margin-left:12px; margin-top:25px; margin-bottom:-10px; line-height:0.5px;">
                                                REPORT</p>
                                            <p
                                                style="color:#1e398d; text-align:center; margin-top:20px; margin-left:10px; font-size: 19px; font-weight:500; margin-bottom:-15px;  font-family: 'Montserrat', sans-serif; font-weight: 500;">
                                                {{ $student_behaviour_skill['term']['name'] }}</p>
                                            <h3
                                                style="color:#1e398d; font-weight:500; font-size:19px; text-align:center; margin-left:10px;  margin-top: 7px;       font-family: 'Montserrat', sans-serif; font-weight: 500;">
                                                @if (str_contains($student_behaviour_skill['com_class']['class_name'], '1') ||
                                                        str_contains($student_behaviour_skill['com_class']['class_name'], 'one') ||
                                                        str_contains($student_behaviour_skill['com_class']['class_name'], '2') ||
                                                        str_contains($student_behaviour_skill['com_class']['class_name'], 'two'))
                                                    Lower Primary
                                                @else
                                                    Upper Primary
                                                @endif
                                            </h3>
                                            <h3 class="montser-font"
                                                style="color:#1e398d; font-size:18px; text-align:center; margin-left:10px; margin-top:-10px;  font-family: 'Montserrat', sans-serif;  font-weight: 400; ">
                                                {{ $student_behaviour_skill['com_class']['class_name'] }}
                                            </h3>
                                            <h5
                                                style="margin-top: -10px; margin-left:10px; font-family: 'Montserrat', sans-serif; font-size:14px; font-weight: 300;">
                                                ACADEMIC YEAR
                                                <br>
                                                <h5
                                                    style="font-family: 'Montserrat', sans-serif; font-size:13px; font-weight: 400; margin-top:-10px;">

                                                    <input type="number"
                                                        value="{{ substr($student_behaviour_skill['academic_year']['title'], 0, 4) }}"
                                                        style="width: 40px; margin-bottom:5px; border: none !important; margin-left:5px; ">
                                                    -
                                                    <input type="number"
                                                        value="{{ substr($student_behaviour_skill['academic_year']['title'], -2) }}"
                                                        style="width: 40px; border: none !important; ">
                                                </h5>
                                            </h5>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="position:relative;">
                                <table class="table-img cover-table mob-width" style="top: 40% !important;">
                                    <img class="d-none d-md-glex"
                                        style="left: 50%;
                                transform: translateX(-50%);
                                width: 82%;position:absolute; height:180px; margin:0 auto;"
                                        src="{{ asset('/assets/img/1x/name2.png') }}">
                                    <tr>
                                        <td>
                                            <table class="w-100">
                                                <tr style="display:flex !important;">
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d;  font-size: 12px;"
                                                            for="">Name:</label>
                                                        <input type="text" style="text-align: center"
                                                            value="{{ ucwords($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']) }}">
                                                    </td>
                                                </tr>
                                                <tr class="d-flex input-spc">
                                                    <td class="d-flex" style="display:flex !important; width:180%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Computer
                                                            ID:</label>
                                                        <input type="text" style="text-align: center"
                                                            value="{{ $student['roll_no'] }}">
                                                    </td>
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Class:</label>
                                                        <input type="text" style="text-align: center"
                                                            value="{{ $student_behaviour_skill['com_class']['class_name'] }}">
                                                    </td>
                                                </tr>
                                                <tr class="input-spc" style="display:flex !important;">
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Class
                                                            Teacher:</label>
                                                        <input type="text" style="text-align: center"
                                                            value="{{ $class_teacher_name ? $class_teacher_name : 'N/A' }}">
                                                    </td>
                                                </tr>
                                                <tr class="input-spc" style="display:flex !important;">
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Campus:</label>
                                                        <input type="text" style="text-align: center"
                                                            value="{{ $student_behaviour_skill['branch']['br_name'] }}">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table class="w-100" style="margin-top: 170px;text-align:center;">
                                    <tr class="input-spc  mob-flex"
                                        style=" display:flex !important; margin-top: 10px;  margin-bottom: 0px;justify-content: center;">
                                        <td>
                                            <div class="d-flex" style="align-items: center;white-space: nowrap;">
                                                <img src="{{ asset('/assets/img/1x/web_icon.png') }}" height="10px"
                                                    width="10px" alt="" style="margin-right: 3px">

                                                <p class="small-text text-vline" id="text-vline"
                                                    style="color: blue; font-size: small;">
                                                    www.ucs.edu.pk |</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex" style="align-items: center;white-space: nowrap;">
                                                <img src="{{ asset('/assets/img/1x/mail_icon.png') }}" height="10px"
                                                    width="10px" alt="" style="margin-right: 3px">

                                                <p class="small-text text-vline" id="text-vline"
                                                    style="color: blue; font-size: small;">
                                                    info@ucs.edu.pk |</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex" style="align-items: center;white-space: nowrap;">
                                                <img src="{{ asset('/assets/img/1x/call_icon.png') }}" height="10px"
                                                    width="10px" alt="" style="margin-right: 3px">

                                                <p class="small-text" style="color: blue; font-size: small;">
                                                    042-111-827-111</p>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr
                                        style=" display:flex !important; margin-top: 0px;margin-bottom: 0px;justify-content: center;">
                                        <td style="display: flex;align-items:center" class="mob-p">
                                            <img src="{{ asset('/assets/img/1x/location_icon.png') }}" height="10px"
                                                width="10px" alt="">
                                            <p class="small-text" style="color: blue; font-size: small;">10-11
                                                Gurumangat Road, Gulberg III, Lahore, Pakistan</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="page-break">
        <div class="container-fluid">
            <div class="row" id="add-flex-equal flex-td">
                <div id="first-table f-padding big-table-flex" class="col-lg-6 py-2 order-1">
                    <p class="info-headings">Progress Report - {{ $student_behaviour_skill['term']['name'] }}</p>
                    <table style="width:100%; border: 1px solid grey; border-radius:5px;" class="single-border">
                        <tr>
                            <td>student's age: {{ calculate_age($student['date_of_birth']) }} years</td>
                            <td>class average age: {{ $class_average_age }}</td>
                            <td>
                                1st parent-teacher meeting:
                                <br><br>
                                {{ $student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended'] ? 'Attended' : 'Not Attended' }}
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table style="width:100%; border: 1px solid grey; border-radius:5px; background-color:#dcdddf;"
                        class="single-border">
                        <tr>
                            <td>no. of working days:{{ $total_no_of_working_days }}</td>
                            <td rowspan="2">days present: {{ $present_attendances }}</td>
                            <td rowspan="2"> days absent: {{ $absent_attendances }}</td>
                        </tr>
                        <tr>
                            <td>percentage of attendence:{{ $attendance_percentage }}%</td>
                        </tr>
                    </table>
                    <br>
                    <div class="scroll-table" style="max-width:100%;">
                        <table style="width:100%; border: 1px solid grey; border-radius:5px;"
                            class="single-border wrap-table">
                            <tr class="drk-blue-clr">
                                <th rowspan="2">subjects</th>
                                @if (isset($assessment_weightage))
                                    @foreach ($assessment_weightage as $assessment_name => $weightage)
                                        <th>{{ $assessment_name }}</th>
                                    @endforeach
                                @endif
                                <td>total marks</td>
                                <td rowspan="2">overall grade</td>
                                <th rowspan="2" class="white-space-nowrap">teacher's comment:</th>

                            </tr>

                            <tr class="drk-blue-clr">
                                @if (isset($assessment_weightage))
                                    @foreach ($assessment_weightage as $assessment_name => $weightage)
                                        <th style="font-weight: bold !important;">{{ $weightage }} Marks</th>
                                    @endforeach
                                    <th style="font-weight: bold !important;">{{ array_sum($assessment_weightage) }}
                                        Marks
                                    </th>
                                @endif
                            </tr>
                            @if (isset($all_assessment_data))
                                @foreach ($all_assessment_data as $assessment => $single_subject_mark)
                                    <tr>
                                        @if (isset($single_subject_mark['subject_name']))
                                            <td>{{ $single_subject_mark['subject_name'] }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if (isset($assessment_weightage))
                                            @foreach ($assessment_weightage as $assessment_name => $weightage)
                                                @if (array_key_exists($assessment_name, $single_subject_mark))
                                                    <td>{{ $single_subject_mark[$assessment_name] }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        @else
                                            <td></td>
                                        @endif
                                        @if ($single_subject_mark)
                                            <td>{{ array_sum(array_diff_key($single_subject_mark, array_flip(['subject_sort']))) }}
                                            </td>
                                            <td>{{ get_student_grade($student_behaviour_skill['class_id'], round((array_sum(array_diff_key($single_subject_mark, array_flip(['subject_sort']))) / array_sum($assessment_weightage)) * 100)) }}
                                            </td>
                                        @else
                                            <td></td>
                                            <td></td>
                                        @endif
                                        @if ($subject_assessment_remarks && isset($subject_assessment_remarks[$single_subject_mark['subject_name']]))
                                            <td style="text-transform: unset">
                                                {{ ucfirst($subject_assessment_remarks[$single_subject_mark['subject_name']]) }}
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            @if (isset($all_minor_subject_grade))
                                @foreach ($all_minor_subject_grade as $minor_subject_grade)
                                    <tr>
                                        <td>{{ $minor_subject_grade['subject_name'] }}</td>
                                        @foreach ($assessment_weightage as $assessment_name => $weightage)
                                            <td style="background-color: #dcdddf;">{{-- for assessment names --}}</td>
                                        @endforeach
                                        <td style="background-color: #dcdddf;">{{-- for total marks --}}</td>
                                        <td>{{ $minor_subject_grade['grade'] }}</td>
                                        @if ($subject_assessment_remarks && isset($subject_assessment_remarks[$single_subject_mark['subject_name']]))
                                            <td style="text-transform: unset">
                                                {{ ucfirst($subject_assessment_remarks[$single_subject_mark['subject_name']]) }}
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>


                <div id="second-table s-padding big-table-flex1" class="col-lg-6 py-2 order-0">
                    <table style="width:100%;">
                        <tr>
                            <td>
                                <img src="{{ asset('/assets/img/1x/logo-hori.png') }}" alt="">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table style="margin-bottom: 10px; padding: 20px; border: none; border-radius: 5px; width:100%;"
                        class="tbl-grading">
                        <tr>
                            <td>
                                <h5
                                    style="color:#1e398d;white-space: nowrap; font-size:14px;padding:10px 0px 0px 5px;">
                                    Grading Key
                                </h5>
                            </td>

                        </tr>


                        <tr class="table-into-flex">
                            @foreach ($grading_keys as $key)
                                @if ($loop->odd)
                        <tr class="table-into-flex">
                            @endif
                            <td>
                                <h5 style="color:#1e398d;white-space: nowrap; font-size:14px;padding: 5px;"
                                    class="custom-h5">
                                    {{ $key['grading_key'] }}:
                                    {{ $key['title'] }}
                                </h5>
                            </td>
                            @if ($loop->even)
                        </tr>
                        @endif
                        @endforeach
                    </table>
                    <div class="wrap-table-scroll-cover">
                        <table style="max-width: 100%;width: 100%;" class="single-border for-renove-padding">
                            <tr class="drk-blue-clr">
                                <td class="not for use"></td>
                                <th class="roboto-f text-center" style="font-weight: bolder !important;">grade
                                </th>
                            </tr>
                            @if (isset($general_behaviours))
                                @foreach ($general_behaviours as $behaviour)
                                    <tr class="grey-clr">
                                        <th class=" text-left" style="font-weight: bolder !important;"
                                            colspan="2">
                                            {{ $behaviour['title'] ?? '' }}
                                        </th>
                                    </tr>
                                    @foreach ($behaviour['children'] as $child)
                                        <tr class="{{ $loop->even ? 'grey-clr' : '' }}">
                                            <td class="white-space-nowrap">{{ $child['title'] ?? '' }}</td>
                                            <td class="not for use"><input type="text"
                                                    style="border:none ; text-align:center; background:transparent;"
                                                    class="form-control form-control-sm behaviours"
                                                    data-behaviour_id="{{ $child['id'] ?? '' }}"
                                                    placeholder="Enter Grade"
                                                    value="{{ isset($student_behaviour['student_behaviour_skill_marks'][$child['id']]) ? $student_behaviour['student_behaviour_skill_marks'][$child['id']]['grade'] : '' }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif
                        </table>
                    </div>
                    {{-- <br> --}}
                    <img src="{{ asset('/assets/img/1x/family.png') }}" alt=""
                        style="width: 200px;margin: 0 auto;display: block;" class="table-bottom-img">

                </div>
            </div>
        </div>
    </section>


    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <script>
        const paragraph = document.querySelector(
            '.text-vline'); // Replace 'yourParagraphId' with the actual ID of your paragraph element

        function removeVerticalBar() {
            if (window.innerWidth <= 375) {
                paragraph.innerHTML = paragraph.innerHTML.replace(/\|/g, '');
            }
        }

        // Call the function on page load and on window resize
        window.addEventListener('load', removeVerticalBar);
        window.addEventListener('resize', removeVerticalBar);
    </script>
</body>
