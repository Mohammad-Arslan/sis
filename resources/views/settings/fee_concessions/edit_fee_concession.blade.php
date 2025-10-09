<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Fee Concession</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('fee-concessions.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-fee-concessions')
                    <a href="{{ route('fee-concessions.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Fee Concession
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('fee-concessions.update', $feeConcession->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <div class="form-label-group in-border">
                                <select class="form-select mb-3" id="feeConcessionName" name="fee_concession_type_id"
                                    required>
                                    <option value="" selected>Fee Concessions</option>
                                    @foreach ($fee_concessions_type as $fee_concession_type)
                                        <option value="{{ $fee_concession_type->id }}"
                                            @if ($feeConcession->fee_concession_type_id == $fee_concession_type->id) {{ 'selected' }} @endif>
                                            {{ $fee_concession_type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="feeConcessionName" class="form-label">Fee Concession</label>
                                <div class="invalid-tooltip">Kindly select the fee concession!</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control" id="concession_percentage"
                                name="concession_percentage" placeholder="Please enter concession_percentage"
                                value="{{ $feeConcession->concession_percentage }}" required>
                            <label for="concession_percentage" class="form-label">Concession Percentage</label>
                            <div class="invalid-tooltip">Concession Percentage is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select mb-3" id="companyName" name="company_id"
                                data-target="branch_id" data-url="{{ route('list-branches') }}" required>
                                <option value="" selected>Companies options</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        @if ($feeConcession->company_id == $company->id) {{ 'selected' }} @endif>
                                        {{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <label for="companyName" class="form-label">Companies</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select mb-3" id="branchName" name="branch_id"
                                data-target="academic_year_id" data-url="{{ route('list-academic-years') }}"
                                required>
                                <option value="" selected>Branches options</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if ($feeConcession->branch_id == $branch->id) {{ 'selected' }} @endif>
                                        {{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branchName" class="form-label">Branches</label>
                            <div class="invalid-tooltip">Kindly select the branch name!</div>
                        </div>
                    </div>
                    {{-- {{ dd($academic_years[0]['id'] == $feeConcession->academic_year_id) }} --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="branch"
                                name="academic_year_id" aria-label="Country select" required>
                                <option value="">Please select</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        {{ $academic_year->id == $feeConcession->academic_year_id ? 'selected' : '' }}>
                                        {{ $academic_year->title }}
                                    </option>
                                @endforeach
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
                        <a href="{{ route('fee-concessions.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
