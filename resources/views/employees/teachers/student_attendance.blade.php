@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">
        <div class="col-xl-12">
            <form id="attendanceForm" method="POST" action="{{ route('student-attendances.store') }}">
                <div class="card">
                    <div class="card-header align-items-center">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $data['title'] }}</h4>
                            </div>
                            <div class="col-md-4 text-center align-items-center">
                                <h5>{{ parse_date($data['date'], 'd-m-Y') }}</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-end align-items-center">
                                    <a class="btn btn-sm btn-primary ms-2"
                                        href="{{ route('viewAttendanceCalendar', [
                                            'branch_class_section_id' => request()->route('branch_class_section_id'),
                                            'subject_id' => request()->route('subject_id'),
                                        ]) }}">
                                        View Calendar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <input type="hidden" name="attendance_date" value="{{ $data['date'] }}" />
                            <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-muted">
                                        <th scope="col" style="width: 5%;">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col" style="width: 16%;" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['class_students'] as $class_student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            {{-- @if (isset($data['student_attendances']))
                                            @foreach ($data['student_attendances'] as $student_attendance)
                                                @if ($class_student['students']['id'] == $student_attendance['student_id'])
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('uploads/employees/0rqUUbBjZj2wQS9JQsgrWReox2RWLltq.jpg') }}" alt="" class="avatar-xs rounded-circle me-2">
                                                            <div class="ms-2">
                                                                <h5 class="fs-14 my-1"><a href="#javascript: void(0);" class="text-reset">{{$class_student['students']['first_name'] . ' '.$class_student['students']['middle_name'] .' '.$class_student['students']['last_name'] }}</a></h5>
                                                                <span class="text-muted">Reg. no. {{ $class_student['students']['registration_number'] }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        {!! $student_attendance->attendance_status_id !!}
                                                    </td>
                                                @endif
                                            @endforeach
                                        @else --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ get_file_from_s3('images/' . $class_student['students']['student_image'], $class_student['students']['student_image']) }}"
                                                        alt="" class="avatar-xs rounded-circle me-2">
                                                    <div class="ms-2">
                                                        <h5 class="fs-14 my-1"><a href="#javascript: void(0);"
                                                                class="text-reset">{{ $class_student['students']['first_name'] . ' ' . $class_student['students']['middle_name'] . ' ' . $class_student['students']['last_name'] }}</a>
                                                        </h5>
                                                        <span
                                                            class="text-muted">{{ !empty($class_student['students']['roll_no']) ? $class_student['students']['roll_no'] : $class_student['students']['registration_no'] }}</span>
                                                        <input type="hidden" id="branchClassSectionId"
                                                            name="{{ 'branch_class_section_id' }}"
                                                            value="{{ $class_student['branch_class_section_id'] }}" />
                                                        <input type="hidden" id="academicYearId"
                                                            name="{{ 'academic_year_id' }}"
                                                            value="{{ $class_student['academic_year_id'] }}" />
                                                        <input type="hidden" id="academicYearId"
                                                            name="{{ 'subject_id' }}"
                                                            value="{{ $data['subject_id'] }}" />
                                                        <input type="hidden" id="studentId"
                                                            name="{{ 'attendance[' . $loop->iteration . '][student_id]' }}"
                                                            value="{{ $class_student['students']['id'] }}" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                {{-- @if (isset($class_student->student_attendance))
                                                {!! $class_student->student_attendance->attendance_status_id !!}
                                            @else
                                                <input type="hidden" class="{{ 'attendance_input input_attendance_'.$loop->iteration }}" name="{{ "attendance[" . $loop->iteration . "][attendance_status_id]" }}" value="{{1}}" />
                                                <button type="button" data-target="{{ 'input_attendance_'.$loop->iteration }}" class="{{ 'btn btn-primary btn-sm waves-effect waves-light attendance_btn btn_attendance_'.$loop->iteration }}">Present</button>
                                            @endif --}}
                                                @if (($data['date'] >
                                                    Carbon\Carbon::today()->subDays(185)->format('Y-m-d') &&
                                                    $data['date'] <= Carbon\Carbon::today()->format('Y-m-d')) ||
                                                    isset($class_student['student_attendance']))
                                                    @foreach ($data['attendance_statuses'] as $attendance_status)
                                                        <input type="radio"
                                                            name="{{ 'attendance[' . $loop->parent->iteration . '][attendance_status_id]' }}"
                                                            {{ isset($class_student['student_attendance']) && $class_student['student_attendance']->getRawOriginal('attendance_status_id') == $attendance_status->id ? 'checked' : ($loop->iteration == 1 ? 'checked' : '') }}
                                                            value="{{ $attendance_status->id }}">
                                                        {{ $attendance_status->name }}
                                                    @endforeach
                                                @else
                                                    Not Marked
                                                @endif
                                            </td>
                                            {{-- @endif --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No Record Found</td>
                                        </tr>
                                    @endforelse
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                            @csrf
                            @if ($data['date'] >
                                Carbon\Carbon::today()->subDays(185)->format('Y-m-d') &&
                                $data['date'] <= Carbon\Carbon::today()->format('Y-m-d') &&
                                $data['class_students']->isNotEmpty())
                                <div class="border mt-3 border-dashed"></div>
                                <div class="col-12 text-end p-3">
                                    <button class="btn btn-primary btn-sm" type="submit">Submit Attendance</button>
                                    <button type="button"
                                        class="btn btn-light btn-sm bg-gradient waves-effect waves-light">Cancel</button>
                                </div>
                            @endif
                        </div><!-- end table responsive -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </form>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            function changeAttendanceButton(inputClass) {
                var statuses = @json($data['attendance_statuses']);
                var inputVal = Number($(`.${inputClass}`).val());
                var actionBtn = $(`.${inputClass}`).next();

                function getStyleClass(id) {
                    //add btn-sm class here as well because on click of action button all classes start with btn- removed from action button
                    return id === 'Present' ? 'btn-primary btn-sm' :
                        id === 'Absent' ? 'btn-danger btn-sm' :
                        id === 'Leave' ? 'btn-info btn-sm' :
                        id === 'Tardy' ? 'btn-warning btn-sm' :
                        id === 'Exempted' ? 'btn-success btn-sm' : ''
                }

                for (let i = 0; i < statuses.length; i++) {
                    if (inputVal === statuses[i].id) {
                        actionBtn.removeClass(function(index, className) {
                            return (className.match(/(^|\s)btn-\S+/g) || []).join(' ');
                        }).addClass(getStyleClass(statuses[i].name)).html(statuses[i].name)
                    }
                }
            }

            $('.attendance_btn').on('click', function() {
                var inputClass = $(this).data('target');
                var input = $(`.${inputClass}`);
                var nextVal = input.val() < 5 ? Number(input.val()) + 1 : 1;
                input.val(nextVal);
                changeAttendanceButton(inputClass);
            })

            function submitAttendance() {
                let attendance_status_id = $(this).data('action');
                let attendance_marked_date =
                    {{ !empty(Request::route('attendance_marked_date')) ? Request::route('attendance_marked_date') : '0' }};

                $.ajax({
                    url: '{{ route('student-attendances.store') }}',
                    type: 'POST',
                    data: {
                        attendance_status_id: attendance_status_id,
                        branch_class_section_id: {{ Request::route('branch_class_section_id') }},
                        subject_id: {{ Request::route('subject_id') }},
                        attendance_marked_date: attendance_marked_date, //if attendance marked date available then mark attendance on that day otherwise mark attendace for today
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        alert('success');
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {}
                });
            }

            $('.markAttendance').on('click', submitAttendance);
        });
    </script>
@endpush
