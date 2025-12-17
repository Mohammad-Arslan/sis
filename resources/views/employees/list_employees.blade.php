@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Search Employee List </h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('employees.index') }}" class="btn btn-success-new btn-label btn-sm">
                        <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                    </a>
                    <button type="button"
                        class="btn btn-sm btn-primary btn-label waves-effect waves-light import-employees-btn"
                        href=""><i class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</button>
                    <button type="button" class="btn btn-sm btn-success-new btn-label waves-effect waves-light" id="exportEmployeesBtn">
                        <i class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export Employees
                    </button>
                    <button type="button" class="btn btn-sm btn-info btn-label waves-effect waves-light" id="viewExportProgressBtn" style="display: none;">
                        <i class="ri-eye-line label-icon align-middle fs-16 me-2"></i> View Export Progress
                    </button>
                    <a href="{{ route('employees.create') }}?tab=basic_info" class="btn btn-success-new btn-label btn-sm">
                        <i class="ri-user-line label-icon align-middle fs-16 me-2"></i> Add New Employee
                    </a>
                    <button type="button"
                        class="btn btn-sm btn-warning btn-label waves-effect waves-light"
                        id="viewImportLogsBtn">
                        <i class="ri-file-list-3-line label-icon align-middle fs-16 me-2"></i> Logs
                    </button>
                    <button type="button"
                        class="btn btn-sm btn-info btn-label waves-effect waves-light"
                        id="viewImportProgressBtn" style="display: none;">
                        <i class="ri-progress-1-line label-icon align-middle fs-16 me-2"></i> Progress
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @role('super_admin|human_resource|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="company_id" name="company_id" placeholder="Company">
                                        <option value="">Please select</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="company_id" class="form-label">Company</label>
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
                                    <label for="designation_id" class="form-label">Branch</label>
                                </div>
                            </div>
                        @endrole
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="department_id" name="department_id"
                                    placeholder="Department">
                                    <option value="">Please select</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="department_id" class="form-label">Department</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="designation_id" name="designation_id"
                                    placeholder="Designation">
                                    <option value="">Please select</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->designation_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="designation_id" class="form-label">Designation</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="gender" name="gender" placeholder="Gender">
                                    <option value="">Please select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <label for="gender" class="form-label">Gender</label>
                            </div>
                        </div>
                        @role('super_admin')
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                    <label for="mySearch" class="form-label">Search...</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="ri-refresh-line me-1"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        @endrole
                    </div>
                    <table id="employee-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                    <th>Company</th>
                                    <th>Branch Code</th>
                                    <th>Branch</th>
                                @endrole
                                <th>Department</th>
                                <th>Designation</th>
                                <th>City</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                                    <th>Company</th>
                                    <th>Branch Code</th>
                                    <th>Branch</th>
                                @endrole
                                <th>Department</th>
                                <th>Designation</th>
                                <th>City</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>


                </div>
            </div>
        </div>
    </div>

    <!-- Include the import modal -->
    @include('employees.employee_import_modal')

    <!-- Modal for Import Error Logs -->
    <div class="modal fade" id="importLogsModal" tabindex="-1" aria-labelledby="importLogsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="importLogsModalLabel">
              <i class="ri-error-warning-line me-2"></i>Import Error Logs
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Error Summary -->
            <div class="row mb-4" id="errorSummaryRow" style="display: none;">
              <div class="col-12">
                <h6 class="text-muted mb-3">Error Summary</h6>
                <div class="row g-2">
                  <div class="col-md-2">
                    <div class="card border-danger">
                      <div class="card-body text-center p-2">
                        <small class="text-danger fw-bold">Validation</small>
                        <div class="fs-5 text-danger" id="summaryValidationErrors">0</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="card border-warning">
                      <div class="card-body text-center p-2">
                        <small class="text-warning fw-bold">Import</small>
                        <div class="fs-5 text-warning" id="summaryImportErrors">0</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="card border-info">
                      <div class="card-body text-center p-2">
                        <small class="text-info fw-bold">Lookup</small>
                        <div class="fs-5 text-info" id="summaryLookupErrors">0</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="card border-secondary">
                      <div class="card-body text-center p-2">
                        <small class="text-secondary fw-bold">Missing</small>
                        <div class="fs-5 text-secondary" id="summaryMissingErrors">0</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="card border-dark">
                      <div class="card-body text-center p-2">
                        <small class="text-dark fw-bold">Database</small>
                        <div class="fs-5 text-dark" id="summaryDatabaseErrors">0</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="card border-primary">
                      <div class="card-body text-center p-2">
                        <small class="text-primary fw-bold">Total</small>
                        <div class="fs-5 text-primary" id="summaryTotalErrors">0</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
              <div class="col-md-4">
                <select class="form-select" id="errorTypeFilter">
                  <option value="">All Error Types</option>
                  <option value="validation_error">Validation Errors</option>
                  <option value="import_error">Import Errors</option>
                  <option value="lookup_error">Lookup Errors</option>
                  <option value="missing_fields">Missing Fields</option>
                  <option value="database_error">Database Errors</option>
                </select>
              </div>
              <div class="col-md-4">
                <button class="btn btn-outline-danger btn-sm" id="clearErrorLogsBtn">
                  <i class="ri-delete-bin-line me-1"></i>Clear Logs
                </button>
              </div>
              <div class="col-md-4 text-end">
                <small class="text-muted" id="errorLogsInfo">Loading...</small>
              </div>
            </div>

            <!-- Error Logs Table -->
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
              <table class="table table-sm table-hover">
                <thead class="table-dark sticky-top">
                  <tr>
                    <th width="5%">Row</th>
                    <th width="15%">Error Type</th>
                    <th width="20%">Field</th>
                    <th width="35%">Message</th>
                    <th width="15%">Value</th>
                    <th width="10%">Time</th>
                  </tr>
                </thead>
                <tbody id="errorLogsTableBody">
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                      <i class="ri-error-warning-line fs-1 d-block mb-2"></i>
                      No error logs found. Select an import to view its error logs.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Error logs pagination" class="mt-3">
              <ul class="pagination pagination-sm justify-content-center" id="errorLogsPagination">
                <!-- Pagination will be loaded here -->
              </ul>
            </nav>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-outline-primary" id="refreshErrorLogsBtn">
              <i class="ri-refresh-line me-1"></i>Refresh
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Import Progress Modal -->
    <div class="modal fade" id="importProgressModal" tabindex="-1" aria-labelledby="importProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="importProgressModalLabel">
                        <i class="ri-upload-cloud-2-line me-2"></i>Employee Import Progress
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" 
                                 role="progressbar" 
                                 style="width: 0%" 
                                 id="importProgressBar">0%</div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span id="importProgressText">Starting import...</span>
                            <span id="importProgressPercentage">0%</span>
                        </div>
                    </div>

                    <!-- Progress Statistics -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="ri-file-list-3-line text-primary fs-1 mb-2"></i>
                                    <h6 class="text-primary mb-1">Total Rows</h6>
                                    <h3 class="mb-0 text-primary" id="importTotalRows">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success h-100">
                                <div class="card-body text-center">
                                    <i class="ri-check-line text-success fs-1 mb-2"></i>
                                    <h6 class="text-success mb-1">Imported</h6>
                                    <h3 class="mb-0 text-success" id="importedCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <i class="ri-skip-forward-line text-warning fs-1 mb-2"></i>
                                    <h6 class="text-warning mb-1">Skipped</h6>
                                    <h3 class="mb-0 text-warning" id="importSkippedCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info h-100">
                                <div class="card-body text-center">
                                    <i class="ri-file-list-3-line text-info fs-1 mb-2"></i>
                                    <h6 class="text-info mb-1">Current Row</h6>
                                    <h3 class="mb-0 text-info" id="importCurrentRow">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="alert alert-info" id="importProgressMessage">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="spinner-border spinner-border-sm text-info" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>Status:</strong> <span id="importProgressStatus">Preparing...</span>
                                <br>
                                <span id="importProgressMessageText">Initializing import process...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error Details (hidden by default) -->
                    <div class="card border-danger" id="importErrorsCard" style="display: none;">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0">
                                <i class="ri-error-warning-line me-2"></i>
                                Import Errors (<span id="importErrorCount">0</span>)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Row</th>
                                            <th>Field</th>
                                            <th>Error</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="importErrorsTableBody">
                                        <!-- Error rows will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="importCloseBtn" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" id="importDownloadBtn" style="display: none;">
                        <i class="ri-download-line me-1"></i>Download Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Progress Modal -->
    <div class="modal fade" id="exportProgressModal" tabindex="-1" aria-labelledby="exportProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="exportProgressModalLabel">
                        <i class="ri-download-2-line me-2"></i>Employee Export Progress
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                 role="progressbar" 
                                 style="width: 0%" 
                                 id="exportProgressBar">0%</div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span id="exportProgressText">Starting export...</span>
                            <span id="exportProgressPercentage">0%</span>
                        </div>
                    </div>

                    <!-- Progress Statistics -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="ri-file-list-3-line text-primary fs-1 mb-2"></i>
                                    <h6 class="text-primary mb-1">Total Rows</h6>
                                    <h3 class="mb-0 text-primary" id="exportTotalRows">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success h-100">
                                <div class="card-body text-center">
                                    <i class="ri-check-line text-success fs-1 mb-2"></i>
                                    <h6 class="text-success mb-1">Exported</h6>
                                    <h3 class="mb-0 text-success" id="exportedCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <i class="ri-skip-forward-line text-warning fs-1 mb-2"></i>
                                    <h6 class="text-warning mb-1">Skipped</h6>
                                    <h3 class="mb-0 text-warning" id="exportSkippedCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info h-100">
                                <div class="card-body text-center">
                                    <i class="ri-file-list-3-line text-info fs-1 mb-2"></i>
                                    <h6 class="text-info mb-1">Current Row</h6>
                                    <h3 class="mb-0 text-info" id="exportCurrentRow">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="alert alert-info" id="exportProgressMessage">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="spinner-border spinner-border-sm text-info" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>Status:</strong> <span id="exportProgressStatus">Preparing...</span>
                                <br>
                                <span id="exportProgressMessageText">Initializing export process...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error Details (hidden by default) -->
                    <div class="card border-danger" id="exportErrorsCard" style="display: none;">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0">
                                <i class="ri-error-warning-line me-2"></i>
                                Export Errors (<span id="exportErrorCount">0</span>)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Row</th>
                                            <th>Employee ID</th>
                                            <th>Error</th>
                                        </tr>
                                    </thead>
                                    <tbody id="exportErrorsTableBody">
                                        <!-- Error rows will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="exportCloseModalBtn">
                        <i class="ri-close-line me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-success-new" id="exportDownloadBtn" style="display: none;">
                        <i class="ri-download-2-line me-1"></i>Download File
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script type="text/javascript">
        // Global variable for DataTable
        var employeeTable;

        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            // Initialize DataTable with optimized settings
            try {
                employeeTable = $('#employee-table').DataTable({
                    searching: false,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    bLengthChange: false,
                    ordering: true,
                    pageLength: 25,
                    scrollX: true,
                    deferRender: true,
                    scroller: true,
                    stateSave: true,
                    bAutoWidth: false,
                    bDestroy: true,
                    bRetrieve: true,
                    language: {
                        search: "",
                        processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                        searchPlaceholder: "Search...",
                        error: "Error loading data. Please refresh the page.",
                        emptyTable: "No employees found matching your criteria.",
                        info: "Showing _START_ to _END_ of _TOTAL_ employees",
                        infoEmpty: "Showing 0 to 0 of 0 employees",
                        infoFiltered: "(filtered from _MAX_ total employees)"
                    },
                    // Add debugging for AJAX requests
                    ajax: {
                        url: "{{ route('employees.index') }}",
                        type: 'GET',
                        data: function(d) {
                            // Capture all filter values
                            d.gender = $('#gender').val();
                            d.company_id = $('#company_id').val();
                            d.branch_id = $('#branch_id').val();
                            d.department_id = $('#department_id').val();
                            d.designation_id = $('#designation_id').val();
                            @role('super_admin')
                                d.searchName = $('#mySearch').val().toLowerCase();
                            @endrole
                            
                            // Debug logging
                            console.log('AJAX data being sent:', d);
                            
                            return d;
                        },
                        error: function(xhr, error, thrown) {
                            console.error('DataTables AJAX error:', error, thrown);
                            console.error('Response:', xhr.responseText);
                            
                            // Show user-friendly error message
                            $('#employee-table tbody').html('<tr><td colspan="11" class="text-center text-danger">Error loading data. Please refresh the page.</td></tr>');
                            
                            // Show error notification
                            if (typeof toastr !== 'undefined') {
                                toastr.error('Error loading employee data. Please try again.');
                            }
                        }
                    },
                    columns: [
                        {
                            data: 'employee_id',
                            name: 'employee_id'
                        },
                        {
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'user.gender',
                            name: 'user.gender'
                        },
                        @role('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')
                        {
                            data: 'company_name',
                            name: 'company.company_name'
                        },
                        {
                            data: 'branch_code',
                            name: 'branch.branch_code'
                        },
                        {
                            data: 'branch_name',
                            name: 'branch.br_name'
                        },
                        @endrole
                        {
                            data: 'department_name',
                            name: 'department.department_name'
                        },
                        {
                            data: 'designation_name',
                            name: 'designation.designation_name'
                        },
                        {
                            data: 'city_name',
                            name: 'cities.city_name'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            sClass: 'text-center'
                        }
                    ],
                    drawCallback: function(settings) {
                        // Handle empty results
                        if (settings.json && settings.json.recordsTotal === 0) {
                            $('#employee-table tbody').html('<tr><td colspan="11" class="text-center text-muted">No employees found matching your criteria.</td></tr>');
                        }
                    }
                });
                
                console.log('DataTable initialized successfully:', employeeTable);
                
                // Add a flag to indicate DataTable is ready
                window.dataTableReady = true;
                
            } catch (error) {
                console.error('Error initializing DataTable:', error);
                $('#employee-table tbody').html('<tr><td colspan="11" class="text-center text-danger">Error initializing table. Please refresh the page.</td></tr>');
                window.dataTableReady = false;
            }
        });

        // Function to safely reload table with filters
        function reloadTableWithFilters() {
            console.log('reloadTableWithFilters called');
            console.log('employeeTable exists:', !!employeeTable);
            console.log('employeeTable type:', typeof employeeTable);
            
            if (employeeTable && typeof employeeTable.ajax !== 'undefined') {
                console.log('Reloading table with current filters');
                try {
                    employeeTable.ajax.reload();
                    employeeTable.page('first').draw('page');
                    console.log('Table reload successful');
                } catch (error) {
                    console.error('Error reloading table:', error);
                }
            } else {
                console.error('DataTable not initialized properly');
                console.log('employeeTable:', employeeTable);
            }
        }

        // Function to check if DataTable is ready
        function isDataTableReady() {
            return window.dataTableReady && employeeTable && typeof employeeTable.ajax !== 'undefined';
        }

        // Safe filter change handler
        function handleFilterChange() {
            if (isDataTableReady()) {
                reloadTableWithFilters();
            } else {
                console.warn('DataTable not ready, waiting for initialization...');
                // Wait a bit and try again
                setTimeout(function() {
                    if (isDataTableReady()) {
                        reloadTableWithFilters();
                    } else {
                        console.error('DataTable still not ready after timeout');
                    }
                }, 1000);
            }
        }

        // Filter change handler
        $(document).on('change', '.filter', function() {
            console.log('Filter changed:', $(this).attr('id'), $(this).val());
            console.log('DataTable ready flag:', window.dataTableReady);
            console.log('employeeTable exists:', !!employeeTable);
            handleFilterChange();
        });

        // Also handle input events for better responsiveness
        $(document).on('input', '.filter', function() {
            console.log('Filter input:', $(this).attr('id'), $(this).val());
        });

        // Handle select2 changes if using select2
        $(document).on('select2:select select2:unselect', '.filter', function() {
            console.log('Select2 filter changed:', $(this).attr('id'), $(this).val());
            handleFilterChange();
        });

        @role('super_admin')
            // Debounced search to reduce AJAX calls
            var searchTimeout;
            $(document).on("keyup", '#mySearch', function() {
                clearTimeout(searchTimeout);
                var value = $(this).val().toLowerCase();
                console.log('Search value:', value);
                searchTimeout = setTimeout(function() {
                    if (value.length > 0 || value.length == 0) {
                        console.log('Reloading table with search:', value);
                        handleFilterChange();
                    }
                }, 500); // 500ms delay
            });
        @endrole

        // Memory cleanup and optimization
        $(window).on('beforeunload', function() {
            if (employeeTable && typeof employeeTable.destroy === 'function') {
                try {
                    employeeTable.destroy();
                } catch (error) {
                    console.error('Error destroying DataTable:', error);
                }
            }
        });

        // Clear filters functionality
        $(document).on('click', '#clearFilters', function() {
            console.log('Clearing all filters');
            // Clear all filter values
            $('.filter').val('');
            $('#mySearch').val('');
            
            // Reload the table with cleared filters
            handleFilterChange();
        });

        // Optimize table rendering on window resize
        var resizeTimeout;
        $(window).on('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                if (employeeTable && typeof employeeTable.columns !== 'undefined') {
                    try {
                        employeeTable.columns.adjust();
                    } catch (error) {
                        console.error('Error adjusting columns:', error);
                    }
                }
            }, 250);
        });

        // Employee Import Modal Functionality
        $('#employeeImportForm').on('submit', function(event) {
            event.preventDefault();
            const form = $(this);
            const submitBtn = $('#uploadBtn');
            const alertContainer = $('#alert-container');

            // Clear previous alerts
            alertContainer.empty();

            // Show progress bar and disable submit button
            $('#uploadProgress').show();
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="ri-loader-4-line me-1"></i>Processing...');

            // Simulate progress for better UX
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                $('#progressBar').css('width', progress + '%').text(Math.round(progress) + '%');
                $('#progressText').text('Processing file...');
            }, 200);

            try {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: new FormData(form[0]),
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        clearInterval(progressInterval);
                        $('#progressBar').css('width', '100%').text('100%');
                        $('#progressText').text('Import started successfully!');
                        $('#importEmployeesModal').modal('hide');
                        
                        if (result.success && result.import_id) {
                            // Store import ID globally for modal reopening
                            importId = result.import_id;
                            
                            // Show the progress button
                            $('#viewImportProgressBtn').show();
                            
                            // Show import progress modal
                            $('#importProgressModal').modal('show');
                            
                            // Load initial progress data
                            loadImportProgress(result.import_id);
                            
                            // Connect to WebSocket
                            connectToImportChannel(result.import_id);
                            
                            // Start progress polling as backup
                            importProgressInterval = setInterval(function() {
                                loadImportProgress(result.import_id);
                            }, 2000);
                        } else if (result.success) {
                            // Fallback for immediate completion
                            $('#successMessage').text(result.success);
                            
                            const importedCount = result.imported_count || 0;
                            const skippedCount = result.skipped_count || 0;
                            const totalProcessed = result.total_processed || (importedCount + skippedCount);
                            
                            $('#totalProcessed').text(totalProcessed);
                            $('#importedCount').text(importedCount);
                            $('#skippedCount').text(skippedCount);
                            $('#importedCountText').text(importedCount);
                            $('#skippedCountText').text(skippedCount);
                            
                            // Only show stats modal if there were actual imports or skips
                            if (totalProcessed > 0) {
                                $('#importStatsModal').modal('show');
                            }
                        }
                        
                        form[0].reset();
                    },
                    error: function(xhr) {
                        clearInterval(progressInterval);
                        $('#uploadProgress').hide();
                        let errorHtml = '';
                        
                        // Handle different types of errors
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            // Validation errors
                            if (typeof xhr.responseJSON.errors === 'object') {
                                errorHtml = Object.values(xhr.responseJSON.errors)
                                    .flat()
                                    .map(error => `<div>${error}</div>`)
                                    .join('');
                            } else if (Array.isArray(xhr.responseJSON.errors)) {
                                errorHtml = xhr.responseJSON.errors.map(error =>
                                    `<div>${error}</div>`).join('');
                            } else {
                                errorHtml = `<div>${xhr.responseJSON.errors}</div>`;
                            }
                        } else {
                            // General error message from server
                            errorHtml = `<div>${xhr.responseJSON?.message || xhr.statusText || 'Unknown error'}</div>`;
                        }
                        
                        // Display error message and keep modal open
                        alertContainer.html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Import Error:</strong><br>
                                ${errorHtml}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                        
                        // Ensure the import modal stays open
                        $('#importEmployeesModal').modal('show');
                        
                        // Scroll to the error message for better visibility
                        setTimeout(() => {
                            alertContainer[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false).html('Upload');
                    }
                });
            } catch (error) {
                clearInterval(progressInterval);
                $('#uploadProgress').hide();
                submitBtn.prop('disabled', false).html('Upload');
                
                alertContainer.html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Import Error:</strong><br>
                        <div>An unexpected error occurred: ${error.message}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });

        // Handle import button click
        $(document).on('click', '.import-employees-btn', function() {
            // Clear previous errors and alerts
            $('#alert-container').empty();
            $('.import-employees-form').removeClass('was-validated');
            $('.import-employees-form')[0].reset();
            
            // Reset progress bar
            $('#uploadProgress').hide();
            $('#progressBar').css('width', '0%').text('0%');
            $('#progressText').text('Preparing upload...');
            
            // Reset submit button
            $('#uploadBtn').prop('disabled', false).html('Upload');
            
            // Show the modal using Bootstrap 5 API
            const importModal = document.getElementById('importEmployeesModal');
            if (importModal) {
                // Remove any existing aria-hidden attribute that might be causing issues
                importModal.removeAttribute('aria-hidden');
                
                const modalInstance = new bootstrap.Modal(importModal, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                modalInstance.show();
                
                console.log('Import modal should be showing now');
            } else {
                console.error('Import modal element not found');
            }
        });
        
        // Handle modal close events to ensure proper cleanup
        $('#importEmployeesModal').on('hidden.bs.modal', function() {
            // Clear any remaining error messages when modal is closed
            $('#alert-container').empty();
            $('#uploadProgress').hide();
            $('#progressBar').css('width', '0%').text('0%');
            $('#progressText').text('Preparing upload...');
            $('#uploadBtn').prop('disabled', false).html('Upload');
        });

        // Handle import progress modal close events
        $('#importProgressModal').on('hidden.bs.modal', function() {
            // Disconnect from WebSocket
            if (importChannel) {
                importChannel.unsubscribe();
                importChannel = null;
            }
            
            // Clear progress polling
            if (importProgressInterval) {
                clearInterval(importProgressInterval);
                importProgressInterval = null;
            }
            
            // Reset progress UI
            $('#importProgressBar').css('width', '0%').text('0%');
            $('#importProgressPercentage').text('0%');
            $('#importProgressText').text('Starting import...');
            $('#importTotalRows').text('0');
            $('#importedCount').text('0');
            $('#importSkippedCount').text('0');
            $('#importCurrentRow').text('0');
            $('#importErrorsCard').hide();
            $('#importDownloadBtn').hide();
            
            // Refresh the employee table
            $('#employee-table').DataTable().ajax.reload();
        });

        // Handle logs button click (updated to use new error logs system)
        $(document).on('click', '#viewImportLogsBtn', function() {
            if (importId) {
                // Show error logs modal
                const logsModal = new bootstrap.Modal(document.getElementById('importLogsModal'));
                logsModal.show();
                
                // Load error logs
                loadErrorLogs(importId, 1, '');
            } else {
                alert('No import ID available. Please start an import first.');
            }
        });

        // Legacy logs button handler (keeping for compatibility)
        $(document).on('click', '#viewImportLogsBtnLegacy', function() {
            $.ajax({
                url: '{{ route("import.stats") }}',
                method: 'GET',
                success: function(response) {
                    if (response.log_file_exists && response.log_content) {
                        try {
                            // Parse the log content and create a table
                            const logLines = response.log_content.trim().split('\n');
                            let tableHtml = `
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Type</th>
                                                <th>Row</th>
                                                <th>Field</th>
                                                <th>Error</th>
                                                <th>Value</th>
                                                <th>Timestamp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;
                            
                            let logCount = 0;
                            logLines.forEach(function(line) {
                                if (line.trim()) {
                                    try {
                                        const logEntry = JSON.parse(line);
                                        
                                        // Get row number - now properly tracked in the import
                                        let rowNumber = 'N/A';
                                        if (logEntry.row !== null && logEntry.row !== undefined) {
                                            if (typeof logEntry.row === 'number') {
                                                rowNumber = 'Row ' + logEntry.row;
                                            } else {
                                                rowNumber = 'Row ' + logEntry.row;
                                            }
                                        }
                                        
                                        // Get the actual problematic value
                                        let problematicValue = 'N/A';
                                        if (logEntry.value) {
                                            // For import errors, the value should now be specific, not raw JSON
                                            if (logEntry.type === 'import_error') {
                                                problematicValue = logEntry.value;
                                            } else if (typeof logEntry.value === 'string' && logEntry.value.startsWith('Row data: ')) {
                                                // Extract specific field value from row data (for backward compatibility)
                                                try {
                                                    const rowData = JSON.parse(logEntry.value.replace('Row data: ', ''));
                                                    if (logEntry.field && rowData[logEntry.field]) {
                                                        problematicValue = rowData[logEntry.field];
                                                    } else {
                                                        problematicValue = logEntry.value.substring(0, 100) + (logEntry.value.length > 100 ? '...' : '');
                                                    }
                                                } catch (e) {
                                                    problematicValue = logEntry.value.substring(0, 100) + (logEntry.value.length > 100 ? '...' : '');
                                                }
                                            } else {
                                                problematicValue = logEntry.value;
                                            }
                                        }
                                        
                                        // Format the value for better display
                                        if (problematicValue !== 'N/A' && typeof problematicValue === 'string' && problematicValue.length > 50) {
                                            problematicValue = problematicValue.substring(0, 50) + '...';
                                        }
                                        
                                        const field = logEntry.field || 'N/A';
                                        const error = logEntry.error || 'N/A';
                                        const timestamp = logEntry.timestamp || 'N/A';
                                        
                                        // Determine row class based on error type
                                        let rowClass = '';
                                        if (logEntry.type === 'validation_error') rowClass = 'table-warning';
                                        else if (logEntry.type === 'lookup_error') rowClass = 'table-info';
                                        else if (logEntry.type === 'import_error') rowClass = 'table-danger';
                                        else if (logEntry.type === 'missing_fields') rowClass = 'table-secondary';
                                        
                                        tableHtml += `
                                            <tr class="${rowClass}">
                                                <td><span class="badge bg-${getBadgeColor(logEntry.type)}">${logEntry.type || 'N/A'}</span></td>
                                                <td><strong>${rowNumber}</strong></td>
                                                <td>${field}</td>
                                                <td>${error}</td>
                                                <td><code>${problematicValue}</code></td>
                                                <td><small>${timestamp}</small></td>
                                            </tr>
                                        `;
                                        logCount++;
                                    } catch (parseError) {
                                        // If JSON parsing fails, show the raw line
                                        tableHtml += `
                                            <tr class="table-secondary">
                                                <td colspan="6"><small>${line}</small></td>
                                            </tr>
                                        `;
                                    }
                                }
                            });
                            
                            tableHtml += `
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <p class="text-muted"><strong>Total Log Entries:</strong> ${logCount}</p>
                                </div>
                            `;
                            
                            $('#importLogsContent').html(tableHtml);
                        } catch (error) {
                            // Fallback to raw display if table creation fails
                            $('#importLogsContent').html(`
                                <div class="alert alert-info">
                                    <h6>Import Log Details:</h6>
                                    <pre style="max-height: 400px; overflow-y: auto;">${response.log_content}</pre>
                                </div>
                            `);
                        }
                    } else {
                        $('#importLogsContent').html(`
                            <div class="alert alert-warning">
                                <h6>No Import Logs Found</h6>
                                <p>No import logs are available. This could mean:</p>
                                <ul>
                                    <li>No imports have been performed yet</li>
                                    <li>The log file has been cleared</li>
                                    <li>There were no errors during the last import</li>
                                </ul>
                            </div>
                        `);
                    }
                    $('#importLogsModal').modal('show');
                },
                error: function() {
                    $('#importLogsContent').html(`
                        <div class="alert alert-danger">
                            <h6>Error Loading Logs</h6>
                            <p>Failed to load import logs. Please try again later.</p>
                        </div>
                    `);
                    $('#importLogsModal').modal('show');
                }
            });
        });

        // Helper function to get badge color based on log type
        function getBadgeColor(type) {
            switch(type) {
                case 'validation_error': return 'warning';
                case 'lookup_error': return 'info';
                case 'missing_fields': return 'secondary';
                case 'import_error': return 'danger';
                case 'error': return 'danger';
                default: return 'primary';
            }
        }

        // Function to refresh the employee table
        function refreshEmployeeTable() {
            $('#employee-table').DataTable().ajax.reload();
            $('#importStatsModal').modal('hide');
        }

        // Function to download import report
        function downloadImportReport() {
            const importedCount = $('#importedCount').text();
            const skippedCount = $('#skippedCount').text();
            const totalProcessed = $('#totalProcessed').text();
            const timestamp = new Date().toLocaleString();
            
            const reportContent = `Employee Import Report
Generated on: ${timestamp}

Summary:
- Total Records Processed: ${totalProcessed}
- New Employees Imported: ${importedCount}
- Skipped Records: ${skippedCount}

Details:
${importedCount} new employees were successfully imported into the system.
${skippedCount} existing employees were skipped to prevent duplicates.

This report was generated automatically by the SuperNova SIS system.`;

            const blob = new Blob([reportContent], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `employee_import_report_${new Date().toISOString().split('T')[0]}.txt`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        }

        // Export functionality
        let exportId = null;
        let exportChannel = null;
        let isExportInProgress = false;

        // Handle direct export button click
        $(document).on('click', '#exportEmployeesBtn', function() {
            const exportBtn = $(this);
            
            // Disable button and show loading
            exportBtn.prop('disabled', true);
            exportBtn.html('<i class="ri-loader-4-line ri-spin me-1"></i> Starting Export...');
            
            // Create form data with no filters (export all data)
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            
            // Submit via AJAX
            fetch('{{ route("employees.export") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exportId = data.export_id;
                    isExportInProgress = true;
                    
                    // Show progress modal
                    const progressModal = new bootstrap.Modal(document.getElementById('exportProgressModal'));
                    progressModal.show();
                    
                    // Show view progress button
                    $('#viewExportProgressBtn').show();
                    
                    // Load initial progress data
                    loadExportProgress(data.export_id);
                    
                    // Connect to WebSocket
                    connectToExportChannel(exportId);
                    
                    // Reset export button
                    exportBtn.prop('disabled', false);
                    exportBtn.html('<i class="ri-download-2-line me-1"></i> Export Employees');
                } else {
                    throw new Error(data.message || 'Export failed');
                }
            })
            .catch(error => {
                console.error('Export error:', error);
                alert('Export failed: ' + error.message);
                
                // Re-enable button
                exportBtn.prop('disabled', false);
                exportBtn.html('<i class="ri-download-2-line me-1"></i> Export Employees');
            });
        });

        // Import Progress Tracking Variables
        let importChannel = null;
        let importProgressInterval = null;

        // Connect to Import WebSocket channel for real-time updates
        function connectToImportChannel(importId) {
            // Initialize Pusher/Reverb
            const pusher = new Pusher('{{ config("broadcasting.connections.reverb.key") }}', {
                wsHost: '{{ config("broadcasting.connections.reverb.options.host") }}',
                wsPort: {{ config('broadcasting.connections.reverb.options.port') }},
                wssPort: {{ config('broadcasting.connections.reverb.options.port') }},
                forceTLS: {{ config('broadcasting.connections.reverb.options.scheme') === 'https' ? 'true' : 'false' }},
                enabledTransports: ['ws', 'wss'],
                cluster: 'mt1',
                disableStats: true
            });

            // Subscribe to public channel
            importChannel = pusher.subscribe('employee-import.' + importId);
            
            // Listen for progress updates
            importChannel.bind('import.progress', function(data) {
                console.log('Import progress update:', data);
                updateImportProgress(data);
            });

            // Handle connection errors
            pusher.connection.bind('error', function(err) {
                console.error('Import WebSocket connection error:', err);
            });

            // Handle successful connection
            pusher.connection.bind('connected', function() {
                console.log('Connected to Import WebSocket');
            });
        }

        // Update import progress UI
        function updateImportProgress(data) {
            const progressBar = document.getElementById('importProgressBar');
            const progressText = document.getElementById('importProgressText');
            const progressPercentage = document.getElementById('importProgressPercentage');
            const progressStatus = document.getElementById('importProgressStatus');
            const progressMessageText = document.getElementById('importProgressMessageText');
            const progressMessageContainer = document.getElementById('importProgressMessage');
            
            // Update counts
            const totalRows = document.getElementById('importTotalRows');
            const importedCount = document.getElementById('importedCount');
            const skippedCount = document.getElementById('importSkippedCount');
            const currentRow = document.getElementById('importCurrentRow');
            const errorsCard = document.getElementById('importErrorsCard');
            const errorCount = document.getElementById('importErrorCount');
            const errorsTableBody = document.getElementById('importErrorsTableBody');
            const downloadBtn = document.getElementById('importDownloadBtn');
            
            // Update counts (with null checks)
            if (totalRows) totalRows.textContent = data.total || 0;
            if (importedCount) importedCount.textContent = data.imported || 0;
            if (skippedCount) skippedCount.textContent = data.skipped || 0;
            if (currentRow) currentRow.textContent = data.current_row || 0;
            
            // Update status (with null checks)
            if (progressStatus) {
                progressStatus.textContent = getImportStatusText(data.status);
                progressStatus.className = 'badge fs-6 ' + getImportStatusBadgeClass(data.status);
            }
            if (progressMessageText) {
                progressMessageText.textContent = data.message || 'Processing...';
            }
            
            // Update progress bar (with null checks)
            const percentage = data.percentage || 0;
            if (progressBar) {
                progressBar.style.width = percentage + '%';
                progressBar.textContent = percentage.toFixed(1) + '%';
            }
            if (progressPercentage) {
                progressPercentage.textContent = percentage.toFixed(1) + '%';
            }
            
            // Update progress text (with null checks)
            if (progressText) {
                progressText.textContent = `${data.processed || 0} of ${data.total || 0} rows processed`;
            }
            
            // Handle different statuses
            if (data.status === 'completed') {
                if (progressMessageContainer) {
                    progressMessageContainer.className = 'alert alert-success';
                    progressMessageContainer.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-check-circle-fill text-success fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>Import Completed!</strong><br>
                                ${data.message || 'Import completed successfully!'}
                            </div>
                        </div>
                    `;
                }
                if (downloadBtn) {
                    downloadBtn.style.display = 'inline-block';
                }
                
                // Hide progress button when completed
                $('#viewImportProgressBtn').hide();
                
                // Stop progress polling
                if (importProgressInterval) {
                    clearInterval(importProgressInterval);
                    importProgressInterval = null;
                }
            } else if (data.status === 'failed') {
                if (progressMessageContainer) {
                    progressMessageContainer.className = 'alert alert-danger';
                    progressMessageContainer.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-fill text-danger fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>Import Failed!</strong><br>
                                ${data.message || 'Import failed with errors.'}
                            </div>
                        </div>
                    `;
                }
                
                // Hide progress button when failed
                $('#viewImportProgressBtn').hide();
                
                // Stop progress polling
                if (importProgressInterval) {
                    clearInterval(importProgressInterval);
                    importProgressInterval = null;
                }
            } else {
                if (progressMessageContainer) {
                    progressMessageContainer.className = 'alert alert-info';
                    progressMessageContainer.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="spinner-border spinner-border-sm text-info" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>Status:</strong> <span class="badge fs-6 ${getImportStatusBadgeClass(data.status)}">${getImportStatusText(data.status)}</span><br>
                                ${data.message || 'Processing...'}
                            </div>
                        </div>
                    `;
                }
            }
            
            // Show errors if any
            if (data.errors && data.errors > 0) {
                if (errorsCard) errorsCard.style.display = 'block';
                if (errorCount) errorCount.textContent = data.errors;
            }
        }

        // Get import status text
        function getImportStatusText(status) {
            switch (status) {
                case 'starting': return 'Starting';
                case 'processing': return 'Processing';
                case 'completed': return 'Completed';
                case 'failed': return 'Failed';
                default: return 'Unknown';
            }
        }

        // Get import status badge class
        function getImportStatusBadgeClass(status) {
            switch (status) {
                case 'starting': return 'bg-info';
                case 'processing': return 'bg-primary';
                case 'completed': return 'bg-success';
                case 'failed': return 'bg-danger';
                default: return 'bg-secondary';
            }
        }

        // Get import progress bar class
        function getImportProgressBarClass(status) {
            switch (status) {
                case 'starting': return 'bg-info';
                case 'processing': return 'bg-primary';
                case 'completed': return 'bg-success';
                case 'failed': return 'bg-danger';
                default: return 'bg-info';
            }
        }

        // Load import progress data
        function loadImportProgress(importId) {
            $.ajax({
                url: '{{ route("import.stats") }}',
                method: 'GET',
                data: { import_id: importId },
                success: function(data) {
                    updateImportProgress({
                        processed: data.processed_rows,
                        total: data.total_rows,
                        imported: data.imported_count,
                        skipped: data.skipped_count,
                        errors: data.error_count,
                        current_row: data.current_row,
                        percentage: data.progress_percentage,
                        status: data.status,
                        message: data.current_message
                    });
                }
            });
        }

        // Connect to WebSocket channel for real-time updates
        function connectToExportChannel(exportId) {
            // Initialize Pusher/Reverb
            const pusher = new Pusher('{{ config("broadcasting.connections.reverb.key") }}', {
                wsHost: '{{ config("broadcasting.connections.reverb.options.host") }}',
                wsPort: {{ config('broadcasting.connections.reverb.options.port') }},
                wssPort: {{ config('broadcasting.connections.reverb.options.port') }},
                forceTLS: {{ config('broadcasting.connections.reverb.options.scheme') === 'https' ? 'true' : 'false' }},
                enabledTransports: ['ws', 'wss'],
                cluster: 'mt1',
                disableStats: true
            });

            // Subscribe to public channel
            exportChannel = pusher.subscribe('employee-export.' + exportId);
            
            // Listen for progress updates
            exportChannel.bind('export.progress', function(data) {
                console.log('Export progress update:', data);
                updateExportProgress(data);
            });

            // Handle connection errors
            pusher.connection.bind('error', function(err) {
                console.error('Export WebSocket connection error:', err);
            });

            // Handle successful connection
            pusher.connection.bind('connected', function() {
                console.log('Connected to Export WebSocket');
            });
        }

        // Update export progress UI
        function updateExportProgress(data) {
            console.log('updateExportProgress called with data:', data);
            
            const progressBar = document.getElementById('exportProgressBar');
            const progressText = document.getElementById('exportProgressText');
            const progressPercentage = document.getElementById('exportProgressPercentage');
            const progressStatus = document.getElementById('exportProgressStatus');
            const progressMessage = document.getElementById('exportProgressMessage');
            const progressMessageText = document.getElementById('exportProgressMessageText');
            
            const totalRows = document.getElementById('exportTotalRows');
            const exportedCount = document.getElementById('exportedCount');
            const skippedCount = document.getElementById('exportSkippedCount');
            const currentRow = document.getElementById('exportCurrentRow');
            const errorsCard = document.getElementById('exportErrorsCard');
            const errorCount = document.getElementById('exportErrorCount');
            const errorsTableBody = document.getElementById('exportErrorsTableBody');
            const downloadBtn = document.getElementById('exportDownloadBtn');
            
            // Debug: Check which elements are missing
            const missingElements = [];
            if (!progressBar) missingElements.push('exportProgressBar');
            if (!progressText) missingElements.push('exportProgressText');
            if (!progressPercentage) missingElements.push('exportProgressPercentage');
            if (!progressStatus) missingElements.push('exportProgressStatus');
            if (!progressMessage) missingElements.push('exportProgressMessage');
            if (!progressMessageText) missingElements.push('exportProgressMessageText');
            if (!totalRows) missingElements.push('exportTotalRows');
            if (!exportedCount) missingElements.push('exportedCount');
            if (!skippedCount) missingElements.push('exportSkippedCount');
            if (!currentRow) missingElements.push('exportCurrentRow');
            
            if (missingElements.length > 0) {
                console.warn('Missing export progress elements:', missingElements);
            }
            
            // Update progress bar with null checks
            const percentage = data.percentage || 0;
            if (progressBar) {
                progressBar.style.width = percentage + '%';
                progressBar.setAttribute('aria-valuenow', percentage);
            }
            if (progressText) {
                progressText.textContent = percentage.toFixed(1) + '%';
            }
            if (progressPercentage) {
                progressPercentage.textContent = percentage.toFixed(1) + '%';
            }
            
            // Update counts with null checks
            if (totalRows) totalRows.textContent = data.total || 0;
            if (exportedCount) exportedCount.textContent = data.exported || 0;
            if (skippedCount) skippedCount.textContent = data.skipped || 0;
            if (currentRow) currentRow.textContent = data.current_row || 0;
            
            // Update status with null checks
            if (progressStatus) {
                progressStatus.textContent = getExportStatusText(data.status);
                progressStatus.className = 'badge fs-6 ' + getExportStatusBadgeClass(data.status);
            }
            if (progressMessageText) {
                progressMessageText.textContent = data.message || 'Processing...';
            }
            
            // Update message alert class based on status
            if (progressMessage) {
                progressMessage.className = 'alert ' + getExportAlertClass(data.status);
            }
            
            // Handle errors display
            if (data.errors && data.errors > 0) {
                if (errorsCard) {
                    errorsCard.style.display = 'block';
                }
                if (errorCount) {
                    errorCount.textContent = data.errors;
                }
            }
            
            // Handle completion
            if (data.status === 'completed') {
                if (progressBar) {
                    progressBar.classList.remove('progress-bar-animated');
                    progressBar.classList.add('bg-success');
                }
                if (progressStatus) {
                    progressStatus.classList.remove('bg-info');
                    progressStatus.classList.add('bg-success');
                }
                
                // Hide view progress button and reset export state
                $('#viewExportProgressBtn').hide();
                isExportInProgress = false;
                
                // Show download button with null check
                if (downloadBtn) {
                    downloadBtn.style.display = 'inline-block';
                    downloadBtn.onclick = function() {
                        window.location.href = '{{ route("export.download") }}?export_id=' + exportId;
                    };
                }
                
                // Auto-download the file
                setTimeout(() => {
                    window.location.href = '{{ route("export.download") }}?export_id=' + exportId;
                }, 2000); // 2 second delay to show completion message
                
                // Auto-scroll to errors if any
                if (data.errors > 0) {
                    const errorsCollapse = document.getElementById('exportErrorsCollapse');
                    if (errorsCollapse) {
                        const bsCollapse = new bootstrap.Collapse(errorsCollapse, {show: true});
                    }
                }
                
            } else if (data.status === 'failed') {
                if (progressBar) {
                    progressBar.classList.remove('progress-bar-animated');
                    progressBar.classList.add('bg-danger');
                }
                if (progressStatus) {
                    progressStatus.classList.remove('bg-info');
                    progressStatus.classList.add('bg-danger');
                }
                
                // Hide view progress button on failure
                $('#viewExportProgressBtn').hide();
                isExportInProgress = false;
            }
        }

        // Get export status text
        function getExportStatusText(status) {
            const statusMap = {
                'starting': 'Starting export...',
                'processing': 'Processing employees...',
                'completed': 'Export completed!',
                'failed': 'Export failed!'
            };
            return statusMap[status] || 'Processing...';
        }

        // Get export status badge class
        function getExportStatusBadgeClass(status) {
            const statusMap = {
                'starting': 'bg-warning',
                'processing': 'bg-info',
                'completed': 'bg-success',
                'failed': 'bg-danger'
            };
            return statusMap[status] || 'bg-info';
        }

        // Get export alert class
        function getExportAlertClass(status) {
            const statusMap = {
                'starting': 'alert-info',
                'processing': 'alert-info',
                'completed': 'alert-success',
                'failed': 'alert-danger'
            };
            return statusMap[status] || 'alert-info';
        }

        // Handle view export progress button click
        $(document).on('click', '#viewExportProgressBtn', function() {
            if (exportId) {
                // Show progress modal
                const progressModal = new bootstrap.Modal(document.getElementById('exportProgressModal'));
                progressModal.show();
                
                // Load current progress data
                loadExportProgress(exportId);
                
                // Reconnect to WebSocket if not already connected
                if (!exportChannel) {
                    connectToExportChannel(exportId);
                }
            }
        });

        // Load export progress data
        function loadExportProgress(exportId) {
            fetch(`{{ route('export.stats') }}?export_id=${exportId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.export_id) {
                        // Update UI with current progress
                        updateExportProgress({
                            processed: data.processed_rows,
                            total: data.total_rows,
                            exported: data.imported_count,
                            skipped: data.skipped_count,
                            errors: data.error_count,
                            current_row: data.current_row,
                            percentage: data.progress_percentage,
                            status: data.status,
                            message: data.current_message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading export progress:', error);
                });
        }

        // Add event listener for modal show to reload progress
        document.getElementById('exportProgressModal').addEventListener('show.bs.modal', function () {
            if (exportId) {
                loadExportProgress(exportId);
            }
        });

        // ==================== ERROR LOGS FUNCTIONALITY ====================
        
        let currentImportId = null;
        let currentErrorPage = 1;
        let currentErrorType = '';

        // Handle Progress button click
        $(document).on('click', '#viewImportProgressBtn', function() {
            if (importId) {
                // Show import progress modal
                const progressModal = new bootstrap.Modal(document.getElementById('importProgressModal'));
                progressModal.show();
            } else {
                alert('No import ID available. Please start an import first.');
            }
        });

        // Handle Logs button click
        $(document).on('click', '#logsBtn', function() {
            if (importId) {
                // Show error logs modal
                const logsModal = new bootstrap.Modal(document.getElementById('importLogsModal'));
                logsModal.show();
                
                // Load error logs
                loadErrorLogs(importId, 1, '');
            } else {
                alert('No import ID available. Please start an import first.');
            }
        });

        // Load error logs
        function loadErrorLogs(importId, page = 1, errorType = '') {
            currentErrorPage = page;
            currentErrorType = errorType;
            
            const params = new URLSearchParams({
                import_id: importId,
                page: page,
                per_page: 50
            });
            
            if (errorType) {
                params.append('error_type', errorType);
            }
            
            fetch(`{{ route('import.error-logs') }}?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateErrorLogsUI(data);
                        updateErrorSummary(data.error_summary);
                    } else {
                        console.error('Error loading error logs:', data.error);
                        showErrorLogsMessage('Error loading logs: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error loading error logs:', error);
                    showErrorLogsMessage('Error loading logs: ' + error.message);
                });
        }

        // Update error logs UI
        function updateErrorLogsUI(data) {
            const tbody = document.getElementById('errorLogsTableBody');
            const pagination = document.getElementById('errorLogsPagination');
            const info = document.getElementById('errorLogsInfo');
            
            // Update info
            if (info && data.pagination) {
                const pag = data.pagination;
                info.textContent = `Showing ${pag.from || 0} to ${pag.to || 0} of ${pag.total} errors`;
            }
            
            // Update table
            if (data.data && data.data.length > 0) {
                tbody.innerHTML = data.data.map(error => `
                    <tr>
                        <td><span class="badge bg-secondary">${error.row_number}</span></td>
                        <td>
                            <span class="badge ${getErrorTypeBadgeClass(error.error_type)}">
                                ${getErrorTypeName(error.error_type)}
                            </span>
                        </td>
                        <td><small>${error.field_name || '-'}</small></td>
                        <td>
                            <small title="${error.error_message}">
                                ${truncateText(error.error_message, 60)}
                            </small>
                        </td>
                        <td>
                            <small title="${error.problematic_value || ''}">
                                ${truncateText(error.problematic_value || '-', 30)}
                            </small>
                        </td>
                        <td><small>${formatTime(error.occurred_at)}</small></td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="ri-check-line fs-1 d-block mb-2 text-success"></i>
                            No error logs found for this import.
                        </td>
                    </tr>
                `;
            }
            
            // Update pagination
            updateErrorLogsPagination(data.pagination);
        }

        // Update error summary
        function updateErrorSummary(summary) {
            if (summary && summary.total > 0) {
                document.getElementById('errorSummaryRow').style.display = 'block';
                document.getElementById('summaryValidationErrors').textContent = summary.validation_error || 0;
                document.getElementById('summaryImportErrors').textContent = summary.import_error || 0;
                document.getElementById('summaryLookupErrors').textContent = summary.lookup_error || 0;
                document.getElementById('summaryMissingErrors').textContent = summary.missing_fields || 0;
                document.getElementById('summaryDatabaseErrors').textContent = summary.database_error || 0;
                document.getElementById('summaryTotalErrors').textContent = summary.total || 0;
            } else {
                document.getElementById('errorSummaryRow').style.display = 'none';
            }
        }

        // Update pagination
        function updateErrorLogsPagination(pagination) {
            const paginationEl = document.getElementById('errorLogsPagination');
            if (!pagination || pagination.last_page <= 1) {
                paginationEl.innerHTML = '';
                return;
            }
            
            let html = '';
            
            // Previous button
            if (pagination.current_page > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadErrorLogs('${currentImportId}', ${pagination.current_page - 1}, '${currentErrorType}'); return false;">Previous</a></li>`;
            }
            
            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                const active = i === pagination.current_page ? 'active' : '';
                html += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="loadErrorLogs('${currentImportId}', ${i}, '${currentErrorType}'); return false;">${i}</a></li>`;
            }
            
            // Next button
            if (pagination.current_page < pagination.last_page) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadErrorLogs('${currentImportId}', ${pagination.current_page + 1}, '${currentErrorType}'); return false;">Next</a></li>`;
            }
            
            paginationEl.innerHTML = html;
        }

        // Helper functions
        function getErrorTypeBadgeClass(errorType) {
            const classes = {
                'validation_error': 'bg-danger',
                'import_error': 'bg-warning',
                'lookup_error': 'bg-info',
                'missing_fields': 'bg-secondary',
                'database_error': 'bg-dark'
            };
            return classes[errorType] || 'bg-secondary';
        }

        function getErrorTypeName(errorType) {
            const names = {
                'validation_error': 'Validation',
                'import_error': 'Import',
                'lookup_error': 'Lookup',
                'missing_fields': 'Missing',
                'database_error': 'Database'
            };
            return names[errorType] || 'Unknown';
        }

        function truncateText(text, maxLength) {
            if (!text) return '-';
            return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
        }

        function formatTime(timeString) {
            if (!timeString) return '-';
            const date = new Date(timeString);
            return date.toLocaleString();
        }

        function showErrorLogsMessage(message) {
            const tbody = document.getElementById('errorLogsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger py-4">
                        <i class="ri-error-warning-line fs-1 d-block mb-2"></i>
                        ${message}
                    </td>
                </tr>
            `;
        }

        // Event listeners
        $(document).on('change', '#errorTypeFilter', function() {
            const errorType = $(this).val();
            if (currentImportId) {
                loadErrorLogs(currentImportId, 1, errorType);
            }
        });

        $(document).on('click', '#clearErrorLogsBtn', function() {
            if (!currentImportId) {
                alert('No import selected');
                return;
            }
            
            if (confirm('Are you sure you want to clear all error logs for this import?')) {
                fetch(`{{ route('import.error-logs.clear') }}?import_id=${currentImportId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Cleared ${data.deleted_count} error log entries`);
                        loadErrorLogs(currentImportId, 1, currentErrorType);
                    } else {
                        alert('Error clearing logs: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error clearing logs:', error);
                    alert('Error clearing logs: ' + error.message);
                });
            }
        });

        $(document).on('click', '#refreshErrorLogsBtn', function() {
            if (currentImportId) {
                loadErrorLogs(currentImportId, currentErrorPage, currentErrorType);
            }
        });

        // Add event listener for modal show to reload error logs
        document.getElementById('importLogsModal').addEventListener('show.bs.modal', function () {
            if (currentImportId) {
                loadErrorLogs(currentImportId, 1, '');
            }
        });

        // Fix aria-hidden focus issues
        document.addEventListener('DOMContentLoaded', function() {
            // Handle modal focus properly
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    // Remove any remaining focus from modal elements
                    const focusedElement = document.activeElement;
                    if (focusedElement && modal.contains(focusedElement)) {
                        focusedElement.blur();
                    }
                });
            });
        });

        // Add event listener for modal show to reload progress
        document.getElementById('importProgressModal').addEventListener('show.bs.modal', function () {
            if (importId) {
                // Load current progress data
                loadImportProgress(importId);
                
                // Reconnect to WebSocket if not already connected
                if (!importChannel) {
                    connectToImportChannel(importId);
                }
                
                // Restart progress polling if not already running
                if (!importProgressInterval) {
                    importProgressInterval = setInterval(function() {
                        loadImportProgress(importId);
                    }, 2000);
                }
            }
        });
    </script>
@endpush
