<table id="{{ $table_id }}" class="table table-bordered table-striped align-middle table-nowrap mb-0"
    style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Branch Name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Branch Name</th>
            <th>Class</th>
            <th>Section</th>
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

            $('#{{ $table_id }}').DataTable({
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
                ajax: "{{ route('branch-class-sections.index', $route_parameters) }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'branches.br_name',
                        name: 'branches.br_name'
                    },
                    {
                        data: 'com_classes.class_name',
                        name: 'com_classes.class_name'
                    },
                    {
                        data: 'sections.section_name',
                        name: 'sections.section_name'
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
