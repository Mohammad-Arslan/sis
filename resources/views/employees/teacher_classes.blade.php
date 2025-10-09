<div class="col-lg-12">

    <table id="class-teacher-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
        style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Academic Year</th>
                <th>Class</th>
                <th>Section</th>
                <th>Teacher Type</th>
                <th>Subject</th>
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
                <th>Academic Year</th>
                <th>Class</th>
                <th>Section</th>
                <th>Teacher Type</th>
                <th>Subject</th>
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

            $('#class-teacher-data-table').DataTable({
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
                ajax: "{{ route('class-teachers.index', ['employee_id' => isset($employee) ? $employee[0]->id : 0]) }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'academic_year.title',
                        name: 'academic_year.title'
                    },
                    {
                        data: 'branch_class_section.com_classes.class_name',
                        name: 'branch_class_section.com_classes.class_name'
                    },
                    {
                        data: 'branch_class_section.sections.section_name',
                        name: 'branch_class_section.sections.section_name',
                    },
                    {
                        data: 'teacher_type.name',
                        name: 'teacher_type.name'
                    },
                    {
                        data: 'subject.subject_name',
                        name: 'subject.subject_name'
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
