<div id='guardianAlert' role="alert"></div>

@if (isset($academic_years))
    @permission('create-student-academic-info')
    @include('students.academic_info_form')
    @endpermission
@endif
<div class="border my-3 border-dashed"></div>

<div class="col-lg-12">

    <table id="academic-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
        style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Class</th>
                <th>Section</th>
                <th>Academic Year</th>
                <th>Status</th>
                <th>Active Till</th>
                <th>Created At</th>
                <!-- <th>Action</th> -->
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Class</th>
                <th>Section</th>
                <th>Academic Year</th>
                <th>Status</th>
                <th>Active Till</th>
                <th>Created At</th>
                <!-- <th>Action</th> -->
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

            $('#academic-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('class-students.index', ['student' => isset($student) ? $student->id : 0]) }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'branch_class_sections.com_classes.class_name',
                        name: 'branch_class_sections.com_classes.class_name'
                    },
                    {
                        data: 'branch_class_sections.sections.section_name',
                        name: 'branch_class_sections.sections.section_name'
                    },
                    {
                        data: 'academic_years.title',
                        name: 'academic_years.title'
                    },
                    {
                        data: 'is_valid',
                        name: 'is_valid'
                    },
                    {
                        data: 'active_till',
                        name: 'active_till'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false,
                    //     width: "5%"
                    // }
                ]
            });
        });
    </script>
@endpush
