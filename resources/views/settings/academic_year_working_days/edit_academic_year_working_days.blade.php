<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Academic Year</h4>
            <div class="flex-shrink-0">
                @permission('add-language')
                    <a href="{{ route('academic-year-working-days.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Academic Year
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('academic-year-working-days.update', $academicYearWorkingDays->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="academic_year_working_days_id"
                        value="{{ $academicYearWorkingDays->id }}">
                    <div class="col-md-6 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('branch_id')) is-invalid @endif"
                                id="branch" name="branch_id" aria-label="Country select" required>
                                <option value="">Please select</option>
                                @if (isset($branch))
                                    <option value="{{ $branch['id'] }}"
                                        {{ $branch['id'] == $academicYearWorkingDays->branch_id ? 'selected' : '' }}>
                                        {{ $branch['br_name'] }}</option>
                                @else
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ $branch->id == $academicYearWorkingDays->branch_id ? 'selected' : '' }}>
                                            {{ $branch->br_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="branch" class="form-label">Branch</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('branch_id'))
                                    {{ $errors->first('branch_id') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                id="academic_year_id" name="academic_year_id" aria-label="Country select">
                                <option value="">Please select</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        {{ $academic_year->id == $academicYearWorkingDays->academic_year_id ? 'selected' : '' }}>
                                        {{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="branch" class="form-label">Academic year</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('academic_year_id'))
                                    {{ $errors->first('sacademic_year_id') }}
                                @else
                                    Academic year is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('term_id')) is-invalid @endif"
                                id="region" name="term_id" aria-label="Country select" required>
                                <option value="">Please select</option>
                                @if (isset($terms))
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}"
                                            {{ $term->id == $academicYearWorkingDays->term_id ? 'selected' : '' }}>
                                            {{ $term->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="term_id" class="form-label">Term</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('term_id'))
                                    {{ $errors->first('term_id') }}
                                @else
                                    Term is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="working_days" name="working_days"
                                placeholder="Please enter name" value="{{ $academicYearWorkingDays->working_days }}"
                                required>
                            <label for="working_days" class="form-label">Working Days</label>
                            <div class="invalid-tooltip">Working Days is required!</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="remarks" id="remarks" placeholder="Enter remarks here...">{{ $academicYearWorkingDays->remarks }}</textarea>
                            <label for="remarks" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
