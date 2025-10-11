<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Branch Security Charges</h4>
        </div><!-- end card header -->
        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('branch-securities.store') }}"
                    method="post">
                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control @if ($errors->has('amount')) is-invalid @endif"
                                id="amount" name="amount"
                                placeholder="Please enter amount"
                                value="{{ old('amount') }}" required>
                            <label for="amount" class="form-label">Amount</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('amount'))
                                    {{ $errors->first('amount') }}
                                @else
                                    Amount is required!
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="branchName" name="branch_id"
                                aria-label="Branch select" required>
                                <option value="" disabled selected>Branches options</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if (old('branch_id') == $branch->id) {{ 'selected' }} @endif>
                                        {{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branchName" class="form-label">Branches</label>
                            <div class="invalid-tooltip">Kindly select the branch name!</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id"
                                name="academic_year_id" aria-label="Academic select" required>
                                <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}"
                                            @if (old('academic_year_id') == $academic_year->id) {{ 'selected' }} @elseif ($academic_year->active == 1) {{ 'selected'}} @else {{ '' }}  @endif>
                                            {{ $academic_year->title }}
                                        </option>
                                    @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic year</label>
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
                            <input type="text" class="form-control"
                                id="remarks" name="remarks"
                                placeholder="Please enter remarks"
                                value="{{ old('remarks') }}">
                            <label for="remarks" class="form-label">Remarks</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <input type="hidden" class="form-control" value="{{ auth()->user()->id }}" name="created_by" id="created_by">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
