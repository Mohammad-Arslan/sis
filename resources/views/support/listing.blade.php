<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Support List</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="branch_id_filter">
                                <option value="">Please select</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label class="form-label">Branch</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select filter" id="priority_filter">
                                <option value="">Please select</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                            <label class="form-label">Priority</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control filter"
                                   data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" id="from_date">
                            <label class="form-label">From</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control filter"
                                   data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" id="to_date">
                            <label class="form-label">To</label>
                        </div>
                    </div>
                </div>
                <table id="SupportDatatable" class="table table-bordered table-striped align-middle mb-0" style="width:100%">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Prioriry</th>
                        <th>Document</th>
                        <th>URL</th>
                        <th>Branch ID</th>
                        <th>Branch</th>
                        <th>Raised By</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Description</th>
                        <th>Prioriry</th>
                        <th>Document</th>
                        <th>URL</th>
                        <th>Branch ID</th>
                        <th>Branch</th>
                        <th>Raised By</th>
                        <th>Date</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@push('header_scripts')
    <style type="text/css">
        .ql-editor{
            line-height: 2.0 !important;
        }
    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#SupportDatatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                ordering: false,
                searching: false,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: '{{route('support-query.index')}}',
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.branch_id = $('#branch_id_filter').val();
                        d.priority = $('#priority_filter').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'priority',
                        name: 'priority'
                    },
                    {
                        name: 'document_name',
                        data: 'document_name'
                    },
                    {
                        name: 'url',
                        data: 'url'
                    },
                    {
                        data: 'branch_id',
                        name: 'branch_id'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'raised_by',
                        name: 'raised_by'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                ],
            });

            $(document).on('change', '.filter', function() {
                $('#SupportDatatable').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
