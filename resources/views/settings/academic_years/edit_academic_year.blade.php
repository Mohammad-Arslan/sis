<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Academic Year</h4>
            <div class="flex-shrink-0">
                @permission('add-language')
                <a href="{{ route('academic-year.index') }}" class="btn btn-sm btn-soft-success">
                    <i class="ri-add-circle-line align-middle me-1"></i> Add New Academic Year
                </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('academic-year.update', $academicYear->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Please enter name" value="{{ $academicYear->title }}" required>
                            <label for="title" class="form-label">Title</label>
                            <div class="invalid-tooltip">Title is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-control form-select" id="active" name="active" required>
                                <option value="" disabled>Status</option>
                                <option @if($academicYear->active == 1) selected @endif value="1" >Active</option>
                                <option @if($academicYear->active == 0) selected @endif value="0" >Inactive</option>
                            </select>
                            <label for="active" class="form-label">Status</label>
                            <div class="invalid-tooltip">Status is required!</div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('academic-year.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
