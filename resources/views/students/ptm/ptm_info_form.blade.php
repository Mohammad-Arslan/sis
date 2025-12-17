@if (isset($student))
    @permission('create-student-ptm-info')
        <form id="ptmInfoForm" class="row g-3 needs-validation update_student_ptm_form" method="post"
            action="{{ route('ptm.update', $student->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="col-md-6 col-sm-12">
                <div class="input-group form-label-group in-border">
                    <input type="text" class="form-control disable-max-date @error('ptm_date') is-invalid @enderror" id="ptm_date"
                        name="ptm_date"
                        data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                        value="{{ old('ptm_date') }}"
                        required>
                        <div class="text-white input-group-text bg-primary border-primary">
                            <i class="ri-calendar-2-line"></i>
                        </div>
                    <label for="ptm_date" class="form-label">PTM Date <span class="text-danger">*</span></label>
                    @if ($errors->has('ptm_date'))
                        <div class="invalid-feedback">{{ $errors->first('ptm_date') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="form-select @error('ptm_type') is-invalid @enderror" id="ptm_type" name="ptm_type"
                        required>
                        <option value="">Select PTM Type</option>
                        <option value="regular"
                            {{ old('ptm_type') == 'regular' ? 'selected' : '' }}>
                            Regular</option>
                        <option value="special"
                            {{ old('ptm_type') == 'special' ? 'selected' : '' }}>
                            Special</option>
                    </select>
                    <label for="ptm_type" class="form-label">PTM Type <span class="text-danger">*</span></label>
                    @error('ptm_type')
                        <div class="invalid-feedback">{{ $errors->first('ptm_type') }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="form-label-group in-border">
                    <textarea class="form-control @error('discussion_summary') is-invalid @enderror" id="discussion_summary"
                        name="discussion_summary" rows="3" placeholder="Enter discussion summary" required>{{ old('discussion_summary') }}</textarea>
                    <label for="discussion_summary" class="form-label">Discussion Summary <span
                            class="text-danger">*</span></label>
                    @error('discussion_summary')
                        <div class="invalid-feedback">{{ $errors->first('discussion_summary') }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="form-label-group in-border">
                    <textarea class="form-control @error('outcomes') is-invalid @enderror" id="outcomes" name="outcomes" rows="3"
                        placeholder="Enter outcomes" required>{{ old('outcomes') }}</textarea>
                    <label for="outcomes" class="form-label">Outcomes <span class="text-danger">*</span></label>
                    @error('outcomes')
                        <div class="invalid-feedback">{{ $errors->first('outcomes') }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="form-label-group in-border">
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                        placeholder="Enter additional notes" required>{{ old('notes') }}</textarea>
                    <label for="notes" class="form-label">Additional Notes <span class="text-danger">*</span></label>
                    @error('notes')
                        <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                    @enderror
                </div>
            </div>

            <input type="hidden" id="ptm_id" name="id" value="{{ old('id') }}" />

            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <button type="button" class="btn btn-light bg-gradient waves-effect waves-light" id="ptm_cancel_edit">Cancel</button>
            </div>
        </form>
    @endpermission
@endif
