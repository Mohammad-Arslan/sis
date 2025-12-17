<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Designation</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('designations.store') }}" method="post">
                    @csrf
                    {{--<div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="companyName" name="company_id" required>
                                <option value="" disabled selected>Companies options</option>
                                @foreach($companies as $company)
                                <option value="{{$company->id}}" @if (old('company_id')==$company->id) {{ 'selected' }} @endif>{{$company->company_name}}</option>
                                @endforeach
                            </select>
                            <label for="companyName" class="form-label">Companies</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>--}}
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="roleName" name="role_id" required>
                                <option value="" disabled selected>Role Option</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            <label for="roleName" class="form-label">Role Name</label>
                            <div class="invalid-tooltip">Role is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="type_id" name="type_id" required>
                                <option value="" disabled selected>Staff type options</option>
                                @foreach ($designation_types as $designation_type)
                                    <option value="{{ $designation_type->id }}"
                                        @if (old('designation_type_id')) {{ 'selected' }} @endif>
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
                            value="" required>
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
                            <input type="text" class="form-control" id="abbreviation" name="abbreviation" placeholder="Please enter abbreviation" value="{{ old('abbreviation') }}">
                            <label for="abbreviation" class="form-label">Abbreviation</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="for_school" name="for_school" required>
                                <option value="1" >Yes</option>
                                <option value="0" selected>No</option>
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
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
