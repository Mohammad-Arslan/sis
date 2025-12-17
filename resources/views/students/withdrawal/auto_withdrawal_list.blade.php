@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Auto Withdrawal Students</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Auto Withdrawal Students List</h4>
                    <div class="flex-shrink-0">
                        <div class="form-check">
                            <!--<label for="selectAllStudents" class="d-flex align-items-center">
                                <p class="text-muted m-0 pe-4 me-2">Select all students</p>
                                <input class="form-check-input" type="checkbox" id="selectAllWithdrawalStudents"
                                    style="font-size: 16px">
                            </label>-->
                        </div>

                    </div>
                </div>

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
                    <div id="alertBar"></div>


                    <table id="auto-withdrawal-datatable"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Full Name</th>
                                <th>Branch</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Current Status</th>
                                <th>Change Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr #</th>
                                <th>Full Name</th>
                                <th>Branch</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Current Status</th>
                                <th>Change Status</th>
                            </tr>
                        </tfoot>
                    </table>

                    <!--<form id="withdraw-student-submit" class="mt-3 d-flex">
                        <button type="submit" class="btn btn-primary me-2" onclick="withdrawStudentsApi()">Withdraw
                            Selected Students</button>
                    </form>-->
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
            var selectedStudents = [];

            // Select All Students from List

            selectAllItems('selectAllWithdrawalStudents', 'student_withdrawal_checkbox', selectedStudents,
                function() {
                    console.log(selectedStudents)
                })

            // Toggle Select Single Student from List

            $('#auto-withdrawal-datatable').on('change', 'input[type=checkbox]', function(e) {
                if (e.target.checked) {
                    if (!checkIfValueExistsInArray(selectedStudents, e.target.value)) selectedStudents
                        .push(
                            e.target.value)
                } else removeElementFromArray(selectedStudents, e.target.value)
                console.log(selectedStudents)
            })

            // Withdraw Students API

            $('#withdraw-student-submit').submit(function(event) {
                event.preventDefault();


                if (!selectedStudents.length)
                    return document.getElementById('alertBar').innerHTML =
                        "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Please any Students<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"


                $.ajax({
                    url: `/withdraw-students?students=${selectedStudents}`,
                    success: function(result) {
                        $('#auto-withdrawal-datatable').DataTable().ajax.reload(null, false)
                        document.getElementById('alertBar').innerHTML =
                        `<div class='alert alert-success alert-dismissible fade show' role='alert'>${result.success}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>`
                    }
                })
            })


            // Datatable Stuff

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#auto-withdrawal-datatable').DataTable({
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
                    url: "{{ route('auto-withdrawal') }}",
                    data: function(d) {
                        //d.gender = $('#gender').val();
                        d.section_id = $('#section_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.class_id = $('#class_id').val();
                        d.status = $('#status-list').val();
                        d.searchName = $('#student-search').val().toLowerCase();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_Row_Index',
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    },
                    {
                        data: "full_name",
                        name: "full_name"
                    },
                    {
                        data: "branch.br_name",
                        name: "branch.br_name"
                    },
                    {
                        data: "class_section",
                        name: "class_section"
                    },
                    {
                        data: "class_section",
                        name: "class_section"
                    },

                    /*{
                        data: "active_class.branch_class_sections.com_classes.class_name",
                        name: "active_class.branch_class_sections.com_classes.class_name"
                    },
                    {
                        data: "active_class.branch_class_sections.sections.section_name",
                        name: "active_class.branch_class_sections.sections.section_name"
                    },*/
                    {
                        data: "status",
                        name: "status"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "20%"
                    }
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#auto-withdrawal-datatable').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#student-search', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#auto-withdrawal-datatable').DataTable().ajax.reload(null, false);
            }
        });

        $(document).on('change', '#auto-withdrawal-status-change', onStudentStatusChange);

        function onStudentStatusChange() {

            var studentId = $(this).data('student-id');

            var newStatus = this.value;

            $.ajax({
                url: '{{route('update-auto-withdraw-students')}}',
                type: "POST",
                data: {'student_id': studentId, 'new_status':newStatus},
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (result) {
                    //Reload page
                    location.reload();
                }, error: function () {
                    console.log("Sorry! Server error!");
                },
            })
        }
    </script>
@endpush
