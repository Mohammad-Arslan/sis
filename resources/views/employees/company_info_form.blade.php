<form class="row g-3 needs-validation" action="{{ route('employees.update',isset($employee->id) ? $employee->id : '') }}" method="POST" novalidate>
    @csrf
    @method('PATCH')

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('previous_id')) is-invalid @endif" id="previousId" name="previous_id" placeholder="Previous ID" value="{{ old('previous_id') }}">
            <label for="previousId" class="form-label">Previous ID</label>
        </div>
    </div>

    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="number" class="form-control @if($errors->has('employee_id')) is-invalid @endif" id="employee_id" name="employee_id" placeholder="Employee ID" value="{{ old('employee_id') }}" required>
            <label for="employee_id" class="form-label">Employee ID *</label>
            <div class="invalid-tooltip">
                @if($errors->has('employee_id'))
                {{ $errors->first('employee_id') }}
                @else
                Employee id is required!
                @endif
            </div>
        </div>
    </div> --}}

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('company_id')) is-invalid @endif" id="company" name="company_id" aria-label="Company select" required>
                <option value="">Please select</option>
                @foreach ($comapnies as $company)
                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }} >{{ $company->company_name }}</option>
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
            <select class="form-select @if($errors->has('branch_id')) is-invalid @endif" id="branchId" name="branch_id" aria-label="Branch select" required>
                <option value="">Please select</option>
                @role(['super_admin','human_resource'])
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }} >{{ $branch->br_name }}</option>
                @endforeach
                @endrole
                @role('network_associate')
                @foreach (auth()->user()['networkAssociates']['branches'] as $branch)
                    <option value="{{ $branch->id }}" {{ get_set_NWABranchId() == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                @endforeach
                @endrole
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
            <select class="form-select @if($errors->has('department_id')) is-invalid @endif" id="departmentId" name="department_id" aria-label="Department select" required>
                <option value="">Please select</option>
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }} >{{ $department->department_name }}</option>
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
            @php
                if(isset($employee->id))
                {
                    $emp_id = $employee->id;
                }
                else{
                    $emp_id = '';
                }
            @endphp
            <select class="form-select @if($errors->has('designation_id')) is-invalid @endif" id="designationId" name="designation_id" aria-label="Designation select" required onchange="GetReportingManager(this.value,{{ $emp_id }})">
                <option value="">Please select</option>
                @foreach ($designations as $designation)
                <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }} >{{ $designation->designation_name }}</option>
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

    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('appr_cat')) is-invalid @endif" id="apprId" name="appr_id" aria-label="Appr select">
               <option value="">Please select</option>
                 @foreach ($appraisal as $appr)
                <option value="{{ $appr->id }}" {{ old('appr_cat') == $appr->appr_cat ? 'selected' : '' }} >{{ $appr->br_name }}</option>
                @endforeach
            </select>
            <label for="appr_id" class="form-label">Appr Categ</label>
            <div class="invalid-tooltip">
                @if($errors->has('appr_id'))
                {{ $errors->first('appr_id') }}
                @else
                Appr Category is required!
                @endif
            </div>
        </div>
    </div>--}}

   {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('premises_id')) is-invalid @endif" id="preId" name="premises_id" aria-label="Premises select">
                <option value="">Please select</option>
                 @foreach ($premises as $pre)
                <option value="{{ $pre->id }}" {{ old('premises_id') == $pre->id ? 'selected' : '' }} >{{ $pre->name }}</option>
                @endforeach
            </select>
            <label for="preId" class="form-label">Premises</label>
            <div class="invalid-tooltip">
                @if($errors->has('premises_id'))
                {{ $errors->first('premises_id') }}
                @else
                Premises is required!
                @endif
            </div>
        </div>
    </div>--}}

    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('category_id')) is-invalid @endif" id="categoryId" name="category_id" aria-label="Category select">
                <option value="">Please select</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} >{{ $category->category_name }}</option>
                @endforeach
            </select>
            <label for="categoryId" class="form-label">Category</label>
            <div class="invalid-tooltip">
                @if($errors->has('category_id'))
                {{ $errors->first('category_id') }}
                @else
                Branch is required!
                @endif
            </div>
        </div>
    </div> --}}

    {{--
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('category_wef')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="{{ date('d-m-Y') }}" value="{{ old('category_wef') }}" name="category_wef" id="categoryWEF">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="categoryWEF" class="form-label">Categ w.e.f</label>
        </div>
    </div>
     --}}

    <div class="border mt-3 border-dashed"></div>

    <div class="col-md-6 col-sm-12 mt-4">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('insurance_plan')) is-invalid @endif" id="insurance_plan" name="insurance_plan" placeholder="Please enter insurance plan" value="{{ old('insurance_plan') }}">
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
            <input type="text" class="form-control @if($errors->has('grade')) is-invalid @endif" id="grade" name="grade" placeholder="Please enter grade" value="{{ old('grade') }}">
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
    {{-- Removed salary fields: basic_salary, gross_salary, allownces --}}
    <div class="col-md-12 col-sm-12 mt-4">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('address')) is-invalid @endif" id="address" name="address" placeholder="Please enter address" value="{{ old('address') }}">
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
    {{--
    <div class="col-md-3 col-sm-12">
        <div class="form-check m-2 mt-md-4">
            <input class="form-check-input" type="checkbox" id="sm" name="sm" value="1">
            <label class="form-check-label" for="sm">
                S.M
            </label>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-check m-2 mt-md-4">
            <input class="form-check-input" type="checkbox" id="hm" name="hm" value="1">
            <label class="form-check-label" for="hm">
                H.M
            </label>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-check m-2 mt-md-4">
            <input class="form-check-input" type="checkbox" id="acc_secr" name="acc_secr" value="1">
            <label class="form-check-label" for="acc_secr">
                Accountant / Secretary
            </label>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-check m-2 mt-md-4">
            <input class="form-check-input" type="checkbox" id="multi_appraiser" name="multi_appraiser" value="1">
            <label class="form-check-label" for="multi_appraiser">
                Multiple Appraisers
            </label>
        </div>
    </div>
    --}}





    <div class="border mt-3 border-dashed"></div>
    <div class="col-12 text-end">

        @if (isset($employee->id))
            @permission('add-employee-company-info')
                <input type="hidden" class="form-control" id="form_info" name="form_info"  value="company">
                <button class="btn btn-primary" type="submit">Submit form</button>
            @endpermission
        @endif
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
