<div class="col-lg-9 col-md-8 col-sm-12">
    <form id="bulk-challan-form" method="POST" action="{{ route('generate-bulk-challan') }}" class="card">
        @csrf
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0 flex-grow-1">Invoices</h4>
            <div class="flex-shrink-0">
                <div class="form-check">
                    <label for="selectAllInvoices" class="d-flex align-items-center">
                        <p class="text-muted m-0 pe-4 me-2">Select all invoices</p>
                        <input class="form-check-input" type="checkbox" id="selectAllInvoices" style="font-size: 16px">
                    </label>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="fee-period-academic-year form-select" id="academic_year_id" name="academic_year_id"
                        data-target="feePeriodFilter" data-url="{{ route('get-fee-periods') }}">
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
                        <select class="filter form-select" id="feePeriodFilter" name="fee_period_id"
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
                        <label for="feePeriodFilter" class="form-label">Fee Period</label>
                    </div>
                </div>
            </div>
            <table id="bulk-invoice-list" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                style="width:100%">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Invoice no.</th>
                        <th>Student Name</th>
                        <th>Invoice Type</th>
                        <th>Fee Period</th>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Concession %</th>
                        <th>Concession Type</th>
                        <th>Payment Status</th>
                        <th>Payment Date</th>
                        {{-- <th>Total</th> --}}
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Select</th>
                        <th>Invoice no.</th>
                        <th>Student Name</th>
                        <th>Invoice Type</th>
                        <th>Fee Period</th>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Concession %</th>
                        <th>Concession Type</th>
                        <th>Payment Status</th>
                        <th>Payment Date</th>
                        {{-- <th>Total</th> --}}
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>


            <input type="hidden" name="invoices" id="invoices_input">
            @if ($errors->has('invoices'))
                <p class="text-danger">
                    No invoice is selected.
                </p>
            @endif
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary me-2">Generate Challans</button>
            </div>
        </div>
    </form>
</div>
