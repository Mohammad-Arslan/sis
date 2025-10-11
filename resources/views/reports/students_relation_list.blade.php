@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Search Paid List </h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('students-relation-list') }}" class="btn btn-info btn-label btn-sm">
                        <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                    </a>
                    <a href="{{ route('students-relation-export') }}" class="btn btn-info btn-label btn-sm">
                        <i class="ri-file-line label-icon align-middle fs-16 me-2"></i> Export
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="designation_id" class="form-label">Branch</label>
                                </div>
                            </div>
                        @endrole
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="class_id" name="class_id"
                                    placeholder="Class">
                                    <option value="">Please select</option>
                                    @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                    @foreach ($classes as $class)
                                        @if($class->com_classes)
                                            <option value="{{ $class->com_classes->id }}">{{ $class->com_classes->class_name }} </option>
                                        @endif
                                    @endforeach
                                    @endrole
                                    @role('network_associate')
                                    @foreach ($classes as $class)
                                        @if($class->com_classes)
                                            <option value="{{ $class->com_classes->id }}">{{ $class->com_classes->class_name }} </option>
                                        @endif
                                    @endforeach
                                    @endrole
                                </select>
                                <label for="class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="section_id" name="section_id" placeholder="Section">
                                    <option value="">Please select</option>
                                    <!-- Sections will be loaded dynamically based on class selection -->
                                </select>
                                <label for="section_id" class="form-label">Sections</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="gender" name="gender" placeholder="Gender">
                                    <option value="">Please Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <label for="gender" class="form-label">Gender</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="student-relation-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                <th>Branch ID</th>
                                <th>Branch</th>
                                @endrole
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Fee Package</th>
                                <th>Monthly Fee (Rs.)</th>
                                <th>Parent Name</th>
                                <th>Parent Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                <th>Branch ID</th>
                                <th>Branch</th>
                                @endrole
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Fee Package</th>
                                <th>Monthly Fee (Rs.)</th>
                                <th>Parent Name</th>
                                <th>Parent Contact</th>
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

            $('#student-relation-table').DataTable({
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
                    searchPlaceholder: "Search...",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />"
                },
                ajax: {
                    url: "{{ route('students-relation-list') }}",
                    data: function(d) {
                        d.gender = $('#gender').val();
                        d.section_id = $('#section_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.class_id = $('#class_id').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [
                    @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                    {
                        data: 'branch_code',
                        name: 'branch_code'
                    },
                    {
                        data: 'br_name',
                        name: 'br_name'
                    },
                    @endrole
                    {
                        data: 'student_id',
                        name: 'student_id'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
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
                        data: 'package_name',
                        name: 'package_name',
                    },
                    {
                        data: 'cost',
                        name: 'cost',
                    },
                    {
                        data: 'parent_name',
                        name: 'parent_name',
                    },
                    {
                        data: 'parent_contact',
                        name: 'parent_contact',
                    }
                ]
            });
        });

        // Handle class change to load sections
        $(document).on('change', '#class_id', function() {
            var classId = $(this).val();
            var branchId = $('#branch_id').val();
            var sectionSelect = $('#section_id');
            
            // Clear sections dropdown
            sectionSelect.empty().append('<option value="">Please select</option>');
            
            if (classId && branchId) {
                // Load sections for the selected class and branch
                $.ajax({
                    url: "{{ route('get-sections-for-class') }}",
                    type: 'GET',
                    data: { 
                        class_id: classId,
                        branch_id: branchId
                    },
                    success: function(response) {
                        if (response.sections && response.sections.length > 0) {
                            $.each(response.sections, function(index, section) {
                                sectionSelect.append('<option value="' + section.id + '">' + section.section_name + '</option>');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading sections:', error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            }
            
            // Reload DataTable
            $('#student-relation-table').DataTable().ajax.reload(null, false);
        });

        // Handle branch change to clear sections and reload
        $(document).on('change', '#branch_id', function() {
            // Clear class and section dropdowns
            $('#class_id').val('').trigger('change');
            $('#section_id').empty().append('<option value="">Please select</option>');
            
            // Reload DataTable
            $('#student-relation-table').DataTable().ajax.reload(null, false);
        });

        $(document).on('change', '.filter', function() {
            $('#student-relation-table').DataTable().ajax.reload(null, false);
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#student-relation-table').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush
