<div id='guardianAlert' role="alert"></div>

<div class="col-lg-12">

    <table id="student-subjects-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Class</th>
            <th>Section</th>
            <th>Academic Year</th>
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
            <th>Class</th>
            <th>Section</th>
            <th>Academic Year</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Active Till</th>
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

            $('#student-subjects-data-table').DataTable({
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
                ajax: "{{ route('class-student-subjects.index', [ 'student' => isset($student) ? $student->id : 0 ]) }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'class_student.branch_class_sections.com_classes.class_name',
                        name: 'class_student.branch_class_sections.com_classes.class_name'
                    },
                    {
                        data: 'class_student.branch_class_sections.sections.section_name',
                        name: 'class_student.branch_class_sections.sections.section_name'
                    },
                    {
                        data: 'class_student.academic_years.title',
                        name: 'class_student.academic_years.title'
                    },
                    {
                        data: 'subject.subject_name',
                        name: 'subject.subject_name'
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
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    }
                ]
            });

            $(document).on('click', '.inactive-record', function(e) {
                e.preventDefault();

                var row_id = $(this).attr('data-id');
                Swal.fire({
                    html: '<div class="mt-3">' +
                        /*'<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +*/
                        '<div class="mt-4 pt-2 fs-15 mx-5">' +
                        '<h4>Are you sure?</h4>' +
                        '<p class="text-muted mx-4 mb-0">Are you Sure You want to Inactive this Record ?</p>' +
                        '</div>' +
                        '</div>',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, InActive It!',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {

                    if (result.isConfirmed) {

                        //get index route and concate the id and send it on put request to make the update route
                        let route = '{{route('class-student-subjects.index')}}'+'/'+row_id;

                        $.ajax({
                            url:route,
                            type: "PUT",
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            cache: false,
                            success: function(data) {
                                $('#student-subjects-data-table').DataTable().ajax.reload(null, false);
                            },
                            error: function() {

                            },
                            beforeSend: function() {

                            },
                            complete: function() {

                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
