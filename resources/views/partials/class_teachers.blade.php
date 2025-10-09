<table id="class-section-teachers-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Academic Year</th>
            <th>Class</th>
            <th>Section</th>
            <th>Teacher</th>
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
            <th>Teacher</th>
            <th>Teacher Type</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Active Till</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#class-section-teachers-data-table').DataTable({
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
                ajax: "{{ route('class-section-teachers', [ 'branch_class_section_id' => $branch_class_section_id ]) }}",
                columns: [
                    {
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
                        name: 'branch_class_section.sections.section_name'
                    },
                    {
                        data: 'employee_full_name',
                        name: 'employee_full_name'
                    },
                    {
                        data: 'teacher_type.name',
                        name: 'teacher_type.name'
                    },
                    {
                        data: 'subject_name',
                        name: 'subject_name'
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
                        width: '4%'
                    }
                ]
            });
        });
    </script>
@endpush
