<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Fee Charge</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('fee-charges.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-fee-charges')
                    <a href="{{ route('fee-charges.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Fee Charge
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('fee-charges.update', $feeCharge->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="academic_year_id" name="academic_year_id" required>
                                <option value="" disabled selected>Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        @if ($feeCharge->academic_year_id == $academic_year->id) {{ 'selected' }} @endif>
                                        {{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic Year</label>
                            <div class="invalid-tooltip">Kindly select the academic year!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <div class="form-label-group in-border">
                                <select class="form-select mb-3" id="feeChargeName" name="fee_charges_type_id" required>
                                    <option value="" disabled selected>Fee Charges</option>
                                    @foreach ($fee_charges_type as $fee_charge_type)
                                        <option value="{{ $fee_charge_type->id }}"
                                            @if ($feeCharge->fee_charges_type_id == $fee_charge_type->id) {{ 'selected' }} @endif>
                                            {{ $fee_charge_type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="feeChargeName" class="form-label">Fee Charges</label>
                                <div class="invalid-tooltip">Kindly select the fee charge!</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control" id="amount" name="amount"
                                placeholder="Please enter amount" value="{{ $feeCharge->amount }}" required>
                            <label for="amount" class="form-label">Amount</label>
                            <div class="invalid-tooltip">Amount is required!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select mb-3" id="companyName" name="company_id"
                                data-target="branch_id" data-url="{{ route('list-branches') }}" required>
                                <option value="" disabled selected>Companies options</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        @if ($feeCharge->company_id == $company->id) {{ 'selected' }} @endif>
                                        {{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <label for="companyName" class="form-label">Company</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="branchName" name="branch_id" required>
                                <option value="" disabled selected>Branches options</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if ($feeCharge->branch_id == $branch->id) {{ 'selected' }} @endif>
                                        {{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branchName" class="form-label">Branch</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="isDiscountable" name="is_discountable" required>
                                <option value="" disabled selected>Discount options</option>
                                <option value="1" @if ($feeCharge->is_discountable == 1) {{ 'selected' }} @endif>Yes
                                </option>
                                <option value="0" @if ($feeCharge->is_discountable == 0) {{ 'selected' }} @endif>No
                                </option>
                            </select>
                            <label for="isDiscountable" class="form-label">Discount</label>
                            <div class="invalid-tooltip">Kindly select the discount options!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('fee-charges.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
