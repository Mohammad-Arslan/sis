<div class="table-responsive">
    <table class="table table-bordered" id="extraCurriculumInfoTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Activity Name</th>
                <th>Activity Type</th>
                <th>Activity Date</th>
                <th>Remarks</th>
                <th>Marks/Grade</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Activity Name</th>
                <th>Activity Type</th>
                <th>Activity Date</th>
                <th>Remarks</th>
                <th>Marks/Grade</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
@push('footer_scripts')
    @php
        $student_id = isset($student) ? $student->id : (isset($student_id) ? $student_id : null);
    @endphp
    <script>
        $(document).ready(function() {
            @if(isset($student))
            const table = $('#extraCurriculumInfoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('extra-curriculum.getExtraCurriculumInfo', ['student_id' => $student_id]) }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'activity_name',
                        name: 'activity_name'
                    },
                    {
                        data: 'activity_type',
                        name: 'activity_type'
                    },
                    {
                        data: 'activity_date',
                        name: 'activity_date'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'marks',
                        name: 'marks',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'attachment',
                        name: 'attachment',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            @endif

            // Hide/show fields for marks type
            function toggleMarksFields(type) {
                if (type === 'grade') {
                    $('#grade_container').show();
                    $('#marks_container').hide();
                } else if (type === 'marks') {
                    $('#grade_container').hide();
                    $('#marks_container').show();
                } else {
                    $('#grade_container, #marks_container').hide();
                }
            }

            // Show/hide custom activity name
            $('#activity_type').on('change', function() {
                if ($(this).val() === 'other') {
                    $('#custom_activity_container').show();
                } else {
                    $('#custom_activity_container').hide();
                }
            });

            $('#marks_type').on('change', function() {
                toggleMarksFields($(this).val());
            });

            // Form field population on edit
            function populateActivityFormFields(data) {
                $('#activity_id').val(data.id);
                $('#activity_name').val(data.activity_name);

                // Check if activity_type is not one of the predefined types
                const predefinedTypes = ['sports', 'cultural', 'academic'];
                if (!predefinedTypes.includes(data.activity_type)) {
                    $('#activity_type').val('other').trigger('change');
                    $('#custom_activity_name').val(data.activity_type);
                } else {
                    $('#activity_type').val(data.activity_type).trigger('change');
                    $('#custom_activity_name').val('');
                }

                $('#activity_date').val(data.activity_date);
                $('#remarks').val(data.remarks);

                $('#marks_type').val(data.marks_type).trigger('change');

                if (data.marks_type === 'grade') {
                    $('#grade').val(data.grade);
                } else if (data.marks_type === 'marks') {
                    $('#total_marks').val(data.total_marks);
                    $('#obtained_marks').val(data.obtained_marks);
                }

                if (data.attachment) {
                    $('#attachment_preview').attr('src', '{{ asset('storage') }}' + '/' + data.attachment).show();
                    $('#attachment_preview').removeClass('d-none');
                } else {
                    $('#attachment_preview').hide();
                    $('#attachment_preview').addClass('d-none');
                }

                $('#extraCurriculumInfoForm').show();
            }

            // Edit button
            $('#extraCurriculumInfoTable').on('click', '.edit', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('extra-curriculum.edit', ['id' => ':id']) }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        populateActivityFormFields(response);
                    }
                });
            });

            // Delete button
            $('#extraCurriculumInfoTable').on('click', '.delete', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('extra-curriculum.destroy', ['id' => ':id']) }}".replace(':id',
                        id),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload(null, false);
                        $('#extraCurriculumAlert').html('<div class="alert alert-success">' +
                            response.message + '</div>');
                        setTimeout(() => $('#extraCurriculumAlert').html(''), 1500);
                    }
                });
            });

            // Preview image on file select
            $('#attachment').on('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#attachment_preview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#attachment_preview').hide();
                }
            });

            $('#cancel_edit').on('click', function() {
                $('#activity_id').val('');
                $('#activity_name').val('');
                $('#activity_type').val('');
                $('#custom_activity_name').val('');
                $('#activity_date').val('');
                $('#remarks').val('');
                $('#marks_type').val('');
                $('#grade').val('');
                $('#total_marks').val('');
                $('#obtained_marks').val('');
                $('#attachment').val('');
                $('#attachment_preview').hide();
                $('#attachment_preview').addClass('d-none');
            });
        });
    </script>
@endpush
