<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Withdrawal Reason</h4>
            <div class="flex-shrink-0">
                @permission('add-withdrawal-reason')
                <a href="{{ route('withdrawal-reason.index') }}" class="btn btn-sm btn-soft-success">
                    <i class="ri-add-circle-line align-middle me-1"></i> Add New Withdrawal Reason
                </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('withdrawal-reason.update', $withdrawalReason->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="withdrawal_reason" name="withdrawal_reason" placeholder="Please enter name" value="{{ $withdrawalReason->withdrawal_reason }}" required>
                            <label for="withdrawal_reason" class="form-label">Name</label>
                            <div class="invalid-tooltip">Name is required!</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="withdrawalDescription" required placeholder="Enter description here...">{{ $withdrawalReason->description }}</textarea>
                            <label for="withdrawalDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('withdrawal-reason.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
