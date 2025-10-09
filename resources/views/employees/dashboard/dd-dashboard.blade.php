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
                                <select class="form-select" id="state_id" name="state_id" aria-label="State select">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="region_id" class="form-label">State / Province</label>
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
                        <table id="branch-detail-table" class="table table-nowrap table-centered align-middle">
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
                                <select class="form-select" id="student_state_id" name="student_state_id"
                                    aria-label="State select" placeholder="State">
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
                        <table id="student-detail-table" class="table table-nowrap table-centered align-middle">
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
                    <div class="flex-shrink-0">
                        <a href="https://drive.google.com/drive/folders/1MlQdUWnB7oaaJ-rb9G0lc0IpXaDWvwge" target="_blank"
                            class="btn btn-sm btn-primary pull-right ml-3">
                            Franchise Agreements
                        </a>
                    </div>
                </div><!-- end cardheader -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter load-select form-select" id="franchise_state_id"
                                    name="franchise_state_id" placeholder="state" data-target="city_id"
                                    data-url="{{ route('list-cities') }}" aria-label="State select" required>
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
                                <select class="filter form-select" id="school_type" name="school_type"
                                    aria-label="Agreement select">
                                    <option value="">Please select</option>
                                    @foreach ($school_type as $school_type)
                                        <option value="{{ $school_type->id }}">
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
                        <table id="onboarding-detail-table" class="table table-nowrap table-centered align-middle">
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
                            <table id="fee-structure-table"
                                class="table mb-0 align-middle table-bordered table-striped table-nowrap"
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
                                                    $approvedFeeDetail = $record->new_fee_structure_details
                                                        ->where('fee_status_by_dd', 'Approved')
                                                        ->first();
                                                @endphp

                                                @if ($approvedFeeDetail)
                                                    {{ $approvedFeeDetail->final_fee_charges }}
                                                @else
                                                    Not Approved
                                                @endif
                                            </td>

                                            <td>
                                                @php
                                                    $approvedFeeDetail = $record->new_fee_structure_details
                                                        ->where('fee_status_by_dd', 'Approved')
                                                        ->first();
                                                @endphp

                                                @if ($approvedFeeDetail)
                                                    {{ $approvedFeeDetail->round_final_fee_charges ?? 'N/A' }}
                                                @else
                                                    Not Approved
                                                @endif
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                                    id="view_detail" data-id="record_Id"
                                                    data-target="#viewModal{{ $record->id }}">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $approvedFeeDetail = $record->new_fee_structure_details
                                                        ->where('fee_status_by_dd', 'Approved')
                                                        ->first();
                                                @endphp

                                                @if ($approvedFeeDetail)
                                                    {{ $approvedFeeDetail->approval_date ?? 'N/A' }}
                                                @else
                                                    Not Approved
                                                @endif
                                            </td>

                                            <td>
                                                @permission('fee-structure-edit-index')
                                                    <a href="{{ route('fee_structure.index_edit', $record->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endpermission
                                                @permission('fee-structure-delete-index')
                                                    <form action="{{ route('fee_structure.destroy', $record->id) }}"
                                                        method="post" class="d-inline"
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
                                        <div class="modal fade" id="viewModal{{ $record->id }}" tabindex="1"
                                            role="dialog" aria-labelledby="viewModalLabel{{ $record->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel{{ $record->id }}">
                                                            Fee
                                                            Option Details
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
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
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
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
    {{-- Visitor Table start --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        @if (isset(request()->req) && request()->req == 'me' && !isSuperAdmin())
                            Requested
                        @else
                            Employee
                        @endif Visits Details
                    </h4>
                    {{-- <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                                class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                    </div> --}}
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        @if (isSuperAdmin() || (isset(request()->req) && request()->req == 'me'))
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="s_user_id" name="s_user_id"
                                        aria-label="Employee select">
                                        <option value="">Please select</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->user_id }}">
                                                {{ $employee->user->name . ' [' . $employee->department->department_name . ']' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="employee" class="form-label">Employee</label>

                                </div>
                            </div>
                        @endif
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_campus_office_id" name="s_campus_office_id"
                                    aria-label="Campus Office select">
                                    <option value="">Please select</option>
                                    @foreach ($campus_types as $campus_type)
                                        <option value="{{ $campus_type->id }}">{{ $campus_type->type }}</option>
                                    @endforeach
                                </select>
                                <label for="s_campus_office_id" class="form-label">Campus/Office Type</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_branch_id" name="s_branch_id"
                                    aria-label="Branch select">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="s_branch_id" class="form-label">Campus Name</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_from_city_id" name="s_from_city_id"
                                    aria-label="City from select">
                                    <option value="">Please select</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                <label for="s_from_city_id" class="form-label">From Location</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_to_city_id" name="s_to_city_id"
                                    aria-label="City select">
                                    <option value="">Please select</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                <label for="s_to_city_id" class="form-label">To Location</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_total_duration" name="s_total_duration"
                                    aria-label="Duration select">
                                    <option value="">Please select</option>
                                    <option value="0.25 Day">0.25 Day</option>
                                    <option value="0.5 Day">0.5 Day</option>
                                    <option value="1 Day">1 Day</option>
                                    <option value="2 Days">2 Days</option>
                                    <option value="3 Days">3 Days</option>
                                    <option value="4 Days">4 Days</option>
                                    <option value="5 Days">5 Days</option>
                                </select>
                                <label for="s_total_duration" class="form-label">No. of Hours / Days</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_approval_status" name="s_approval_status"
                                    aria-label="Visit Status Select">
                                    <option value="">Please select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approve">Approved</option>
                                    <option value="cancel">Cancelled</option>
                                </select>
                                <label for="s_approval_status" class="form-label">Visit Status</label>
                            </div>
                        </div>
                        {{-- @role('super_admin')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                    <label for="mySearch" class="form-label">Search...</label>
                                </div>
                            </div>
                        @endrole --}}
                    </div>
                    <table id="visits-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Name</th>
                                <th>Department</th>
                                <th>Campus/Office Type</th>
                                <th>Campus Name</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>No. Of Hours / Days</th>
                                <th>Travel On</th>
                                <th>Return On</th>
                                <th>Travel Mode</th>
                                <th style="width: 20em;">Purpose</th>
                                <th style="width: 20em;">Remarks</th>
                                <th>Status</th>
                                <th>Approval Auth</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Name</th>
                                <th>Department</th>
                                <th>Campus/Office Type</th>
                                <th>Campus Name</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>No. Of Hours / Days</th>
                                <th>Travel On</th>
                                <th>Return On</th>
                                <th>Travel Mode</th>
                                <th>Purpose</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Approval Auth</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>


                </div>
            </div>
        </div>
    </div><!-- end row -->
    {{-- visitor  Table End --}}
@endsection
@push('header_scripts')
    <link rel="stylesheet" href="{{ asset('theme/dist/default/assets/libs/dragula/dragula.min.css') }}" />
@endpush

@push('footer_scripts')
    <script src="{{ asset('theme/dist/default/assets/libs/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/libs/dom-autoscroller/dom-autoscroller.min.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">

    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>



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
            $('#branch-detail-table').DataTable({
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
                        d.region_id = $('#region_id').val();
                        d.nwa_id = $('#nwa_id').val();
                        d.state_id = $('#state_id').val();
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
            $('#branch-detail-table').DataTable().ajax.reload(null, false);
        });
        $(document).ready(function() {
            $('#onboarding-detail-table').DataTable({
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
            $('#onboarding-detail-table').DataTable().ajax.reload(null, false);
        });
        $(document).ready(function() {
            $('#student-detail-table').DataTable({
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
                        // d.region_id = $('#region_id').val();
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
            $('#student-detail-table').DataTable().ajax.reload(null, false);
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

        $(document).on("keyup", '#myInput', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 1 || value.length == 0) {
                $('#branch-detail-table').DataTable().ajax.reload(null, false);
            }
        });
    </script>
    <script>
        // var academic_year_id_graph = $('#academic_year_id_graph').val();
        $(document).ready(function() {
            ajaxcall(academic_year_id_graph = $('#academic_year_id_graph').val());
            $(document).on('change', '#academic_year_id_graph', function(e) {
                ajaxcall(academic_year_id_graph = $('#academic_year_id_graph').val());
            });
        });

        function getChartColorsArray(e) {
            if (null !== document.getElementById(e)) {
                var e = document.getElementById(e).getAttribute("data-colors");
                return (e = JSON.parse(e)).map(function(e) {
                    var t = e.replace(" ", "");
                    if (-1 === t.indexOf(",")) {
                        var r = getComputedStyle(document.documentElement).getPropertyValue(t);
                        return r || t
                    }
                    e = e.split(",");
                    return 2 != e.length ? t : "rgba(" + getComputedStyle(document.documentElement)
                        .getPropertyValue(e[0]) + "," + e[1] + ")"
                })
            }
        }
        //Line graph. to get the url parametes month, week, day


        function ajaxcall(academic_year_id_graph) {
            // var academic_year_id_graph = $('#academic_year_id_graph').val();
            $.ajax({
                type: "GET",
                //url: "/get-linegraph",
                url: '{{ route('graph-data') }}',
                data: {
                    academic_year_id_graph: academic_year_id_graph
                },
                processing: true,
                dataType: 'json',
                success: function(res) {
                    console.log('the response', res);
                    $('#OR').html(res[0].onroll);
                    $('#R').html(res[0].register);
                    $('#L').html(res[0].left);
                    // $('#SF').html(response.fee.amount);
                    var linechartcustomerColors = getChartColorsArray("projects-overview-chart"),
                        options = {
                            series: [{
                                    name: "Register Students",
                                    type: "bar",
                                    data: [res[0].registered[0], res[0].registered[1], res[0].registered[2],
                                        res[0].registered[3], res[0].registered[4], res[0].registered[
                                            5], res[0].registered[6], res[0].registered[7], res[0]
                                        .registered[8], res[0].registered[9], res[0].registered[10],
                                        res[0].registered[11]
                                    ],
                                    // data: [res.registered.aug, res.registered.sep],
                                },
                                {
                                    name: "On roll Students",
                                    type: "area",
                                    data: [res[1].on_roll[0], res[1].on_roll[1], res[1].on_roll[2], res[1]
                                        .on_roll[3], res[1].on_roll[4], res[1].on_roll[5], res[1]
                                        .on_roll[6], res[1].on_roll[7], res[1].on_roll[8], res[1]
                                        .on_roll[9], res[1].on_roll[10], res[1].on_roll[11]
                                    ],

                                },
                                {
                                    name: "Left students",
                                    type: "bar",
                                    data: [res[2].lefts[0], res[2].lefts[2], res[2].lefts[2], res[2].lefts[
                                        3], res[2].lefts[4], res[2].lefts[5], res[2].lefts[6], res[
                                        2].lefts[7], res[2].lefts[8], res[2].lefts[9], res[2].lefts[
                                        10], res[2].lefts[11]],

                                }
                            ],
                            chart: {
                                height: 374,
                                type: "line",
                                toolbar: {
                                    show: !1
                                }
                            },
                            stroke: {
                                curve: "smooth",
                                dashArray: [0, 3, 0],
                                width: [0, 1, 0]
                            },
                            fill: {
                                opacity: [1, .1, 1]
                            },
                            markers: {
                                size: [0, 4, 0],
                                strokeWidth: 2,
                                hover: {
                                    size: 4
                                }
                            },
                            xaxis: {
                                categories: ["Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr",
                                    "May", "Jun", "Jul"
                                ],
                                axisTicks: {
                                    show: !1
                                },
                                axisBorder: {
                                    show: !1
                                }
                            },
                            grid: {
                                show: !0,
                                xaxis: {
                                    lines: {
                                        show: !0
                                    }
                                },
                                yaxis: {
                                    lines: {
                                        show: !1
                                    }
                                },
                                padding: {
                                    top: 0,
                                    right: -2,
                                    bottom: 15,
                                    left: 10
                                }
                            },
                            legend: {
                                show: !0,
                                horizontalAlign: "center",
                                offsetX: 0,
                                offsetY: -5,
                                markers: {
                                    width: 9,
                                    height: 9,
                                    radius: 6
                                },
                                itemMargin: {
                                    horizontal: 10,
                                    vertical: 0
                                }
                            },
                            plotOptions: {
                                bar: {
                                    columnWidth: "30%",
                                    barHeight: "70%"
                                }
                            },
                            colors: linechartcustomerColors,
                            tooltip: {
                                shared: !0,
                                y: [{
                                        formatter: function(e) {
                                            return void 0 !== e ? e.toFixed(0) : e
                                        }
                                    },
                                    {
                                        formatter: function(e) {
                                            return void 0 !== e ? e.toFixed(0) : e
                                        }
                                    },
                                    {
                                        formatter: function(e) {
                                            return void 0 !== e ? e.toFixed(0) : e
                                        }
                                    }
                                ]
                            }
                        },
                        chart = new ApexCharts(document.querySelector("#projects-overview-chart"), options);
                    chart.render();
                    chart.update();
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#state_id').change(function() {
                updateTable($(this));
            });

            // Attach city change event
            $('#city').change(function() {
                updateTable(false);
            });

            $('#academic_year_id').change(function() {
                updateTable(false);
            });

            // Function to update the DataTable with filtered data
            function updateTable(thiss) {
                const stateId = $('#state_id').val();
                const cityId = $('#city').val();
                const academicYear = $('#academic_year_id').val();

                if (thiss) {
                    const url = thiss.data('city-url');
                    const target = thiss.data('target');
                    const target_name = target?.split('_')[0];

                    $.ajax({
                        url: url + '?id=' + thiss.val(),
                        type: "GET",
                        cache: false,
                        success: function(data) {
                            console.log('here after the city');
                            var options = `<option value="">Please select a ${target_name}</option>`;
                            $.each(data, function(index, value) {
                                console.log('in the last else');
                                options +=
                                    '<option value="' + value.id + '">' + value[
                                        `${target_name}_name`] + '</option>';
                            });
                            $('select[name="' + target + '"]').html(options).attr('disabled', false);
                        }
                    });
                }

                $.ajax({
                    url: '{{ route('fee-structure-filter') }}',
                    type: 'GET',
                    data: {
                        state_id: stateId,
                        city_id: cityId,
                        academic_year_id: academicYear,
                    },
                    success: function(data) {
                        $('.fee-structure-table-wrapper').html(data);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('Error fetching data:', errorThrown);
                    }
                });
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });


            $('#visits-data-table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },

                ajax: {
                    url: "{{ route('dd-visit-request') }}",
                    data: function(d) {
                        //d.s_user_id = $('#s_user_id').val();
                        d.campus_office_id = $('#s_campus_office_id').val();
                        d.branch_id = $('#s_branch_id').val();
                        d.from_city_id = $('#s_from_city_id').val();
                        d.to_city_id = $('#s_to_city_id').val();
                        d.total_duration = $('#s_total_duration').val();
                        d.approval_status = $('#s_approval_status').val();
                    }
                },
                columns: [
                    // {
                    //     data: 'id',
                    //     name: 'id',
                    //     width: "5%"
                    // },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'campus_office',
                        name: 'campus_office'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'city_from',
                        name: 'city_from'
                    },
                    {
                        data: 'city_to',
                        name: 'city_to'
                    },
                    {
                        data: 'total_duration',
                        name: 'total_duration'
                    },
                    {
                        data: 'travel_on',
                        name: 'travel_on'
                    },
                    {
                        data: 'return_on',
                        name: 'return_on'
                    },
                    {
                        data: 'travel_mode',
                        name: 'travel_mode'
                    },
                    {
                        data: 'purpose',
                        name: 'purpose',
                        // render: function ( data, type, row ) {
                        //     return '<span style="white-space:normal; width: 150px;">' + data + "</span>";
                        // }
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        // render: function ( data, type, row ) {
                        //     return '<span style="white-space:normal; width: 40em;">' + data + "</span>";
                        // }
                    },
                    {
                        data: 'approval_status',
                        // name: 'approval_status',
                        render: function(data, type, row) {
                            if (data == 'pending') {
                                return '<span class="badge bg-warning text-bold">Pending</span>';
                            } else if (data == 'approve') {
                                return '<span class="badge bg-success text-bold">Approved</span>';
                            } else {
                                return '<span class="badge bg-danger text-bold">Cancelled</span>';
                            }

                        }
                    },
                    {
                        data: 'approval_auth',
                        name: 'approval_auth',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: "text-center"
                    },
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        });
        // @role('super_admin')
        // $(document).on("keyup", '#mySearch', function() {
        //     var value = $(this).val().toLowerCase();
        //     if (value.length > 0 || value.length == 0) {
        //         $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        //     }
        // });
        // @endrole
    </script>
@endpush
