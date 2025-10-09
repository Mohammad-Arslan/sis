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
                            <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="form-label">Select File <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                           id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                    <div class="form-text">
                                        Supported formats: Excel (.xlsx, .xls) and CSV (.csv). Maximum file size: 10MB.
                                    </div>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-upload me-1"></i> Import Employees
                                    </button>
                                    <a href="{{ route('employees.index') }}" class="btn btn-secondary ms-2">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to Employees
                                    </a>
                                </div>
                            </form>
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
<script>
    // File input validation
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        if (file && file.size > maxSize) {
            alert('File size exceeds 10MB limit. Please choose a smaller file.');
            this.value = '';
        }
    });
</script>
@endpush
@endsection 