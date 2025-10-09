<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Follow Up Type</h4>
            <div class="flex-shrink-0">
                @permission('add-follow-up-type')
                    <a href="{{ route('followUpType.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Follow Up Type
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('followUpType.update', $followUpType->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="follow_up_type" id="follow_up_typeid"
                                placeholder="Please enter follow up type" value="{{ $followUpType->follow_up_type }}" required>
                            <label for="typeid" class="form-label">Follow Up Type</label>
                            <div class="invalid-tooltip">Follow Up Type is required!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('followUpType.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
