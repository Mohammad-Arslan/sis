<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Department</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('designations.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-designation')
                    <a href="{{ route('designations.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Designation
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('designations.update', $designation->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    {{--<div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="companyName" name="company_id" required>
                                <option value="" disabled selected>Companies options</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        @if ($designation->company_id == $company->id) {{ 'selected' }} @endif>
                                        {{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <label for="companyName" class="form-label">Companies List</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>--}}
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="roleName" name="role_id" required>
                                <option value="" disabled selected>Role Option</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{$designation->role_id == $role->id ? 'selected' : ''}}>{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            <label for="roleName" class="form-label">Role Name</label>
                            <div class="invalid-tooltip">Role is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="type_id" name="type_id" required>
                                <option value="" disabled selected>Companies options</option>
                                @foreach ($designation_types as $designation_type)
                                    <option value="{{ $designation_type->id }}"
                                        @if ($designation->type_id == $designation_type->id) {{ 'selected' }} @endif>
                                        {{ $designation_type->type_name }}</option>
                                @endforeach
                            </select>
                            <label for="type_id" class="form-label">Type List</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('type_id'))
                                    {{ $errors->first('type_id') }}
                                @else
                                    Designation type is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('designation_name')) is-invalid @endif"
                                id="designation_name" name="designation_name" placeholder="Please enter designation name"
                                value="{{ $designation->designation_name }}" required>
                            <label for="designation_name" class="form-label">Designation name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('designation_name'))
                                    {{ $errors->first('designation_name') }}
                                @else
                                    Designation name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="abbreviation" name="abbreviation"
                                placeholder="Please enter abbreviation" value="{{ $designation->abbreviation }}">
                            <label for="abbreviation" class="form-label">Abbreviation</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="for_school" name="for_school" required>
                                <option value="1" {{$designation->for_school == '1' ? 'selected' : ''}}>Yes</option>
                                <option value="0" {{$designation->for_school == '0' ? 'selected' : ''}}>No</option>
                            </select>
                            <label for="for_school" class="form-label">Designation for School</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('for_school'))
                                    {{ $errors->first('for_school') }}
                                @else
                                    Designation for School is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('designations.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
