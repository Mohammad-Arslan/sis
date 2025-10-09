<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Subject Marks List</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="form-select filter" id="academic_year_id_list" name="academic_year_id_list">
                            <option value="">Please select an Academic Year</option>
                            @foreach ($academic_years as $academic_year)
                                <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                            @endforeach
                        </select>
                        <label for="section" class="form-label">Academic Year <span
                                class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="form-select filter" id="term_id_list" name="term_id_list">
                            <option value="">Please select a Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                        <label for="section" class="form-label">Term <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="load-select form-select filter" data-target="class_id_list"
                            data-url="{{ route('list-branch-classes') }}" id="branch_id_list" name="branch_id_list"
                            aria-label="Branch select">
                            <option value="">Please select</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                            @endforeach
                        </select>
                        <label for="branchId" class="form-label">Branch <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select
                            class="load-select form-select filter @if ($errors->has('class_id_list')) is-invalid @endif"
                            data-target="subject_id_list" id="class_id_list" name="class_id_list">
                            <option value="">Please select a class</option>
                            @if (isset($branch_classes))
                                @foreach ($branch_classes as $class)
                                    <option value="{{ $class->id }}">{{ $class['com_classes']['class_name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="form-select filter @if ($errors->has('subject_id_list')) is-invalid @endif"
                            id="subject_id_list" name="subject_id_list" aria-label="Select Subject">
                            <option value="">Please select a subject</option>
                            @if (isset($subjects))
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="sectionID" class="form-label">Subject <span class="text-danger">*</span></label>
                    </div>
                </div>
            </div>
            <table id="subject-marks-datatable"
                class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Term</th>
                        <th>Branch</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                        <th>Marks</th>
                        <th>Remarks</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Academic Year</th>
                        <th>Term</th>
                        <th>Branch</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                        <th>Marks</th>
                        <th>Remarks</th>
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

            $('#subject-marks-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                paging: true,
                pageLength: 10,

                scrollX: true,
                language: {
                    search: "",
                    processing: `<img class='ucs_loader' src='{{ asset('loader.gif') }}' />`,
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('subject-marks-setup.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id_list').val();
                        d.branch_id = $('#branch_id_list').val();
                        d.class_id = $('#class_id_list').val();
                        d.subject_id = $('#subject_id_list').val();
                        d.term_id = $('#term_id_list').val();
                    }
                },
                columns: [{
                        data: 'academic_year.title',
                        name: 'academic_year.title'
                    },
                    {
                        data: 'term.name',
                        name: 'term.name'
                    },
                    {
                        data: 'branch.br_name',
                        name: 'branch.br_name'
                    },
                    {
                        data: 'com_class.class_name',
                        name: 'com_class.class_name'
                    },
                    {
                        data: 'subject.subject_name',
                        name: 'subject.subject_name'
                    },
                    {
                        data: 'assessment_level_one.name',
                        name: 'assessment_level_one.name'
                    },
                    {
                        data: 'assessment_level_two.name',
                        name: 'assessment_level_two.name'
                    },
                    {
                        data: 'level_three_name',
                        name: 'level_three_name'
                    },
                    {
                        data: 'marks',
                        name: 'marks'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
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

            $(document).on('change', '.filter', function() {
                $('#subject-marks-datatable').DataTable().ajax.reload(null, false);
            });

            $('#branch_id_list').on('change', function() {
                $('#subject_id_list').find('option').not(':first').remove();

                let route = 'get-class-subjects/' + $(this).val();
                $('#class_id_list').data('url', route);
            });
        });
    </script>
@endpush
