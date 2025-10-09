@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Curriculum List</li>
    </x-breadcrumb>
    <div class="row">

        {{--}}@if (isset($town))
            @include('settings.curriculum.edit_town')
        @else
            @permission('add-town')
                @include('settings.curriculum.add_town')
            @endpermission
        @endif
        {{--}}

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Curriculum List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('curriculum.create') }}" class="btn btn-success-new btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Curriculum
                        </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">



                    <div class="row">

                        {{--<div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if($academic_year->active == 1) selected @endif value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>--}}
                        @role(auth()->user()->hasRole('head_of_bd') ||
                        auth()->user()->hasRole('bd_sales'))
                        {{--<div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="region_id" name="region_id" placeholder="Region" disabled>
                                    <option value="">Please select</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}" selected>{{ $region->region_name }}</option>
                                    @endforeach
                                </select>
                                <label for="region_id" class="form-label">Region</label>
                            </div>
                        </div>--}}
                        @endrole
                        @if(isSuperAdmin() || isHeadOfficeEmp())
                            {{--<div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name. ' ('.$branch->branch_code.')' }}</option>
                                        @endforeach
                                    </select>
                                    <label for="designation_id" class="form-label">Branch</label>
                                </div>
                            </div>--}}
                            @endrole
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="class_id" name="class_id"
                                            placeholder="Class">
                                        <option value="">Show All</option>
                                        @if(!isSuperAdmin() && !isHeadOfficeEmp())
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->com_classes->id }}">{{ $class->com_classes->class_name }} </option>
                                            @endforeach
                                        @else
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="class_id" class="form-label">Class</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="subject_id" name="subject_id" placeholder="Subject">
                                        <option value="">Show All</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="subject_id" class="form-label">Subjects</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="curriculum_type_id" name="curriculum_type_id" placeholder="Curriculum Type">
                                        <option value="">Show All</option>
                                        @foreach ($curriculum_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="curriculum_type_id" class="form-label">Curriculum Type</label>
                                </div>
                            </div>
                            {{--<div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="section_id" name="section_id" placeholder="Section">
                                        <option value="">Please select</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="section_id" class="form-label">Sections</label>
                                </div>
                            </div>--}}
                            {{--<div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="gender" name="gender" placeholder="Gender">
                                        <option value="">Please Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <label for="gender" class="form-label">Gender</label>
                                </div>
                            </div>--}}
                            {{--<div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="status_list" name="status">
                                        <option value="all">Status</option>
                                        <option value="on_roll">On Roll</option>
                                        <option value="registered">Registered</option>
                                        <option value="processing">Processing</option>
                                        <option value="left">Left</option>
                                        <option value="pass-out">Pass-Out</option>
                                        <option value="transferred">Transferred</option>
                                    </select>
                                    <label for="status" class="form-label">Status</label>
                                </div>
                            </div>--}}
                    </div>



                    <table id="curriculum-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created At</th>
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
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });


            $('#curriculum-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                /*language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },*/
                ajax: {
                    url: "{{ route('curriculum.index') }}",
                    data: function(d) {
                        d.class_id = $('#class_id').val();
                        d.subject_id = $('#subject_id').val();
                        d.curriculum_type_id = $('#curriculum_type_id').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'curriculum_class.class_name',
                        name: 'curriculum_class.class_name'
                    },
                    {
                        data: 'curriculum_subject.subject_name',
                        name: 'curriculum_subject.subject_name'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        width: '15%',
                        render: function (data, type, row) {
                            if (type === 'display' && data.length > 30) {
                                return data.substr(0, 50) + '...';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'description',
                        name: 'description',
                        width: '15%',
                        render: function (data, type, row) {
                            if (type === 'display' && data.length > 30) {
                                return data.substr(0, 30) + '...';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: "text-center"
                    },
                ]
            });

            $(document).on('change', '.filter', function() {
                $('#curriculum-data-table').DataTable().ajax.reload(null, false).page('first');
            });
        });
    </script>
@endpush
