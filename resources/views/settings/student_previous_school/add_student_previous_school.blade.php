<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add Student Previous School</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('student-previous-school.store') }}"
                      method="post">
                    @csrf
                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="school_name" name="school_name"
                                   placeholder="Please enter school name" value="{{ old('school_name') }}" required>
                            <label for="school_name" class="form-label">* School Name</label>
                            <div class="invalid-tooltip">School Name is required!</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="description" placeholder="Enter description here...">{{ old('description') }}</textarea>
                            <label for="description" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button"
                                class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
