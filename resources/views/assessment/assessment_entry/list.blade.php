<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Assessment Entry List</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <table id="assessment-entries-datatable"
                class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        {{-- <th>Academic Year</th>
                    <th>Branch</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th> --}}
                        <th>Term</th>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                        <th>Marks/Grade</th>
                        <th>Remarks</th>
                        <th>Assessment Date</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        {{-- <th>Academic Year</th>
                    <th>Branch</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th> --}}
                        <th>Term</th>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                        <th>Marks/Grade</th>
                        <th>Remarks</th>
                        <th>Assessment Date</th>
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

            $('#assessment-entries-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 50,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('assessment-entry.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.class_id = $('#class_id').val();
                        d.section_id = $('#section_id').val();
                        d.subject_id = $('#subject_id').val();
                        d.term_id = $('#term_id').val();
                    }
                },
                columns: [
                    /*{
                        data: 'academic_year.title',
                        name: 'academic_year.title'
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
                        data: 'section.section_name',
                        name: 'section.section_name'
                    },
                    {
                        data: 'subject.subject_name',
                        name: 'subject.subject_name'
                    },*/
                    {
                        data: 'term.name',
                        name: 'term.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'assessment_level_one.name',
                        name: 'assessment_level_one.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'assessment_level_two.name',
                        name: 'assessment_level_two.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'level_three_name',
                        name: 'level_three_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'grade_marks',
                        name: 'grade_marks',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'assessment_date',
                        name: 'assessment_date',
                        defaultContent: 'N/A',
                        width: "15%"
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

            $(document).on('click', '.fetch_assessments', function() {
                if ($('#academic_year_id').val() == "") {
                    alert('Please select academic year');
                    return false;
                } else if ($('#branch_id').val() == "") {
                    alert('Please select branch');
                    return false;
                } else if ($('#class_id').val() == "") {
                    alert('Please select class');
                    return false;
                } else if ($('#section_id').val() == "") {
                    alert('Please select section');
                    return false;
                } else if ($('#subject_id').val() == "") {
                    alert('Please select subject');
                    return false;
                }

                $('#assessment-entries-datatable').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
