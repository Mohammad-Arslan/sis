@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-h-100">
                <div class="card-body">
                    @permission('filter-lessonplan-calendar')
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                    @if(!auth()->user()->hasRole('super_admin'))
                                    @if(get_set_NWABranchId())
                                        <option value="{{ $academic_year->id }}" {{ get_current_acad_year_by_branch_id(get_set_NWABranchId()) && get_current_acad_year_by_branch_id(get_set_NWABranchId())->academic_year_id == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                    @else
                                        <option value="{{ $academic_year->id }}" {{ get_current_acad_year_by_branch_id(auth()->user()['employee']['branch_id']) && get_current_acad_year_by_branch_id(auth()->user()['employee']['branch_id'])->academic_year_id == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                    @endif
                                @else
                                    <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                @endif
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select filter" id="branch_id" {{auth()->user()->hasRole('teacher') ? 'disabled' : ''}}>
                                    @foreach ($branches as $branch)
                                        @if($loop->first && !auth()->user()->hasRole('network_associate'))
                                            <option value="">Please select</option>
                                        @endif
                                        @if(auth()->user()->hasRole('teacher'))
                                            <option value="{{ $branch->id }}" {{auth()->user()['employee']['branch_id'] == $branch->id ? 'selected' : ''}}>{{ $branch->br_name }}</option>
                                        @else
                                            <option value="{{ $branch->id }}" {{get_set_NWABranchId() == $branch->id ? 'selected' : ''}}>{{ $branch->br_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select filter" id="term_id">
                                    <option value="">Please select a term</option>
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                                <label for="term_id" class="form-label">Term</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select filter" id="com_class_id">
                                    <option value="">Please select</option>
                                    @foreach ($classes as $com_class)
                                        <option value="{{ $com_class->id }}">{{ $com_class->class_name }}</option>
                                    @endforeach
                                </select>
                                <label for="com_class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select filter" id="subject_id">
                                    <option value="">Please select</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                                <label for="subject_id" class="form-label">Subject</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control filter" id="topic">
                                <label for="topic" class="form-label">Topic</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select filter" id="teacher_id" {{auth()->user()->hasRole('teacher') ? 'disabled' : ''}}>
                                    <option value="">Please select</option>
                                    @foreach ($teachers as $teacher)
                                        @if(auth()->user()->hasRole('teacher'))
                                            <option value="{{ $teacher->id }}" {{auth()->user()['employee']['id'] == $teacher->id ? 'selected' : ''}}>{{$teacher['user']['first_name'] . ' '.$teacher['user']['middle_name'] .' '.$teacher['user']['last_name'] }}</option>
                                        @else
                                            <option value="{{ $teacher->id }}">{{$teacher['user']['first_name'] . ' '.$teacher['user']['middle_name'] .' '.$teacher['user']['last_name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="subject_id" class="form-label">Teacher</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control filter"
                                       data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" id="from_date">
                                <label for="from_date" class="form-label">From</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control filter"
                                       data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" id="to_date">
                                <label for="to_date" class="form-label">To</label>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
<style>
    /* Teacher Absence Indicators */
    .teacher-absence {
        background-color: #f8d7da !important;
        border-color: #f5c6cb !important;
        color: #721c24 !important;
    }
    
    .teacher-unmarked {
        background-color: #fff3cd !important;
        border-color: #ffeaa7 !important;
        color: #856404 !important;
    }
    
    /* Calendar event styling */
    .fc-event.teacher-absence {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    
    .fc-event.teacher-unmarked {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000 !important;
    }
    
    /* Event title styling */
    .fc-event-title {
        font-weight: 500;
    }
</style>
@endpush
@push('footer_scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            getCalendarEvents();

            $(document).on('change', '.filter', function() {
                getCalendarEvents();
            });
        });

        function getCalendarEvents(){
            // var date = new Date();
            // var d = date.getDate();
            // var m = date.getMonth();
            // var y = date.getFullYear();
            $.ajax({
                url: '{{route('lesson-plans.calendar')}}',
                type: 'GET',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                data: {
                    academic_year_id: $('#academic_year_id').val(),
                    branch_id: $('#branch_id').val(),
                    term_id: $('#term_id').val(),
                    com_class_id: $('#com_class_id').val(),
                    subject_id: $('#subject_id').val(),
                    teacher_id: $('#teacher_id').val(),
                    topic: $('#topic').val(),
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val(),
                },
                cache: false,
                success: function (data) {
                    if (data.code == 200){
                        var events = data.data.events;
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            // weekends: true,
                            // timeZone: 'local',
                            themeSystem: 'bootstrap',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                                // right: 'prev,next today',
                            },
                            dayHeaderFormat: { weekday: 'short' },
                            views: {
                                timeGridWeek: {
                                    dayHeaderFormat: { 
                                        weekday: 'long',
                                        day: 'numeric',
                                        month: 'long'
                                    }
                                },
                                timeGridDay: {
                                    dayHeaderFormat: { 
                                        weekday: 'long',
                                        day: 'numeric',
                                        month: 'long'
                                    }
                                }
                            },
                            defaultDate: '2022-07-04',
                            events: events,
                        });

                        calendar.render();
                    }
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });

        }
    </script>
@endpush
