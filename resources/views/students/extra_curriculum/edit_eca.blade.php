@extends('layouts.master')
@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('eca.index') }}">ECA</a></li>
    </x-breadcrumb>

    @include('components.flash_message')

    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="card-title">Extra Curricular Activities (ECA)</h4>
                    <form id="eca_form" action="{{ route('extra-curriculum.updateEca', $eca->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            {{-- Branch --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="branch_id">Branch</label>
                                    <select class="form-control @error('branch_id') is-invalid @enderror" id="branch_id"
                                        name="branch_id">
                                        <option value="">Select Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ $eca->student->branch_id == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->br_name }}</option>
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
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}"
                                                {{ $eca->student->active_class->branch_class_sections->class_id == $class->id ? 'selected' : '' }}>
                                                {{ $class->class_name }}</option>
                                        @endforeach
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
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ $eca->student->active_class->branch_class_sections->section_id == $section->id ? 'selected' : '' }}>
                                                {{ $section->section_name }}</option>
                                        @endforeach
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
                                        @foreach ($eca_students as $student)
                                            <option value="{{ $student->id }}"
                                                {{ $eca->student_id == $student->id ? 'selected' : '' }}>
                                                {{ $student->first_name }} {{ $student->middle_name }}
                                                {{ $student->last_name }}</option>
                                        @endforeach
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
                                        value="{{ $eca->activity_name }}" required>
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
                                        <option value="sports" {{ $eca->activity_type == 'sports' ? 'selected' : '' }}>
                                            Sports</option>
                                        <option value="cultural" {{ $eca->activity_type == 'cultural' ? 'selected' : '' }}>
                                            Cultural
                                        </option>
                                        <option value="academic" {{ $eca->activity_type == 'academic' ? 'selected' : '' }}>
                                            Academic
                                        </option>
                                        <option value="other" {{ $eca->activity_type == 'other' ? 'selected' : '' }}>
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
                                        placeholder="Enter activity name" value="{{ $eca->custom_activity_name }}">
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
                                        value="{{ $eca->activity_date }}" required>
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
                                        <option value="grade" {{ $eca->marks_type == 'grade' ? 'selected' : '' }}>Grade
                                        </option>
                                        <option value="marks" {{ $eca->marks_type == 'marks' ? 'selected' : '' }}>Marks
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
                                        <option value="A+" {{ $eca->grade == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A" {{ $eca->grade == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $eca->grade == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ $eca->grade == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ $eca->grade == 'D' ? 'selected' : '' }}>D</option>
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
                                        value="{{ $eca->total_marks }}">
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
                                        value="{{ $eca->obtained_marks }}">
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
                                        placeholder="Enter remarks" required>{{ $eca->remarks }}</textarea>
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
                            <input type="hidden" id="activity_id" name="id" value="{{ $eca->id }}" />

                            <!-- Buttons -->
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                <a href="{{ route('eca.index') }}" class="btn btn-light bg-gradient waves-effect waves-light"
                                    id="cancel_edit">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
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

            const eca = @json($eca);

            // Set activity type and custom activity name if it's not a predefined option
            const predefinedOptions = ['sports', 'cultural', 'academic', 'other'];
            if (eca.activity_type && !predefinedOptions.includes(eca.activity_type)) {
                $('#activity_type').val('other');
                $('#custom_activity_name').val(eca.activity_type);
            }

            // Show attachment preview if it exists
            if (eca.attachment) {
                $('#attachment_preview').attr('src', '{{ asset('storage') }}/' + eca.attachment).removeClass(
                    'd-none');
            }

            // Initial setup of conditional fields
            toggleCustomActivity();
            toggleMarksTypeFields();

            // Event listeners
            $('#activity_type').on('change', toggleCustomActivity);
            $('#marks_type').on('change', toggleMarksTypeFields);

            // Image preview on file selection
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
    </script>
@endpush
