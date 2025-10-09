<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Branch Academic Year</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('branch-academic-year.store') }}"
                    method="post">
                    @csrf

                    <div class="col-md-4 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <select class="form-select" aria-label="form-select-sm example" id="academic_year_id"
                                name="academic_year_id" required>
                                <option value="" disabled selected>Select academic year</option>
                                @if (isset($academicyears))
                                    @foreach ($academicyears as $academicyear)
                                        <option value="{{ $academicyear->id }}"
                                            {{ old('academic_year_id') == $academicyear->id ? 'selected' : '' }}>
                                            {{ $academicyear->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="academic_year_id" class="form-label">Branch academic year</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('academic_year_id'))
                                    {{ $errors->first('academic_year_id') }}
                                @else
                                    Please choose a branch academic.
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 mt-4">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('start_date')) is-invalid @endif"
                                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                value="{{ old('start_date') }}" name="start_date" id="startDate" required>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="startDate" class="form-label">Start Date</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('start_date'))
                                    {{ $errors->first('start_date') }}
                                @else
                                    Start Date is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 mt-4">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('end_date')) is-invalid @endif"
                                data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                value="{{ old('end_date') }}" name="end_date" id="endDate" required>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="endDate" class="form-label">End Date</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('end_date'))
                                    {{ $errors->first('end_date') }}
                                @else
                                    End Date is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <select class="form-select" aria-label="form-select-sm example" id="branch_id"
                                name="branch_id" required>
                                <option value="" disabled selected>Select branch</option>
                                @if (isset($branches))
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->br_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="branch_id" class="form-label">Branch</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('branch_id'))
                                    {{ $errors->first('branch_id') }}
                                @else
                                    Please choose a branch.
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
