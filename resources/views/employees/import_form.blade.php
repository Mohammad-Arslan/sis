@extends('layouts.master')

@section('title', 'Import Employees')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                        <li class="breadcrumb-item active">Import Employees</li>
                    </ol>
                </div>
                <h4 class="page-title">Import Employees</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Import Employees from Excel/CSV</h4>
                    <p class="text-muted mb-0">Upload an Excel or CSV file to import multiple employees at once.</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Validation Error!</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="form-label">Select File <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                           id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                    <div class="form-text">
                                        Supported formats: Excel (.xlsx, .xls) and CSV (.csv). Maximum file size: 100MB.
                                    </div>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary" id="importBtn">
                                        <i class="mdi mdi-upload me-1"></i> Import Employees
                                    </button>
                                    <a href="{{ route('employees.index') }}" class="btn btn-secondary ms-2">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to Employees
                                    </a>
                                </div>
                            </form>

                            <!-- Real-time Progress Modal -->
                            <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="progressModalLabel">
                                                <i class="mdi mdi-progress-clock me-2"></i>Employee Import Progress
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Progress Overview -->
                                            <div class="row mb-4">
                                                <div class="col-md-8">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0">Overall Progress</h6>
                                                        <span class="badge bg-primary fs-6" id="progressPercentage">0%</span>
                                                    </div>
                                                    <div class="progress mb-2" style="height: 30px;">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                                             role="progressbar" 
                                                             id="progressBar" 
                                                             style="width: 0%"
                                                             aria-valuenow="0" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            <span id="progressText">0%</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between text-muted small">
                                                        <span id="processedCount">0</span>
                                                        <span id="totalRows">0</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Status</h6>
                                                        <span class="badge bg-info fs-6" id="progressStatus">Initializing</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Statistics Cards -->
                                            <div class="row mb-4">
                                                <div class="col-md-3">
                                                    <div class="card border-success h-100">
                                                        <div class="card-body text-center">
                                                            <i class="mdi mdi-check-circle text-success fs-1 mb-2"></i>
                                                            <h6 class="text-success mb-1">Imported</h6>
                                                            <h3 class="mb-0 text-success" id="importedCount">0</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card border-warning h-100">
                                                        <div class="card-body text-center">
                                                            <i class="mdi mdi-skip-next text-warning fs-1 mb-2"></i>
                                                            <h6 class="text-warning mb-1">Skipped</h6>
                                                            <h3 class="mb-0 text-warning" id="skippedCount">0</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card border-danger h-100">
                                                        <div class="card-body text-center">
                                                            <i class="mdi mdi-alert-circle text-danger fs-1 mb-2"></i>
                                                            <h6 class="text-danger mb-1">Errors</h6>
                                                            <h3 class="mb-0 text-danger" id="errorsCount">0</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card border-info h-100">
                                                        <div class="card-body text-center">
                                                            <i class="mdi mdi-counter text-info fs-1 mb-2"></i>
                                                            <h6 class="text-info mb-1">Current Row</h6>
                                                            <h3 class="mb-0 text-info" id="currentRow">0</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Current Status -->
                                            <div class="alert alert-info" id="progressMessage">
                                                <i class="mdi mdi-information-outline me-2"></i>
                                                <span id="progressMessageText">Starting import...</span>
                                            </div>

                                            <!-- Errors Log (Collapsible) -->
                                            <div class="card" id="errorsCard" style="display: none;">
                                                <div class="card-header">
                                                    <h6 class="mb-0">
                                                        <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#errorsCollapse">
                                                            <i class="mdi mdi-alert-circle text-danger me-2"></i>
                                                            Import Errors (<span id="errorCount">0</span>)
                                                        </button>
                                                    </h6>
                                                </div>
                                                <div id="errorsCollapse" class="collapse">
                                                    <div class="card-body">
                                                        <div class="table-responsive" style="max-height: 300px;">
                                                            <table class="table table-sm table-hover">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th>Row</th>
                                                                        <th>Field</th>
                                                                        <th>Error</th>
                                                                        <th>Value</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="errorsTableBody">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalBtn">
                                                <i class="mdi mdi-close me-1"></i>Close
                                            </button>
                                            <button type="button" class="btn btn-success" id="viewResultsBtn" style="display: none;">
                                                <i class="mdi mdi-eye me-1"></i>View Results
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="mdi mdi-download me-1"></i> Download Template
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Download the Excel template with all required columns and sample data to help you prepare your import file.
                                    </p>
                                    <a href="{{ route('employees.download-template') }}" class="btn btn-outline-primary">
                                        <i class="mdi mdi-download me-1"></i> Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Import Instructions</h5>
                            <div class="alert alert-info">
                                <h6><i class="mdi mdi-information-outline me-1"></i> Important Notes:</h6>
                                <ul class="mb-0">
                                    <li><strong>Required Fields:</strong> first_name, email, cnic</li>
                                    <li><strong>Date Formats:</strong> Use YYYY-MM-DD format (e.g., 2024-01-15) or Excel date numbers</li>
                                    <li><strong>Lookup Values:</strong> Use exact names from the system (e.g., "Pakistan" for country, "Islam" for religion)</li>
                                    <li><strong>Gender:</strong> Use "Male" or "Female"</li>
                                    <li><strong>Prefix:</strong> Use "Mr", "Mrs", or "Ms"</li>
                                    <li><strong>Marital Status:</strong> Use "Single", "Married", "Divorced", or "Widowed"</li>
                                    <li><strong>Job Status:</strong> Use "Active", "Inactive", "Left", or "Suspended"</li>
                                    <li><strong>Password:</strong> If not provided, default password "password123" will be used</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5>Available Lookup Values</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <h6>Countries</h6>
                                    <ul class="list-unstyled">
                                        @foreach(\App\Models\Country::take(5)->get() as $country)
                                            <li><small>{{ $country->country_name }}</small></li>
                                        @endforeach
                                        <li><small><em>... and more</em></small></li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <h6>Religions</h6>
                                    <ul class="list-unstyled">
                                        @foreach(\App\Models\Religion::take(5)->get() as $religion)
                                            <li><small>{{ $religion->religion_name }}</small></li>
                                        @endforeach
                                        <li><small><em>... and more</em></small></li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <h6>Nationalities</h6>
                                    <ul class="list-unstyled">
                                        @foreach(\App\Models\Nationality::take(5)->get() as $nationality)
                                            <li><small>{{ $nationality->nationality_name }}</small></li>
                                        @endforeach
                                        <li><small><em>... and more</em></small></li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <h6>Companies</h6>
                                    <ul class="list-unstyled">
                                        @foreach(\App\Models\Company::take(5)->get() as $company)
                                            <li><small>{{ $company->company_name }}</small></li>
                                        @endforeach
                                        <li><small><em>... and more</em></small></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="mdi mdi-alert-outline me-1"></i> Troubleshooting
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Common Issues:</h6>
                                            <ul class="mb-0">
                                                <li>Duplicate email addresses</li>
                                                <li>Duplicate CNIC numbers</li>
                                                <li>Invalid date formats</li>
                                                <li>Missing required fields</li>
                                                <li>Invalid lookup values</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Solutions:</h6>
                                            <ul class="mb-0">
                                                <li>Check for duplicate emails/CNICs</li>
                                                <li>Use correct date format (YYYY-MM-DD)</li>
                                                <li>Fill all required fields</li>
                                                <li>Use exact lookup values from the system</li>
                                                <li>Check import logs for detailed errors</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    let importChannel = null;
    let importId = null;

    // File input validation
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const maxSize = 100 * 1024 * 1024; // 100MB
        
        if (file && file.size > maxSize) {
            alert('File size exceeds 100MB limit. Please choose a smaller file.');
            this.value = '';
        }
    });

    // Handle form submission
    document.getElementById('importForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const importBtn = document.getElementById('importBtn');
        const progressCard = document.getElementById('progressCard');
        
        // Disable button and show loading
        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Uploading...';
        
        // Submit via AJAX
        fetch('{{ route("employees.import") }}', {
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
                importId = data.import_id;
                
                // Show progress modal
                const progressModal = new bootstrap.Modal(document.getElementById('progressModal'));
                progressModal.show();
                
                // Load initial progress data
                loadImportProgress(data.import_id);
                
                // Connect to WebSocket
                connectToImportChannel(importId);
                
                // Update button
                importBtn.innerHTML = '<i class="mdi mdi-check me-1"></i> Import Started';
            } else {
                throw new Error(data.message || 'Import failed');
            }
        })
        .catch(error => {
            console.error('Import error:', error);
            alert('Import failed: ' + error.message);
            
            // Re-enable button
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="mdi mdi-upload me-1"></i> Import Employees';
        });
    });

    // Connect to WebSocket channel for real-time updates
    function connectToImportChannel(importId) {
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
        importChannel = pusher.subscribe('private-employee-import.' + importId);
        
        // Listen for progress updates
        importChannel.bind('import.progress', function(data) {
            console.log('Progress update:', data);
            updateProgress(data);
        });

        // Handle connection errors
        pusher.connection.bind('error', function(err) {
            console.error('WebSocket connection error:', err);
        });

        // Handle successful connection
        pusher.connection.bind('connected', function() {
            console.log('Connected to WebSocket');
        });
    }

    // Update progress UI
    function updateProgress(data) {
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const progressPercentage = document.getElementById('progressPercentage');
        const progressStatus = document.getElementById('progressStatus');
        const progressMessage = document.getElementById('progressMessage');
        const progressMessageText = document.getElementById('progressMessageText');
        
        const processedCount = document.getElementById('processedCount');
        const totalRows = document.getElementById('totalRows');
        const importedCount = document.getElementById('importedCount');
        const skippedCount = document.getElementById('skippedCount');
        const errorsCount = document.getElementById('errorsCount');
        const currentRow = document.getElementById('currentRow');
        const errorsCard = document.getElementById('errorsCard');
        const errorCount = document.getElementById('errorCount');
        const errorsTableBody = document.getElementById('errorsTableBody');
        const viewResultsBtn = document.getElementById('viewResultsBtn');
        
        // Update progress bar
        const percentage = data.percentage || 0;
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressText.textContent = percentage.toFixed(1) + '%';
        progressPercentage.textContent = percentage.toFixed(1) + '%';
        
        // Update counts
        processedCount.textContent = data.processed || 0;
        totalRows.textContent = data.total || 0;
        importedCount.textContent = data.imported || 0;
        skippedCount.textContent = data.skipped || 0;
        errorsCount.textContent = data.errors || 0;
        currentRow.textContent = data.current_row || 0;
        
        // Update status
        progressStatus.textContent = getStatusText(data.status);
        progressStatus.className = 'badge fs-6 ' + getStatusBadgeClass(data.status);
        progressMessageText.textContent = data.message || 'Processing...';
        
        // Update message alert class based on status
        progressMessage.className = 'alert ' + getAlertClass(data.status);
        
        // Handle errors display
        if (data.errors && data.errors > 0) {
            errorsCard.style.display = 'block';
            errorCount.textContent = data.errors;
            
            // Update errors table if errors data is available
            if (data.errors_data && data.errors_data.length > 0) {
                errorsTableBody.innerHTML = '';
                data.errors_data.forEach(error => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${error.row}</td>
                        <td>${error.field || 'N/A'}</td>
                        <td class="text-danger">${error.error}</td>
                        <td class="text-muted">${error.value || 'N/A'}</td>
                    `;
                    errorsTableBody.appendChild(row);
                });
            }
        }
        
        // Handle completion
        if (data.status === 'completed') {
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-success');
            progressStatus.classList.remove('bg-info');
            progressStatus.classList.add('bg-success');
            
            // Show view results button
            viewResultsBtn.style.display = 'inline-block';
            
            // Auto-scroll to errors if any
            if (data.errors > 0) {
                const errorsCollapse = document.getElementById('errorsCollapse');
                const bsCollapse = new bootstrap.Collapse(errorsCollapse, {show: true});
            }
            
        } else if (data.status === 'failed') {
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-danger');
            progressStatus.classList.remove('bg-info');
            progressStatus.classList.add('bg-danger');
        }
    }

    // Get status text
    function getStatusText(status) {
        const statusMap = {
            'starting': 'Starting import...',
            'processing': 'Processing employees...',
            'completed': 'Import completed!',
            'failed': 'Import failed!'
        };
        return statusMap[status] || 'Processing...';
    }

    // Get status badge class
    function getStatusBadgeClass(status) {
        const statusMap = {
            'starting': 'bg-warning',
            'processing': 'bg-info',
            'completed': 'bg-success',
            'failed': 'bg-danger'
        };
        return statusMap[status] || 'bg-info';
    }

    // Get alert class
    function getAlertClass(status) {
        const statusMap = {
            'starting': 'alert-info',
            'processing': 'alert-info',
            'completed': 'alert-success',
            'failed': 'alert-danger'
        };
        return statusMap[status] || 'alert-info';
    }

    // Load import progress data
    function loadImportProgress(importId) {
        fetch(`{{ route('import.stats') }}?import_id=${importId}`)
            .then(response => response.json())
            .then(data => {
                if (data.import_id) {
                    // Update UI with current progress
                    updateProgress({
                        processed: data.processed_rows,
                        total: data.total_rows,
                        imported: data.imported_count,
                        skipped: data.skipped_count,
                        errors: data.error_count,
                        current_row: data.current_row,
                        percentage: data.progress_percentage,
                        status: data.status,
                        message: data.current_message,
                        errors_data: data.errors || []
                    });
                }
            })
            .catch(error => {
                console.error('Error loading import progress:', error);
            });
    }

    // Add event listener for modal show to reload progress
    document.getElementById('progressModal').addEventListener('show.bs.modal', function () {
        if (importId) {
            loadImportProgress(importId);
        }
    });
</script>
@endpush
@endsection 