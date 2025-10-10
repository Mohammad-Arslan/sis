@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Employee List </h4>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="mdi mdi-download me-1"></i>Export Employees
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="employee-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Eamil</th>
                                <th>Roles</th>
                                <th>Permissions</th>
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
                                <th>Eamil</th>
                                <th>Roles</th>
                                <th>Permissions</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="mdi mdi-download me-2"></i>Export Employees
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="exportForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="export_branch_id" class="form-label">Branch</label>
                                    <select class="form-select" id="export_branch_id" name="branch_id">
                                        <option value="">All Branches</option>
                                        @foreach(\App\Models\Branch::all() as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="export_department_id" class="form-label">Department</label>
                                    <select class="form-select" id="export_department_id" name="department_id">
                                        <option value="">All Departments</option>
                                        @foreach(\App\Models\Department::all() as $department)
                                            <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="export_designation_id" class="form-label">Designation</label>
                                    <select class="form-select" id="export_designation_id" name="designation_id">
                                        <option value="">All Designations</option>
                                        @foreach(\App\Models\Designation::all() as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="export_job_status" class="form-label">Job Status</label>
                                    <select class="form-select" id="export_job_status" name="job_status">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="left">Left</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="export_date_from" class="form-label">Hiring Date From</label>
                                    <input type="date" class="form-control" id="export_date_from" name="date_from">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="export_date_to" class="form-label">Hiring Date To</label>
                                    <input type="date" class="form-control" id="export_date_to" name="date_to">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="mdi mdi-close me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success" id="exportBtn">
                            <i class="mdi mdi-download me-1"></i>Export Employees
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Progress Modal -->
    <div class="modal fade" id="exportProgressModal" tabindex="-1" aria-labelledby="exportProgressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="exportProgressModalLabel">
                        <i class="mdi mdi-progress-clock me-2"></i>Employee Export Progress
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
                                    <i class="mdi mdi-check-circle text-success fs-1 mb-2"></i>
                                    <h6 class="text-success mb-1">Exported</h6>
                                    <h3 class="mb-0 text-success" id="exportedCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-skip-next text-warning fs-1 mb-2"></i>
                                    <h6 class="text-warning mb-1">Skipped</h6>
                                    <h3 class="mb-0 text-warning" id="exportSkippedCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info h-100">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-counter text-info fs-1 mb-2"></i>
                                    <h6 class="text-info mb-1">Current Row</h6>
                                    <h3 class="mb-0 text-info" id="exportCurrentRow">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="alert alert-info" id="exportProgressMessage">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <span id="exportProgressMessageText">Starting export...</span>
                    </div>

                    <!-- Errors Log (Collapsible) -->
                    <div class="card" id="exportErrorsCard" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#exportErrorsCollapse">
                                    <i class="mdi mdi-alert-circle text-danger me-2"></i>
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
                        <i class="mdi mdi-close me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-success" id="exportDownloadBtn" style="display: none;">
                        <i class="mdi mdi-download me-1"></i>Download File
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#employee-table').dataTable({
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
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('users.index') }}",
                    // data: function ( d ) {
                    //     d.id = $('#id').val();
                    //     d.name = $('#name').val();
                    //     d.email = $('#email').val();
                    //     d.roles = $('#roles').val();
                    //     d.permissions = $('#permissions').val();
                    //     d.searchName = $('#mySearch').val().toLowerCase();
                    // }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    }
                ]
            });
        });

        // $(document).on('change', '.filter', function() {
        //     $('#employee-table').DataTable().ajax.reload(null, false);
        // });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#employee-table').DataTable().ajax.reload(null, false);
            }
        });

        // Export functionality
        let exportId = null;
        let exportChannel = null;

        // Handle export form submission
        document.getElementById('exportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const exportBtn = document.getElementById('exportBtn');
            
            // Disable button and show loading
            exportBtn.disabled = true;
            exportBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Starting Export...';
            
            // Submit via AJAX
            fetch('{{ route("employees.export") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exportId = data.export_id;
                    
                    // Close export modal and show progress modal
                    const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
                    exportModal.hide();
                    
                    const progressModal = new bootstrap.Modal(document.getElementById('exportProgressModal'));
                    progressModal.show();
                    
                    // Load initial progress data
                    loadExportProgress(data.export_id);
                    
                    // Connect to WebSocket
                    connectToExportChannel(exportId);
                    
                    // Reset export button
                    exportBtn.disabled = false;
                    exportBtn.innerHTML = '<i class="mdi mdi-download me-1"></i>Export Employees';
                } else {
                    throw new Error(data.message || 'Export failed');
                }
            })
            .catch(error => {
                console.error('Export error:', error);
                alert('Export failed: ' + error.message);
                
                // Re-enable button
                exportBtn.disabled = false;
                exportBtn.innerHTML = '<i class="mdi mdi-download me-1"></i>Export Employees';
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
                disableStats: true,
            });

            // Subscribe to private channel
            exportChannel = pusher.subscribe('private-employee-export.' + exportId);
            
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
                
                // Show download button
                downloadBtn.style.display = 'inline-block';
                downloadBtn.onclick = function() {
                    window.location.href = '{{ route("export.download") }}?export_id=' + exportId;
                };
                
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
