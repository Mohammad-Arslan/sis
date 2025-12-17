
@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style>
    .custom-list-group {
        list-style: none;
        padding: 0;
        font-family: "Courier New", monospace;
    }

    .header {
        display: flex;
        justify-content: space-between;
        padding: 8px;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        color: #333;
        /* Darker text color */
    }

    .values {
        display: flex;
        justify-content: space-between;
        padding: 8px;
    }

    .label {
        width: 25%;
        text-align: center;
        font-weight: bold;
        color: #555;
        /* Slightly darker text color */
    }

    .value {
        width: 25%;
        text-align: center;
        font-weight: normal;
        /* Regular font weight */
        color: #000;
        /* Black text color */
    }

    .table-style tr th:nth-child(2) {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: white;
    }

    .table-style tr td:nth-child(2) {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: white;
    }

    .table-style1 tr th:first-child {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: white;
    }

    .table-style1 tr td:first-child {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: white;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @if (auth()->user()->hasRole('head-of-finance|ho-accountant') && isHeadOfficeEmp())
    <div class="row project-wrapper">
        <div class="col-xxl-12">
            {{-- <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="filter form-select" id="academic_year_id">
                    <option value="">Please select</option>
                    @foreach ($academic_years as $academic_year)
                        <option @if ($academic_year->active == 1) selected @endif value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                    @endforeach
                </select>
                <label for="academic_year_id" class="form-label">Academic Year</label>
            </div>
        </div> --}}
            <div class="col-xl-12">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex align-items-center">
                                    <h4 id='reg-student' class="fs-4 flex-grow-1 mb-0"><span>Branch Data</span></h4>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div><!-- end col -->
            <div class="row">
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                        <i data-feather="globe" class="text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Punjab</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 id='reg-student' class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="{{ $punjab_count }}">{{ $punjab_count }}</span> &nbsp; Branches
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                        <i data-feather="globe" class="text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Sindh</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 id='reg-student' class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="{{ $sindh_count }}">{{ $sindh_count }}</span> &nbsp; Branches
                                        </h4>
                                    </div>
                                    {{-- <p class="text-muted mb-0">Registrations this month</p> --}}
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                        <i data-feather="globe" class="text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">KPK</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 id='active-student-rev' class="fs-4 flex-grow-1 mb-0"><span
                                                class="counter-value">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->


            </div><!-- end row -->
            {{-- Graph start --}}

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Student Details</h4>
                            <div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="filter form-select" id="academic_year_id_graph"
                                            name="academic_year_id_graph">
                                            <option value="">Please select</option>
                                            @foreach ($academic_years as $academic_year)
                                                <option @if ($academic_year->active == 1) selected @endif
                                                    value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                            @endforeach
                                        </select>
                                        <label for="academic_year_id_graph" class="form-label">Academic Year</label>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-soft-light">
                            <div class="row g-0 text-center">
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1" id="OR"><span class="counter-value"
                                                data-target="{{ $onroll }}">{{ $onroll }}</span></h5>
                                        <p class="text-muted mb-0">On Roll</p>
                                    </div>
                                </div><!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1" id="R"><span class="counter-value"
                                                data-target="{{ $register }}">{{ $register }}</span></h5>
                                        <p class="text-muted mb-0">Register</p>
                                    </div>
                                </div><!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="{{ $processing }}">{{ $processing }}</span></h5>
                                        <p class="text-muted mb-0">Processing</p>
                                    </div>
                                </div><!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                                        <h5 class="mb-1 text-danger" id="L"><span class="counter-value"
                                                data-target="{{ $left }}">{{ $left }}</span></h5>
                                        <p class="text-muted mb-0">Left</p>
                                    </div>
                                </div><!--end col-->
                            </div>
                        </div><!-- end card header -->
                        <div class="card-body p-0 pb-2 pr-2">
                            <div>
                                <div id="projects-overview-chart"
                                    data-colors='["--vz-secondary", "--vz-warning", "--vz-success"]' class="apex-charts"
                                    dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div>
    </div>
    {{-- BRanch Details Table start --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-height-100">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title flex-grow-1 mb-0">Branch Details</h4>
                    {{-- <div class="flex-shrink-0">
                    <a href="javascript:void(0);" class="btn btn-soft-info btn-sm">Export Report</a>
                </div> --}}
                </div><!-- end cardheader -->
                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-md-2 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="company_id" name="company_id" placeholder="Company">
                                <option value="">Please select</option>
                                @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <label for="company_id" class="form-label">Company</label>
                        </div>
                    </div> --}}
                        {{-- <div class="col-md-2 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="nwa_id" name="nwa_id"
                                placeholder="Network Assosiate">
                                <option value="">Please select</option>
                            </select>
                            <label for="nwa_id" class="form-label">Network Assosiate</label>
                        </div>
                    </div> --}}

                        {{-- <div class="col-md-2 col-sm-12">
                            <div class="filter form-label-group in-border">
                                <select class="form-select" id="region_id" name="region_id" aria-label="Region select"
                                    placeholder="Region">
                                    <option value="">Please select</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                    @endforeach
                                </select>
                                <label for="region_id" class="form-label">Region</label>
                            </div>
                        </div> --}}
                        <div class="col-md-2 col-sm-12">
                            <div class="filter form-label-group in-border">
                                <select class="form-select" id="branch_state_id" name="branch_state_id" aria-label="State select">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_state_id" class="form-label">State / Province</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="myInput" type="text" placeholder="Search.." class="form-control">
                                <label for="myInput" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-card">
                        <table id="finance-branch-detail-table" class="table table-nowrap table-centered align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                <tr>
                                    <th>Branch Code</th>
                                    <th>Branch Name</th>
                                    <th>Province</th>
                                    {{-- <th>Region</th> --}}
                                    <th>NWA Name</th>
                                    <th>NWA Email</th>
                                    <th>NWA Contact</th>
                                </tr>
                                </tr><!-- end tr -->
                            </thead><!-- thead -->
                        </table><!-- end table -->
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
    {{-- BRanch Details Table End --}}
    {{-- Student Details Table start --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-height-100">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title flex-grow-1 mb-0">Student / Employee Details</h4>
                    {{-- <div class="flex-shrink-0">
                    <a href="javascript:void(0);" class="btn btn-soft-info btn-sm">Export Report</a>
                </div> --}}
                </div><!-- end cardheader -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="student_academic_year_id"
                                    name="student_academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="student_academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        {{-- <div class="col-md-2 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="nwa_id" name="nwa_id"
                                placeholder="Network Assosiate">
                                <option value="">Please select</option>
                            </select>
                            <label for="nwa_id" class="form-label">Network Assosiate</label>
                        </div>
                    </div> --}}

                  <div class="col-md-2 col-sm-12">
                      <div class="filter form-label-group in-border">
                          <select class="form-select" id="student_state_id" name="student_state_id" aria-label="State select"
                              placeholder="State">
                              <option value="">Please select</option>
                              @foreach ($states as $state)
                                  <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                              @endforeach
                          </select>
                          <label for="student_state_id" class="form-label">State / Province</label>
                      </div>
                  </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <div class="filter input-group">
                                    <input type="text"
                                        class="form-control @if ($errors->has('start_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y"
                                        name="start_date" id="start_date">
                                    <label for="start_date" class="form-label">From Month</label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <div class="filter input-group">
                                    <input type="text"
                                        class="form-control @if ($errors->has('end_date')) is-invalid @endif"
                                        data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y"
                                        name="end_date" id="end_date">
                                    <label for="end_date" class="form-label">To Month</label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="myInput" type="text" placeholder="Search.." class="form-control">
                                <label for="myInput" class="form-label">Search...</label>
                            </div>
                        </div> --}}
                    </div>
                    <div class="table-responsive table-card">
                        <table id="finance-student-detail-table" class="table table-nowrap table-centered align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                <tr>
                                    <th>Branch Code</th>
                                    <th>Branch Name</th>
                                    <th>On Roll</th>
                                    <th>Registered</th>
                                    <th>Left</th>
                                    <th>Total Employees</th>
                                </tr>
                                </tr><!-- end tr -->
                            </thead><!-- thead -->
                            <tbody>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Branch Code</th>
                                    <th>Branch Name</th>
                                    <th>On Roll</th>
                                    <th>Registered</th>
                                    <th>Left</th>
                                    <th>Total Employees</th>
                                </tr>
                            </tfoot>
                        </table><!-- end table -->
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
    {{-- Student Details Table End --}}
    {{-- onboarding Details Table start --}}
    <div class="row">
        <div class="col-xl-12">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="ri-error-warning-line label-icon"></i><strong>Error</strong>
                    - {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card card-height-100">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title flex-grow-1 mb-0">Onboarding Applications</h4>
                </div><!-- end cardheader -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter load-select form-select" id="franchise_state_id" name="franchise_state_id"
                                    placeholder="state" data-target="city_id" data-url="{{ route('list-cities') }}"
                                    aria-label="State select" required>
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="franchise_state_id" class="form-label">State/Province</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('city')) is-invalid @endif"
                                    id="city" name="city_id" aria-label="City select" required>
                                    <option value="">Please select</option>
                                    @if (old('city_id'))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->city_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="city" class="form-label">City</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select"
                                    id="school_type" name="school_type" aria-label="Agreement select" >
                                    <option value="">Please select</option>
                                        @foreach ($school_type as $school_type)
                                            <option value="{{ $school_type->id }}"
                                               >
                                                {{ $school_type->name }}</option>
                                        @endforeach
                                </select>
                                <label for="school_type" class="form-label">School Type</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="status_list" name="status">
                                    <option value=" ">Please select</option>
                                    {{-- <option value="pending">Pending</option> --}}
                                    <option value="approved">Approved</option>
                                    <option value="not_approved">Rejected</option>
                                </select>
                                <label for="status" class="form-label">Status</label>
                            </div>
                        </div>
                        {{-- <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="myInput_onboard" type="text" placeholder="Search.." class="form-control">
                                <label for="myInput_onboard" class="form-label">Search...</label>
                            </div>
                        </div> --}}
                    </div>
                    <div class="table-responsive table-card table-style">
                        <table id="finance-onboarding-detail-table" class="table table-nowrap table-centered align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Proposed School Name</th>
                                    <th>School Type</th>
                                    <th>Agreement Type</th>
                                    <th>Total Franchise Fee</th>
                                    <th>Received Amount</th>
                                    <th>Agreement Date</th>
                                    <th>Operational Date</th>
                                    <th>Status</th>
                                    <th>DD Review / Approval</th>
                                    <th>Action</th>
                                    <th>QA / IASF Report</th>
                                </tr>
                                </tr><!-- end tr -->
                            </thead><!-- thead -->
                            <tbody>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Proposed School Name</th>
                                    <th>School Type</th>
                                    <th>Agreement Type</th>
                                    <th>Total Franchise Fee</th>
                                    <th>Received Amount</th>
                                    <th>Agreement Date</th>
                                    <th>Operational Date</th>
                                    <th>Status</th>
                                    <th>DD Review / Approval</th>
                                    <th>Action</th>
                                    <th>QA / IASF Report</th>
                                </tr>
                            </tfoot>
                        </table><!-- end table -->
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
    {{-- onboarding Details Table End --}}
    {{-- Fee structure Table start --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Branch Fee Structures</h4>
                    {{-- <a href="{{ route('fee_structure.create') }}" class="btn btn-primary">Add New School Fee Structure</a> --}}
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="state_id" name="state_id" placeholder="state"
                                    data-target="city_id" data-city-url="{{ route('list-cities') }}"
                                    data-url="{{ route('fee-structure-filter') }}" aria-label="State select" required>
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="state_id" class="form-label">State/Province</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('city')) is-invalid @endif"
                                    id="city" name="city_id" aria-label="City select" required>
                                    <option value="">Please select</option>
                                    @if (old('state_id'))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->city_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="city" class="form-label">City</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('city_id'))
                                        {{ $errors->first('city_id') }}
                                    @else
                                        City is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-card table-style1">
                        <table id="fee-structure-table" class="table mb-0 align-middle table-bordered table-striped table-nowrap"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Proposed School Name</th>
                                <th>Region</th>
                                <th>City</th>
                                <th>Academic Year</th>
                                <th>School Type</th>
                                <th>Proposed Campus Area</th>
                                <th>Final Tution Fee Charges</th>
                                <th>Tution Fee Charges (Rounded )</th>
                                <th>Fee Option Details</th>
                                <th>Approval Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fee_structure_records as $record)
                                {{-- {{ dd($record->fee_structure_details)}} --}}
                                {{-- @dd($fee_structure_records) --}}
                                <tr>
                                    <td>{{ $record->school_name }}</td>
                                    <td>{{ $record->state->state_name }}</td>
                                    <td>{{ $record->city->city_name }}</td>
                                    <td>{{ $record->academic_years->title }}</td>
                                    <td>{{ $record->class_group->name }}</td>
                                    <td>{{ $record->campus_area }}</td>
                                    <td>
                                        @php
                                            $approvedFeeDetail = $record->new_fee_structure_details->where('fee_status_by_dd', 'Approved')->first();
                                        @endphp

                                        @if ($approvedFeeDetail)
                                            {{ $approvedFeeDetail->final_fee_charges }}
                                        @else
                                           Not Approved
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $approvedFeeDetail = $record->new_fee_structure_details->where('fee_status_by_dd', 'Approved')->first();
                                        @endphp

                                        @if ($approvedFeeDetail)
                                            {{ $approvedFeeDetail->round_final_fee_charges ?? 'N/A' }}
                                        @else
                                            Not Approved
                                        @endif
                                    </td>

                                    <td>
                                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" id="view_detail" data-id="record_Id"
                                            data-target="#viewModal{{ $record->id }}">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $approvedFeeDetail = $record->new_fee_structure_details->where('fee_status_by_dd', 'Approved')->first();
                                        @endphp

                                        @if ($approvedFeeDetail)
                                            {{ $approvedFeeDetail->approval_date ?? 'N/A' }}
                                        @else
                                            Not Approved
                                        @endif
                                    </td>

                                    <td>
                                        @permission('fee-structure-edit-index')
                                        <a href="{{ route('fee_structure.index_edit', $record->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endpermission
                                        @permission('fee-structure-delete-index')
                                        <form action="{{ route('fee_structure.destroy', $record->id) }}" method="post" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endpermission
                                    </td>
                                </tr>


                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal{{ $record->id }}" tabindex="1" role="dialog"
                                    aria-labelledby="viewModalLabel{{ $record->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewModalLabel{{ $record->id }}">Fee
                                                    Option Details
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="custom-list-group">
                                                    <li class="header">
                                                        <span class="label">BSS School Name</span>
                                                        <span class="label">BSS Fee Charges</span>
                                                        <span class="label">Final Fee Charges</span>
                                                        <span class="label">Final Fee (Rounded Value)</span>
                                                        <span class="label">Status</span>
                                                        <span class="label">Approval Date</span>
                                                    </li>

                                                    @foreach ($record->new_fee_structure_details as $recordFeeStructure)
                                                        <li class="values">
                                                            <span class="value">
                                                                {{ $recordFeeStructure->nearest_bss_school ?? 'No Record' }}
                                                            </span>
                                                            <span class="value">
                                                                {{ $recordFeeStructure->fee_charges ?? 'No Record' }}
                                                            </span>
                                                            <span class="value">

                                                                {{ $recordFeeStructure->final_fee_charges ?? 'No Record' }}

                                                            </span>
                                                            <span class="value">

                                                                {{ $recordFeeStructure->round_final_fee_charges ?? 'No Record' }}

                                                            </span>
                                                            <span class="value">

                                                                {{ $recordFeeStructure->fee_status_by_dd ?? 'No Record' }}

                                                            </span>
                                                            <span class="value">

                                                                {{ $recordFeeStructure->approval_date ?? 'No Record' }}

                                                            </span>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->
    </div><!-- end row -->
    {{-- Fee structure  Table End --}}
    @else
    <div class="row">
  <div class="col-xl-12">
      <div class="card crm-widget">
          <div class="card-body p-0">
              <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                  <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-center">
                                <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                    @if (Auth::user()->employee->emp_image != '')
                                    <img src="{{ get_file_from_s3('images/'.Auth::user()->employee->emp_image) }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                    @else
                                    <img src="{{ asset('uploads/employees/user-dummy-img.jpg') }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                    @endif
                                </div>
                                <h5 class="fs-12 mb-1">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</h5>
                                <p class="text-muted mb-0">{{ Auth::user()->employee->department->department_name}} / {{ Auth::user()->employee->designation->designation_name}}</p>
                            </div>
                        </div>
                  </div><!-- end col -->
                    <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-left">
                                <h5>Status:</h5>
                                <p class="fs-12 mb-1"><b>Status:</b> {{ Auth::user()->employee->job_status }}</p>
                                <p class="fs-12 mb-1"><b>Employee ID:</b> {{ Auth::user()->employee->employee_id }}</p>
                                <p class="fs-12 mb-1"><b>Branch:</b> {{ Auth::user()->employee->branch->br_name }}</p>
                                <p class="fs-12 mb-1"><b>Hire Date:</b> {{ date('d-m-Y', strtotime(Auth::user()->employee->hiring_date)) }}</p>
                                <p class="fs-12 mb-1"><b>Service Length:</b> {{ now()->diffInDays(Auth::user()->employee->hiring_date) }} days</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-left">
                                @php
                                    $employee = Auth::user()->employee;
                                    $salaryStructure = $employee->currentSalaryStructure;
                                    $basic_salary = $salaryStructure ? (float) $salaryStructure->basic_salary : 0;
                                    $gross_salary = $salaryStructure ? (float) $salaryStructure->gross_salary : 0;
                                    $allownces = $salaryStructure ? (float) ($salaryStructure->house_rent_allowance + $salaryStructure->medical_allowance + $salaryStructure->transport_allowance + $salaryStructure->other_allowances) : 0;
                                @endphp
                                <h5>Salary Information:</h5>
                                <p class="fs-12 mb-1"><b>Basic:</b> {{ number_format($basic_salary) }}</p>
                                <p class="fs-12 mb-1"><b>Gross:</b> {{ number_format($gross_salary) }}</p>
                                <p class="fs-12 mb-1"><b>Allownces:</b> {{ number_format($allownces) }}</p>
                                {{--<p class="fs-12 mb-1"><b>Cost to School:</b> {{ number_format($gross_salary + $allownces + 8000) }}</p>--}}
                                <p class="fs-12 mb-1"><b>Cost to School:</b> {{ number_format($gross_salary) }}</p>                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-left">
                                <h5>Other Information:</h5>
                                <p class="fs-12 mb-1"><b>DOB:</b> {{ date('d-m-Y', strtotime(Auth::user()->employee->date_of_birth)) }}</p>
                                <p class="fs-12 mb-1"><b>CNIC:</b> {{ Auth::user()->CNIC }}</p>
                                <p class="fs-12 mb-1"><b>EOBI:</b> {{ Auth::user()->employee->eobi_number }} </p>
                                <p class="fs-12 mb-1"><b>Email:</b> {{ Auth::user()->email }} </p>
                                <p class="fs-12 mb-1"><b>Contact:</b><br> {{ Auth::user()->employee->address }} <br> {{ Auth::user()->employee->mobile_number }} </p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col">
                        <div class="py-4 px-3">
                            <div class="text-left">
                                <h5>Mark Attendance:</h5>
                                <p class="fs-12 mb-1">
                                    @if(isset($employee_info['time_in']) && !isset($employee_info['time_out']))
                                        <button type="button" title="Time Out" class="btn btn-sm btn-danger mark_attendance_out" data-status="out"  data-id="{{ $employee_info['id'] }}" data-employee-id="{{ Auth::user()->employee->id }}" data-route="{{ route('mark-attendance-out') }}">Time Out</button>
                                    @elseif(!isset($employee_info['time_in']) && !isset($employee_info['time_out']))
                                        <button type="button" title="Time In" class="btn btn-sm btn-success mark_attendance_in" data-status="in" data-employee-id="{{ Auth::user()->employee->id }}" data-route="{{ route('mark-attendance-in') }}">Time In</button>
                                    @else
                                        <p class="fs-12 mb-1"><b>Time In:</b> {{ $employee_info['time_in'] }} </p>
                                        <p class="fs-12 mb-1"><b>Time Out:</b> {{ $employee_info['time_out'] }} </p>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div><!-- end col -->


              </div><!-- end row -->
              <div class="row">
                <div class="col-md-12">
                    <div class="py-4 px-3">
                        <div class="text-left">
                            <h5>Leave Qouta:</h5>
                            <table id="leave_quotas_table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Leave&nbsp;Type</th>
                                        <th>Leave Allowed</th>
                                        <th>Leave Acquired</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee_info['leaveQuotas'] as $key => $leaveQuota)
                                        <tr>
                                            <td>{{ $leaveQuota->leaveType->name ?? '' }}</td>
                                            <td>{{ $leaveQuota->no_of_allowed_leaves ?? '' }}</td>
                                            <td>{{ $leaveQuota->no_of_balanced_leaves ?? '' }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Leave&nbsp;Type</th>
                                        <th>Leave Allowed</th>
                                        <th>Leave Acquired</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div><!-- end col -->
              </div>
          </div><!-- end card body -->
      </div><!-- end card -->
  </div><!-- end col -->
    </div><!-- end row -->
    @endif


@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')
<script src="{{ asset('theme/dist/default/assets/libs/dragula/dragula.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/dom-autoscroller/dom-autoscroller.min.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
    rel="stylesheet">

<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    /** Mark in*/
    $(document).on('click', '.mark_attendance_in', function (e)
        {
            e.preventDefault();
            var today = new Date();
            let url = $(this).attr('data-route');
            let employee_id = $(this).attr('data-employee-id');
            let time_in = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            let attendance_type = 1;
            Swal.fire({
                icon: 'question',
                title: 'Do you want to mark your attendance?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            employee_id,
                            time_in,
                            attendance_type
                        },
                        success:function (response) {
                            Swal.fire('Done!', '', 'success')
                            location.reload();
                        }
                    })
                }
            })
        });
        $(document).on('click', '.mark_attendance_out', function (e)
        {
            e.preventDefault();
            var today = new Date();
            let url = $(this).attr('data-route');
            let id = $(this).attr('data-id');
            let time_out = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            Swal.fire({
                icon: 'question',
                title: 'Do you want to mark out your attendance?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            id,
                            time_out
                        },
                        success:function (response) {
                            Swal.fire('Done!', '', 'success')
                            location.reload();
                        }
                    })
                }
            })
        });
});
</script>


