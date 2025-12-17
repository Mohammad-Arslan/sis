@extends('layouts.master')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create New Fee Package</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <form class="row g-3 needs-validation" novalidate action="{{ route('fee-packages.store') }}"
                        method="post">
                        @csrf
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control @if ($errors->has('package_name')) is-invalid @endif"
                                    id="packageName" name="package_name" placeholder="Enter Package Name"
                                    value="{{ old('package_name') }}" required>
                                <label for="branch_name" class="form-label">Package</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('package_name'))
                                        {{ $errors->first('package_name') }}
                                    @else
                                        Package name is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control @if ($errors->has('abbreviation')) is-invalid @endif"
                                    id="abbreviation" name="abbreviation" placeholder="Package Abbreviation"
                                    value="{{ old('abbreviation') }}" required>
                                <label for="branch_name" class="form-label">Abbreviation</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('abbreviation'))
                                        {{ $errors->first('abbreviation') }}
                                    @else
                                        Abbreviation is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select @if ($errors->has('fee_package_type_id')) is-invalid @endif"
                                    id="feePackageType" name="fee_package_type_id" aria-label="Fee Package Type select" required>
                                    <option value="">Please select</option>
                                    @if (isset($fee_package_types))
                                        @foreach ($fee_package_types as $fee_package_type)
                                            <option value="{{ $fee_package_type->id }}"
                                                {{ $fee_package_type->id == old('fee_package_type_id') ? 'selected' : '' }}>
                                                {{ $fee_package_type->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="feePackageType" class="form-label">Fee Package Type</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('fee_package_type_id'))
                                        {{ $errors->first('fee_package_type_id') }}
                                    @else
                                        Fee Package is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select @if ($errors->has('company_id')) is-invalid @endif"
                                    id="company" name="company_id" data-target="branch_id"
                                    data-url="{{ route('list-branches') }}" aria-label="Country select" required>
                                    <option value="">Please select</option>
                                    @if (isset($companies))
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ $company->id == old('company_id') ? 'selected' : '' }}>
                                                {{ $company->company_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="company" class="form-label">Company</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('company_id'))
                                        {{ $errors->first('company_id') }}
                                    @else
                                        Company is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select @if ($errors->has('branch_id')) is-invalid @endif"
                                    id="branch" name="branch_id" data-target="academic_year_id"
                                    data-url="{{ route('list-academic-years') }}" aria-label="Country select" required>
                                    <option value="">Please select</option>
                                    @if (old('branch_id') !== null)
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ $branch->id == old('branch_id') ? 'selected' : '' }}>
                                                {{ $branch->br_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="branch" class="form-label">Branch</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('branch_id'))
                                        {{ $errors->first('branch_id') }}
                                    @else
                                        Branch is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif" id="branch"
                                    name="academic_year_id" aria-label="Country select" required>
                                    <option value="">Please select</option>
                                    @if (old('academic_year_id') !== null)
                                        @foreach ($academic_years as $academic_year)
                                            <option value="{{ $academic_year->id }}"
                                                {{ $academic_year->id == old('academic_year_id') ? 'selected' : '' }}>
                                                {{ $academic_year->title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="branch" class="form-label">Academic year</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('academic_year_id'))
                                        {{ $errors->first('academic_year_id') }}
                                    @else
                                        Academic year is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 border rounded mx-2">
                            <div class="row pt-4 pb-1 px-2" style="position: relative">
                                <div style="width: max-content; position: absolute; top: -8px">
                                    <h6 class="bg-white px-1">Apply to
                                        Classes:</h6>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select
                                            class="load-select form-select @if ($errors->has('from_class_id')) is-invalid @endif"
                                            id="class" name="from_class_id" aria-label="Class select" required>
                                            <option value="">Please select a class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ old('from_class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="class" class="form-label">From</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('from_class_id'))
                                                {{ $errors->first('from_class_id') }}
                                            @else
                                                From Class is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select
                                            class="load-select form-select @if ($errors->has('to_class_id')) is-invalid @endif"
                                            id="class" name="to_class_id" aria-label="Class select" required>
                                            <option value="">Please select a class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ old('to_class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="class" class="form-label">To</label>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('to_class_id'))
                                                {{ $errors->first('to_class_id') }}
                                            @else
                                                To Class is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <textarea class="form-control @if ($errors->has('description')) is-invalid @endif" name="description"
                                    id="feePackagesDescription" placeholder="Enter Fee Packages description here...">{{ old('description') }}</textarea>
                                <label for="feePackagesDescription" class="form-label">Description</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('description'))
                                        {{ $errors->first('description') }}
                                    @else
                                        Description is required!
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
@endsection
