<div class="col-md-6 col-sm-12 mt-4">
    <div class="form-label-group in-border">
        <select class="form-select" id="class_id" name="class_id" placeholder="Class">
            <option value="">Please select a Class</option>
            @foreach ($classes as $class)
                <option value="{{ $class->com_classes->id }}">{{ $class->com_classes->class_name }}
                </option>
            @endforeach
        </select>
        <label for="class_id" class="form-label">Class</label>
    </div>
</div>

<div class="col-md-6 col-sm-12 mt-4">
    <div class="form-label-group in-border">
        <select class="filter form-select" id="feePackage" name="fee_package_id" placeholder="Fee Package">
            <option value="">Please select a Fee Package</option>
            @foreach ($fee_packages as $fee_package)
                <option value="{{ $fee_package->id }}">{{ $fee_package->package_name }}
                </option>
            @endforeach
        </select>
        <label for="feePackage" class="form-label">Fee Package</label>
    </div>
</div>
<div class="col-md-6 col-sm-12 mt-4">
    <div class="form-label-group in-border">
        <select class="filter load-select form-select" id="feePeriod" name="fee_package_id"
            data-target="fee_period_dates" data-url="{{ route('get-fee-period') }}" placeholder="Fee Package">
            <option value="">Please select a Fee Period</option>
            @foreach ($fee_periods as $fee_period)
                <option value="{{ $fee_period->id }}" {{ old('fee_period_id') == $fee_period->id ? 'selected' : '' }}>
                    {{ $fee_period->period_name }}
                    ({{ \Carbon\Carbon::parse($fee_period->from_date)->format('d-m-Y') . ' - ' . \Carbon\Carbon::parse($fee_period->to_date)->format('d-m-Y') }})
                </option>
            @endforeach
        </select>
        <label for="feePackage" class="form-label">Fee Period</label>
    </div>
</div>

<div class="col-md-6 col-sm-12 mt-4">
    <div class="input-group form-label-group in-border">
        <input type="date" class="form-control" data-provider="flatpickr" data-date-format="d-m-Y"
            data-altFormat="d-m-Y" value="{{ old('paid_date') }}" name="paid_date" id="loadPaidDate" required>
        <div class="input-group-text bg-primary border-primary text-white">
            <i class="ri-calendar-2-line"></i>
        </div>
        <label for="paidDate" class="form-label">Paid Date</label>
    </div>
</div>

<div class="col-md-12 text-end mb-1">
    <button type="button" class="btn btn-primary me-2" id="fetch_student_invoices">Get Students</button>
    {{-- <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button> --}}
</div>