<script>

    $(document).ready(function() {
        $('#qa-finance-onboarding-detail-table').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
            },
            ajax: {
                url: "{{ route('on-boarding-list') }}",
                data: function(d) {
                    d.state_id = $('#franchise_state_id').val();
                    d.city_id = $('#city').val();
                    d.school_type = $('#school_type').val();
                    d.status = $('#status_list').val();
                }
            },
            columns: [
                /*{data: 'id', name: 'id', width: "5%",orderable: true},
                {data: 'company.company_name', name: 'company.company_name'},*/
                {
                    data: 'full_name',
                    name: 'full_name',
                    width: "10%",
                },
                {
                    data: 'purposed_school_name',
                    name: 'purposed_school_name',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'school_type',
                    name: 'school_type',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'agreement_type',
                    name: 'agreement_type',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'total_franchise_fee',
                    name: 'total_franchise_fee',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'amount_received',
                    name: 'amount_received',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'agreement_date',
                    name: 'agreement_date',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'operational_date',
                    name: 'operational_date',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'statuses',
                    name: 'statuses',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'franchise_application_dd_status',
                    name: 'franchise_application_dd_status',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'reports_action',
                    name: 'reports_action',
                    orderable: false,
                    searchable: false,
                    width: "5%"
                }
            ],
            order: [
                [0, "desc"]
            ]
        });
    });

    $(document).on('change', '.filter', function() {
        $('#qa-finance-onboarding-detail-table').DataTable().ajax.reload(null, false);
    });
