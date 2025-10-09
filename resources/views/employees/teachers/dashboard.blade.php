@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                        <div class="col">
                            <div class="py-4 px-3">
                                <div class="text-center">
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        @if (Auth::user()->employee && Auth::user()->employee->emp_image != '')
                                            <img src="{{ get_file_from_s3('images/' . Auth::user()->employee->emp_image) }}"
                                                class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                                alt="user-profile-image">
                                        @else
                                            <img src="{{ asset('uploads/employees/user-dummy-img.jpg') }}"
                                                class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                                alt="user-profile-image">
                                        @endif
                                    </div>
                                    <h5 class="fs-12 mb-1">{{ (Auth::user()->first_name ?? '') . ' ' . (Auth::user()->last_name ?? '') }}</h5>
                                    <p class="text-muted mb-0">{{ Auth::user()->employee->department->department_name ?? '' }} /
                                        {{ Auth::user()->employee->designation->designation_name ?? '' }}</p>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="py-4 px-3">
                                <div class="text-left">
                                    <h5>Status:</h5>
                                    <p class="fs-12 mb-1"><b>Status:</b> {{ Auth::user()->employee->job_status ?? '' }}</p>
                                    <p class="fs-12 mb-1"><b>Employee ID:</b> {{ Auth::user()->employee->employee_id ?? '' }}</p>
                                    <p class="fs-12 mb-1"><b>Branch:</b> {{ Auth::user()->employee->branch->br_name ?? '' }}</p>
                                    <p class="fs-12 mb-1"><b>Hire Date:</b>
                                        {{ Auth::user()->employee && Auth::user()->employee->hiring_date ? date('d-m-Y', strtotime(Auth::user()->employee->hiring_date)) : '' }}</p>
                                    <p class="fs-12 mb-1"><b>Service Length:</b>
                                        {{ Auth::user()->employee && Auth::user()->employee->hiring_date ? now()->diffInDays(Auth::user()->employee->hiring_date) : 0 }} days</p>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="py-4 px-3">
                                <div class="text-left">
                                    @php
                                        $employee = Auth::user()->employee;
                                        $salaryStructure = $employee->currentSalaryStructure;
                                        $basic_salary = $salaryStructure ? (float) $salaryStructure->basic_salary : 0;
                                        $gross_salary = $salaryStructure ? (float) $salaryStructure->gross_salary : 0;
                                        $allownces = $salaryStructure ? (float) ($salaryStructure->house_rent_allowance + $salaryStructure->medical_allowance + $salaryStructure->transport_allowance + $salaryStructure->other_allowances) : 0;
                                    @endphp
                                    <h5>Salary Information:</h5>
                                    <p class="fs-12 mb-1"><b>Basic:</b> {{ number_format($basic_salary) }}</p>
                                    <p class="fs-12 mb-1"><b>Gross:</b> {{ number_format($gross_salary) }}</p>
                                    <p class="fs-12 mb-1"><b>Allownces:</b> {{ number_format($allownces) }}</p>
                                    {{-- <p class="fs-12 mb-1"><b>Cost to School:</b> {{ number_format($gross_salary + $allownces + 8000) }}</p> --}}
                                    <p class="fs-12 mb-1"><b>Cost to School:</b> {{ number_format($gross_salary) }}</p>
                                    @permission('generate-salary-slip')
                                        <a href="{{ route('employee.salary-slip') }}" target="_blank"
                                            title="Generate Salary Slip"
                                            class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                            <i class="ri-article-line"></i>
                                        </a>
                                    @endpermission

                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="py-4 px-3">
                                <div class="text-left">
                                    <h5>Other Information:</h5>
                                    <p class="fs-12 mb-1"><b>DOB:</b>
                                        {{ Auth::user()->employee && Auth::user()->employee->date_of_birth ? date('d-m-Y', strtotime(Auth::user()->employee->date_of_birth)) : '' }}</p>
                                    <p class="fs-12 mb-1"><b>CNIC:</b> {{ Auth::user()->CNIC ?? '' }}</p>
                                    <p class="fs-12 mb-1"><b>EOBI:</b> {{ Auth::user()->employee->eobi_number ?? '' }} </p>
                                    <p class="fs-12 mb-1"><b>Email:</b> {{ Auth::user()->email ?? '' }} </p>
                                    <p class="fs-12 mb-1"><b>Contact:</b><br> {{ Auth::user()->employee->address ?? '' }} <br>
                                        {{ Auth::user()->employee->mobile_number ?? '' }} </p>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="py-4 px-3">
                                <div class="text-left">
                                    <h5>Mark Attendance:</h5>
                                    <p class="fs-12 mb-1">
                                        @if (isset($time_in) && !isset($time_out))
                                            <button type="button" title="Time Out"
                                                class="btn btn-sm btn-danger mark_attendance_out" data-status="out"
                                                data-id="{{ $id ?? '' }}"
                                                data-employee-id="{{ Auth::user()->employee->id ?? '' }}"
                                                data-route="{{ route('mark-attendance-out') }}">Time Out</button>
                                        @elseif(!isset($time_in) && !isset($time_out))
                                            <button type="button" title="Time In"
                                                class="btn btn-sm btn-success mark_attendance_in" data-status="in"
                                                data-employee-id="{{ Auth::user()->employee->id ?? '' }}"
                                                data-route="{{ route('mark-attendance-in') }}">Time In</button>
                                        @else
                                            <p class="fs-12 mb-1"><b>Time In:</b> {{ $time_in ?? '' }} </p>
                                            <p class="fs-12 mb-1"><b>Time Out:</b> {{ $time_out ?? '' }} </p>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div><!-- end col -->


                    </div><!-- end row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="py-4 px-3">
                                <div class="text-left">
                                    <h5>Leave Quota:</h5>
                                    <table id="leave_quotas_table"
                                        class="table table-bordered table-striped align-middle table-nowrap mb-0"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Leave&nbsp;Type</th>
                                                <th>Leave Allowed</th>
                                                <th>Leave Acquired</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($leaveQuotas ?? [] as $key => $leaveQuota)
                                                <tr>
                                                    <td>{{ $leaveQuota->leaveType->name ?? '' }}</td>
                                                    <td>{{ $leaveQuota->no_of_allowed_leaves ?? '' }}</td>
                                                    <td>{{ $leaveQuota->no_of_balanced_leaves ?? '' }}</td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Leave&nbsp;Type</th>
                                                <th>Leave Allowed</th>
                                                <th>Leave Acquired</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Attendance List</h4>
            {{-- <div class="flex-shrink-0">
            <div class="dropdown card-header-dropdown">
                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="text-muted">02 Nov 2021 to 31 Dec 2021<i class="mdi mdi-chevron-down ms-1"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Today</a>
                    <a class="dropdown-item" href="#">Last Week</a>
                    <a class="dropdown-item" href="#">Last Month</a>
                    <a class="dropdown-item" href="#">Current Year</a>
                </div>
            </div>
        </div> --}}
        </div><!-- end card header -->

        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted">
                            <th scope="col">Class</th>
                            <th scope="col" style="width: 20%;">Section</th>
                            <th scope="col" style="width: 16%;">Subject</th>
                            <th scope="col" style="width: 12%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($attendance_BCR_arr = [])
                        @foreach ($attendanceClasses ?? [] as $attendanceClass)
                            @if (!in_array($attendanceClass['branch_class_section_id'] ?? null, $attendance_BCR_arr))
                                @if (isset($attendanceClass['branch_class_section']['com_classes']['attendance_type']['abbreviation']) &&
                                        $attendanceClass['branch_class_section']['com_classes']['attendance_type']['abbreviation'] == 'class')
                                    @php(array_push($attendance_BCR_arr, $attendanceClass['branch_class_section_id']))
                                @endif
                                <tr>
                                    <td><strong>{{ $attendanceClass->branch_class_section->com_classes->class_name ?? '' }}</strong>
                                    </td>
                                    <td>{{ $attendanceClass->branch_class_section->sections->section_name ?? '' }}</td>
                                    <td>{{ in_array($attendanceClass['branch_class_section_id'] ?? null, $attendance_BCR_arr) ? '-' : ($attendanceClass->subject->subject_name ?? '') }}
                                    </td>
                                    <td>
                                        <div class="dropdown text-right">
                                            <button class="btn" type="button" id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill h4 text-muted"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @php($student_param_arr['branch_class_section_id'] = $attendanceClass->branch_class_section_id ?? null)
                                                @php($student_param_arr['subject_id'] = ($attendanceClass['teacher_type']['abbreviation'] ?? '') == 'subject' ? ($attendanceClass->subject_id ?? 0) : 0)
                                                <a class="dropdown-item"
                                                    href={{ route('getStudents', $student_param_arr) }}>Mark Attendance</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('viewAttendanceCalendar', $student_param_arr) }}">View
                                                    Calendar</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody><!-- end tbody -->
                </table><!-- end table -->
            </div><!-- end table responsive -->
        </div><!-- end card body -->
    </div><!-- end card -->

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Class List</h4>
            {{-- <div class="flex-shrink-0">
          <div class="dropdown card-header-dropdown">
              <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="text-muted">02 Nov 2021 to 31 Dec 2021<i class="mdi mdi-chevron-down ms-1"></i></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="#">Today</a>
                  <a class="dropdown-item" href="#">Last Week</a>
                  <a class="dropdown-item" href="#">Last Month</a>
                  <a class="dropdown-item" href="#">Current Year</a>
              </div>
          </div>
      </div> --}}
        </div><!-- end card header -->

        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted">
                            <th scope="col">Class</th>
                            <th scope="col" style="width: 20%;">Section</th>
                            <th scope="col" style="width: 16%;">Subject</th>
                            {{-- <th scope="col" style="width: 12%;">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teacher_classes ?? [] as $teacher_class)
                            <tr>
                                <td><strong>{{ $teacher_class->branch_class_section->com_classes->class_name ?? '' }}</strong>
                                </td>
                                <td>{{ $teacher_class->branch_class_section->sections->section_name ?? '' }}</td>
                                <td>{{ $teacher_class->subject->subject_name ?? '' }}</td>
                                {{-- <td>
                            <div class="dropdown text-right">
                            <button class="btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill h4 text-muted"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if ($teacher_class->teacher_type->id == 1 && $teacher_class->teacher_type->name == 'Class')
                                <a class="dropdown-item" href={{ route('getStudents', ['branch_class_section_id' => $teacher_class->branch_class_section_id, 'subject_id' => $teacher_class->subject_id]) }}>Mark Attendance</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('viewAttendanceCalendar', ['branch_class_section_id' => $teacher_class->branch_class_section_id, 'subject_id' => $teacher_class->subject_id]) }}">View Calendar</a>
                            </div>
                            </div>
                        </td> --}}
                            </tr>
                        @endforeach
                    </tbody><!-- end tbody -->
                </table><!-- end table -->
            </div><!-- end table responsive -->
        </div><!-- end card body -->
    </div><!-- end card -->
@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            /** Mark in*/
            $(document).on('click', '.mark_attendance_in', function(e) {
                e.preventDefault();
                var today = new Date();
                let url = $(this).attr('data-route');
                let employee_id = $(this).attr('data-employee-id');
                let time_in = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                let attendance_type = 1;
                Swal.fire({
                    icon: 'question',
                    title: 'Do you want to mark your attendance?',
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                employee_id,
                                time_in,
                                attendance_type
                            },
                            success: function(response) {
                                Swal.fire('Done!', '', 'success')
                                location.reload();
                            }
                        })
                    }
                })
            });
            $(document).on('click', '.mark_attendance_out', function(e) {
                e.preventDefault();
                var today = new Date();
                let url = $(this).attr('data-route');
                let id = $(this).attr('data-id');
                let time_out = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

                Swal.fire({
                    icon: 'question',
                    title: 'Do you want to mark out your attendance?',
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id,
                                time_out
                            },
                            success: function(response) {
                                Swal.fire('Done!', '', 'success')
                                location.reload();
                            }
                        })
                    }
                })
            });
        });
    </script>
@endpush
