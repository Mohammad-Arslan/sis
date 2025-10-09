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

                            <!-- Real-time Progress Card -->
                            <div class="card border-info mt-3" id="progressCard" style="display: none;">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="mdi mdi-progress-clock me-1"></i> Import Progress
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span id="progressStatus">Initializing...</span>
                                            <span id="progressPercentage">0%</span>
                                        </div>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                 role="progressbar" 
                                                 id="progressBar" 
                                                 style="width: 0%"
                                                 aria-valuenow="0" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                0%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="card border-success">
                                                <div class="card-body py-2">
                                                    <h6 class="mb-0 text-success">Processed</h6>
                                                    <h4 class="mb-0" id="processedCount">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-primary">
                                                <div class="card-body py-2">
                                                    <h6 class="mb-0 text-primary">Imported</h6>
                                                    <h4 class="mb-0" id="importedCount">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-warning">
                                                <div class="card-body py-2">
                                                    <h6 class="mb-0 text-warning">Skipped</h6>
                                                    <h4 class="mb-0" id="skippedCount">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-danger">
                                                <div class="card-body py-2">
                                                    <h6 class="mb-0 text-danger">Errors</h6>
                                                    <h4 class="mb-0" id="errorsCount">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info mt-3" id="progressMessage">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        <span id="progressMessageText">Starting import...</span>
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
                
                // Show progress card
                progressCard.style.display = 'block';
                progressCard.scrollIntoView({ behavior: 'smooth' });
                
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
        const progressPercentage = document.getElementById('progressPercentage');
        const progressStatus = document.getElementById('progressStatus');
        const progressMessage = document.getElementById('progressMessage');
        const progressMessageText = document.getElementById('progressMessageText');
        
        const processedCount = document.getElementById('processedCount');
        const importedCount = document.getElementById('importedCount');
        const skippedCount = document.getElementById('skippedCount');
        const errorsCount = document.getElementById('errorsCount');
        
        // Update progress bar
        const percentage = data.percentage || 0;
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressBar.textContent = percentage.toFixed(1) + '%';
        progressPercentage.textContent = percentage.toFixed(1) + '%';
        
        // Update counts
        processedCount.textContent = data.processed || 0;
        importedCount.textContent = data.imported || 0;
        skippedCount.textContent = data.skipped || 0;
        errorsCount.textContent = data.errors || 0;
        
        // Update status
        progressStatus.textContent = getStatusText(data.status);
        progressMessageText.textContent = data.message || 'Processing...';
        
        // Update message alert class based on status
        progressMessage.className = 'alert mt-3 ' + getAlertClass(data.status);
        
        // Handle completion
        if (data.status === 'completed') {
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-success');
            
            setTimeout(function() {
                alert('Import completed successfully!\nImported: ' + data.imported + '\nSkipped: ' + data.skipped + '\nErrors: ' + data.errors);
                
                // Optionally redirect or reload
                // window.location.href = '{{ route("employees.index") }}';
            }, 1000);
        } else if (data.status === 'failed') {
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-danger');
            
            alert('Import failed: ' + data.message);
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

    // Get alert class based on status
    function getAlertClass(status) {
        const classMap = {
            'starting': 'alert-info',
            'processing': 'alert-info',
            'completed': 'alert-success',
            'failed': 'alert-danger'
        };
        return classMap[status] || 'alert-info';
    }
</script>
@endpush
@endsection 