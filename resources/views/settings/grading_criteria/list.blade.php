<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Grading Criteria List</h4>
        </div><!-- end card header -->

        <div class="card-body">

            <table id="grading-criteria-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                   style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Grading Key</th>
                    <th>Classes</th>
                    <th>Start (%)</th>
                    <th>End (%)</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Grading Key</th>
                    <th>Classes</th>
                    <th>Start (%)</th>
                    <th>End (%)</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>


        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#grading-criteria-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: `<img class='ucs_loader' src='{{asset('loader.gif')}}' />`,
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('grading-criteria.index') }}",
                columns: [{
                    data: 'id',
                    name: 'id',
                    width: "5%"
                },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'grading_key',
                        name: 'grading_key'
                    },
                    {
                        data: 'classes',
                        name: 'classes'
                    },
                    {
                        data: 'starting_percentage',
                        name: 'starting_percentage'
                    },
                    {
                        data: 'ending_percentage',
                        name: 'ending_percentage'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                        width: "5%",
                        sClass: 'text-center'
                    }
                ]
            });
        });
    </script>
@endpush
