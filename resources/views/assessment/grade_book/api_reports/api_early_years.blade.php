<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KG Progress Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900;&family=Roboto:wght@100;300;400;500;700;800;900&display=swap');

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

        .white-space-nowrap {
            white-space: nowrap !important;
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
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #cdede8;
            white-space: nowrap !important;
        }

        .w-30_h-20 {
            width: 30px;
            height: 20px;
        }

        .drk-blue-clr {
            background-color: #1e398d;
            color: #fff;
        }

        .tbl-inputs {
            border: 2px solid #00A88E;
            margin: 0;
            padding: 0;
            border-radius: 5px;
            width: 100%;
            background-color: #b9e4ef;
        }

        .pd-lt-10 {
            padding-left: 10px;
        }

        .name-inputs {
            color: #76a7ff;

        }

        .set-to-left-img {
            left: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .set-to-bottom {
            right: 10px;
            bottom: -86px;
        }

        .set-to-bottom2 {
            left: 10px;
            bottom: -86px;
        }

        .cover-table label {
            white-space: nowrap;
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


        #lined {
            line-height: 31px;
            background-image: -webkit-linear-gradient(left, white 0, transparent 0), -webkit-linear-gradient(right, white 0, transparent 0), -webkit-linear-gradient(white 30px, #000 30px, #000 31px, white 31px);
            background-repeat: repeat-y;
            background-size: 100% 100%, 100% 100%, 100% 31px;
            background-attachment: local;
            border: none;
        }


        #linedTransparent,
        #linedTransparent_2 {
            line-height: 31px;
            background-image: -webkit-linear-gradient(left, transparent 0, transparent 0), -webkit-linear-gradient(right, transparent 0, transparent 0), -webkit-linear-gradient(#b9e4ef 30px, #000 30px, #000 31px, transparent 31px);
            background-repeat: repeat-y;
            background-size: 100% 100%, 100% 100%, 100% 31px;
            background-attachment: local;
            border: none;
            width: -webkit-fill-available;
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

        textarea:focus,
        input:focus {
            outline: none;
            border: none;
        }

        input {
            border: 1px solid #00A88E;

        }

        #lined {
            line-height: 31px;
            background-image: -webkit-linear-gradient(left, white 0, transparent 0), -webkit-linear-gradient(right, white 0, transparent 0), -webkit-linear-gradient(white 30px, #000 30px, #000 31px, white 31px);
            background-repeat: repeat-y;
            background-size: 100% 100%, 100% 100%, 100% 31px;
            background-attachment: local;
            border: none;
        }

        .d-flex {
            display: flex !important;
        }

        .input-border {
            border: none !important;
            border-bottom: 1px solid #000 !important;
            background: transparent;
        }


        .all-text {
            font-size: 12px;
            margin-bottom: 4px
        }


        .info-headings {
            color: azure;
            background-color: #1e398d;
            padding: 3px 35px 3px 35px;
            display: inline-block;
            margin: 15px 0px !important;
            border-radius: 25px;
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


        .table-img {
            top: 50%;
        }

        .table-img,
        tr,
        td {
            border: none !important;
        }

        .name-inputs {
            color: #76a7ff;
        }

        .cover-table label {
            white-space: nowrap;
        }

        .small-text {
            color: blue;
            font-size: small;
        }

        .input-spc {
            margin: 10px 0px 10px 0px;
        }

        .tbl-info2 td {
            border-left: 1px solid grey !important;
            padding: 0 5px;

        }

        .tbl-info2 td:first-child {
            border-left: none !important;

        }


        .tbl-lang {
            width: 100%;
            border: 1px solid grey;
            border-radius: 5px;

        }

        .tbl-urdu {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #cdede8;
            border: 1px solid grey;
            border-radius: 5px;

        }

        .fade-bg {
            background: rgba(205, 237, 232, 0.65);
            position: inherit;
            z-index: 1;
        }

        .custom-lang-table tr td {
            padding-left: 10px;
        }

        .check-box-padding tr td {
            padding-left: 5px;
        }

        .table-img tr td input {
            border: none !important;
            border-bottom: 1px solid #000 !important;
            background: transparent;
            width: 100%;
        }


        .custom-check-box {
            display: block;
            /* margin-bottom: 15px; */
        }

        .custom-check-box input {
            padding: 0;
            height: initial;
            width: initial;
            margin-bottom: 0;
            display: none;
            cursor: pointer;
        }


        .custom-check-label {
            position: relative;
            cursor: pointer;
            margin: 0;
        }

        .custom-check-box .custom-check-label:before {
            content: '';
            -webkit-appearance: none;
            background: #00A88E;
            border: 2px solid #00A88E;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
            padding: 5px;
            display: inline-block;
            position: relative;
            vertical-align: middle;
            cursor: pointer;
            margin-right: 5px;
        }


        .custom-check-box input:checked+.custom-check-label:after {
            content: '';
            display: block;
            position: absolute;
            top: 4px;
            left: 5px;
            width: 5px;
            height: 10px;
            border: solid #FFF;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .mt-50 {
            margin-top: 50px;
        }

        textarea,
        input[type] {
            pointer-events: none;
        }

        .label-font-size {
            font-size: 10px;
        }

        /* *:read-write{
            pointer-events: none;
        } */

        /* .table-img tr td  {
  display: flex;
  } */
        @media print {
            .pagebreak {
                clear: both;
                page-break-after: always;
            }
        }

        @media print {
            footer {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div style="position:relative;">
                    <img src="{{ asset('assets/img/1x/new-kids.png') }}" class="img-fluid" style=""
                        alt="">
                    <table class="table-img cover-table">
                        <tbody>
                            <tr>
                                <td style="text-align: center;">
                                    <h2
                                        style="color:#00A88E; margin-top: 128px; font-family:dexa, expanded, extra bold, Sans-serif; font-size:17px;">
                                        Progress Report: {{ $student_behaviour_skill['term']['name'] ?? 'N/A' }}</h2>
                                    <h3
                                        style="color:#1e398d; margin-top: -8px; font-family:raleway, medium; font-size: 16px;;">
                                        Early
                                        Years: {{ $student_behaviour_skill['com_class']['class_name'] ?? 'N/A' }}</h3>
                                    <h5 style="margin-top: -8px; font-size:12px;">ACADEMIC YEAR
                                        <input type="number"
                                            value="{{ substr($student_behaviour_skill['academic_year']['title'] ?? '', 0, 4) }}"
                                            style=" width:35px;  border: none !important;">
                                        -
                                        <input type="number"
                                            value="{{ substr($student_behaviour_skill['academic_year']['title'] ?? '', -2) }}"
                                            style=" width:30px; border: none !important;">
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="margin-bottom: 80px; margin-top:-10px;">
                                        <tbody>
                                            <tr>
                                                <td class="d-flex" style="display:flex !important; width:100%;">
                                                    <label class="name-inputs all-text label-font-size"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Name:</label>
                                                    <input type="text" style="text-align:center; font-size: 9px;"
                                                        value="{{ ucwords(($student['first_name'] ?? '') . ' ' . ($student['middle_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) }}">
                                                </td>
                                            </tr>
                                            <tr class="d-flex input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text label-font-size"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Computer ID:</label>
                                                    <input type="text" style="text-align:left; font-size: 9px;"
                                                        value="{{ $student['roll_no'] ?? 'N/A' }}">
                                                </td>
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text label-font-size"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Class:</label>
                                                    <input type="text" style="text-align:center; font-size: 9px;"
                                                        value="{{ $student_behaviour_skill['com_class']['class_name'] ?? 'N/A' }}">
                                                </td>
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text label-font-size"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Class Teacher:</label>
                                                    <input type="text" style="text-align: center; font-size: 9px;"
                                                        value="{{ $class_teacher_name ? $class_teacher_name : 'N/A' }}">
                                                </td>
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text label-font-size"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Campus:</label>
                                                    <input type="text" style="text-align:center; font-size: 9px;"
                                                        value="{{ $student_behaviour_skill['branch']['br_name'] ?? 'N/A' }}">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <table class="table-img2" style="position:relative;">
                    <tbody>
                        <tr>
                            <td>
                                <img style="left: 0px; bottom:253px; position:absolute;z-index:1 " class="img-fluid"
                                    src="{{ asset('assets/img/1x/new-kids-p.png') }}">
                                <table class="tbl-inputs"
                                    style="margin-bottom: 0 !important; margin-top: 10px !important;position: relative;">

                                    <tbody>
                                        <tr>
                                            <td style="z-index: inherit;">
                                                <p class="all-text"
                                                    style="margin-top:20px; margin-right:20px; margin-left:20px; margin-bottom:10px; ">
                                                    Class Teacher's Comments:</p>
                                                <p style="margin-right:20px; margin-left:20px; margin-bottom:10px;"
                                                    id="linedTransparent">
                                                    {{ $student_behaviour_skill['student_behaviour_skill_remark']['teacher_comments'] ?? 'N/A' }}
                                                </p>
                                                <p class="all-text" style="margin-left:20px; ">School Head's Comments:
                                                </p>

                                                <p style="margin-right:20px; margin-left:20px; margin-bottom:10px; width: 300px"
                                                    id="linedTransparent_2">
                                                    {{ $student_behaviour_skill['student_behaviour_skill_remark']['schoolhead_comments'] ?? 'N/A' }}
                                                </p>
                                                <p class="all-text"
                                                    style="margin-right:20px; margin-left:20px; margin-bottom:10px;">
                                                    Class Teacher's
                                                    Signature: <input class="input-border" type="text"
                                                        value="{{ $class_teacher_name ? $class_teacher_name : 'N/A' }}"
                                                        style="width: 60%;">
                                                </p>
                                                <p class="all-text"
                                                    style="margin-right:20px; margin-left:20px; margin-bottom:10px;">
                                                    School
                                                    Head's Signature: <input class="input-border" type="text"
                                                        style="width: 60%;">
                                                </p>
                                                <p class="all-text"
                                                    style="margin-right:20px; margin-left:20px; margin-bottom:120px;">
                                                    Dated:
                                                    <input class="input-border" type="text"
                                                        value="{{ Carbon\Carbon::now()->format('d-m-Y') }}">
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- <table style="/*margin-left:105px*/; margin: 25px auto auto;">
                                    <tbody>
                                        <tr class="input-spc" style="display: flex !important;">
                                            <td>
                                                <img src="{{ asset('assets/img/1x/web_icon.png') }}" alt="">
                                            </td>
                                            <td>
                                                <p class="small-text" style="font-size: 11px;">www.ucs.edu.pk |</p>
                                            </td>
                                            <td>
                                                <img src="{{ asset('assets/img/1x/mail_icon.png') }}" alt="">
                                            </td>
                                            <td>
                                                <p class="small-text" style="font-size: 11px;">info@ucs.edu.pk |</p>
                                            </td>
                                            <td>
                                                <img src="{{ asset('assets/img/1x/call_icon.png') }}" alt="">
                                            </td>
                                            <td>
                                                <p class="small-text" style="font-size: 11px;">042-111-827-111</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="/*margin-left:115px;*/ margin: -20px auto;">
                                    <tbody>
                                        <tr style="display: flex !important;">
                                            <td>
                                                <img src="{{ asset('assets/img/1x/location_icon.png') }}"
                                                    alt="">
                                            </td>
                                            <td>
                                                <p class="small-text" style="font-size: 11px;">10-11 Gurumangat Road,
                                                    Gulberg
                                                    III, Lahore, Pakistan</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="/*margin-left:165px; margin-top:-10px;*/ margin: 10px auto;">
                                    <tbody>
                                        <tr style="display: flex !important;">
                                            <td>
                                                <img src="{{ asset('assets/img/1x/fb_icon.png') }}" alt="">
                                                <img src="{{ asset('assets/img/1x/insta_icon.png') }}"
                                                    alt="">
                                                <img src="{{ asset('assets/img/1x/youtube_icon.png') }}"
                                                    alt="">
                                                <img src="{{ asset('assets/img/1x/linked_in.png') }}" alt="">
                                            </td>
                                            <td>
                                                <p class="small-text" style="font-size: 11px;">United Charter Schools
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="/*margin-left:80px;*/ text-align: center;">
                                    <tbody>
                                        <tr style="width:100%; ">
                                            <td>
                                                <img src="{{ asset('assets/img/1x/bss_logo.png') }}"
                                                    style="margin: 10px;">
                                                <img src="{{ asset('assets/img/1x/montserri_acad.png') }}"
                                                    style="margin: 10px;">
                                                <img src="{{ asset('assets/img/1x/educators.png') }}"
                                                    style="margin: 10px;">
                                                <img src="{{ asset('assets/img/1x/concordia_logo.png') }}"
                                                    style="margin: 10px;">
                                                <img src="{{ asset('assets/img/1x/remierdlc_logo.png') }}"
                                                    style="margin: 10px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="small-text" style="font-size: 12px;">
                                                    Belgium | Malaysia | Oman | Pakistan | Phillipines | Thailand | UAE
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> --}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6 col-md-12">
                <div
                    style="position:relative;padding: 0px 10px 0;margin-top:30px; display: flex;flex-direction: column; align-items: center;">
                    <p class="info-headings" style="margin-bottom:25px;">Student's Information</p>

                    <table style="width:100%;border:1px solid grey; border-collapse:collapse;" class="tbl-info2">
                        <tbody>
                            <tr style="height: 0px; paddding: 0px;">
                                <td class="all-text pd-lt-10" style="padding-left:10px;">Student's Age
                                </td>
                                <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                                        style="background: transparent; border:none;"
                                        value="{{ calculate_age($student['date_of_birth'] ?? null) }} years">
                                </td>
                            </tr>
                            <tr style="background-color:#cdede8;height: 0px; paddding: 0px;">
                                <td class="all-text pd-lt-10" style="padding-left:10px;">Class Average Age
                                </td>
                                <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                                        value="{{ $class_average_age ?? 'N/A' }} years"
                                        style="background: transparent; border:none;">
                                </td>
                            </tr>
                            <tr style="height: 0px; paddding: 0px;">
                                <td class="all-text pd-lt-10" style="padding-left:10px;">Term 1: Total No.
                                    of
                                    Working Days
                                </td>
                                <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                                        value="{{ $total_no_of_working_days ?? 'N/A' }}"
                                        style="background: transparent; border:none;">
                                </td>
                            </tr>
                            <tr style="background-color:#cdede8;height: 0px; paddding: 0px;">
                                <td class="all-text pd-lt-10" style="padding-left:10px;">Term 1: Student's
                                    Attendance
                                </td>
                                <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                                        value="{{ $attendance_percentage ?? 'N/A' }}%"
                                        style="background: transparent; border:none;">
                                </td>
                            </tr>
                            <tr style="height: 0px; paddding: 0px;">
                                <td class="all-text pd-lt-10" style="padding-left:10px;">1st
                                    Parent-Teacher
                                    Meeting
                                </td>
                                <td class="all-text" style="padding-left:10px;"><input type="text"
                                        style="background: transparent; border:none;"
                                        value="{{ ($student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended'] ?? false) ? 'Attended' : 'Not Attended' }}">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="info-headings" style="margin-bottom:-15px;">Grading Key:</p>

                    <table class="tbl-grading" style="width:100%;white-space: initial !important;">
                        <tbody>
                            @foreach ($grading_keys->chunk(2) as $grading_keys_chunks)
                                <tr>
                                    @foreach ($grading_keys_chunks as $grading_key)
                                        <td style="vertical-align: baseline; padding:10px 10px 0px 10px;">
                                            <h5 style="color:#1e398d; font-size:14px; ">
                                                {{ $grading_key['grading_key'] }}
                                                : {{ $grading_key['title'] }}</h5>
                                            @if ($grading_key['description'])
                                                <p class="all-text" style=" white-space:initial;">
                                                    {{ $grading_key['description'] }}</p>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (isset($outer_skills))
                        @foreach ($outer_skills as $level)
                            @include('assessment.grade_book.api_reports.api_partials.api_outer_skill', [
                                'level' => $level,
                            ])
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div style="width: 100%; position: relative;padding: 0px 10px 0;margin-top:30px">

                    <div style="position: relative" class="tb-flex-column">
                        <img src="{{ asset('assets/img/1x/logo-hori.png') }}" alt=""
                            style="max-width: 100%;height:auto" height="100px" width="250px">
                        <div style="height:100%; background-color:#cdede8b8;" class="for-add-new-padding">
                            @foreach ($inner_skills as $sort_no => $skills)
                                @if ($sort_no == 1)
                                    @foreach ($skills as $level)
                                        @include(
                                            'assessment.grade_book.api_reports.api_partials.api_col_one_inner_skill',
                                            ['level' => $level]
                                        )
                                    @endforeach
                                @else
                                    <td>
                                        <table
                                            style="height:100%;background-color:{{ $sort_no % 2 == 0 ? '' : '#cdede8' }};"
                                            class="check-box-padding">
                                            @foreach ($skills as $inner_loop_index => $level)
                                                @include(
                                                    'assessment.grade_book.api_reports.api_partials.api_col_two_inner_skill',
                                                    [
                                                        'level' => $level,
                                                        'sort_no' => $sort_no,
                                                        'inner_loop_index' => $inner_loop_index,
                                                    ]
                                                )
                                            @endforeach
                                        </table>
                                    </td>
                                @endif
                            @endforeach

                        </div>
                        <img src="{{ asset('assets/img/1x/new-kids3.png') }}" style="position:absolute;"
                            class="set-to-bottom img-fluid" alt="">
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
