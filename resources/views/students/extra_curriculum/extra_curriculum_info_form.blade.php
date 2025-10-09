<div id='extraCurriculumAlert' role="alert"></div>
@if (isset($student))
    @permission('create-student-extra-curriculum-info')
        <form id="extraCurriculumInfoForm" class="row g-3 needs-validation update_student_extra_curriculum_form" method="post"
            action="{{ route('extra-curriculum.update', $student->id) }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <!-- Activity Name -->
            <div class="col-md-6 col-sm-12">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control @error('activity_name') is-invalid @enderror" id="activity_name"
                        name="activity_name" placeholder="Enter activity name" value="{{ old('activity_name') }}" required>
                    <label for="activity_name" class="form-label">Activity Name <span class="text-danger">*</span></label>
                    @error('activity_name')
                        <div class="invalid-feedback">{{ $errors->first('activity_name') }}</div>
                    @enderror
                </div>
            </div>

            <!-- Activity Type -->
            <div class="col-md-6 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="form-select @error('activity_type') is-invalid @enderror" id="activity_type"
                        name="activity_type" required>
                        <option value="">Select Activity Type</option>
                        <option value="sports" {{ old('activity_type') == 'sports' ? 'selected' : '' }}>Sports</option>
                        <option value="cultural" {{ old('activity_type') == 'cultural' ? 'selected' : '' }}>Cultural
                        </option>
                        <option value="academic" {{ old('activity_type') == 'academic' ? 'selected' : '' }}>Academic
                        </option>
                        <option value="other" {{ old('activity_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <label for="activity_type" class="form-label">Activity Type <span class="text-danger">*</span></label>
                    @error('activity_type')
                        <div class="invalid-feedback">{{ $errors->first('activity_type') }}</div>
                    @enderror
                </div>
            </div>

            <!-- Custom Activity Name -->
            <div class="col-md-6 col-sm-12" id="custom_activity_container" style="display: none;">
                <div class="form-label-group in-border">
                    <input type="text" class="form-control @error('custom_activity_name') is-invalid @enderror"
                        id="custom_activity_name" name="custom_activity_name" placeholder="Enter activity name"
                        value="{{ old('custom_activity_name') }}">
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
                    <input type="date" class="form-control disable-max-date @error('activity_date') is-invalid @enderror"
                        id="activity_date" name="activity_date" placeholder="Enter activity date"
                        value="{{ old('activity_date') }}" required>
                    <div class="text-white input-group-text bg-primary border-primary">
                        <i class="ri-calendar-2-line"></i>
                    </div>
                    <label for="activity_date" class="form-label">Activity Date <span class="text-danger">*</span></label>
                    @error('activity_date')
                        <div class="invalid-feedback">{{ $errors->first('activity_date') }}</div>
                    @enderror
                </div>
            </div>

            <!-- Marks Type -->
            <div class="col-md-6 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="form-select @error('marks_type') is-invalid @enderror" id="marks_type" name="marks_type"
                        required>
                        <option value="">Select Marks Type</option>
                        <option value="grade" {{ old('marks_type') == 'grade' ? 'selected' : '' }}>Grade</option>
                        <option value="marks" {{ old('marks_type') == 'marks' ? 'selected' : '' }}>Marks</option>
                    </select>
                    <label for="marks_type" class="form-label">Marks Type <span class="text-danger">*</span></label>
                    @error('marks_type')
                        <div class="invalid-feedback">{{ $errors->first('marks_type') }}</div>
                    @enderror
                </div>
            </div>

            <!-- Grade Dropdown -->
            <div class="col-md-6 col-sm-12" id="grade_container" style="display: none;">
                <div class="form-label-group in-border">
                    <select class="form-select @error('grade') is-invalid @enderror" id="grade" name="grade">
                        <option value="">Select Grade</option>
                        <option value="A+" {{ old('grade') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A" {{ old('grade') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('grade') == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                    <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                    @error('grade')
                        <div class="invalid-feedback">{{ $errors->first('grade') }}</div>
                    @enderror
                </div>
            </div>

            <!-- Marks Inputs -->
            <div class="col-md-3 col-sm-6" id="marks_container" style="display: none;">
                <div class="form-label-group in-border">
                    <input type="number" class="form-control @error('total_marks') is-invalid @enderror" id="total_marks"
                        name="total_marks" placeholder="Total Marks" value="{{ old('total_marks') }}">
                    <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
                    @error('total_marks')
                        <div class="invalid-feedback">{{ $errors->first('total_marks') }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-3 col-sm-6" id="marks_obtained_container" style="display: none;">
                <div class="form-label-group in-border">
                    <input type="number" class="form-control @error('obtained_marks') is-invalid @enderror"
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
                    <label for="remarks" class="form-label">Remarks <span class="text-danger">*</span></label>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $errors->first('remarks') }}</div>
                    @enderror
                </div>
            </div>

            {{-- Attachment with preview --}}
            <div class="col-md-6 col-sm-12">
                <div class="form-label-group in-border">
                    <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment"
                        name="attachment" accept="image/*">
                    <label for="attachment" class="form-label">Attachment (Optional)</label>
                    @error('attachment')
                        <div class="invalid-feedback">{{ $errors->first('attachment') }}</div>
                    @enderror
                </div>
                <div class="mt-2">
                    <img id="attachment_preview" src="#" alt="Image Preview" class="img-fluid rounded d-none"
                        style="max-height: 200px;">
                </div>
            </div>

            <!-- Hidden ID -->
            <input type="hidden" id="activity_id" name="id" value="{{ old('id') }}" />

            <!-- Buttons -->
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <button type="button" class="btn btn-light bg-gradient waves-effect waves-light" id="cancel_edit">Cancel</button>
            </div>
        </form>
    @endpermission
@endif

@push('footer_scripts')
    <script>
        $(document).ready(function() {
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
    </script>
@endpush
