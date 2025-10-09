@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Student Promotion Request List</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Promotion Report</h4>

                    <div class="flex-shrink-0">
                        <a href="{{ route('student-promotion-report') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                        </a>
                        <a href="{{ route('student-promortions-export') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
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
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="load-select filter form-select" id="branch_id" name="branch_id" data-target="class_id" data-url="{{ 'get-branch-classes' }}"
                                    placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" data-target="section_id" onchange="GetSection(this.value,document.getElementById('branch_id').selectedIndex)" id="class_id" name="class_id"
                                    placeholder="Class">
                                    <option value="">Please select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                    @endforeach
                                </select>
                                <label for="class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="section_id" name="section_id"
                                    placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">
                                            {{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="section_id" class="form-label">Section</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="is_promoted" name="is_promoted">
                                    <option value="">Please select</option>
                                    <option value="one">
                                        Promoted
                                    </option>
                                    <option value="zero">
                                        Not Promoted
                                    </option>
                                </select>
                                <label for="is_promoted" class="form-label">Promotion Status </label>
                            </div>
                        </div>

                        {{-- <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div> --}}
                    </div>
                    <table id="promotion-request-list"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Class / Section</th>
                                <th>Permotion Status</th>
                                <th>Head Remarks</th>
                                <th>Teacher Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Branch</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Class / Section</th>
                                <th>Head Remarks</th>
                                <th>Teacher Remarks</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="promotion_modal_div"></div>
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

            $('#promotion-request-list').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />"
                },
                ajax: {
                    url: "{{ route('student-promotion-report') }}",
                    data: function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.academic_year_id = $('#academic_year_id').val();
                        d.class_id = $('#class_id').val();
                        d.section_id = $('#section_id').val();
                        d.is_promoted = $('#is_promoted').val();
                        //d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [
                    {
                        data: 'branch',
                        name: 'branch',
                        'defaultContent': ''
                    },
                    {
                        data: 'student_id',
                        name: 'student_id',
                        'defaultContent': ''
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        'defaultContent': ''
                    },
                    {
                        data: 'class_section',
                        name: 'class_section',
                        'defaultContent': ''
                    },
                    {
                        data: "status",
                        name: "status",
                        width: '5%'
                    },
                    {
                        data: "schoolhead_comments",
                        name: "schoolhead_comments",
                        width: '15%'
                    },
                    {
                        data: "teacher_comments",
                        name: "teacher_comments",
                        width: '15%'
                    }

                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#promotion-request-list').DataTable().ajax.reload(null, false).page('first');
        });

        // $(document).on("keyup", '#mySearch', function() {
        //     var value = $(this).val().toLowerCase();
        //     if (value.length > 0 || value.length == 0) {
        //         $('#promotion-request-list').DataTable().ajax.reload(null, false);
        //     }
        // });

        function GetSection(class_id,branch_id)
        {
            var target = 'section_id';
            var target_name = target?.split('_')[0];
            let url = 'get-branch-classes-sections';
            $.ajax({
            url: url + '?class_id=' + class_id+'&branch_id=' + branch_id,
            type: "GET",
            cache: false,
            success: function(data) {
                var gen_options = `<option value="">Please select a ${target_name}</option>`;
                $.each(data, function(index, value) {
                    gen_options += '<option value="' + value.id + '">' + value.section_name + '</option>';
                })
                console.log(gen_options);
                $('select[name="' + target + '"]').html(gen_options).attr('disabled', false);

            },
            error: function() {

            },
            beforeSend: function() {
                showLoading();
            },
            complete: function() {
                hideLoading();
            }
        });
        }
    </script>
@endpush
