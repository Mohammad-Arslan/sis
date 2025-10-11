<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Campus/Office Type</h4>
            <div class="flex-shrink-0">
                @permission('add-campus-type')
                    <a href="{{ route('campusOfficeType.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Campus/Office Type
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('campusOfficeType.update', $campusOfficeType->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="type" id="typeid"
                                placeholder="Please enter type name" value="{{ $campusOfficeType->type }}" required>
                            <label for="typeid" class="form-label">Type name</label>
                            <div class="invalid-tooltip">Type name is required!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('campusOfficeType.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