</script>
<script type="text/javascript">
    // $("#start_date").datepicker({
    //     format: "mm",
    //     startView: "months",
    //     minViewMode: "months"
    // });
    // $("#end_date").datepicker({
    //     format: "mm-yyyy",
    //     startView: "months",
    //     minViewMode: "months"
    // });
    $(document).ready(function() {
        $('#finance-branch-detail-table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                //search: "",
                //searchPlaceholder: "Search...",
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
            },
            ajax: {
                url: "{{ route('branches.index') }}",
                data: function(d) {
                    d.company_id = $('#company_id').val();
                    // d.region_id = $('#region_id').val();
                    d.nwa_id = $('#nwa_id').val();
                    d.state_id = $('#branch_state_id').val();
                    d.searchTerm = $('#myInput').val().toLowerCase();
                }
            },
            columns: [
                /*{data: 'id', name: 'id', width: "5%",orderable: true},
                {data: 'company.company_name', name: 'company.company_name'},*/
                {
                    data: 'branch_code',
                    name: 'branch_code'
                },
                {
                    data: 'br_name',
                    name: 'br_name'
                },
                {
                    data: 'contact_information.state.state_name',
                    name: 'contact_information.state.state_name',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                // {
                //     data: 'region.region_name',
                //     name: 'region.region_name'
                // },
                {
                    data: 'nwa.user.name',
                    name: 'nwa.user.name',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'nwa.user.email',
                    name: 'nwa.user.email',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'nwa.contact_information.mobile',
                    name: 'nwa.contact_information.mobile',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
            ],
            order: [
                [0, "desc"]
            ]
        });
    });
    $(document).on('change', '.filter', function() {
        $('#finance-branch-detail-table').DataTable().ajax.reload(null, false);
    });
    $(document).ready(function() {
        $('#finance-onboarding-detail-table').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
            },
            ajax: {
                url: "{{ route('on-boarding-list') }}",
                data: function(d) {
                    d.state_id = $('#franchise_state_id').val();
                    d.city_id = $('#city').val();
                    d.school_type = $('#school_type').val();
                    d.status = $('#status_list').val();
                }
            },
            columns: [
                /*{data: 'id', name: 'id', width: "5%",orderable: true},
                {data: 'company.company_name', name: 'company.company_name'},*/
                {
                    data: 'full_name',
                    name: 'full_name',
                    width: "10%",
                },
                {
                    data: 'purposed_school_name',
                    name: 'purposed_school_name',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'school_type',
                    name: 'school_type',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'agreement_type',
                    name: 'agreement_type',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'total_franchise_fee',
                    name: 'total_franchise_fee',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'amount_received',
                    name: 'amount_received',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'agreement_date',
                    name: 'agreement_date',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'operational_date',
                    name: 'operational_date',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'statuses',
                    name: 'statuses',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'franchise_application_dd_status',
                    name: 'franchise_application_dd_status',
                    width: "10%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "15%"
                },
                {
                    data: 'reports_action',
                    name: 'reports_action',
                    orderable: false,
                    searchable: false,
                    width: "5%"
                }
            ],
            order: [
                [0, "desc"]
            ]
        });
    });

    $(document).on('change', '.filter', function() {
        $('#finance-onboarding-detail-table').DataTable().ajax.reload(null, false);
    });
    $(document).ready(function() {
        $('#finance-student-detail-table').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                //search: "",
                //searchPlaceholder: "Search...",
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
            },
            ajax: {
                url: "{{ route('student-details-card') }}",
                data: function(d) {
                    d.academic_year_id = $('#student_academic_year_id').val();
                    d.region_id = $('#region_id').val();
                    d.state_id = $('#student_state_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    // d.searchTerm = $('#myInput').val().toLowerCase();
                }
            },
            columns: [
                /*{data: 'id', name: 'id', width: "5%",orderable: true},
                {data: 'company.company_name', name: 'company.company_name'},*/
                {
                    data: 'branch_code',
                    name: 'branch_code'
                },
                {
                    data: 'br_name',
                    name: 'br_name',
                    width: "25%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'on_roll_students',
                    name: 'on_roll_students',
                    width: "25%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'registered_students',
                    name: 'registered_students',
                    width: "25%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'left_students',
                    name: 'left_students',
                    width: "25%",
                    'defaultContent': '<i>-</i>'
                },
                {
                    data: 'employee',
                    name: 'employee',
                    width: "25%",
                    'defaultContent': '<i>-</i>'
                },
            ],
            order: [
                [0, "desc"]
            ]
        });
    });

    $(document).on('change', '.filter', function() {
        $('#finance-student-detail-table').DataTable().ajax.reload(null, false);
    });
    $(document).on('change', '#company_id', function(e) {

        $.ajax({

            url: "{{ route('list-network-associates') }}?id=" + $(this).val(),
            type: "GET",
            cache: false,
            success: function(data) {

                var options = `<option value="">Please select</option>`;

                if (data) {
                    console.log(data)
                    $.each(data, function(index, value) {
                        options += '<option value="' + value.id + '">' + value.user.name +
                            '</option>';
                    });
                }

                $('#nwa_id').html(options).attr('disabled', false);
            },
            error: function() {

            },
            beforeSend: function() {
                showLoading();
            },
            complete: function() {
                hideLoading();
            }
        });
    });

</script>
@endpush
