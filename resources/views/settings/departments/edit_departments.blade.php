<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Department</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('departments.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-department')
                    <a href="{{ route('departments.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Department
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('departments.update', $department->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="companyName" name="company_id" required>
                                <option value="" disabled selected>Companies options</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        @if ($department->company_id == $company->id) {{ 'selected' }} @endif>
                                        {{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <label for="companyName" class="form-label">Companies List</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="parentID" name="parent_id">
                                <option value="" disabled selected>Parent options</option>
                            </select>
                            <label for="parentID" class="form-label">Parent</label>
                            <div class="invalid-tooltip">Kindly select the parent name!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('department_name')) is-invalid @endif"
                                id="departmentName" name="department_name" placeholder="Please enter department name"
                                value="{{ $department->department_name }}" required>
                            <label for="departmentName" class="form-label">Department name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('department_name'))
                                    {{ $errors->first('department_name') }}
                                @else
                                    Department name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="abbreviation" name="abbreviation"
                                placeholder="Please enter abbreviation" value="{{ $department->abbreviation }}">
                            <label for="abbreviation" class="form-label">Abbreviation</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="for_school" name="for_school" required>
                                <option value="1" {{$department->for_school == '1' ? 'selected' : ''}}>Yes</option>
                                <option value="0" {{$department->for_school == '0' ? 'selected' : ''}}>No</option>
                            </select>
                            <label for="for_school" class="form-label">Department for School</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('for_school'))
                                    {{ $errors->first('for_school') }}
                                @else
                                    Department for School is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('departments.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
