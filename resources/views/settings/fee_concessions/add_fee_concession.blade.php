<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Fee Concession</h4>
        </div><!-- end card header -->
        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('fee-concessions.store') }}"
                    method="post">
                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3 @if ($errors->has('fee_concession_type_id')) is-invalid @endif"
                                id="feeConcessionType" name="fee_concession_type_id" required>
                                <option value="" disabled selected>Fee Concessions</option>
                                @foreach ($fee_concessions_type as $fee_concession_type)
                                    <option value="{{ $fee_concession_type->id }}"
                                        @if (old('fee_concession_type_id') == $fee_concession_type->id) {{ 'selected' }} @endif>
                                        {{ $fee_concession_type->name }}</option>
                                @endforeach
                            </select>
                            <label for="feeConcessionType" class="form-label">Fee Concession</label>
                            <div class="invalid-tooltip"></div>
                            <div class="invalid-tooltip">
                                @if ($errors->has('fee_concession_type_id'))
                                    The fee concession type has already been taken!
                                @else
                                    Kindly select the fee concession!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text"
                                class="form-control @if ($errors->has('concession_percentage')) is-invalid @endif"
                                id="concession_percentage" name="concession_percentage"
                                placeholder="Please enter concession_percentage"
                                value="{{ old('concession_percentage') }}" required>
                            <label for="concession_percentage" class="form-label">Concession Percentage</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('concession_percentage'))
                                    {{ $errors->first('concession_percentage') }}
                                @else
                                    Concession percentage is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select mb-3" id="companyName" name="company_id"
                                data-target="branch_id" data-url="{{ route('list-branches') }}" required>
                                <option value="" disabled selected>Companies options</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        @if (old('company_id') == $company->id) {{ 'selected' }} @endif>
                                        {{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <label for="companyName" class="form-label">Company</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select mb-3" id="branchName" name="branch_id"
                                data-target="academic_year_id" data-url="{{ route('list-academic-years') }}"
                                aria-label="Branch select" required>
                                <option value="" disabled selected>Branches options</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if (old('branch_id') == $branch->id) {{ 'selected' }} @endif>
                                        {{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branchName" class="form-label">Branch</label>
                            <div class="invalid-tooltip">Kindly select the branch name!</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                                id="branch" name="academic_year_id" aria-label="Country select" required>
                                <option value="">Please select</option>
                                @if (old('academic_year_id') !== null)
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}"
                                            {{ $academic_year->id == old('academic_year_id') ? 'selected' : '' }}>
                                            {{ $academic_year->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="branch" class="form-label">Academic year</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('academic_year_id'))
                                    {{ $errors->first('academic_year_id') }}
                                @else
                                    Academic year is required!
                                @endif
                            </div>
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
