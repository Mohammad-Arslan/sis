<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Fee Charge Type</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('fee-charges-type.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-fee-charges-type')
                    <a href="{{ route('fee-charges-type.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Fee Charge Type
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('fee-charges-type.update', $feeChargesType->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Please enter name" value="{{ $feeChargesType->name }}" required>
                            <label for="name" class="form-label">Name</label>
                            <div class="invalid-tooltip">Name is required!</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="abbreviation" name="abbreviation"
                                placeholder="Please enter abbreviation" value="{{ $feeChargesType->abbreviation }}">
                            <label for="abbreviation" class="form-label">Abbreviation</label>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="frequency" name="frequency" required>
                                <option value="" disabled selected>Frequency options</option>
                                <option value="annual" @if ($feeChargesType->frequency == 'annual') {{ 'selected' }} @endif>
                                    Annual</option>
                                <option value="monthly" @if ($feeChargesType->frequency == 'monthly') {{ 'selected' }} @endif>
                                    Monthly</option>
                                <option value="one-time" @if ($feeChargesType->frequency == 'one-time') {{ 'selected' }} @endif>
                                    One-Time</option>
                            </select>
                            <label for="frequency" class="form-label">Frequency</label>
                            <div class="invalid-tooltip">Frequency is required!</div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="feeChargesDescription"
                                placeholder="Enter fee charges description here...">{{ $feeChargesType->description }}</textarea>
                            <label for="feeChargesDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('fee-charges-type.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
