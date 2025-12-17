@extends('layouts.master')
@section('content')
    @include('components.flash_message')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Business Development Application</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="needs-validation" novalidate>
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="school_type" name="school_type" required>
                                    <option value="">Select</option>
                                </select>
                                <label class="form-label">School Type</label>
                                <div class="invalid-tooltip">
                                    School Type is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="number" class="form-control" name="total_franchise_fee" id="total_franchise_fee" placeholder="Total Franchise Fee" required>
                                <label for="total_franchise_fee" class="form-label">Total Franchise Fee</label>
                                <div class="invalid-tooltip">
                                    Total Franchise Fee is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="number" class="form-control" name="royalty_rate" id="royalty_rate" placeholder="Total Franchise Fee" required>
                                <label for="total_franchise_fee" class="form-label">Royalty Rate</label>
                                <div class="invalid-tooltip">
                                    Royalty Rate is required!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="number" class="form-control" name="payment_on_mou" id="payment_on_mou" placeholder="Payment On MOU" required>
                                <label class="form-label">Payment On MOU</label>
                                <div class="invalid-tooltip">
                                    Payment On MOU is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="number" class="form-control" name="payment_on_agreement" id="payment_on_agreement" placeholder="Payment On Agreement" required>
                                <label class="form-label">Payment On Agreement</label>
                                <div class="invalid-tooltip">
                                    Payment On Agreement is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group form-label-group in-border">
                                <input type="date" class="form-control" name="renovation_period" id="renovation_period" data-provider="flatpickr" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label class="form-label">Renovation Period</label>
                                <div class="invalid-tooltip">
                                    Renovation Period is required!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="number" class="form-control" name="token_money" id="token_money" placeholder="Token Money" required>
                                <label class="form-label">Token Money</label>
                                <div class="invalid-tooltip">
                                    Token Money is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="token_money_mode" name="token_money_mode" required>
                                    <option value="">Select</option>
                                    <option value="cash">Cash</option>
                                    <option value="draft">Draft</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                                <label class="form-label">Token Money Mode</label>
                                <div class="invalid-tooltip">
                                    Token Money Mode is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="payment_mode" name="payment_mode" required>
                                    <option value="">Select</option>
                                </select>
                                <label class="form-label">Payment Mode</label>
                                <div class="invalid-tooltip">
                                    Payment Mode is required!
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-4">
                            <label class="form-label">Building Type: &nbsp;&nbsp;&nbsp;</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="building_type" id="building_type_new" value="new">
                                <label>New</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="building_type" id="building_type_renovate" value="renovate">
                                <label>Renovate</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group form-label-group in-border">
                                <input type="date" class="form-control" name="new_renovate_date_from" id="new_renovate_date_from" data-provider="flatpickr" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label class="form-label">New/Renovate Date From</label>
                                <div class="invalid-tooltip">
                                    New/Renovate Date From is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group form-label-group in-border">
                                <input type="date" class="form-control" name="new_renovate_date_to" id="new_renovate_date_to" data-provider="flatpickr" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label class="form-label">New/Renovate Date To</label>
                                <div class="invalid-tooltip">
                                    New/Renovate Date To is required!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" name="agreement_type" id="agreement_type" placeholder="Agreement Type" required>
                                <label for="total_franchise_fee" class="form-label">Agreement Type</label>
                                <div class="invalid-tooltip">
                                    Agreement Type is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="number" class="form-control" name="amount_received" id="amount_received" placeholder="Amount Received" required>
                                <label for="total_franchise_fee" class="form-label">Amount Received</label>
                                <div class="invalid-tooltip">
                                    Amount Received is required!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <label class="form-label">Agreement Date</label>
                                <input type="date" class="form-control" name="agreement_date" id="agreement_date" data-provider="flatpickr" required>
                                <div class="invalid-tooltip">
                                    Agreement Date is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group form-label-group in-border">
                                <input type="date" class="form-control" name="operational_date" id="operational_date" data-provider="flatpickr" required>
                                <label class="form-label">Operational Date</label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                    Operational Date is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group form-label-group in-border">
                                <input type="date" class="form-control" name="renewal_date" id="renewal_date" data-provider="flatpickr" required>
                                <label class="form-label">Renewal Date</label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                    Renewal Date is required!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" required>
                                <label for="total_franchise_fee" class="form-label">Bank Name</label>
                                <div class="invalid-tooltip">
                                    Bank Name is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" name="bank_account" id="bank_account" placeholder="Bank Account" required>
                                <label for="total_franchise_fee" class="form-label">Bank Account</label>
                                <div class="invalid-tooltip">
                                    Bank Account is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group form-label-group in-border">
                                <input type="date" class="form-control" name="bank_acc_opening_date" id="bank_acc_opening_date" data-provider="flatpickr" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label class="form-label">Opening Date</label>
                                <div class="invalid-tooltip">
                                    Opening Date is required!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="review_by" name="review_by" required>
                                    <option value="">Select</option>
                                </select>
                                <label class="form-label">Review By</label>
                                <div class="invalid-tooltip">
                                    Review By is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="forward_to" name="forward_to" required>
                                    <option value="">Select</option>
                                </select>
                                <label class="form-label">Forward To</label>
                                <div class="invalid-tooltip">
                                    Forward To is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="dd_status" name="status" required>
                                    <option value="">Select</option>
                                </select>
                                <label class="form-label">Status</label>
                                <div class="invalid-tooltip">
                                    Status is required!
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="remarks" id="remarks" placeholder="Write Here..."></textarea>
                            <label class="form-label">Remarks</label>
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

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Application List</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <table id="city-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th>Review By</th>
                        <th>Review Date</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Recommended By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Review By</th>
                        <th>Review Date</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Recommended By</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>


            </div>
        </div>
    </div>

@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">

    </script>
@endpush
