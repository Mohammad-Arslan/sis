<div id='guardianAlert' role="alert"></div>

@include('branches.add_branch_classess_form')
<div class="border my-3 border-dashed"></div>

<div class="col-lg-12">

    <table id="branch-classes-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
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

</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#branch-classes-table').DataTable({
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
                ajax: "{{ route('branch-class-sections.index', ['from_branch_setup_teacher' => 1]) }}&branch_id={{Request::route('branch')['id']}}",
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
                    },
                ]
            });


        });
    </script>
@endpush
