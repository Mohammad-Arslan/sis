<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Branch Security Charges</h4>
            <div class="flex-shrink-0">
                @permission('add-branch-security')
                    <a href="{{ route('branch-securities.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add Branch Security Charges
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('branch-securities.update', $branch_security->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control" id="amount"
                                name="amount" placeholder="Please enter amount"
                                value="{{ $branch_security->amount }}" required>
                            <label for="amount" class="form-label">Amount</label>
                            <div class="invalid-tooltip">Amount is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="branchName" name="branch_id"
                                required>
                                <option value="" selected>Branches options</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if ($branch_security->branch_id == $branch->id) {{ 'selected' }} @endif>
                                        {{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branchName" class="form-label">Branches</label>
                            <div class="invalid-tooltip">Kindly select the branch name!</div>
                        </div>
                    </div>
                    {{-- {{ dd($academic_years[0]['id'] == $branch_security->academic_year_id) }} --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id"
                                name="academic_year_id" aria-label="Academic year select" required>
                                <option value="">Please select</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        {{ $academic_year->id == $branch_security->academic_year_id ? 'selected' : '' }}>
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
                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="remarks"
                                name="remarks" placeholder="Please enter remarks"
                                value="{{ $branch_security->remarks }}">
                            <label for="remarks" class="form-label">Remarks</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <input type="hidden" class="form-control" value="{{ auth()->user()->id }}" name="created_by" id="created_by">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('branch-securities.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
