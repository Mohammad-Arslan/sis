@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div id="alertDiv"></div>
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12 mb-4">
            <div class="accordion" id="bulk-invoice-filter-collapse">
                @role('super_admin')
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#branchesCollapse" aria-expanded="true" aria-controls="branchesCollapse">
                                Branches
                            </button>
                        </h2>
                        <div id="branchesCollapse" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#bulk-invoice-filter-collapse">
                            <div class="accordion-body p-0" style="max-height: 500px;">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <p class="fs-15 mb-0">Select all</p>
                                        <div class="flex-shrink-0">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllBranches"
                                                    style="font-size: 16px">
                                            </div>
                                        </div>
                                    </div><!-- end card header -->

                                    <div class="card-body py-0">
                                        <div id="contact-existing-list">
                                            {{-- <div class="row mb-2">
                                            <div class="col">
                                                <div>
                                                    <input class="search form-control" placeholder="Search" />
                                                </div>
                                            </div>
                                        </div> --}}

                                            <div data-simplebar style="max-height: 500px;" class="mx-n3">
                                                <ul class="list list-group list-group-flush mb-0">
                                                    @foreach ($branches as $branch)
                                                        <li class="list-group-item px-3 ps-4" data-id="{{ $loop->index + 1 }}">
                                                            <div class="d-flex align-items-start">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <h5 class="contact-name fs-13 mb-1"><a href="#"
                                                                            class="link text-dark">{{ $branch->br_name }}</a>
                                                                    </h5>
                                                                    <p class="contact-born text-muted mb-0">No. of Students:
                                                                        {{ count($branch->students) }}</p>
                                                                </div>

                                                                <div class="flex-shrink-0 ms-2">
                                                                    <div class="text-muted">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input branch_checkbox"
                                                                                type="checkbox" id="branch_{{ $branch->id }}"
                                                                                value="{{ $branch->id }}"
                                                                                style="font-size: 16px">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                    <li style="height: 50px"></li>
                                                </ul>
                                                <!-- end ul list -->
                                            </div>
                                        </div>
                                    </div><!-- end card -->
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#classesCollapse" aria-expanded="true" aria-controls="classesCollapse">
                            Classes
                        </button>
                    </h2>
                    <div id="classesCollapse" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#bulk-invoice-filter-collapse">
                        <div class="accordion-body p-0" style="max-height: 500px;">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <p class="fs-15 mb-0">Select all</p>
                                    <div class="flex-shrink-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllClasses"
                                                style="font-size: 16px">
                                        </div>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body py-0">
                                    <div id="contact-existing-list">
                                        {{-- <div class="row mb-2">
                                            <div class="col">
                                                <div>
                                                    <input class="search form-control" placeholder="Search" />
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div data-simplebar style="max-height: 500px;" class="mx-n3">
                                            <ul class="list list-group list-group-flush mb-0">
                                                @foreach ($classes as $class)
                                                    <li class="list-group-item px-3 ps-4" data-id="{{ $loop->index + 1 }}">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <h5 class="contact-name fs-13 mb-1"><a href="#"
                                                                        class="link text-dark">{{ $class->class_name }}</a>
                                                                </h5>
                                                                <p class="contact-born text-muted mb-0">
                                                                    {{-- {{ $class->abbreviation }} --}}</p>
                                                            </div>

                                                            <div class="flex-shrink-0 ms-2">
                                                                <div class="text-muted">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input class_checkbox"
                                                                            type="checkbox" id="class_{{ $class->id }}"
                                                                            value="{{ $class->id }}"
                                                                            style="font-size: 16px">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                                <li style="height: 50px"></li>
                                            </ul>
                                            <!-- end ul list -->
                                        </div>
                                    </div>
                                </div><!-- end card -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#sectionCollapse" aria-expanded="true" aria-controls="sectionCollapse">
                            Sections
                        </button>
                    </h2>
                    <div id="sectionCollapse" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#bulk-invoice-filter-collapse">
                        <div class="accordion-body p-0" style="max-height: 500px;">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <p class="fs-15 mb-0">Select all</p>
                                    <div class="flex-shrink-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllSections"
                                                style="font-size: 16px">
                                        </div>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body py-0">
                                    <div id="contact-existing-list">
                                        {{-- <div class="row mb-2">
                                            <div class="col">
                                                <div>
                                                    <input class="search form-control" placeholder="Search" />
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div data-simplebar style="max-height: 500px;" class="mx-n3">
                                            <ul class="list list-group list-group-flush mb-0" id="sectionsCard">
                                                <li style="height: 50px; border: none"></li>
                                            </ul>
                                            <!-- end ul list -->
                                        </div>
                                    </div>
                                </div><!-- end card -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (request()->route()->getName() == 'bulk-invoices')
            @include('students.bulk_invoices.students_datatable')
        @else
            @include('students.bulk_invoices.invoices_datatable')
        @endif
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        var selectedBranches = [];
        @if (auth()->user()->hasRole('network_associate'))
            selectedBranches = [{{ get_set_NWABranchId() }}];
        @else
            selectedBranches = [{{ get_branch_id_for_employee() }}]
        @endif

        var datatable_url;
        var table_id;

        @if (request()->route()->getName() == 'bulk-invoices')
            let academic_year_id = $('#academic_year_id').val();
            console.log(academic_year_id);
            datatable_url = '/list-students?academic_year_id=' + academic_year_id,
            table_id = '#branches-students-list'
        @else
            datatable_url = '/nwa-list-invoices'
            table_id = '#bulk-invoice-list'
        @endif

        var selectedClasses = [];
        var selectedSections = [];
        var selectedStudents = [];
        var selectInvoices = [];
        $(document).ready(function() {
            $('.form-check-input').prop('checked', false)

            let branches = $('.branch_checkbox');
            let classes = $('.class_checkbox');
            let sections = $('.section_checkbox');
            let feePeriod = $('#feePeriodFilter');
            let feePeriodInput = $('#feePeriod');
            let academic_year_id = $('#academic_year_id');

            // Branches Checkboxes Script

            selectAllItems('selectAllBranches', 'branch_checkbox', selectedBranches, function() {
                populateDatatable();
                if (!selectedSections.length) getSections();
            })

            toggleItemCheckbox('.branch_checkbox', selectedBranches, function() {
                populateDatatable();
                if (!selectedSections.length) getSections();
            })

            // Classes Checkboxes Script

            selectAllItems('selectAllClasses', 'class_checkbox', selectedClasses, function() {
                populateDatatable();
                if (!selectedSections.length) getSections();
            })

            toggleItemCheckbox('.class_checkbox', selectedClasses, function() {
                populateDatatable();
                if (!selectedSections.length) getSections();
                console.log(selectedClasses);
            })


            // Sections Checkboxes Script

            selectAllItems('selectAllSections', 'section_checkbox', selectedSections, function() {
                populateDatatable()
            })

            // Student Datatable Script

            selectAllItems('selectAllStudents', 'student_checkbox', selectedStudents, function() {
                console.log(selectedStudents)
            })

            $('#branches-students-list').on('change', 'input[type=checkbox]', function(e) {
                if (e.target.checked) {
                    if (!checkIfValueExistsInArray(selectedStudents, e.target.value)) selectedStudents
                        .push(
                            e.target.value)
                } else removeElementFromArray(selectedStudents, e.target.value)
            })

            // Select Invoices Datatable Script

            selectAllItems('selectAllInvoices', 'invoice_checkbox', selectInvoices, function() {
                console.log(selectInvoices)
                document.getElementById('invoices_input').value = selectInvoices;
            })

            $('#bulk-invoice-list').on('change', 'input[type=checkbox]', function(e) {
                if (e.target.checked) {
                    if (!checkIfValueExistsInArray(selectInvoices, e.target.value)) selectInvoices
                        .push(
                            e.target.value)
                } else removeElementFromArray(selectInvoices, e.target.value)
                console.log(selectInvoices)
                document.getElementById('invoices_input').value = selectInvoices;
            })

            feePeriod.on('change', function() {
                populateDatatable()
            })

            feePeriodInput.on('change', function() {
                populateDatatable()
            })

            $('#branches-students-list').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // bLengthChange: false,
                // pageLength: 50,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: datatable_url,
                    data: function(d) {
                        d.branches = !!selectedBranches.length ? selectedBranches : [0]
                        d.classes = selectedClasses
                        d.sections = selectedSections
                        d.filters = {
                            feePeriod: feePeriod.val(),
                            feePeriodInput: feePeriodInput.val()
                        }
                        d.academic_year_id = academic_year_id.val()
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'roll_no',
                        name: 'roll_no'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'active_class.branch_class_sections.com_classes.class_name',
                        name: 'active_class.branch_class_sections.com_classes.class_name'
                    },
                    {
                        data: 'active_class.branch_class_sections.sections.section_name',
                        name: 'active_class.branch_class_sections.sections.section_name'
                    },
                    {
                        data: 'invoice_status',
                        name: 'invoice_status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '5%'
                    },
                ]
            });


            $('#bulk-invoice-list').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // bLengthChange: false,
                // paging: false,
                // pageLength: 100,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: datatable_url,
                    data: function(d) {
                        d.branches = !!selectedBranches.length ? selectedBranches : [0]
                        d.classes = selectedClasses
                        d.sections = selectedSections
                        d.filters = {
                            feePeriod: feePeriod.val()
                        }
                        d.academic_year_id = academic_year_id.val()
                    }
                },
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        width: "5%"
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'student_fee_package.fee_package.fee_package_type.name',
                        name: 'student_fee_package.fee_package.fee_package_type.name'
                    },
                    {
                        data: 'fee_period_range',
                        name: 'fee_period_range'
                    },
                    {
                        data: 'student_fee_package.academic_year.title',
                        name: 'student_fee_package.academic_year.title'
                    },
                    {
                        data: 'student_fee_package.com_class.class_name',
                        name: 'student_fee_package.com_class.class_name'
                    },
                    {
                        data: 'student_fee_package.section.section_name',
                        name: 'student_fee_package.section.section_name'
                    },
                    {
                        data: 'concessions_percent',
                        name: 'concessions_percent'
                    },
                    {
                        data: 'concessions_type',
                        name: 'concessions_type'
                    },
                    {
                        data: 'is_paid',
                        name: 'is_paid'
                    },
                    {
                        data: 'paid_date',
                        name: 'paid_date'
                    },
                    // {
                    //     data: 'total',
                    //     name: 'total',
                    //     width: "15%"
                    // },
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
                        width: "5%"
                    }
                ]
            });



            function getSections() {
                let section_list = "";
                console.log(selectedBranches, selectedClasses)
                if (selectedBranches.length) {
                    $.ajax({
                        url: `/list-sections/${selectedBranches}?type=bulk&classes=${selectedClasses}`,
                        success: function(result) {
                            result.map(item => {
                                section_list += `<li class="list-group-item px-3 ps-4"
                                    data-id="${item.id}">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="contact-name fs-13 mb-1"><a href="#"
                                                class="link text-dark">${item.sections.section_name}</a>
                                            </h5>
                                            <p class="contact-born text-muted mb-0" style="font-size: 12px">
                                                <span class="text-muted">${item.com_classes.class_name}</span></p>
                                        </div>

                                        <div class="flex-shrink-0 ms-2">
                                            <div class="text-muted">
                                                <div class="form-check">
                                                    <input class="form-check-input section_checkbox"
                                                        type="checkbox"
                                                        id="section_${item.id}"
                                                        value="${item.id}"
                                                        onchange="toggleSectionCheckbox(this)"
                                                        style="font-size: 16px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>`
                            })
                            document.getElementById('sectionsCard').innerHTML = section_list
                        }
                    })
                }

            }

            function populateDatatable() {
                $(table_id).DataTable().ajax.reload(null, false)
            }
        });

        function toggleSectionCheckbox(element) {
            if (element.checked) {
                if (!checkIfValueExistsInArray(selectedSections, element.value)) selectedSections.push(
                    element.value)
            } else
                removeElementFromArray(selectedSections, element.value)
            $(table_id).DataTable().ajax.reload(null, false)
            console.log(selectedSections)
        }

        $('#bulk-invoices-form').submit(function(event) {
            event.preventDefault();
            const fee_package = $('#feePackage').val();
            const fee_period = $('#feePeriod').val();
            const issue_date = $('#loadIssueDate').val();
            const due_date = $('#loadDueDate').val();
            const validity_date = $('#loadValidDate').val();
            const academic_year_id = $('#academic_year_id').val();

            $.ajax({
                url: '/generate-bulk-invoices',
                method: 'POST',
                data: {
                    students: selectedStudents,
                    fee_package,
                    fee_period,
                    issue_date,
                    due_date,
                    validity_date,
                    academic_year_id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    document.getElementById('alertDiv').innerHTML =
                        "<div class='alert alert-success alert-dismissible alert-label-icon label-arrow fade show' role='alert'><i class='ri-notification-off-line label-icon'></i><strong>Success</strong>- Invoices generated successsfully<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"
                }
            })

            console.log(selectedStudents, fee_package, fee_period);
        })


        // $('#bulk-challan-form').submit(function(event) {
        //     event.preventDefault();

        //     $.ajax({
        //         url: '/generate-bulk-challan',
        //         method: 'POST',
        //         data: {
        //             invoices: selectInvoices,
        //             _token: "{{ csrf_token() }}",
        //         },
        //         success: function(result) {
        //             document.getElementById('alertDiv').innerHTML =
        //             "<div class='alert alert-success alert-dismissible alert-label-icon label-arrow fade show' role='alert'><i class='ri-notification-off-line label-icon'></i><strong>Success</strong>- Invoices generated successsfully<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"
        //         }
        //     })

        //     console.log(selectInvoices);
        // })
    </script>
@endpush
