<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Documents List</h4>
            </div><!-- end card header -->

            <div class="card-body">
                @if(!isset($student))
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id_filter">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Academic Year</label>
                            </div>
                        </div>
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
                                <select class="filter form-select" id="attachment_type_id_filter">
                                    <option value="">Please select</option>
                                    @foreach ($attachment_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Type</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="state_id_filter">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Province</label>
                            </div>
                        </div>
                    </div>
                @endif
                <table id="generalDocumentDatatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                    <tr>
                        <th>Document Name</th>
                        @if(!isset($student))
                            <th>Branch</th>
                            <th>Class</th>
                            <th>Subject</th>
                        @endif
                        <th>Type</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th>Remarks</th>
                        @if(!isset($student))
                            <th>Status</th>
                        @endif
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Document Name</th>
                        @if(!isset($student))
                            <th>Branch</th>
                            <th>Class</th>
                            <th>Subject</th>
                        @endif
                        <th>Type</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th>Remarks</th>
                        @if(!isset($student))
                            <th>Status</th>
                        @endif
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            let url = '';

            @if(isset($student))
                url = "{{ route('general-document.index') }}"+'?student_id={{$student->id}}&general_document_id={{request()->query('general_document_id')}}';
            @else
                url = "{{ route('general-document.index') }}";
            @endif

            $('#generalDocumentDatatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                ordering: false,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: url,
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id_filter').val();
                        d.branch_id = $('#branch_id_filter').val();
                        //d.com_class_id = $('#com_class_id').val();
                        //d.subject_id = $('#subject_id').val();
                        d.state_id = $('#state_id_filter').val();
                        d.attachment_type_id = $('#attachment_type_id_filter').val();
                    }
                },
                columns: [
                    {
                        data: 'document_name',
                        name: 'document_name'
                    },
                    @if(!isset($student))
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'subject_name',
                        name: 'subject_name'
                    },
                    @endif
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'uploaded_by',
                        name: 'uploaded_by'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    @if(!isset($student))
                    {
                        data: 'status',
                        name: 'status',
                        width: "5%",
                        sClass: 'text-center'
                    },
                    @endif
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    },
                ],
            });

            $(document).on('change', '.filter', function() {
                $('#generalDocumentDatatable').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
