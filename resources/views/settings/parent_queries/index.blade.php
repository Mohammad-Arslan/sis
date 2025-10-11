@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Parent Queries List</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                </select>
                                <label for="designation_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="type" name="type"
                                    placeholder="Type Of Query">
                                    <option value="">Please select</option>
                                    <option value="Complaint">Complaint</option>
                                    <option value="Suggestion">Suggestion</option>
                                </select>
                                <label for="type" class="form-label">Type of Query</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <input class="form-control filter" name="date_range" id="date_range">
                                <label for="date_range" class="form-label">Date</label>
                            </div>
                        </div>
                    </div>
                    <table id="parent-queries-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guardian Name</th>
                                <th>Guardian Mobile</th>
                                <th>Student Name</th>
                                <th>Branch</th>
                                <th>Type of Query</th>
                                <th>Complain Type</th>
                                <th>Remarks</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Guardian Name</th>
                                <th>Guardian Mobile</th>
                                <th>Student Name</th>
                                <th>Branch</th>
                                <th>Type of Query</th>
                                <th>Complain Type</th>
                                <th>Remarks</th>
                                <th>Created At</th>
                            </tr>
                        </tfoot>
                    </table>
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
            $('#date_range').flatpickr({
                mode: "range"
            });
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#parent-queries-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('parent-queries.index') }}",
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.type = $('#type').val();
                        d.branch_id = $('#branch_id').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'guardian.guardian_name',
                        name: 'guardian.guardian_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'guardian.mobile',
                        name: 'guardian.mobile',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'std_name',
                        name: 'std_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'student.branch.br_name',
                        name: 'student.branch.br_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'complain_type',
                        name: 'complain_type',
                        defaultContent: 'N/A'

                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "5%"
                    },
                ]
            });
            $(document).on('change', '.filter', function() {
                $('#parent-queries-data-table').DataTable().ajax.reload(null, false).page('first');
            });
        });
    </script>
@endpush
