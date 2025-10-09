@extends('layouts.master')
@section('content')
    @include('components.flash_message')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Admission Inquiries</li>
    </x-breadcrumb>
    <div class="row">
        @if (isset($admission_query))
            @include('admission_queries.edit_inquiry')
        @else
            @permission('add-admission-inquiry')
                @include('admission_queries.add_inquiry')
            @endpermission
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Admission Inquiries</h4>
                    <div class="flex-shrink-0">
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href="{{ route('exportAdmissionQueries') }}">
                            <i class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id"  name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}" {{$academic_year->active == 1 ? 'selected' : ''}}>{{ $academic_year->title }}</option>
                                    @endforeach

                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        @if (isHeadOfficeEmp() || isSuperAdmin())
                            <div class="col-md-3 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="s_branch_id">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label">Branch</label>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_class_id">
                                    <option value="">Please select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Class</label>
                            </div>
                        </div>
                        @if (isHeadOfficeEmp() || isSuperAdmin())
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_city_id">
                                    <option value="">Please select</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Cities</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_town_id">
                                    <option value="">Please select</option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}">{{ $town->town_name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Town</label>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control filter"
                                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" id="from_date">
                                <label class="form-label">From</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control filter"
                                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" id="to_date">
                                <label class="form-label">To</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_source_id">
                                    <option value="">Please select</option>
                                    @foreach ($sources as $source)
                                        <option value="{{ $source->id }}">{{ $source->source_name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Source</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_inquiry_type_id">
                                    <option value="">Please select</option>
                                    @foreach ($inquirytypes as $inquirytype)
                                        <option value="{{ $inquirytype->id }}">{{ $inquirytype->type }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Inquiry Via</label>
                            </div>
                        </div>
                    </div>
                    <table id="admission-queries-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                        <tr>
                            <th>Inquiry Number</th>
                            <th>Inquiry Via</th>
                            <th>Student Name</th>
                            <th>Student Age</th>
                            <th>Parent Name</th>
                            <th>Parent Email</th>
                            <th>Parent Contact</th>
                            <th>City</th>
                            <th>Town</th>
                            @if (isHeadOfficeEmp() || isSuperAdmin())
                            <th>Branch</th>
                            @endif
                            <th>Class</th>
                            <th>Source</th>
                            <th>Application Date</th>
                            <th>Academic Year</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Inquiry Number</th>
                            <th>Inquiry Via</th>
                            <th>Student Name</th>
                            <th>Student Age</th>
                            <th>Parent Name</th>
                            <th>Parent Email</th>
                            <th>Parent Contact</th>
                            <th>City</th>
                            <th>Town</th>
                            @if (isHeadOfficeEmp() || isSuperAdmin())
                            <th>Branch</th>
                            @endif
                            <th>Class</th>
                            <th>Source</th>
                            <th>Application Date</th>
                            <th>Academic Year</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#admission-queries-table').DataTable({
                processing: true,
                searching: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                order: [[ 10, 'desc' ]],
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('admission-query.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                        d.branch_id = $('#s_branch_id').val();
                        d.class_id = $('#s_class_id').val();
                        d.city_id = $('#s_city_id').val();
                        d.town_id = $('#s_town_id').val();
                        d.source_id = $('#s_source_id').val();
                        d.inquiry_type_id = $('#s_inquiry_type_id').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [
                    {
                        data: 'inquiry_number',
                        name: 'inquiry_number'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'student_age',
                        name: 'student_age'
                    },
                    {
                        data: 'parent_name',
                        name: 'parent_name'
                    },
                    {
                        data: 'parent_email',
                        name: 'parent_email'
                    },
                    {
                        data: 'parent_contact',
                        name: 'parent_contact'
                    },
                    {
                        data: 'city_name',
                        name: 'city_name'
                    },
                    {
                        data: 'town_name',
                        name: 'town_name'
                    },
                    @if (isHeadOfficeEmp() || isSuperAdmin())
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    @endif
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'application_date',
                        name: 'application_date'
                    },
                    {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    },
                ],
            });

            $(document).on('change', '.filter', function() {
                $('#admission-queries-table').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
