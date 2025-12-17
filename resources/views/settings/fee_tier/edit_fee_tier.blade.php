<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Fee Tier</h4>
            <div class="flex-shrink-0">
                @permission('add-fee-tier')
                <a href="{{ route('fee-tier.index') }}" class="btn btn-sm btn-soft-success">
                    <i class="ri-add-circle-line align-middle me-1"></i> Add New Fee Tier
                </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('fee-tier.update', $feeTier->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="tier_name" name="tier_name" placeholder="Please enter name" value="{{ $feeTier->tier_name }}" required>
                            <label for="tier_name" class="form-label">Name *</label>
                            <div class="invalid-tooltip">Name is required!</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="feeTierDescription" placeholder="Enter description here...">{{ $feeTier->description }}</textarea>
                            <label for="feeTierDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('fee-tier.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
