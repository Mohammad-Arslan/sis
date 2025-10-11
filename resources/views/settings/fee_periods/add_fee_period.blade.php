<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Fee Period</h4>
        </div>

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('fee-period.store') }}"
                    method="post">
                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('period_name')) is-invalid @endif"
                                name="period_name" id="periodName" placeholder="Please enter your period_name"
                                value="{{ old('period_name') }}">
                            <label for="periodName" class="form-label">Period Name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('period_name'))
                                    {{ $errors->first('period_name') }}
                                @else
                                    Period Name is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('period_description')) is-invalid @endif"
                                name="period_description" id="periodDescription"
                                placeholder="Please enter your period_description"
                                value="{{ old('period_description') }}">
                            <label for="periodDescription" class="form-label">Period Description</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('period_description'))
                                    {{ $errors->first('period_description') }}
                                @else
                                    Period Description is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('company_id')) is-invalid @endif"
                                id="company" name="company_id" data-target="branch_id"
                                data-url="{{ route('list-branches') }}" aria-label="Country select">
                                <option value="">Please select</option>
                                @if (isset($companies))
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="company" class="form-label">Company</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('company_id'))
                                    {{ $errors->first('company_id') }}
                                @else
                                    Company is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('branch_id')) is-invalid @endif"
                                id="branch" name="branch_id" data-target="academic_year_id"
                                data-url="{{ route('list-academic-years') }}" aria-label="Country select">
                                <option value="">Please select</option>
                                @if (isset($branches))
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
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

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="branch"
                                name="academic_year_id" aria-label="Country select">
                                <option value="">Please select</option>

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

                    <div class="col-md-4 col-sm-12">
                        <div class="form-check mb-2">
                            <label class="form-check-label" for="autoGeneratedCheck">
                                <input class="form-check-input" name="auto_generated" type="checkbox"
                                    id="autoGeneratedCheck" checked>
                                Auto Generated Fee Period
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('period_gap')) is-invalid @endif"
                                id="periodGap" name="period_gap">
                                <option value="monthly">Monthly</option>
                                <option value="bimonthly">Bimonthly</option>
                            </select>
                            <label for="company" class="form-label">Monthly</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('period_gap'))
                                    {{ $errors->first('period_gap') }}
                                @else
                                    Monthly is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 border rounded">
                        <div class="row py-3 px-2">
                            <div class="col-md-4 col-sm-12 mt-2">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('from_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('from_date') }}" name="from_date" id="fromDate" required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="fromDate" class="form-label">From Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('from_date'))
                                            {{ $errors->first('from_date') }}
                                        @else
                                            From Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('issue_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('issue_date') }}" name="issue_date" id="issueDate" required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="issueDate" class="form-label">Issue Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('issue_date'))
                                            {{ $errors->first('issue_date') }}
                                        @else
                                            Issue Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('due_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('due_date') }}" name="due_date" id="dueDate" required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="dueDate" class="form-label">Due Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('due_date'))
                                            {{ $errors->first('due_date') }}
                                        @else
                                            Due Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('valid_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('valid_date') }}" name="valid_date" id="validDate" required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="validDate" class="form-label">Valid Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('valid_date'))
                                            {{ $errors->first('valid_date') }}
                                        @else
                                            Valid Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('arrears_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('arrears_date') }}" name="arrears_date" id="arrearsDate"
                                        required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="arrearsDate" class="form-label">Arrears Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('arrears_date'))
                                            {{ $errors->first('arrears_date') }}
                                        @else
                                            Arrears Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 mt-2">
                                <div class="input-group form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('to_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                        value="{{ old('to_date') }}" name="to_date" id="toDate" required>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <label for="toDate" class="form-label">To Date</label>

                                    <div class="invalid-tooltip">
                                        @if ($errors->has('to_date'))
                                            {{ $errors->first('to_date') }}
                                        @else
                                            To Date is required!
                                        @endif
                                    </div>
                                </div>
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


@push('footer_scripts')
    <script>
        $('#autoGeneratedCheck').on('change', function() {
            if ($('#autoGeneratedCheck').is(':checked')) {
                $('#periodGap').prop('disabled', false)
            } else {
                $('#periodGap').prop('disabled', true)
            }
        })
    </script>
@endpush
