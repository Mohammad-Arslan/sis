 @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Gradebook History</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="gradebook-history-data-table"
                           class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Academic Year</th>
                            <th>Branch</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Term</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Academic Year</th>
                            <th>Branch</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Term</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
 <div id="progress-report-modal-div">
 </div>

@push('footer_scripts')
    <script type="text/javascript">
        let student_id = {{ $student->id ?? 0 }};

        $(document).on('click', '.fetch_reports', fetchReports);

        function fetchReports(){
            student_id = $('input[name="student_id"]').val();
            $('#gradebook-history-data-table').DataTable().ajax.reload(null, false);
        }


        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#gradebook-history-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: `{{ route('gradebook-history.index') }}`,
                    data: {'student_id' : student_id},
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'student.full_name',
                        name: 'student.full_name'
                    },
                    {
                        data: 'student_behaviour_skill.academic_year.title',
                        name: 'student_behaviour_skill.academic_year.title'
                    },
                    {
                        data: 'student_behaviour_skill.branch.br_name',
                        name: 'student_behaviour_skill.branch.br_name'
                    },
                    {
                        data: 'student_behaviour_skill.com_class.class_name',
                        name: 'student_behaviour_skill.com_class.class_name'
                    },
                    {
                        data: 'student_behaviour_skill.section.section_name',
                        name: 'student_behaviour_skill.section.section_name'
                    },
                    {
                        data: 'student_behaviour_skill.term.name',
                        name: 'student_behaviour_skill.term.name'
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
                        sClass: "text-center"
                    },
                ]
            });

            $(document).on('click', '.show-progress-report-modal', function(e) {
                const target = $(this).data('target');
                const url = $(this).data('url');
                console.log('show modal', target, url);
                $.ajax({
                    url: url,
                    type: "GET",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        $('#progress-report-modal-div').html(data);
                        $(target).modal('show');
                    },
                    error: function() {
                    },
                    beforeSend: function() {
                    },
                    complete: function() {
                    }
                });
            });
        });
    </script>
@endpush
