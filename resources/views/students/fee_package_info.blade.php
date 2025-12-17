<div id='feePackageAlert' role="alert"></div>

@permission('create-student-fee-package')
@include('students.fee_package_form')
@endpermission
<div class="border my-3 border-dashed"></div>

<div class="col-lg-12">

    <table id="fee-package-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
        style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Branch Name</th>
                <th>Fee Package</th>
                <th>Concession Type</th>
                <th>Concession %</th>
                <th>Class</th>
                <th>Section</th>
                <th>Academic Year</th>
                <th>Status</th>
                <th>Active Till</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Branch Name</th>
                <th>Fee Package</th>
                <th>Concession Type</th>
                <th>Concession %</th>
                <th>Class</th>
                <th>Section</th>
                <th>Academic Year</th>
                <th>Status</th>
                <th>Active Till</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#fee-package-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('student-fee-packages.index', ['student' => isset($student) ? $student->id : 0]) }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'students.email',
                        name: 'students.email'
                    },
                    {
                        data: 'fee_package.branch.br_name',
                        name: 'fee_package.branch.br_name'
                    },
                    {
                        data: 'fee_package.package_name',
                        name: 'fee_package.package_name'
                    },
                    {
                        data: 'fee_concession',
                        name: 'fee_concession'
                    },
                    {
                        data: 'fee_concession_percentage',
                        name: 'fee_concession_percentage'
                    },
                    {
                        data: 'com_class.class_name',
                        name: 'com_class.class_name'
                    },
                    {
                        data: 'section.section_name',
                        name: 'section.section_name'
                    },
                    {
                        data: 'academic_year.title',
                        name: 'academic_year.title'
                    },
                    {
                        data: 'is_valid',
                        name: 'is_valid'
                    },
                    {
                        data: 'active_till',
                        name: 'active_till',
                        width: "15%"
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
                        width: "5%"
                    }
                ]
            });
        });
    </script>
@endpush
