
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
                                    @if (Auth::user()->employee->emp_image != '')
                                    <img src="{{ get_file_from_s3('images/'.Auth::user()->employee->emp_image) }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                    @else
                                    <img src="{{ asset('uploads/employees/user-dummy-img.jpg') }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                    @endif
                                </div>
                                <h5 class="fs-12 mb-1">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</h5>
                                <p class="text-muted mb-0">{{ Auth::user()->employee->department->department_name}} / {{ Auth::user()->employee->designation->designation_name}}</p>
                            </div>
                        </div>
                  </div><!-- end col -->
                    <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-left">
                                <h5>Status:</h5>
                                <p class="fs-12 mb-1"><b>Status:</b> {{ Auth::user()->employee->job_status }}</p>
                                <p class="fs-12 mb-1"><b>Employee ID:</b> {{ Auth::user()->employee->employee_id }}</p>
                                <p class="fs-12 mb-1"><b>Branch:</b> {{ Auth::user()->employee->branch->br_name }}</p>
                                <p class="fs-12 mb-1"><b>Hire Date:</b> {{ date('d-m-Y', strtotime(Auth::user()->employee->hiring_date)) }}</p>
                                <p class="fs-12 mb-1"><b>Service Length:</b> {{ now()->diffInDays(Auth::user()->employee->hiring_date) }} days</p>
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
                                {{--<p class="fs-12 mb-1"><b>Cost to School:</b> {{ number_format($gross_salary + $allownces + 8000) }}</p>--}}
                                <p class="fs-12 mb-1"><b>Cost to School:</b> {{ number_format($gross_salary) }}</p>                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-left">
                                <h5>Other Information:</h5>
                                <p class="fs-12 mb-1"><b>DOB:</b> {{ date('d-m-Y', strtotime(Auth::user()->employee->date_of_birth)) }}</p>
                                <p class="fs-12 mb-1"><b>CNIC:</b> {{ Auth::user()->CNIC }}</p>
                                <p class="fs-12 mb-1"><b>EOBI:</b> {{ Auth::user()->employee->eobi_number }} </p>
                                <p class="fs-12 mb-1"><b>Email:</b> {{ Auth::user()->email }} </p>
                                <p class="fs-12 mb-1"><b>Contact:</b><br> {{ Auth::user()->employee->address }} <br> {{ Auth::user()->employee->mobile_number }} </p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-left">
                                <h5>Mark Attendance:</h5>
                                <p class="fs-12 mb-1">
                                    @if(isset($employee_info['time_in']) && !isset($employee_info['time_out']))
                                        <button type="button" title="Time Out" class="btn btn-sm btn-danger mark_attendance_out" data-status="out"  data-id="{{ $employee_info['id'] }}" data-employee-id="{{ Auth::user()->employee->id }}" data-route="{{ route('mark-attendance-out') }}">Time Out</button>
                                    @elseif(!isset($employee_info['time_in']) && !isset($employee_info['time_out']))
                                        <button type="button" title="Time In" class="btn btn-sm btn-success mark_attendance_in" data-status="in" data-employee-id="{{ Auth::user()->employee->id }}" data-route="{{ route('mark-attendance-in') }}">Time In</button>
                                    @else
                                        <p class="fs-12 mb-1"><b>Time In:</b> {{ $employee_info['time_in'] }} </p>
                                        <p class="fs-12 mb-1"><b>Time Out:</b> {{ $employee_info['time_out'] }} </p>
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
                            <h5>Leave Qouta:</h5>
                            <table id="leave_quotas_table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Leave&nbsp;Type</th>
                                        <th>Leave Allowed</th>
                                        <th>Leave Acquired</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee_info['leaveQuotas'] as $key => $leaveQuota)
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



@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')
<script type="text/javascript">
$(document).ready(function() {
    /** Mark in*/
    $(document).on('click', '.mark_attendance_in', function (e)
        {
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
                        type : 'POST',
                        url : url,
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            employee_id,
                            time_in,
                            attendance_type
                        },
                        success:function (response) {
                            Swal.fire('Done!', '', 'success')
                            location.reload();
                        }
                    })
                }
            })
        });
        $(document).on('click', '.mark_attendance_out', function (e)
        {
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
                        type : 'POST',
                        url : url,
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            id,
                            time_out
                        },
                        success:function (response) {
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
