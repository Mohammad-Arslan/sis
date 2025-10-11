<div class="{{$lessonPlan['language'] == 'urdu' ? 'text_dir_rtl' : ''}}">
    <div class="row align-items-center">
        <div class="col-2" style="padding-left: 50px;padding-bottom: 25px">
            <img src="{{ asset('ucs-icon.svg') }}" width="80" class="img-fluid" alt="img">
        </div>
        <div class="col-8 text-center">
            <h3><b>{{__('United Charter Schools')}}</b></h3>
            <h5><b>({{$lessonPlan['academic_year']['title']}})</b></h5>
            <h4><b>{{$lessonPlan['topic']}}</b></h4>
            <h5><b>{{__($lessonPlan['term']['name'])}} / {{__($lessonPlan['week']['name'])}} /  {{__('Day '.$lessonPlan['day'])}}</b></h5>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="row text-center mt-4">
        <div class="col-md-4 col-sm-4 col-4">
            <b class="">{{__('School Type')}}</b>
            <h5 class="fs-13 mt-2">{{isset($lessonPlan['subject']['class_student_subject']['class_student']['branch_class_sections']['com_classes']['class_group_class']['class_group']['name']) ? $lessonPlan['subject']['class_student_subject']['class_student']['branch_class_sections']['com_classes']['class_group_class']['class_group']['name'] : '-'}}</h5>
        </div>
        <div class="col-md-4 col-sm-4 col-4">
            <b>{{__('Class')}}</b>
            <h5 class="fs-13 mt-2">{{$lessonPlan['com_class']['class_name']}}</h5>
        </div>
        <div class="col-md-4 col-sm-4 col-4">
            <b>{{__('Subject')}}</b>
            <h5 class="fs-13 mt-2">{{$lessonPlan['subject']['subject_name']}}</h5>
        </div>
    </div>
    <div class="row text-center mt-4">
        @if(!empty($lessonPlan['theme']))
            <div class="col-md-4 col-sm-4 col-4">
                <b>{{__('Theme')}}</b>
                <h5 class="fs-13 mt-2">{{$lessonPlan['theme']}}</h5>
            </div>
        @elseif($lessonPlan['chapter'])
            <div class="col-md-4 col-sm-4 col-4">
                <b>{{__('Chapter')}}</b>
                <h5 class="fs-13 mt-2">{{$lessonPlan['chapter']}}</h5>
            </div>
        @endif
        <div class="col-md-4 col-sm-4 col-4">
            <b>{{__('Topic')}}</b>
            <h5 class="fs-13 mt-2">{{$lessonPlan['topic']}}</h5>
        </div>
        <div class="col-md-4 col-sm-4 col-4">
            <b>{{__('Taught')}} {{__('Date')}}</b>
            <h5 class="fs-13 mt-2">{{!empty($lessonPlan['taught_date_from']) ? \Carbon\Carbon::parse($lessonPlan['taught_date_from'])->format('d-m-Y') : '-' }} To {{!empty($lessonPlan['taught_date_to']) ? \Carbon\Carbon::parse($lessonPlan['taught_date_to'])->format('d-m-Y') : '-'}}</h5>
        </div>
    </div>

    <div class="mt-5 table-responsive">
        <table class="table table-bordered" id="lesson_plan_table">
            <thead>
            <tr class="table-primary">
                <th style="width:15%">{{__('Student Learning Outcomes')}}</th>
                <th style="width:40%">{{__('Methodology')}}</th>
                <th style="width:5%">{{__('Time') . ' ('.__('min').')'}}</th>
                <th style="width:20%">{{__('Resources')}}</th>
                <th style="width:20%">{{__('Attainment Targets')}}</th>
                <th style="width:20%">{{__('Assessment')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($lessonPlan) && isset($lessonPlan['student_learning_outcomes']))
                @foreach($lessonPlan['student_learning_outcomes'] as $outer_index => $SLO)
                    {{--<tr>
                        <td colspan="6" class="table-light">
                            <div style="display: flex;justify-content: space-between">
                                <b>SLO {{$outer_index + 1}}</b>
                            </div>
                        </td>
                    </tr>--}}
                    <tr class="align-top">
                        <td rowspan="{{isset($SLO['student_activities']) ? count($SLO['student_activities']) + 1 : 0}}">
                            {!! isset($SLO) && !empty($SLO['teacher_activity']) ? $SLO['teacher_activity'] : '-' !!}
                        </td>
                    </tr>
                    @if(isset($SLO['student_activities']))
                        @foreach($SLO['student_activities'] as $inner_index => $student_activity)
                            <tr class="align-top">
                                <td>
                                    {!! isset($student_activity) && !empty($student_activity['methodology']) ? $student_activity['methodology'] : '-' !!}
                                </td>
                                <td>
                                    {{isset($student_activity) && !empty($student_activity['duration']) ? $student_activity['duration'] : '-'}}
                                </td>
                                <td>
                                    {!! isset($student_activity) && !empty($student_activity['resource']) ? $student_activity['resource'] : '-' !!}
                                </td>

                                <td>
                                    <ul id="curriculum-attainment-targets">
                                        {{-- Targets will be dynamically added here using JavaScript --}}
                                    </ul>
                                </td>
                                <td>
                                    {!! isset($student_activity) && !empty($student_activity['assessment']) ? $student_activity['assessment'] : '-' !!}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6 col-sm-12 mb-2">
        <label for="evaluation_of_student" class="form-label">Evaluation of Student:</label>
        <ul>
            <li>What did the students learn in this lesson?</li>
            <li>What were they not able to do / understand?</li>
        </ul>
        <div class="form-label-group in-border mb-1">
            <div class="input-group">
                <textarea class="form-control" name="evaluation_of_student" id="evaluation_of_student" cols="30" rows="4" required>{{!empty($lessonPlan['evaluation_of_student']) ? $lessonPlan['evaluation_of_student'] : ''}}</textarea>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 mb-2">
        <label for="evaluation_of_teacher" class="form-label">Evaluation of Teacher:</label>
        <ul>
            <li>What went well? How do you know?</li>
            <li>What went wrong? How will you fix it?</li>
        </ul>
        <div class="form-label-group in-border mb-1">
            <textarea class="form-control" name="evaluation_of_teacher" id="evaluation_of_teacher" cols="30" rows="4" required>{{!empty($lessonPlan['evaluation_of_teacher']) ? $lessonPlan['evaluation_of_teacher'] : ''}}</textarea>
        </div>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var classId = '{{ $lessonPlan['com_class']['id'] }}';
            var subjectId = '{{ $lessonPlan['subject']['id'] }}';
            var targetsContainer = $('#curriculum-attainment-targets');

            function updateTargets() {
                // Clear existing targets
                targetsContainer.empty();

                // Check if both class and subject are available
                if (classId && subjectId) {
                    // Make an AJAX request to fetch targets based on class and subject
                    $.ajax({
                        url: "{{ route('get-curriculum-attainment-targets') }}",
                        method: 'GET',
                        data: { class_id: classId, subject_id: subjectId },
                        success: function (data) {
                            if (data.length > 0) {
                                // Display the targets
                                data.forEach(function (target) {
                                    targetsContainer.append('<li>' + target.target + '</li>');
                                });
                            } else {
                                // Display a message if no targets are available
                                targetsContainer.append('<li>No curriculum attainment targets available for the selected class and subject.</li>');
                            }
                        },
                        error: function (error) {
                            console.error('Error fetching curriculum attainment targets:', error);
                        }
                    });
                } else {
                    // Display a message if class or subject is not available
                    targetsContainer.append('<li>Please select a class and a subject to view curriculum attainment targets.</li>');
                }
            }

            // Initial update
            updateTargets();
        });
    </script>


