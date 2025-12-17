@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">ECA</li>
    </x-breadcrumb>

    @include('components.flash_message')

    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="card-title">Extra Curricular Activities (ECA)</h4>
                    <form id="eca_form" action="{{ route('extra-curriculum.storeEca') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- Branch --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="branch_id">Branch</label>
                                    <select class="form-control @error('branch_id') is-invalid @enderror" id="branch_id"
                                        name="branch_id">
                                        <option value="">Select Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <div class="invalid-feedback">{{ $errors->first('branch_id') }}</div>
                                    @enderror
                                </div>
                            </div>


                            {{-- Class --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="class_id">Class</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror" id="class_id"
                                        name="class_id">
                                        <option value="">Select Class</option>
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $errors->first('class_id') }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Section --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="section_id">Section</label>
                                    <select class="form-control @error('section_id') is-invalid @enderror" id="section_id"
                                        name="section_id">
                                        <option value="">Select Section</option>
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $errors->first('section_id') }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Student --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="student_id">Student</label>
                                    <select class="form-control @error('student_id') is-invalid @enderror" id="student_id"
                                        name="student_id">
                                        <option value="">Select Student</option>
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $errors->first('student_id') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Activity Name -->
                            <div class="col-md-6 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control @error('activity_name') is-invalid @enderror"
                                        id="activity_name" name="activity_name" placeholder="Enter activity name"
                                        value="{{ old('activity_name') }}" required>
                                    <label for="activity_name" class="form-label">Activity Name <span
                                            class="text-danger">*</span></label>
                                    @error('activity_name')
                                        <div class="invalid-feedback">{{ $errors->first('activity_name') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Activity Type -->
                            <div class="col-md-6 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @error('activity_type') is-invalid @enderror"
                                        id="activity_type" name="activity_type" required>
                                        <option value="">Select Activity Type</option>
                                        <option value="sports" {{ old('activity_type') == 'sports' ? 'selected' : '' }}>
                                            Sports</option>
                                        <option value="cultural"
                                            {{ old('activity_type') == 'cultural' ? 'selected' : '' }}>
                                            Cultural
                                        </option>
                                        <option value="academic"
                                            {{ old('activity_type') == 'academic' ? 'selected' : '' }}>
                                            Academic
                                        </option>
                                        <option value="other" {{ old('activity_type') == 'other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                    <label for="activity_type" class="form-label">Activity Type <span
                                            class="text-danger">*</span></label>
                                    @error('activity_type')
                                        <div class="invalid-feedback">{{ $errors->first('activity_type') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Custom Activity Name -->
                            <div class="col-md-6 col-sm-12" id="custom_activity_container" style="display: none;">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @error('custom_activity_name') is-invalid @enderror"
                                        id="custom_activity_name" name="custom_activity_name"
                                        placeholder="Enter activity name" value="{{ old('custom_activity_name') }}">
                                    <label for="custom_activity_name" class="form-label">Custom Activity Name <span
                                            class="text-danger">*</span></label>
                                    @error('custom_activity_name')
                                        <div class="invalid-feedback">{{ $errors->first('custom_activity_name') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Activity Date -->
                            <div class="col-md-6 col-sm-12">
                                <div class="input-group form-label-group in-border">
                                    <input type="date"
                                        class="form-control disable-max-date @error('activity_date') is-invalid @enderror"
                                        id="activity_date" name="activity_date" placeholder="Enter activity date"
                                        value="{{ old('activity_date') }}" required>
                                    <div class="text-white input-group-text bg-primary border-primary">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="activity_date" class="form-label">Activity Date <span
                                            class="text-danger">*</span></label>
                                    @error('activity_date')
                                        <div class="invalid-feedback">{{ $errors->first('activity_date') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Marks Type -->
                            <div class="col-md-6 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @error('marks_type') is-invalid @enderror" id="marks_type"
                                        name="marks_type" required>
                                        <option value="">Select Marks Type</option>
                                        <option value="grade" {{ old('marks_type') == 'grade' ? 'selected' : '' }}>Grade
                                        </option>
                                        <option value="marks" {{ old('marks_type') == 'marks' ? 'selected' : '' }}>Marks
                                        </option>
                                    </select>
                                    <label for="marks_type" class="form-label">Marks Type <span
                                            class="text-danger">*</span></label>
                                    @error('marks_type')
                                        <div class="invalid-feedback">{{ $errors->first('marks_type') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Grade Dropdown -->
                            <div class="col-md-6 col-sm-12" id="grade_container" style="display: none;">
                                <div class="form-label-group in-border">
                                    <select class="form-select @error('grade') is-invalid @enderror" id="grade"
                                        name="grade">
                                        <option value="">Select Grade</option>
                                        <option value="A+" {{ old('grade') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A" {{ old('grade') == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ old('grade') == 'D' ? 'selected' : '' }}>D</option>
                                    </select>
                                    <label for="grade" class="form-label">Grade <span
                                            class="text-danger">*</span></label>
                                    @error('grade')
                                        <div class="invalid-feedback">{{ $errors->first('grade') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Marks Inputs -->
                            <div class="col-md-3 col-sm-6" id="marks_container" style="display: none;">
                                <div class="form-label-group in-border">
                                    <input type="number" class="form-control @error('total_marks') is-invalid @enderror"
                                        id="total_marks" name="total_marks" placeholder="Total Marks"
                                        value="{{ old('total_marks') }}">
                                    <label for="total_marks" class="form-label">Total Marks <span
                                            class="text-danger">*</span></label>
                                    @error('total_marks')
                                        <div class="invalid-feedback">{{ $errors->first('total_marks') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6" id="marks_obtained_container" style="display: none;">
                                <div class="form-label-group in-border">
                                    <input type="number"
                                        class="form-control @error('obtained_marks') is-invalid @enderror"
                                        id="obtained_marks" name="obtained_marks" placeholder="Obtained Marks"
                                        value="{{ old('obtained_marks') }}">
                                    <label for="obtained_marks" class="form-label">Obtained Marks <span
                                            class="text-danger">*</span></label>
                                    @error('obtained_marks')
                                        <div class="invalid-feedback">{{ $errors->first('obtained_marks') }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3"
                                        placeholder="Enter remarks" required>{{ old('remarks') }}</textarea>
                                    <label for="remarks" class="form-label">Remarks <span
                                            class="text-danger">*</span></label>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $errors->first('remarks') }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Attachment with preview --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                        id="attachment" name="attachment" accept="image/*">
                                    <label for="attachment" class="form-label">Attachment (Optional)</label>
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $errors->first('attachment') }}</div>
                                    @enderror
                                </div>
                                <div class="mt-2">
                                    <img id="attachment_preview" src="#" alt="Image Preview"
                                        class="img-fluid rounded d-none" style="max-height: 200px;">
                                </div>
                            </div>

                            <!-- Hidden ID -->
                            <input type="hidden" id="activity_id" name="id" value="{{ old('id') }}" />

                            <!-- Buttons -->
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                <button type="button" class="btn btn-light bg-gradient waves-effect waves-light"
                                    id="cancel_edit">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="extraCurriculumInfoTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Branch</th>
                                    <th>Class</th>
                                    <th>Section</th>
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
                                    <th>Student Name</th>
                                    <th>Branch</th>
                                    <th>Class</th>
                                    <th>Section</th>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function() {
            // Fetch Classes
            $('#branch_id').change(function() {
                let branchId = $(this).val();
                $('#class_id').html('<option value="">Loading...</option>');
                $('#section_id').html('<option value="">Select Section</option>');
                $('#student_id').html('<option value="">Select Student</option>');

                if (branchId) {
                    $.ajax({
                        url: "{{ route('get.student.class.section') }}",
                        method: "GET",
                        data: {
                            branch_id: branchId
                        },
                        success: function(response) {
                            $('#class_id').html('<option value="">Select Class</option>');
                            $.each(response.classes, function(key, value) {
                                $('#class_id').append('<option value="' + value.id +
                                    '">' + value.class_name + '</option>');
                            });
                        }
                    });
                }
            });

            // Fetch Sections
            $('#class_id').change(function() {
                let branchId = $('#branch_id').val();
                let classId = $(this).val();
                $('#section_id').html('<option value="">Loading...</option>');
                $('#student_id').html('<option value="">Select Student</option>');

                if (branchId && classId) {
                    $.ajax({
                        url: "{{ route('get.student.class.section') }}",
                        method: "GET",
                        data: {
                            branch_id: branchId,
                            class_id: classId
                        },
                        success: function(response) {
                            $('#section_id').html('<option value="">Select Section</option>');
                            $.each(response.sections, function(key, value) {
                                $('#section_id').append('<option value="' + value.id +
                                    '">' + value.section_name + '</option>');
                            });
                        }
                    });
                }
            });

            // Fetch Students
            $('#section_id').change(function() {
                let branchId = $('#branch_id').val();
                let classId = $('#class_id').val();
                let sectionId = $(this).val();
                $('#student_id').html('<option value="">Loading...</option>');

                if (branchId && classId && sectionId) {
                    $.ajax({
                        url: "{{ route('get.student.class.section') }}",
                        method: "GET",
                        data: {
                            branch_id: branchId,
                            class_id: classId,
                            section_id: sectionId
                        },
                        success: function(response) {
                            $('#student_id').html('<option value="">Select Student</option>');
                            $.each(response.students, function(key, value) {
                                $('#student_id').append('<option value="' + value.id +
                                    '">' + value.first_name + ' ' + (value
                                        .middle_name ?? '') + ' ' + value
                                    .last_name + '</option>');
                            });
                        }
                    });
                }
            });


            function toggleCustomActivity() {
                let activityType = $('#activity_type').val();
                if (activityType === 'other') {
                    $('#custom_activity_container').show();
                    $('#custom_activity_name').prop('required', true);
                } else {
                    $('#custom_activity_container').hide();
                    $('#custom_activity_name').prop('required', false);
                }
            }

            function toggleMarksTypeFields() {
                let type = $('#marks_type').val();
                if (type === 'grade') {
                    $('#grade_container').show();
                    $('#marks_container, #marks_obtained_container').hide();
                    $('#grade').prop('required', true);
                    $('#total_marks, #obtained_marks').prop('required', false);
                } else if (type === 'marks') {
                    $('#grade_container').hide();
                    $('#marks_container, #marks_obtained_container').show();
                    $('#grade').prop('required', false);
                    $('#total_marks, #obtained_marks').prop('required', true);
                } else {
                    $('#grade_container, #marks_container, #marks_obtained_container').hide();
                    $('#grade, #total_marks, #obtained_marks').prop('required', false);
                }
            }

            // Initial check
            toggleCustomActivity();
            toggleMarksTypeFields();

            // On change
            $('#activity_type').on('change', toggleCustomActivity);
            $('#marks_type').on('change', toggleMarksTypeFields);

            // Image preview
            $('#attachment').on('change', function() {
                const input = this;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#attachment_preview').attr('src', e.target.result).removeClass('d-none');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });
        });

        $(document).ready(function() {
            const table = $('#extraCurriculumInfoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('extra-curriculum.getEcaInfo') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'class',
                        name: 'class'
                    },
                    {
                        data: 'section',
                        name: 'section'
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
                    url: "{{ route('extra-curriculum.edit', ['id' => ':id']) }}".replace(':id',
                        id),
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

            $('#delete_activity').on('click', function() {
                $('#eca_form').trigger('reset');
                
            });

            $('#cancel_edit').on('click', function() {
                $('#activity_id').val('');
                $('#branch_id').val('');
                $('#class_id').html('<option value="">Select Class</option>');
                $('#section_id').html('<option value="">Select Section</option>');
                $('#student_id').html('<option value="">Select Student</option>');
                $('#activity_name').val('');
                $('#activity_type').val('').trigger('change');
                $('#custom_activity_name').val('');
                $('#activity_date').val('');
                $('#remarks').val('');
                $('#marks_type').val('').trigger('change');
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
