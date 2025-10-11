@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Availing Concession Report</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('students-concession-export') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if($academic_year->active == 1) selected @endif value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        @if (isSuperAdmin() || isHeadOfficeEmp())
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select filter @if ($errors->has('branch_id')) is-invalid @endif"
                                        id="branch_id" name="branch_id" aria-label="Branch select" required>
                                        <option value="">Please select a branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="branch" class="form-label">Branch <span class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('branch_id'))
                                            {{ $errors->first('branch_id') }}
                                        @else
                                            Branch is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="form-select filter @if ($errors->has('class_id')) is-invalid @endif" id="class_id"
                                    name="class_id" aria-label="Class select" required>
                                    <option value="">Please select a class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('class_id'))
                                        {{ $errors->first('class_id') }}
                                    @else
                                        Class is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('section_id')) is-invalid @endif"
                                    id="section_id" name="section_id" aria-label="Branch select" required>
                                    <option value="">Please select a section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">
                                            {{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('section_id'))
                                        {{ $errors->first('section_id') }}
                                    @else
                                        Section is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <div class="filter input-group">
                                    <input type="text"
                                        class="form-control @if ($errors->has('start_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y"
                                        value="{{ old('start_date') }}" name="start_date" id="start_date" required>
                                    <label for="start_date" class="form-label">From Date<span class="text-danger">*</span></label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('start_date'))
                                            {{ $errors->first('start_date') }}
                                        @else
                                            From date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <div class="filter input-group">
                                    <input type="text"
                                        class="form-control @if ($errors->has('end_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y"
                                        value="{{ old('end_date') }}" name="end_date" id="end_date" required>
                                    <label for="end_date" class="form-label">To Date<span class="text-danger">*</span></label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('end_date'))
                                            {{ $errors->first('end_date') }}
                                        @else
                                            To date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="student_concession" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">Students</th>
                                <th colspan="4" class="text-center">Concession</th>
                                <th colspan="2" class="text-center">Concession Period</th>
                                <th colspan="3" class="text-center">Employee</th>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Class Name</th>
                                <th>AY</th>
                                <th>Type</th>
                                <th>100%</th>
                                <th>50%</th>
                                <th>From</th>
                                <th>To</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Branch</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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

        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#student_concession').dataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                searchPlaceholder: "Search..."
            },
            ajax: {
                url: "{{ route('student-concession-report') }}",
                data: function(d) {
                    d.academic_year_id = $('#academic_year_id').val();
                    d.section_id = $('#section_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.class_id = $('#class_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {
                    data: 'roll_no',
                    name: 'roll_no',
                    'defaultContent': ''
                },
                {
                    data: 'full_name',
                    name: 'full_name',
                    'defaultContent': ''
                },
                {
                    data: 'class_name_section',
                    name: 'class_name_section',
                    'defaultContent': ''
                },
                // {
                //     data: 'left_date',
                //     name: 'left_date'
                // },

                {
                    data: 'academic_year',
                    name: 'academic_year',
                    'defaultContent': ''
                },
                {
                    data: 'concession_type',
                    name: 'concession_type',
                    'defaultContent': ''
                },
                {
                    data: 'hundered_percent',
                    name: 'hundered_percent',
                    'defaultContent': ''
                },
                {
                    data: 'fifty_percent',
                    name: 'fifty_percent',
                    'defaultContent': ''
                },
                {
                    data: 'from_date',
                    name: 'from_date',
                    'defaultContent': ''
                },
                {
                    data: 'to_date',
                    name: 'to_date',
                    'defaultContent': ''
                },
                {
                    data: 'emp_id',
                    name: 'emp_id',
                    'defaultContent': ''
                },
                {
                    data: 'emp_name',
                    name: 'emp_name',
                    'defaultContent': ''
                },
                {
                    data: 'br_name',
                    name: 'br_name',
                    'defaultContent': ''
                }
            ]
        });
    });

    $(document).on('change', '.filter', function() {
        $('#student_concession').DataTable().ajax.reload(null, false).page('first');
    });

    $(document).on("keyup", '#mySearch', function() {
        var value = $(this).val().toLowerCase();
        if (value.length > 0 || value.length == 0) {
            $('#student_concession').DataTable().ajax.reload(null, false).page('first');
        }
    });
</script>
@endpush
