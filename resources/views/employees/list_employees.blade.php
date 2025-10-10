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
                        $('#progressText').text('Import completed successfully!');
                        $('#importEmployeesModal').modal('hide');
                        
                        if (result.success) {
                            $('#successMessage').text(result.success);
                        }
                        
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
            
            // Show the modal
            $('#importEmployeesModal').modal('show');
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

        // Handle logs button click
        $(document).on('click', '#viewImportLogsBtn', function() {
            $.ajax({
                url: '{{ route("employees.import-stats") }}',
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
            const progressBar = document.getElementById('exportProgressBar');
            const progressText = document.getElementById('exportProgressText');
            const progressPercentage = document.getElementById('exportProgressPercentage');
            const progressStatus = document.getElementById('exportProgressStatus');
            const progressMessage = document.getElementById('exportProgressMessage');
            const progressMessageText = document.getElementById('exportProgressMessageText');
            
            const processedCount = document.getElementById('exportProcessedCount');
            const totalRows = document.getElementById('exportTotalRows');
            const exportedCount = document.getElementById('exportedCount');
            const skippedCount = document.getElementById('exportSkippedCount');
            const currentRow = document.getElementById('exportCurrentRow');
            const errorsCard = document.getElementById('exportErrorsCard');
            const errorCount = document.getElementById('exportErrorCount');
            const errorsTableBody = document.getElementById('exportErrorsTableBody');
            const downloadBtn = document.getElementById('exportDownloadBtn');
            
            // Update progress bar
            const percentage = data.percentage || 0;
            progressBar.style.width = percentage + '%';
            progressBar.setAttribute('aria-valuenow', percentage);
            progressText.textContent = percentage.toFixed(1) + '%';
            progressPercentage.textContent = percentage.toFixed(1) + '%';
            
            // Update counts
            processedCount.textContent = data.processed || 0;
            totalRows.textContent = data.total || 0;
            exportedCount.textContent = data.exported || 0;
            skippedCount.textContent = data.skipped || 0;
            currentRow.textContent = data.current_row || 0;
            
            // Update status
            progressStatus.textContent = getExportStatusText(data.status);
            progressStatus.className = 'badge fs-6 ' + getExportStatusBadgeClass(data.status);
            progressMessageText.textContent = data.message || 'Processing...';
            
            // Update message alert class based on status
            progressMessage.className = 'alert ' + getExportAlertClass(data.status);
            
            // Handle errors display
            if (data.errors && data.errors > 0) {
                errorsCard.style.display = 'block';
                errorCount.textContent = data.errors;
            }
            
            // Handle completion
            if (data.status === 'completed') {
                progressBar.classList.remove('progress-bar-animated');
                progressBar.classList.add('bg-success');
                progressStatus.classList.remove('bg-info');
                progressStatus.classList.add('bg-success');
                
                // Hide view progress button and reset export state
                $('#viewExportProgressBtn').hide();
                isExportInProgress = false;
                
                // Show download button
                downloadBtn.style.display = 'inline-block';
                downloadBtn.onclick = function() {
                    window.location.href = '{{ route("export.download") }}?export_id=' + exportId;
                };
                
                // Auto-download the file
                setTimeout(() => {
                    window.location.href = '{{ route("export.download") }}?export_id=' + exportId;
                }, 2000); // 2 second delay to show completion message
                
                // Auto-scroll to errors if any
                if (data.errors > 0) {
                    const errorsCollapse = document.getElementById('exportErrorsCollapse');
                    const bsCollapse = new bootstrap.Collapse(errorsCollapse, {show: true});
                }
                
            } else if (data.status === 'failed') {
                progressBar.classList.remove('progress-bar-animated');
                progressBar.classList.add('bg-danger');
                progressStatus.classList.remove('bg-info');
                progressStatus.classList.add('bg-danger');
                
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
    </script>
@endpush

<!-- Include the import modal -->
@include('employees.employee_import_modal')

<!-- Modal for Import Logs -->
<div class="modal fade" id="importLogsModal" tabindex="-1" aria-labelledby="importLogsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importLogsModalLabel">Import Error Logs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="importLogsContent">
        <!-- Logs will be loaded here -->
      </div>
    </div>
  </div>
</div>


<!-- Export Progress Modal -->
<div class="modal fade" id="exportProgressModal" tabindex="-1" aria-labelledby="exportProgressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exportProgressModalLabel">
                    <i class="ri-download-2-line me-2"></i>Employee Export Progress
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Progress Overview -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Overall Progress</h6>
                            <span class="badge bg-success fs-6" id="exportProgressPercentage">0%</span>
                        </div>
                        <div class="progress mb-2" style="height: 30px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                 role="progressbar" 
                                 id="exportProgressBar" 
                                 style="width: 0%"
                                 aria-valuenow="0" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <span id="exportProgressText">0%</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span id="exportProcessedCount">0</span>
                            <span id="exportTotalRows">0</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h6 class="text-muted mb-2">Status</h6>
                            <span class="badge bg-info fs-6" id="exportProgressStatus">Initializing</span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <i class="ri-check-line text-success fs-1 mb-2"></i>
                                <h6 class="text-success mb-1">Exported</h6>
                                <h3 class="mb-0 text-success" id="exportedCount">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="ri-skip-forward-line text-warning fs-1 mb-2"></i>
                                <h6 class="text-warning mb-1">Skipped</h6>
                                <h3 class="mb-0 text-warning" id="exportSkippedCount">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info h-100">
                            <div class="card-body text-center">
                                <i class="ri-number-1 text-info fs-1 mb-2"></i>
                                <h6 class="text-info mb-1">Current Row</h6>
                                <h3 class="mb-0 text-info" id="exportCurrentRow">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="alert alert-info" id="exportProgressMessage">
                    <i class="ri-information-line me-2"></i>
                    <span id="exportProgressMessageText">Starting export...</span>
                </div>

                <!-- Errors Log (Collapsible) -->
                <div class="card" id="exportErrorsCard" style="display: none;">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#exportErrorsCollapse">
                                <i class="ri-error-warning-line text-danger me-2"></i>
                                Export Errors (<span id="exportErrorCount">0</span>)
                            </button>
                        </h6>
                    </div>
                    <div id="exportErrorsCollapse" class="collapse">
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 300px;">
                                <table class="table table-sm table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Row</th>
                                            <th>Employee ID</th>
                                            <th>Error</th>
                                        </tr>
                                    </thead>
                                    <tbody id="exportErrorsTableBody">
                                    </tbody>
                                </table>
                            </div>
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
