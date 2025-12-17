@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Withdrawal Requests</li>
    </x-breadcrumb>
    @include('components.flash_message')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Withdrawal Requests</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        @if(isSuperAdmin() || isHeadOfficeEmp())
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name. ' ('.$branch->branch_code.')' }}</option>
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
                                    @if(!isSuperAdmin() && !isHeadOfficeEmp()){
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
                                <select class="filter form-select" id="section_id" name="section_id" placeholder="Section">
                                    <option value="">Please select</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="section_id" class="form-label">Sections</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="status-list" name="status">
                                    <option value="all">Status</option>
                                    <option value="on_roll">On Roll</option>
                                    <option value="registered">Registered</option>
                                    <option value="processing">Processing</option>
                                    <option value="left">Left</option>
                                </select>
                                <label for="status" class="form-label">Status</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="student-search" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="withdrawal-requests-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Class / Section</th>
                                <th>Branch</th>
                                <th>Guardian Name</th>
                                <th>Reason</th>
                                <th>Message</th>
                                <th>Beneficiary</th>
                                <th>Last Day</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Class / Section</th>
                                <th>Branch</th>
                                <th>Guardian Name</th>
                                <th>Reason</th>
                                <th>Message</th>
                                <th>Beneficiary</th>
                                <th>Last Day</th>
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

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#withdrawal-requests-data-table').DataTable({
                searching: false,
                retrieve: true,
                serverSide: true,
                processing: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                ajax: {
                    url: "{{ route('students-withdrawal-requests') }}",
                    data: function(d) {
                        //d.gender = $('#gender').val();
                        d.section_id = $('#section_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.class_id = $('#class_id').val();
                        d.status = $('#status-list').val();
                        d.searchName = $('#student-search').val().toLowerCase();
                    }
                },
                columns: [
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'roll_no',
                        name: 'roll_no'
                    },
                    {
                        data: 'class_section',
                        name: 'class_section'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },
                    {
                        data: 'guardian_name',
                        name: 'guardian_name'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'feedback_message',
                        name: 'feedback_message'
                    },
                    {
                        data: 'beneficiary_name',
                        name: 'beneficiary_name'
                    },
                    {
                        data: 'last_day_at_school',
                        name: 'last_day_at_school'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: "text-center"
                    }
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#withdrawal-requests-data-table').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#student-search', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#withdrawal-requests-data-table').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush
