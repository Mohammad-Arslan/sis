{{-- <body class="A4"> --}}
<div class="d-block" style="display: block;width:100%">
    <div class="d-block" style="display: block;width:100%; position: relative;">
        <img style="width:100%; height:100vh; position:absolute; z-index:-1;" class="bg-img-main"
            src="{{ asset('/assets/img/1x/img-cld-1.png') }}">
        <table style="width:100%;" class="page-break2">
            <tr class="add-flex-equal">
                <td class="flex-td">
                    <table class="tbl-inputs2 w-100">
                        <tr>
                            <td class="set-bottom-img">
                                <p style="font-size: 12px;" class="all-text">
                                    Class Teacher's Comments:</p>
                                <textarea style="" name="" id="lined1" class="w-100" cols="50" rows="4">{{ $student_behaviour_skill['student_behaviour_skill_remark']['teacher_comments'] }}</textarea>
                                <p style="font-size: 12px;" class="all-text">School Head's
                                    Comments: </p>
                                <textarea style="" name="" id="lined1" class="w-100" cols="50" rows="2">{{ $student_behaviour_skill['student_behaviour_skill_remark']['schoolhead_comments'] }}</textarea>
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
                                <p style="font-size: 12px; " class="all-text">Dated:
                                    <input class="input-border" type="text"
                                        value="{{ Carbon\Carbon::now()->format('d-m-Y') }}">
                                </p>
                                <img class="b-img" src="{{ asset('/assets/img/1x/animate.png') }}">

                            </td>
                        </tr>
                    </table>

                    <table class="w-100">
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
                            style=" display:flex !important; margin-top: 00px;margin-bottom: 0px;justify-content: center;">
                            <td style="display: flex;align-items:center">
                                <img src="{{ asset('/assets/img/1x/fb_icon.png') }}" height="10px" width="10px"
                                    alt="">
                                <img src="{{ asset('/assets/img/1x/insta_icon.png') }}" height="10px" width="10px"
                                    alt="">
                                <img src="{{ asset('/assets/img/1x/youtube_icon.png') }}" height="10px" width="10px"
                                    alt="">
                                <img src="{{ asset('/assets/img/1x/linked_in.png') }}" height="10px" width="10px"
                                    alt="">

                                <p class="small-text" style="color: blue; font-size: small;margin-left:5px">
                                    United
                                    Charter Schools</p>
                            </td>
                        </tr>
                    </table>

                </td>
                <td class="flex-td">
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

                                <img src="{{ asset('/assets/img/1x/title_bg.png') }}" style="margin-top:-100px;"
                                    alt="">
                                <table class="table-img cover-table" style="width:40%;">
                                    <tr>
                                        <td>
                                            <p
                                                style="color:#1e398d; font-family: 'Roboto', sans-serif; font-size:19px; font-weight:900; text-align:center;  margin-top:-20px; margin-bottom:-8px; line-height: 0.5px;">
                                                PROGRESS</p>
                                            <p
                                                style="color:#1e398d; font-family:'Roboto', sans-serif; font-size:26px; font-weight:900; text-align:center;  margin-top:25px; margin-bottom:-10px; line-height:0.5px;">
                                                REPORT</p>
                                            <p
                                                style="color:#1e398d; text-align:center; margin-top:20px;  font-size: 19px; font-weight:500; margin-bottom:-15px;  font-family: 'Montserrat', sans-serif; font-weight: 600;">
                                                {{ $student_behaviour_skill['term']['name'] }}
                                            </p>
                                            <h3
                                                style="color:#1e398d; font-weight:500; font-size:19px; text-align:center;   margin-top: 7px;       font-family: 'Montserrat', sans-serif; font-weight: 600;">
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
                                                style="color:#1e398d; font-size:18px; text-align:center; margin-left:-10px; margin-top:-10px;  font-family: 'Montserrat', sans-serif;  font-weight: 400; ">
                                                {{ $student_behaviour_skill['com_class']['class_name'] }}
                                            </h3>
                                            <h5
                                                style="margin-top: -10px; margin-left:-10px; font-family: 'Montserrat', sans-serif; font-size:16; font-weight: 300;">
                                                ACADEMIC YEAR
                                                <br>
                                                <h5
                                                    style="font-family: 'Montserrat', sans-serif; font-size:16; font-weight: 400; margin-top:-10px;">

                                                    <input type="number"
                                                        value="{{ substr($student_behaviour_skill['academic_year']['title'], 0, 4) }}"
                                                        style="width: 45px; margin-bottom:5px; border: none !important; margin-left:5px; ">
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
                                <table class="table-img cover-table" style="top: 42% !important;">
                                    <img style="left: 50%;
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
                                                        <input type="text" style="text-align: center;"
                                                            value="{{ ucwords($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']) }}">
                                                    </td>
                                                </tr>
                                                <tr class="d-flex input-spc">
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Computer
                                                            ID:</label>
                                                        <input type="text" style="text-align: center;"
                                                            value="{{ $student['roll_no'] }}">
                                                    </td>
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Class:</label>
                                                        <input type="text" style="text-align: center;"
                                                            value="{{ $student_behaviour_skill['com_class']['class_name'] }}">
                                                    </td>
                                                </tr>
                                                <tr class="input-spc" style="display:flex !important;">
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Class
                                                            Teacher:</label>
                                                        <input type="text" style="text-align: center;"
                                                            value="{{ $class_teacher_name ? $class_teacher_name : 'N/A' }}">
                                                    </td>
                                                </tr>
                                                <tr class="input-spc" style="display:flex !important;">
                                                    <td class="d-flex" style="display:flex !important; width:100%">
                                                        <label class="name-inputs all-text"
                                                            style="color: #1e398d; font-size: 12px;"
                                                            for="">Campus:</label>
                                                        <input type="text" style="text-align: center;"
                                                            value="{{ $student_behaviour_skill['branch']['br_name'] }}">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table class="w-100" style="margin-top: 170px;text-align:center;">
                                    <tr class="input-spc"
                                        style=" display:flex !important; margin-top: 10px;  margin-bottom: 0px;justify-content: center;">
                                        <td>
                                            <img src="{{ asset('/assets/img/1x/web_icon.png') }}" height="10px"
                                                width="10px" alt="">
                                        </td>
                                        <td>
                                            <p class="small-text" style="color: blue; font-size: small;">
                                                www.ucs.edu.pk |</p>
                                        </td>
                                        <td>
                                            <img src="{{ asset('/assets/img/1x/mail_icon.png') }}" height="10px"
                                                width="10px" alt="">
                                        </td>
                                        <td>
                                            <p class="small-text" style="color: blue; font-size: small;">
                                                info@ucs.edu.pk |</p>
                                        </td>
                                        <td>
                                            <img src="{{ asset('/assets/img/1x/call_icon.png') }}" height="10px"
                                                width="10px" alt="">
                                        </td>
                                        <td>
                                            <p class="small-text" style="color: blue; font-size: small;">
                                                042-111-827-111</p>
                                        </td>
                                    </tr>

                                    <tr
                                        style=" display:flex !important; margin-top: 0px;margin-bottom: 0px;justify-content: center;">
                                        <td style="display: flex;align-items:center">
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
                </td>
            </tr>
        </table>
    </div>

    <div class="d-block" style="display: block;width:100%">
        <table class="page-break2">
            <tr style="vertical-align: baseline;" class="use-flex">
                <td class="first-table f-padding big-table-flex">
                    <p class="info-headings-1">Progress Report - {{ $student_behaviour_skill['term']['name'] }}
                    </p>
                    <table style="width:100%; border: 1px solid grey; border-radius:5px;" class="single-border">
                        <tr>
                            <td>student's age: {{ calculate_age($student['date_of_birth']) }} years</td>
                            <td>class average age: {{ $class_average_age }}</td>
                            <td>
                                Parent-teacher meeting:
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
                    <table style="width:100%; border: 1px solid grey; border-radius:5px;"
                        class="single-border wrap-table">
                        <tr class="drk-blue-clr">
                            <th rowspan="2">subjects</th>
                            @foreach ($assessment_weightage as $assessment_name => $weightage)
                                <th>{{ $assessment_name }}</th>
                            @endforeach
                            <td>total marks</td>
                            <td rowspan="2">overall grade</td>
                            <th rowspan="2" class="white-space-nowrap">teacher's comment:</th>

                        </tr>

                        <tr class="drk-blue-clr">
                            @foreach ($assessment_weightage as $assessment_name => $weightage)
                                <th style="font-weight: bold !important;">{{ $weightage }} Marks</th>
                            @endforeach
                            <th style="font-weight: bold !important;">{{ array_sum($assessment_weightage) }} Marks
                            </th>
                        </tr>
                        @foreach ($all_assessment_data as $assessment => $single_subject_mark)
                            <tr>
                                <td>{{ $single_subject_mark['subject_name'] }}</td>
                                @foreach ($assessment_weightage as $assessment_name => $weightage)
                                    @if (array_key_exists($assessment_name, $single_subject_mark))
                                        <td>{{ $single_subject_mark[$assessment_name] }}</td>
                                    @else
                                        <td></td>
                                    @endif
                                @endforeach
                                <td>{{ array_sum(array_diff_key($single_subject_mark, array_flip(['subject_sort']))) }}
                                </td>
                                <td>{{ get_student_grade($student_behaviour_skill['class_id'], round((array_sum(array_diff_key($single_subject_mark, array_flip(['subject_sort']))) / array_sum($assessment_weightage)) * 100)) }}
                                </td>
                                @if (isset($subject_assessment_remarks[$single_subject_mark['subject_name']]))
                                    <td style="text-transform: none">{{ $subject_assessment_remarks[$single_subject_mark['subject_name']] }}
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                        @foreach ($all_minor_subject_grade as $minor_subject_grade)
                            <tr>
                                <td>{{ $minor_subject_grade['subject_name'] }}</td>
                                @foreach ($assessment_weightage as $assessment_name => $weightage)
                                    <td style="background-color: #dcdddf;">{{-- for assessment names --}}</td>
                                @endforeach
                                <td style="background-color: #dcdddf;">{{-- for total marks --}}</td>
                                <td>{{ $minor_subject_grade['grade'] }}</td>
                                <td>{{-- for teacher comments --}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td class="second-table s-padding big-table-flex1">
                    <table style="width:100%;">
                        <tr>
                            <td>
                                <img src="{{ asset('/assets/img/1x/logo-hori.png') }}" alt="">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table style="margin-bottom: 10px; padding: 20px; border: none; border-radius: 5px; width:100%;"
                        class="tbl-grading2">
                        <tr>
                            <td>
                                <h5
                                    style="color:#1e398d;white-space: nowrap; font-size:14px;padding:10px 0px 0px 5px;">
                                    Grading Key</h5>
                            </td>
                        </tr>


                        <tr>
                            @foreach ($grading_keys as $key)
                                @if ($loop->odd)
                        <tr>
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
                    <div class="d-block" style="display: block;width:100%">
                        <table style="width:100%" class="single-border for-renove-padding">
                            <tr class="drk-blue-clr">
                                <td class="not for use"></td>
                                <th class="roboto-f text-center"
                                    style="font-weight: bolder !important; text-align:center;">grade</th>
                            </tr>
                            @foreach ($general_behaviours as $behaviour)
                                <tr class="grey-clr">
                                    <th class=" text-left" style="font-weight: bolder !important;" colspan="2">
                                        {{ $behaviour['title'] }}
                                    </th>
                                </tr>
                                @foreach ($behaviour['children'] as $child)
                                    <tr class="{{ $loop->even ? 'grey-clr' : '' }}">
                                        <td class="white-space-nowrap">{{ $child['title'] }}</td>
                                        <td class="not for use"><input type="text"
                                                style="border:none ; background:transparent; text-align:center;"
                                                class="form-control form-control-sm behaviours"
                                                data-behaviour_id="{{ $child['id'] }}" placeholder="Enter Grade"
                                                value="{{ isset($student_behaviour['student_behaviour_skill_marks'][$child['id']]) ? $student_behaviour['student_behaviour_skill_marks'][$child['id']]['grade'] : '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                        <br>
                        <img src="{{ asset('/assets/img/1x/family.png') }}" alt=""
                            style="width: 200px;margin: 0 auto;display: block;">
                    </div>
                </td>
            </tr>
        </table>
    </div>


</div>
{{-- </body> --}}
