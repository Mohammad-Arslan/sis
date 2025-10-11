@extends('layouts.master')

@section('content')
@include('components.flash_message')
    <div class="row">
        @if (isset($employeeAttendance))
            @include('employees.attendance.edit_attendance')
        @else
            {{-- @permission('add-attendance') --}}
                @include('employees.attendance.add_attendance')
            {{-- @endpermission --}}
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Attendance Sheet</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <form class="row g-3 needs-validation" novalidate action="{{ route('attendance-sheet') }}" method="get">
                            @csrf
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select" id="s_academic_year_id" name="s_academic_year_id" aria-label="Academic Year select" required>
                                        <option value="">Please select</option>
                                        @foreach ($academic_years as $academic_year)
                                            <option value="{{ $academic_year->id }}" {{ $academic_year->active == '1' ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                        @endforeach
                                    </select>
                                    <label for="s_academic_year_id" class="form-label">Academic Year</label>
                                    <div class="invalid-tooltip">
                                        Academic Year is required!
                                    </div>
                                </div>
                            </div>
                            {{-- @if(isHeadOfficeEmp() || isSuperAdmin())
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="filter form-select" id="s_state_id" name="s_state_id" aria-label="Province select">
                                            <option value="">Please select</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="s_state_id" class="form-label">Province</label>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select class="filter form-select" id="s_branch_id" name="s_branch_id" aria-label="Branch select">
                                                <option value="">Please select</option>
                                                @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ old("branch_id") == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="s_branch_id" class="form-label">Branch</label>
                                        </div>
                                </div>
                            @endif --}}
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select" id="year" name="year" aria-label="Year select" required>
                                        <option value="">Please select</option>
                                        @for($loopyear = 2020; $loopyear <= date('Y'); $loopyear++)
                                            <option value="{{$loopyear}}" {{$loopyear == date('Y') ? "selected" : ''}}>{{$loopyear}}</option>
                                        @endfor
                                    </select>
                                    <label for="year" class="form-label">Year *</label>
                                    <div class="invalid-tooltip">
                                        Year is required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select" id="month" name="month" aria-label="Month select" required>
                                        <option value="">Please select</option>
                                        <option value="01">Jan</option>
                                        <option value="02">Feb</option>
                                        <option value="03">Mar</option>
                                        <option value="04">Apr</option>
                                        <option value="05">May</option>
                                        <option value="06">Jun</option>
                                        <option value="07">Jul</option>
                                        <option value="08">Aug</option>
                                        <option value="09">Sep</option>
                                        <option value="10">Oct</option>
                                        <option value="11">Nov</option>
                                        <option value="12">Dec</option>
                                    </select>
                                    <label for="month" class="form-label">Month *</label>
                                    <div class="invalid-tooltip">
                                        Month is required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-search"></i>Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 align-middle text-center">
                            <h1 class="text-bold"><i class="ri-calendar-2-line"></i> {{$calendar_date}}</h1>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="attendance-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    @foreach($total_number_of_days as $day)
                                    <th class="text-center">{{$day['day']}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $newDateTime = \Carbon\Carbon::now()->addDay();
                                    $newDateTime = \Carbon\Carbon::parse($newDateTime)->format('Y-m-d');
                                @endphp
                                @foreach ($employees as $employee)
                                <tr>
                                    <td>{{$employee->user->name}}</td>
                                    <td>{{$employee->department->department_name}}</td>
                                    <td>{{$employee->designation->designation_name}}</td>
                                    @foreach($total_number_of_days as $day)
                                        @if(str_contains($day['day'],'Sun'))
                                            <td class="bg-danger"></td>
                                        @elseif($day['dated'] >= $newDateTime)
                                            <td class="bg-warning"></td>
                                        @else
                                            @php
                                                $acd_year_id = isset(request()->s_academic_year_id) ? request()->s_academic_year_id : '';
                                                $attendance = array();
                                                $attendance = getEmployeeAttendance($employee->id,$day['dated'],$acd_year_id);
                                            @endphp
                                            @if(isset($attendance[0]['id']))
                                            <td class="text-center">
                                                @if(isset(request()->month) && isset(request()->year) && isset(request()->s_academic_year_id))
                                                    <a href="{{route('edit-attendance',['id' => $attendance[0]['id'],'type' => 'in', 'month' => request()->month, 'year' => request()->year, 's_academic_year_id' => request()->s_academic_year_id])}}" class="btn btn-outline-success btn-sm">
                                                        {{substr($attendance[0]['time_in'],0,-3)}} <span class="badge {{$attendance[0]['time_in_color']}} ms-1">In</span>
                                                    </a><br>
                                                @else
                                                    <a href="{{route('edit-attendance',['id' => $attendance[0]['id'],'type' => 'in'])}}" class="btn btn-outline-success btn-sm">
                                                        {{substr($attendance[0]['time_in'],0,-3)}} <span class="badge {{$attendance[0]['time_in_color']}} ms-1">In</span>
                                                    </a><br>
                                                @endif
                                                @if(isset(request()->month) && isset(request()->year) && isset(request()->s_academic_year_id))
                                                    @if(isset($attendance[0]['time_out']))
                                                        <a href="{{route('edit-attendance',['id' => $attendance[0]['id'],'type' => 'out', 'month' => request()->month, 'year' => request()->year, 's_academic_year_id' => request()->s_academic_year_id])}}" class="btn btn-outline-success btn-sm mt-1">
                                                            {{substr($attendance[0]['time_out'],0,-3)}} <span class="badge {{$attendance[0]['time_out_color']}} ms-1">Out</span>
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0);" class="btn btn-outline-warning btn-sm mt-1">
                                                            <i class='mdi mdi-close text-danger'></i> <span class="badge bg-danger ms-1">Out</span>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($attendance[0]['time_out']))
                                                        <a href="{{route('edit-attendance',['id' => $attendance[0]['id'],'type' => 'out'])}}" class="btn btn-outline-success btn-sm mt-1">
                                                            {{substr($attendance[0]['time_out'],0,-3)}} <span class="badge {{$attendance[0]['time_out_color']}} ms-1">Out</span>
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0);" class="btn btn-outline-warning btn-sm mt-1">
                                                            <i class='mdi mdi-close text-danger'></i> <span class="badge bg-danger ms-1">Out</span>
                                                        </a>
                                                    @endif
                                                @endif
                                                @if(isset($attendance[0]['leave_name']) && $attendance[0]['leave_name']!='')
                                                    <br><a href="javascript:void(0);" class="btn btn-{{$attendance[0]['leave_color']}} btn-sm mt-1">
                                                        {{$attendance[0]['leave_name']}}
                                                    </a>
                                                @endif
                                                @if(isset($attendance[0]['attendance_day']) && $attendance[0]['attendance_day']!='')
                                                    <br><a href="javascript:void(0);" class="btn btn-{{$attendance[0]['attendance_day_color']}} btn-sm mt-1">
                                                        {{$attendance[0]['attendance_day']}}
                                                    </a>
                                                @endif
                                            </td>
                                            @else
                                            <td class="text-center">
                                                <a type="button" class="btn btn-outline-warning btn-sm">
                                                    <i class="mdi mdi-close text-danger"></i> <span class="badge bg-danger ms-1">In</span>
                                                </a><br>
                                                <a type="button" class="btn btn-outline-warning btn-sm mt-1">
                                                    <i class="mdi mdi-close text-danger"></i> <span class="badge bg-danger ms-1">Out</span>
                                                </a>
                                                @if(isset($attendance[0]['leave_name']) && $attendance[0]['leave_name']!='')
                                                    <br><a href="javascript:void(0);" class="btn btn-{{$attendance[0]['leave_color']}} btn-sm mt-1">
                                                        {{$attendance[0]['leave_name']}}
                                                    </a>
                                                @endif
                                                @if(isset($attendance[0]['attendance_day']) && $attendance[0]['attendance_day']!='')
                                                    <br><a href="javascript:void(0);" class="btn btn-{{$attendance[0]['attendance_day_color']}} btn-sm mt-1">
                                                        {{$attendance[0]['attendance_day']}}
                                                    </a>
                                                @endif
                                            </td>
                                            @endif
                                        @endif
                                    @endforeach
                                    {{-- <td>
                                        <div class="half-day"><span class="first-off"><i class="mdi mdi-check text-success"></i></span> <span class="first-off"><i class="mdi mdi-close text-danger"></i></span></div>
                                    </td> --}}

                                    {{-- <td>
                                        <div class="half-day"><span class="first-off"><i class="mdi mdi-close text-danger"></i></span> <span class="first-off"><i class="mdi mdi-check text-success"></i></span></div>
                                    </td> --}}

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            // $.extend($.fn.dataTableExt.oStdClasses, {
            //     "sFilterInput": "form-control",
            //     "sLengthSelect": "form-control"
            // });


            // $('#homework-list-data-table').DataTable({
            //     searching: false,
            //     processing: true,
            //     serverSide: true,
            //     responsive: true,
            //     bLengthChange: false,
            //     pageLength: 10,
            //     scrollX: true,
            //     language: {
            //         search: "",
            //         processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
            //         searchPlaceholder: "Search..."
            //     },
            //     ajax:{
            //         url:"{{ route('homeWorkDiary.index') }}",
            //         data: function(d) {
            //             d.state_id = $('#s_state_id').val();
            //             d.branch_id = $('#s_branch_id').val();
            //             d.class_id = $('#s_class_id').val();
            //             d.section_id = $('#s_section_id').val();
            //             d.academic_year_id = $('#s_academic_year_id').val();
            //             d.homework_date = $('#s_homework_date').val();
            //             }
            //         },

            //     columns: [
            //         {
            //             data: 'ay',
            //             name: 'ay'
            //         },
            //         {
            //             data: 'province',
            //             name: 'province'
            //         },
            //         {
            //             data: 'branch_name',
            //             name: 'branch_name'
            //         },
            //         {
            //             data: 'class',
            //             name: 'class'
            //         },
            //         {
            //             data: 'section',
            //             name: 'section'
            //         },
            //         {
            //             data: 'date',
            //             name: 'date'
            //         },
            //         {
            //             data: 'remarks',
            //             name: 'remarks'
            //         },
            //         {
            //             data: 'createdby',
            //             name: 'createdby'
            //         },
            //         {
            //             data: 'created_on',
            //             name: 'created_on'
            //         },

            //         {
            //             data: 'action',
            //             name: 'action',
            //             orderable: false,
            //             searchable: false,
            //             width: "5%",
            //             sClass: "text-center"
            //         },
            //     ]
            // });
        });

        // $(document).on('change', '.filter', function() {
        //     $('#homework-list-data-table').DataTable().ajax.reload(null, false).page('first');
        // });
        // @role('super_admin')
        // $(document).on("keyup", '#mySearch', function() {
        //     var value = $(this).val().toLowerCase();
        //     if (value.length > 0 || value.length == 0) {
        //         $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        //     }
        // });
        // @endrole

        // $(document).ready(function() {
        //     $("#addbutton").click(function(){
        //         var lsthmtl = $(".clone").html();
        //         $(".increment").after(lsthmtl);
        //     });
        //     $("body").on("click",".btn-danger",function(){
        //         $(this).parents(".hdtuto").remove();
        //     });
        // });
    </script>
@endpush
