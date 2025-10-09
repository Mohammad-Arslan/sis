<div class="col-lg-9 col-md-8 col-sm-12">
    <form id="bulk-invoices-form" class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0 flex-grow-1">Generate Invoices</h4>
            <div class="flex-shrink-0">
                <div class="form-check">
                    <label for="selectAllStudents" class="d-flex align-items-center">
                        <p class="text-muted m-0 pe-4 me-2">Select all students</p>
                        <input class="form-check-input" type="checkbox" id="selectAllStudents" style="font-size: 16px">
                    </label>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="academic-year-select form-select" id="academic_year_id" name="academic_year_id"
                        data-target="feePackage,feePeriod" data-url="{{ route('get-fee-package-period') }}">
                            <option value="">Please select</option>
                            @foreach ($academic_years as $academic_year)
                                <option @if($academic_year->active == 1) selected @endif value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                            @endforeach
                        </select>
                        <label for="academic_year_id" class="form-label">Academic Year</label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="filter form-select" id="feePackage" name="fee_package_id"
                            placeholder="Fee Package">
                            <option value="">Please select a Fee Package</option>
                            @foreach ($fee_packages as $fee_package)
                                <option value="{{ $fee_package->id }}">{{ $fee_package->package_name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="feePackage" class="form-label">Fee Package</label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="filter load-select form-select" id="feePeriod" name="fee_period_id"
                            data-target="fee_period_dates" data-url="{{ route('get-fee-period') }}"
                            placeholder="Fee Package">
                            <option value="">Please select a Fee Period</option>
                            @foreach ($fee_periods as $fee_period)
                                <option value="{{ $fee_period->id }}"
                                    {{ old('fee_period_id') == $fee_period->id ? 'selected' : '' }}>
                                    {{ $fee_period->period_name }}
                                    ({{ \Carbon\Carbon::parse($fee_period->from_date)->format('d-m-Y') . ' - ' . \Carbon\Carbon::parse($fee_period->to_date)->format('d-m-Y') }})
                                </option>
                            @endforeach
                        </select>
                        <label for="feePeriod" class="form-label">Fee Period</label>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="input-group form-label-group in-border">
                        <input type="text" class="form-control @if ($errors->has('issue_date')) is-invalid @endif"
                            data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                            value="{{ old('issue_date') }}" name="issue_date" id="loadIssueDate" required
                            style="pointer-events: none">
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

                <div class="col-md-4 col-sm-12">
                    <div class="input-group form-label-group in-border">
                        <input type="text" class="form-control @if ($errors->has('due_date')) is-invalid @endif"
                            data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                            value="{{ old('due_date') }}" name="due_date" id="loadDueDate" required>
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

                <div class="col-md-4 col-sm-12">
                    <div class="input-group form-label-group in-border">
                        <input type="text" class="form-control @if ($errors->has('validity_date')) is-invalid @endif"
                            data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                            value="{{ old('validity_date') }}" name="validity_date" id="loadValidDate" required>
                        <div class="input-group-text bg-primary border-primary text-white">
                            <i class="ri-calendar-2-line"></i>
                        </div>
                        <label for="validDate" class="form-label">Valid Date</label>

                        <div class="invalid-tooltip">
                            @if ($errors->has('validity_date'))
                                {{ $errors->first('validity_date') }}
                            @else
                                Valid Date is required!
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <table id="branches-students-list" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice no.</th>
                        <th>Student ID</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Invoice Status</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Invoice no.</th>
                        <th>Student ID</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Invoice Status</th>
                        <th>Select</th>
                    </tr>
                </tfoot>
            </table>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary me-2">Generate Invoices</button>
                {{-- <button class="btn btn-success me-2">Preview</button> --}}
                <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
            </div>
        </div>
    </form>
</div>
