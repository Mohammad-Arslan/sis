<form class="row g-3 needs-validation" action="{{ route('update-employee',isset($employee[0]->id) ? $employee[0]->id : '') }}" method="POST" novalidate>
    @csrf

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('previous_id')) is-invalid @endif" id="previousId" name="previous_id" placeholder="Previous ID" value="{{ old('previous_id', $employee[0]->previous_id) }}">
            <label for="previousId" class="form-label">Previous ID</label>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('company_id')) is-invalid @endif" id="company" name="company_id" aria-label="Company select" required>
                <option value="">Please select</option>
                @foreach ($comapnies as $company)
                <option value="{{ $company->id }}" {{ old('company_id', $employee[0]->company_id) == $company->id ? 'selected' : '' }} >{{ $company->company_name }}</option>
                @endforeach
            </select>
            <label for="company" class="form-label">Company *</label>
            <div class="invalid-tooltip">
                @if($errors->has('company_id'))
                {{ $errors->first('company_id') }}
                @else
                Company is required!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('branch_id')) is-invalid @endif" id="branchId" name="branch_id" aria-label="Branch select">
                <option value="">Please select</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ old('branch_id', $employee[0]->branch_id) == $branch->id ? 'selected' : '' }} >{{ $branch->br_name . ' ('.$branch->branch_code.')' }}</option>
                @endforeach
            </select>
            <label for="branchId" class="form-label">Branch *</label>
            <div class="invalid-tooltip">
                @if($errors->has('branch_id'))
                {{ $errors->first('branch_id') }}
                @else
                Branch is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('region_id')) is-invalid @endif" id="regionId" name="region_id" aria-label="Region select">
                <option value="">Please select</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}" {{ old('region_id', $employee[0]->region_id) == $region->id ? 'selected' : '' }} >{{ $region->region_name }}</option>
                @endforeach
            </select>
            <label for="regionId" class="form-label">Region </label>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('department_id')) is-invalid @endif" id="departmentId" name="department_id" aria-label="Department select" required>
                <option value="">Please select</option>
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id', $employee[0]->department_id) == $department->id ? 'selected' : '' }} >{{ $department->department_name }}</option>
                @endforeach
            </select>
            <label for="departmentId" class="form-label">Department *</label>
            <div class="invalid-tooltip">
                @if($errors->has('department_id'))
                {{ $errors->first('department_id') }}
                @else
                Department is required!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('designation_id')) is-invalid @endif" id="designationId" name="designation_id" aria-label="Designation select" required onchange="GetReportingManager(this.value,{{ $employee[0]->id }})">
                <option value="">Please select</option>
                @foreach ($designations as $designation)
                <option value="{{ $designation->id }}" {{ old('designation_id', $employee[0]->designation_id) == $designation->id ? 'selected' : '' }} >{{ $designation->designation_name }}</option>
                @endforeach
            </select>
            <label for="designationId" class="form-label">Designation *</label>
            <div class="invalid-tooltip">
                @if($errors->has('designation_id'))
                {{ $errors->first('designation_id') }}
                @else
                Designation is required!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('reporting_to')) is-invalid @endif" id="reportingTo" name="reporting_to" aria-label="Reporting to select">
                <option value="">Please select</option>
                @if (isset($employee[0]->reporting_manager->id))
                <option value="{{ $employee[0]->reporting_manager->id }}" {{ old('reporting_to', $employee[0]->reporting_manager->id) == $employee[0]->reporting_manager->id ? 'selected' : '' }}>{{ $employee[0]->reporting_manager->name }}</option>
                @endif
            </select>
            <label for="reportingTo" class="form-label">Reporting to</label>
            <div class="invalid-tooltip">
                @if($errors->has('reporting_to'))
                {{ $errors->first('reporting_to') }}
                @else
                Reporting manager is required!
                @endif
            </div>
        </div>
    </div>

    <div class="border mt-3 border-dashed"></div>

    <div class="col-md-6 col-sm-12 mt-4">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('insurance_plan')) is-invalid @endif" id="insurance_plan" name="insurance_plan" placeholder="Please enter insurance plan" value="{{ old('insurance_plan', $employee[0]->insurance_plan) }}">
            <label for="insurance_plan" class="form-label">Insurance Plan</label>
            <div class="invalid-tooltip">
                @if($errors->has('insurance_plan'))
                {{ $errors->first('insurance_plan') }}
                @else
                Insurance plan is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mt-4">

        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('grade')) is-invalid @endif" id="grade" name="grade" placeholder="Please enter grade" value="{{ old('grade', $employee[0]->grade) }}">
            <label for="grade" class="form-label">Grade</label>
            <div class="invalid-tooltip">
                @if($errors->has('grade'))
                {{ $errors->first('grade') }}
                @else
                Grade is required!
                @endif
            </div>
        </div>
    </div>
    <div class="border mt-3 border-dashed"></div>
    <div class="col-md-12 col-sm-12 mt-4">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('address')) is-invalid @endif" id="address" name="address" placeholder="Please enter address" value="{{ old('address', $employee[0]->address) }}">
            <label for="address" class="form-label">Address</label>
            <div class="invalid-tooltip">
                @if($errors->has('address'))
                {{ $errors->first('address') }}
                @else
                Address is required!
                @endif
            </div>
        </div>
    </div>
    <div class="border mt-3 border-dashed"></div>
    <div class="col-12 text-end">
        @if (isset($employee[0]->id))
            <input type="hidden" class="form-control" id="form_info" name="form_info"  value="company">
            <button class="btn btn-primary" type="submit">Update</button>
        @endif
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
