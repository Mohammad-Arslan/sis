@extends('layouts.master')

@section('content')
    <div class="row">

        <!-- <div class="col-lg-12">
                                    <div class="alert alert-success" role="alert">
                                        A simple Success alert with <a href="#" class="alert-link">an example
                                            link</a>. Give it a click if you like.
                                    </div>
                                </div> -->
        @if (isset($beamsChallan))
            @include('beams_challans.edit_challans')
        @else
            @permission('upload-beams-challan')
                @include('beams_challans.add_challans')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Challans List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        {{-- <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                                class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a> --}}
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        @role('super_admin|finance-manager')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="filter_branch_id" name="filter_branch_id"
                                        placeholder="Branch">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                        @endforeach
                                    </select>
                                    <label for="filter_branch__id" class="form-label">Branch</label>
                                </div>
                            </div>
                        @endrole
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="filter_academic_year_id"
                                    name="filter_academic_year_id" placeholder="Academic Year">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                    @endforeach
                                </select>
                                <label for="filter_academic_year_id" class="form-label">Academic year</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="filter_class_id" name="filter_class_id"
                                    placeholder="Classes">
                                    <option value="">Please select</option>
                                    @role('network_associate|accountant')
                                        @foreach ($classes as $class)
                                            <option value="{{ $class['id'] }}">{{ $class['class_name'] }}</option>
                                        @endforeach
                                    @endrole
                                    @role('super_admin|finance-manager')
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    @endrole
                                </select>
                                <label for="filter_class_id" class="form-label">Classes</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="filter_section_id" name="filter_section_id"
                                    placeholder="Sections">
                                    <option value="">Please select</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="filter_section_id" class="form-label">Sections</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select mb-3" id="filter_months" name="filter_months">
                                    <option value="">Please select</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                                <label for="filter_months" class="form-label">Month</label>
                            </div>
                        </div>
                        @role('super_admin|finance-manager')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                    <label for="mySearch" class="form-label">Search...</label>
                                </div>
                            </div>
                        @endrole
                    </div>
                    <table id="beams-challans-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @role('super_admin|finance-manager')
                                    <th>Branch</th>
                                @endrole
                                <th>Academic Year</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Month</th>
                                <th>Challan</th>
                                <th>Created At</th>
                                @role('super_admin|finance-manager')
                                    <th>Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                @role('super_admin|finance-manager')
                                    <th>Branch</th>
                                @endrole
                                <th>Academic Year</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Month</th>
                                <th>Challan</th>
                                <th>Created At</th>
                                @role('super_admin|finance-manager')
                                    <th>Action</th>
                                @endrole
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

            $('#beams-challans-data-table').dataTable({
                searching: false,
                processing: true,
                serverSide: false,
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
                    url: "{{ route('beams-challans.index') }}",
                    data: function(d) {
                        @role('super_admin|finance-manager')
                            d.filter_branch_id = $('#filter_branch_id').val();
                        @endrole
                        d.filter_academic_year_id = $('#filter_academic_year_id').val();
                        d.filter_class_id = $('#filter_class_id').val();
                        d.filter_section_id = $('#filter_section_id').val();
                        d.filter_months = $('#filter_months').val();
                        @role('super_admin|finance-manager')
                            d.searchName = $('#mySearch').val().toLowerCase();
                        @endrole
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    @role('super_admin|finance-manager')
                        {
                            data: 'branch_name',
                            name: 'branch_name'
                        },
                    @endrole {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'section_name',
                        name: 'section_name'
                    },
                    {
                        data: 'challan_month',
                        name: 'challan_month'
                    },
                    {
                        data: 'challan_pdf',
                        name: 'challan_pdf'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    @role('super_admin|finance-manager')
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            width: "5%",
                            sClass: "text-center"
                        },
                    @endrole
                ]
            });
        });
        $(document).on('change', '.filter', function() {
            $('#beams-challans-data-table').DataTable().ajax.reload(null, false).page('first');
        });
        @role('super_admin|finance-manager')
            $(document).on("keyup", '#mySearch', function() {
                var value = $(this).val().toLowerCase();
                if (value.length > 0 || value.length == 0) {
                    $('#beams-challans-data-table').DataTable().ajax.reload(null, false).page('first');
                }
            });

            $(document).on('change', '.load-select-filter', function(e) {

                var target = $(this).data('target');
                var url = $(this).data('url');
                var query_string = '';
                var target_name = target?.split('_')[0];

                var mul_targets = target.split(',');

                if (target_name == 'branch') {
                    target_name = 'branch';
                }
                if (target_name == 'class') {
                    target_name = 'class';
                }

                console.log(target, url, target_name);
                url_param_split = url.split('/')
                url_param = url_param_split[url_param_split.length - 1];

                if (url_param === 'list-academic-branch-class-sections') {
                    query_string = '?id=' + $(this).val() + '&branch_id=' + $('#branch_id').val();
                } else {
                    query_string = '?id=' + $(this).val();
                }

                $.ajax({
                    url: url + query_string,
                    type: "GET",
                    cache: false,
                    success: function(data) {
                        var options = `<option value="">Please select a ${target_name}</option>`;

                        if (url_param === 'list-academic-branches') {
                            if (data.branches.length >= 1) {
                                $.each(data.branches, function(index, value) {
                                    options += '<option value="' + value.branch.id + '">' +
                                        value.branch.br_name + '</option>';
                                })

                                $('#branch_id').html(options);
                            }

                        }

                        if (url_param === 'list-academic-branch-classes') {
                            if (data.classes.length >= 1) {
                                $.each(data.classes, function(index, value) {
                                    options += '<option value="' + value.com_classes.id + '">' +
                                        value.com_classes.class_name + '</option>';
                                })

                                $('#class_id').html(options);
                            }
                        }
                        if (url_param === 'list-academic-branch-class-sections') {
                            if (data.sections.length >= 1) {
                                $.each(data.sections, function(index, value) {
                                    options += '<option value="' + value.sections.id + '">' +
                                        value.sections.section_name + '</option>';
                                })

                                $('#section_id').html(options);
                            }
                        }

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
            });
        @endrole
    </script>
@endpush
