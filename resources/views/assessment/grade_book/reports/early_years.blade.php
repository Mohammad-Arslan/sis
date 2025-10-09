<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KG Progress Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900&display=swap');
        /*@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900;&family=Roboto:wght@100;300;400;500;700;800;900&display=swap');*/

        .single-border,
        .single-border td,
        .single-border th {
            border: 1px solid grey;
            border-collapse: collapse;

        }

        .tb-flex-column {
            display: flex;
            flex-direction: column
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
            font-weight: thin;


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
            margin: 50px 50px 30px 50px;
            padding: 15px 25px 100px;
            border-radius: 5px;
            width: 450px;
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
            right: 50px;
            bottom: -20px;
            z-index: -1;
            height: 78px;
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


        #linedTransparent {

            overflow: hidden;
            /* flex-grow: 1;
            border: none; */
            background: none;
            color: #000;
            /* background-image: linear-gradient(to bottom, transparent, transparent 30px, #000 30px, #000 32px, transparent 32px);
            background-size: 100% 32px; */
            line-height: 18px;
            /* font-size: 20px; */
            height: 100%;
            width: -webkit-fill-available;
            appearance: none;
            display: block;
            font-size: 12px;
            border: 1px solid #000;
            border-radius: 5px;
        }


        #linedTransparent_2 {
            overflow: hidden;
            /* flex-grow: 1;
            border: none; */
            background: none;
            color: #000;
            /* background-image: linear-gradient(to bottom, transparent, transparent 30px, #000 30px, #000 32px, transparent 32px);
            background-size: 100% 32px; */
            line-height: 18px;
            /* font-size: 20px; */
            height: 100%;
            width: -webkit-fill-available;
            appearance: none;
            display: block;
            font-size: 12px;
            border: 1px solid #000;
            border-radius: 5px;
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
            /* color: azure;
            background-color: #1e398d;
            padding: 3px 35px 3px 35px;
            display: inline-block;
            margin: 15px 0px !important;
            border-radius: 25px; */
            color: azure;
            background-color: #1e398d;
            padding: 3px 35px 3px 35px;
            display: block;
            margin: 15px auto !important;
            border-radius: 25px;
            width: fit-content;
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

        .table-img tr td {
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
            margin: 10 0 10 0px;
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

        .all-text.pd-lt-10 {
            height: 0px;
        }

        .mt-50 {
            margin-top: 50px;
        }

        textarea,
        input[type] {
            pointer-events: none;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        @page {
            margin: 1in;
        }

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


    <table style="width:100%;margin-top:50px;">
        <tr style="vertical-align: middle;">
            <td style="position: relative;">
                <table class="table-img2">
                    <tr>
                        <td>
                            <table class="tbl-inputs" style="margin-bottom: 0 !important; margin-top: 10px !important;position: relative;">
                                <img style="left: 50px; bottom:128px; position:absolute;z-index:1 "
                                        src="{{ asset('assets/img/1x/new-kids-p.png') }}">
                                <tr>
                                    <td style="z-index: inherent;">
                                        <p class="all-text"
                                            style="margin-top:20px; margin-right:20px; margin-left:20px; margin-bottom:10px; ">
                                            Class Teacher's Comments:</p>
                                        <textarea style="margin-right:20px; margin-left:20px; margin-bottom:10px;" name="" id="linedTransparent"
                                            cols="" rows="7">{{ $student_behaviour_skill['student_behaviour_skill_remark']['teacher_comments'] ?? 'N/A' }}</textarea>
                                        <p class="all-text" style="margin-left:20px; ">School Head's Comments: </p>

                                        <textarea style="margin-right:20px; margin-left:20px; margin-bottom:10px;" name="" id="linedTransparent_2"
                                            cols="" rows="6">{{ $student_behaviour_skill['student_behaviour_skill_remark']['schoolhead_comments'] ?? 'N/A' }}</textarea>
                                        <p class="all-text"
                                            style="margin-right:20px; margin-left:20px; margin-bottom:10px;">Class
                                            Teacher's Signature: <input class="input-border" type="text"
                                                value="{{ $class_teacher_name ?? 'N/A' }}" style="width: 60%;">
                                        </p>
                                        <p class="all-text"
                                            style="margin-right:20px; margin-left:20px; margin-bottom:10px;">School
                                            Head's Signature: <input class="input-border" type="text"
                                            value="{{ $school_head_name ?? 'N/A' }}" style="width: 60%;">
                                        </p>
                                        @if (isset($student_behaviour_skill['term']['name']))
                                         @if (str_contains($student_behaviour_skill['term']['name'], '2'))
                                            <p style="margin-left:20px; margin-bottom:10px;"
                                             style="font-weight:bold; font-size: 14px;" class="all-text">
                                           <b>  {{ isset($student_behaviour_skill->student_behaviour_skill_remark->is_promoted) ? ($student_behaviour_skill->student_behaviour_skill_remark->is_promoted ? 'Promoted' : 'Not Promoted') : '' }} </b>
                                            </p>
                                     @endif
                                     @endif
                                        <p class="all-text"
                                        style="margin-right:20px; margin-left:20px; margin-bottom:120px;">Dated:
                                        <input class="input-border" type="text"
                                        value="{{ Carbon\Carbon::now()->format('d-m-Y') }}">
                                    </p>

                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:105px;margin-top: 25px;">
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
                            </table>
                            <table style="margin-left:115px; margin-top:-20px;">
                                <tr style="display: flex !important;">
                                    <td>
                                        <img src="{{ asset('assets/img/1x/location_icon.png') }}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text" style="font-size: 11px;">10-11 Gurumangat Road, Gulberg
                                            III, Lahore, Pakistan</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:165px; margin-top:-10px;">
                                <tr style="display: flex !important;">
                                    <td>
                                        <img src="{{ asset('assets/img/1x/fb_icon.png') }}" alt="">
                                        <img src="{{ asset('assets/img/1x/insta_icon.png') }}" alt="">
                                        <img src="{{ asset('assets/img/1x/youtube_icon.png') }}" alt="">
                                        <img src="{{ asset('assets/img/1x/linked_in.png') }}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text" style="font-size: 11px;">United Charter Schools</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:80px; ">
                                <tr style="width:100%; ">
                                    <td>
                                        <img src="{{ asset('assets/img/1x/bss_logo.png') }}" style="margin: 10px;">
                                        <img src="{{ asset('assets/img/1x/montserri_acad.png') }}"
                                            style="margin: 10px;">
                                        <img src="{{ asset('assets/img/1x/educators.png') }}" style="margin: 10px;">
                                        <img src="{{ asset('assets/img/1x/concordia_logo.png') }}"
                                            style="margin: 10px;">
                                        <img src="{{ asset('assets/img/1x/remierdlc_logo.png') }}"
                                            style="margin: 10px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="small-text" style="font-size: 12px;" style="margin-left: 10px;">
                                            Belgium | Malaysia | Oman | Pakistan | Phillipines | Thailand | UAE</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>

            <td>
                <table style="margin-right: 40px ;">
                    <tr>
                        <td style="position:relative;">
                            <img src="{{ asset('assets/img/1x/new-kids.png') }}" alt="">
                            <table class="table-img cover-table">
                                <tr>
                                    <td style="text-align: center;">
                                        <h2
                                            style="color:#00A88E; margin-top: 60px; font-family:dexa, expanded, extra bold, Sans-serif; font-size:20px;">
                                            Progress Report: {{ $student_behaviour_skill['term']['name'] ?? '' }}</h2>
                                        <h3
                                            style="color:#1e398d; margin-top: -8px; font-family:raleway, medium; font-size: 16px;;">
                                            Early
                                            Years: {{ $student_behaviour_skill['com_class']['class_name'] ?? '' }}</h3>
                                        <h5 style="margin-top: -8px; font-size:12px;">ACADEMIC YEAR
                                            <input type="number"
                                                value="{{ isset($student_behaviour_skill['academic_year']['title']) ? substr($student_behaviour_skill['academic_year']['title'], 0, 4) : '' }}"
                                                style=" width:35px;  border: none !important;">
                                            -
                                            <input type="number"
                                                value="{{ isset($student_behaviour_skill['academic_year']['title']) ? substr($student_behaviour_skill['academic_year']['title'], -2) : '' }}"
                                                style=" width:30px; border: none !important;">
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="margin-bottom: 80px; margin-top:-10px;">
                                            <tr>
                                                <td class="d-flex" style="display:flex !important; width:100%;">
                                                    <label class="name-inputs all-text"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Name:</label>
                                                    <input type="text" style="text-align:center;"
                                                        value="{{ isset($student['first_name']) ? ucwords($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']) : '' }}">
                                                </td>
                                            </tr>
                                            <tr class="d-flex input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Computer ID:</label>
                                                    <input type="text" style="text-align:center;"
                                                        value="{{ $student['roll_no'] ?? 'N/A' }}">
                                                </td>
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Class:</label>
                                                    <input type="text" style="text-align:center;"
                                                        value="{{ $student_behaviour_skill['com_class']['class_name'] . '/' .$student_behaviour_skill['section']['section_name'] ?? 'N/A' }}">
                                                </td>
                                                {{-- $student_behaviour_skill['section']['section_name'] --}}
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Class Teacher:</label>
                                                    <input type="text" style="text-align: center;"
                                                        value="{{ $class_teacher_name ?? 'N/A' }}">
                                                </td>
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text"
                                                        style="color:#1e398d; margin-bottom:0px; margin-right:5px; align-self:center;"
                                                        for="">Campus:</label>
                                                    <input type="text" style="text-align:center;"
                                                        value="{{ $student_behaviour_skill['branch']['br_name'] ?? 'N/A' }}">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <footer>

    </footer>

    {{-- <table style="padding: 20px 10px 0px 10px;margin-top:50px;width:100%">
        <tr style="vertical-align: middle;">
            <td style="position:relative;padding: 20px 10px 0;margin-top:50px">
                <p class="info-headings" style="margin-bottom:25px;">Student's Information</p>

                <table style="width:100%;border:1px solid grey; border-collapse:collapse;" class="tbl-info2">
                    <tr style="height: 0px; paddding: 0px;">
                        <td class="all-text pd-lt-10" style="padding-left:10px;">Student's Age
                        </td>
                        <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                                style="background: transparent; border:none;"
                                value="{{ isset($student['date_of_birth']) ? calculate_age($student['date_of_birth']) : '' }} years">
                        </td>
                    </tr>
                    <tr style="background-color:#cdede8;height: 0px; paddding: 0px;">
                        <td class="all-text pd-lt-10" style="padding-left:10px;">Class Average Age
                        </td>
                        <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                                value="{{ $class_average_age ?? '' }} years"
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
                                value="{{ isset($student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended']) ? ($student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended'] ? 'Attended' : 'Not Attended') : 'N/A' }}">
                        </td>
                    </tr>
                </table>

                <p class="info-headings" style="margin-bottom:-15px;">Grading Key:</p>

                <table class="tbl-grading" style="width:80%;">
                    @if (isset($grading_keys))
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
                    @endif
                </table>


                @if (isset($outer_skills))
                    @foreach ($outer_skills as $level)
                        @include('assessment.grade_book.reports.partials.outer_skill', [
                            'level' => $level,
                        ])
                    @endforeach
                @endif
            </td>

            <td style="width: 100%; position: relative;padding: 20px 10px 0;margin-top:50px">
                <table>
                    <tr>
                        <td>
                            <img src="{{ asset('assets/img/1x/logo-hori.png') }}" alt="">
                        </td>
                    </tr>
                </table>
                <table style="width:100%;">
                    <tr>
                        <td style="position: relative">
                            <table style="height:100%; padding-bottom:120px; background-color:#cdede8b8;"
                                class="for-add-new-padding">
                                <tr>
                                    @if (isset($inner_skills))
                                        @foreach ($inner_skills as $sort_no => $skills)
                                            @if ($sort_no == 1)
                                                @foreach ($skills as $level)
                                                    @include('assessment.grade_book.reports.partials.col_one_inner_skill',
                                                        ['level' => $level])
                                                @endforeach
                                            @else
                                                <td>
                                                    <table
                                                        style="height:100%;background-color:{{ $sort_no % 2 == 0 ? '' : '#cdede8b5' }};"
                                                        class="check-box-padding">
                                                        @foreach ($skills as $inner_loop_index => $level)
                                                            @include('assessment.grade_book.reports.partials.col_two_inner_skill',
                                                                [
                                                                    'level' => $level,
                                                                    'sort_no' => $sort_no,
                                                                    'inner_loop_index' => $inner_loop_index,
                                                                ])
                                                        @endforeach
                                                    </table>
                                                </td>
                                            @endif
                                        @endforeach
                                    @endif
                                </tr>
                            </table>
                            <img src="{{ asset('assets/img/1x/new-kids3.png') }}" style="position:absolute;"
                                class="set-to-bottom" alt="">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table> --}}

    <div style="padding: 0px 10px 0px 10px;width:100%;display:flex;gap:5px">
        <div style="position:relative;padding: 0px 10px 0;margin-top:30px">
            <p class="info-headings" style="margin-bottom:25px;">Student's Information</p>

            <table style="width:100%;border:1px solid grey; border-collapse:collapse;" class="tbl-info2">
                <tr style="height: 0px; paddding: 0px;">
                    <td class="all-text pd-lt-10" style="padding-left:10px;">Student's Age
                    </td>
                    <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                            style="background: transparent; border:none;"
                            value="{{ isset($student['date_of_birth']) ? calculate_age($student['date_of_birth']) : '' }} years">
                    </td>
                </tr>
                <tr style="background-color:#cdede8;height: 0px; paddding: 0px;">
                    <td class="all-text pd-lt-10" style="padding-left:10px;">Class Average Age
                    </td>
                    <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                            value="{{ $class_average_age ?? '' }} years"
                            style="background: transparent; border:none;">
                    </td>
                </tr>
                <tr style="height: 0px; paddding: 0px;">
                    <td class="all-text pd-lt-10" style="padding-left:10px;">{{ $student_behaviour_skill['term']['name'] ?? '' }} : Total No.
                        of
                        Working Days
                    </td>
                    <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                            value="{{ $total_no_of_working_days ?? 'N/A' }}"
                            style="background: transparent; border:none;">
                    </td>
                </tr>
                <tr style="background-color:#cdede8;height: 0px; paddding: 0px;">
                    <td class="all-text pd-lt-10" style="padding-left:10px;">{{ $student_behaviour_skill['term']['name'] ?? '' }} : Student's
                        Attendance
                    </td>
                    <td class="all-text pd-lt-10" style="padding-left:10px;"><input type="text"
                            value="{{ $attendance_percentage ?? 'N/A' }}%"
                            style="background: transparent; border:none;">
                    </td>
                </tr>
                <tr style="height: 0px; paddding: 0px;">
                    <td class="all-text pd-lt-10" style="padding-left:10px;">
                        Parent-Teacher
                        Meeting
                    </td>
                    <td class="all-text" style="padding-left:10px;"><input type="text"
                            style="background: transparent; border:none;"
                            value="{{ isset($student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended']) ? ($student_behaviour_skill['student_behaviour_skill_remark']['parent_meeting_attended'] ? 'Attended' : 'Not Attended') : 'N/A' }}">
                    </td>
                </tr>
            </table>

            <p class="info-headings" style="margin-bottom:-15px;">Grading Key:</p>

            <table class="tbl-grading" style="width:80%;">
                @if (isset($grading_keys))
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
                @endif
            </table>


            @if (isset($outer_skills))
                @foreach ($outer_skills as $level)
                    @include('assessment.grade_book.reports.partials.outer_skill', [
                        'level' => $level,
                    ])
                @endforeach
            @endif
        </div>

        <div style="width: 100%; position: relative;padding: 0px 10px 0;margin-top:30px">

            <div style="position: relative" class="tb-flex-column">
                <img src="{{ asset('assets/img/1x/logo-hori.png') }}" alt=""
                    style="max-width: 100%;height:auto" height="100px" width="250px">
                <div style="height:100%; background-color:#cdede8b8;" class="for-add-new-padding">
                    <div class="d-flex">

                        @if (isset($inner_skills))
                            @foreach ($inner_skills as $sort_no => $skills)
                                @if ($sort_no == 1)
                                    @foreach ($skills as $level)
                                        @include('assessment.grade_book.reports.partials.col_one_inner_skill',
                                            ['level' => $level])
                                    @endforeach
                                @else
                                    <div style="height:100%;background-color:{{ $sort_no % 2 == 0 ? '' : '#cdede8b5' }};"
                                        class="check-box-padding">
                                        {{-- <tr>
                                            <td> --}}
                                        @foreach ($skills as $inner_loop_index => $level)
                                            @include('assessment.grade_book.reports.partials.col_two_inner_skill',
                                                [
                                                    'level' => $level,
                                                    'sort_no' => $sort_no,
                                                    'inner_loop_index' => $inner_loop_index,
                                                ])
                                        @endforeach
                                        {{-- </td>
                                        </tr> --}}
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <img src="{{ asset('assets/img/1x/new-kids3.png') }}" style="position:absolute;"
                    class="set-to-bottom" alt="">
            </div>

        </div>
    </div>

    <footer>
    </footer>

    <div class="gap-2 p-4 hstack justify-content-center d-print-none" style="margin: 80px auto 0;">
        <a href="" id="print_student_progress_report" class="btn btn-info">
            <i class="align-bottom ri-printer-line me-1"></i> Print
        </a>
    </div>


</body>

</html>
