@extends('layouts.master')

@section('title', 'Import Previous Data')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">Import Previous Data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Success Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>
                        {{ session(key: 'success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Import Statistics Modal Trigger -->
                @if (session('import_stats'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-information me-2"></i>
                        Import completed! Click <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#importStatsModal">here</a> to view detailed results.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <!-- Individual Import Section -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Individual Student Import</h4>
                        <p class="text-muted mb-0">Import previous arrears or advance payments for individual students</p>
                    </div>
                    <div class="card-body">
                        <!-- Arrears Import -->
                        <div class="mb-4">
                            <h5 class="text-danger">Import Previous Arrears</h5>
                            <form action="{{ route('students.import.previous.arrears') }}" method="POST" id="arrearsForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="student_id" class="form-label">Student</label>
                                            <select class="form-select select2 @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                                <option value="">Select Student</option>
                                                @foreach ($students ?? [] as $student)
                                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                        {{ $student->roll_no }} - {{ $student->first_name }}
                                                        {{ $student->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('student_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="arrears_amount" class="form-label">Arrears Amount</label>
                                            <input type="number" class="form-control @error('arrears_amount') is-invalid @enderror" id="arrears_amount"
                                                name="arrears_amount" step="0.01" min="0" value="{{ old('arrears_amount') }}" required>
                                            @error('arrears_amount')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="arrears_date" class="form-label">Arrears Date</label>
                                            <input type="date" class="form-control @error('arrears_date') is-invalid @enderror" id="arrears_date" name="arrears_date"
                                                max="{{ date('Y-m-d') }}" value="{{ old('arrears_date') }}" required>
                                            @error('arrears_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="mdi mdi-upload me-1"></i> Add Arrears
                                </button>
                            </form>
                        </div>

                        <hr>

                        <!-- Advance Payment Import -->
                        <div class="mb-4">
                            <h5 class="text-success">Import Previous Advance Payment</h5>
                            <form action="{{ route('students.import.previous.advance') }}" method="POST" id="advanceForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="advance_student_id" class="form-label">Student</label>
                                            <select class="form-select select2 @error('advance_student_id') is-invalid @enderror" id="advance_student_id" name="student_id"
                                                required>
                                                <option value="">Select Student</option>
                                                @foreach ($students ?? [] as $student)
                                                    <option value="{{ $student->id }}" {{ old('advance_student_id') == $student->id ? 'selected' : '' }}>
                                                        {{ $student->roll_no }} - {{ $student->first_name }}
                                                        {{ $student->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('advance_student_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="advance_amount" class="form-label">Advance Amount</label>
                                            <input type="number" class="form-control @error('advance_amount') is-invalid @enderror" id="advance_amount"
                                                name="advance_amount" step="0.01" min="0" value="{{ old('advance_amount') }}" required>
                                            @error('advance_amount')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="advance_date" class="form-label">Advance Date</label>
                                            <input type="date" class="form-control @error('advance_date') is-invalid @enderror" id="advance_date"
                                                name="advance_date" max="{{ date('Y-m-d') }}" value="{{ old('advance_date') }}" required>
                                            @error('advance_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-upload me-1"></i> Add Advance Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Import Section -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Bulk Import</h4>
                        <p class="text-muted mb-0">Import multiple records from CSV or Excel files</p>
                    </div>
                    <div class="card-body">
                        <!-- Bulk Arrears Import -->
                        <div class="mb-4">
                            <h5 class="text-danger">Bulk Import Arrears</h5>
                            <form action="{{ route('students.bulk.import.arrears') }}" method="POST"
                                enctype="multipart/form-data" id="bulkArrearsForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="arrears_file" class="form-label">Arrears File (CSV/Excel)</label>
                                    <input type="file" class="form-control @error('arrears_file') is-invalid @enderror" id="arrears_file" name="arrears_file"
                                        accept=".csv,.xlsx,.xls" required>
                                    @error('arrears_file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        File should contain columns: first_name, middle_name, last_name, cnic, amount, date<br>
                                        <small class="text-muted">Date format: MM/DD/YYYY (e.g., 01/15/2024) or YYYY-MM-DD (e.g., 2024-01-15)</small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="mdi mdi-upload me-1"></i> Bulk Import Arrears
                                </button>
                            </form>
                        </div>

                        <hr>

                        <!-- Bulk Advance Import -->
                        <div class="mb-4">
                            <h5 class="text-success">Bulk Import Advance Payments</h5>
                            <form action="{{ route('students.bulk.import.advance') }}" method="POST"
                                enctype="multipart/form-data" id="bulkAdvanceForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="advance_file" class="form-label">Advance Payments File (CSV/Excel)</label>
                                    <input type="file" class="form-control @error('advance_file') is-invalid @enderror" id="advance_file" name="advance_file"
                                        accept=".csv,.xlsx,.xls" required>
                                    @error('advance_file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        File should contain columns: first_name, middle_name, last_name, cnic, amount, date<br>
                                        <small class="text-muted">Date format: MM/DD/YYYY (e.g., 01/15/2024) or YYYY-MM-DD (e.g., 2024-01-15)</small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-upload me-1"></i> Bulk Import Advance Payments
                                </button>
                            </form>
                        </div>

                        <!-- Download Templates -->
                        <div class="mt-4">
                            <h6>Download Templates</h6>
                            <div class="d-flex gap-2">
                                <a href="{{ route('students.download.template', 'arrears') }}"
                                    class="btn btn-outline-danger btn-sm">
                                    <i class="mdi mdi-download"></i> Arrears Template
                                </a>
                                <a href="{{ route('students.download.template', 'advance') }}"
                                    class="btn btn-outline-success btn-sm">
                                    <i class="mdi mdi-download"></i> Advance Template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Statistics Modal -->
    @if (session('import_stats'))
        @php
            $importStats = session('import_stats');
        @endphp
        <div class="modal fade" id="importStatsModal" tabindex="-1" aria-labelledby="importStatsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importStatsModalLabel">
                            <i class="mdi mdi-chart-line me-2"></i>
                            Import Statistics - {{ ucfirst($importStats['type']) }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info mb-4">
                            <i class="mdi mdi-information me-2"></i>
                            <strong>Note:</strong> Records with the same month and year are automatically updated instead of creating duplicates.
                        </div>
                        
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $importStats['total_rows'] }}</h3>
                                        <p class="mb-0">Total Rows</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $importStats['successful_imports'] }}</h3>
                                        <p class="mb-0">Successful</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $importStats['updated_count'] ?? 0 }}</h3>
                                        <p class="mb-0">Updated</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $importStats['failed_imports'] }}</h3>
                                        <p class="mb-0">Failed</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Details -->
                        @if(count($importStats['success_rows']) > 0)
                            <div class="mb-4">
                                <h6 class="text-success">
                                    <i class="mdi mdi-check-circle me-2"></i>
                                    Successfully Imported Rows
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-success">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Name</th>
                                                <th>CNIC</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($importStats['success_rows'] as $row)
                                                <tr>
                                                    <td>{{ $row['row'] }}</td>
                                                    <td>
                                                        {{ $row['data']['first_name'] ?? '' }}
                                                        {{ $row['data']['middle_name'] ?? '' }}
                                                        {{ $row['data']['last_name'] ?? '' }}
                                                    </td>
                                                    <td>{{ $row['data']['cnic'] ?? '' }}</td>
                                                    <td>{{ number_format($row['data']['amount'] ?? 0, 2) }}</td>
                                                    <td>{{ $row['data']['date'] ?? '' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $row['action'] === 'Updated' ? 'warning' : 'success' }}">
                                                            {{ $row['action'] ?? 'Created' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Error Details -->
                        @if(count($importStats['error_rows']) > 0)
                            <div class="mb-4">
                                <h6 class="text-danger">
                                    <i class="mdi mdi-alert-circle me-2"></i>
                                    Failed Rows
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-danger">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Name</th>
                                                <th>CNIC</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                                <th>Error</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($importStats['error_rows'] as $row)
                                                <tr>
                                                    <td>{{ $row['row'] }}</td>
                                                    <td>
                                                        {{ $row['data']['first_name'] ?? '' }}
                                                        {{ $row['data']['middle_name'] ?? '' }}
                                                        {{ $row['data']['last_name'] ?? '' }}
                                                    </td>
                                                    <td>{{ $row['data']['cnic'] ?? '' }}</td>
                                                    <td>{{ $row['data']['amount'] ?? '' }}</td>
                                                    <td>{{ $row['data']['date'] ?? '' }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">N/A</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-danger small">{{ $row['error'] }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @if(count($importStats['error_rows']) > 0)
                            <a href="{{ route('students.download.template', $importStats['type']) }}" class="btn btn-primary">
                                <i class="mdi mdi-download me-1"></i>
                                Download Template
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('header_scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all student dropdowns
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Student',
                allowClear: true,
                width: '100%'
            });

            // Set max date for date inputs to today (past dates only)
            const today = new Date().toISOString().split('T')[0];
            $('#arrears_date, #advance_date').attr('max', today);

            // Add validation to prevent future dates
            $('#arrears_date, #advance_date').on('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(23, 59, 59, 999); // Set to end of today

                if (selectedDate > today) {
                    alert('Please select a past date only. Future dates are not allowed.');
                    this.value = '';
                    return false;
                }
            });

            // Load student summary
            $('#loadSummary').click(function() {
                const studentId = $('#summary_student_id').val();
                if (!studentId) {
                    alert('Please select a student first.');
                    return;
                }

                $.ajax({
                    url: `/students/${studentId}/previous-data-summary`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            displayStudentSummary(response.data);
                        } else {
                            alert('Failed to load student data: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Failed to load student data. Please try again.');
                    }
                });
            });

            function displayStudentSummary(data) {
                $('#totalArrears').text(parseFloat(data.total_arrears).toFixed(2));
                $('#totalAdvance').text(parseFloat(data.total_advance).toFixed(2));
                $('#netBalance').text(parseFloat(data.net_balance).toFixed(2));

                // Populate arrears table
                const tbody = $('#arrearsTable tbody');
                tbody.empty();

                data.arrears_breakdown.forEach(function(arrears) {
                    const row = `
                <tr>
                    <td>${arrears.id}</td>
                    <td>${parseFloat(arrears.amount).toFixed(2)}</td>
                    <td>${arrears.carried_date}</td>
                    <td>
                        ${arrears.cleared_date ? 
                            '<span class="badge bg-success">Cleared</span>' : 
                            '<span class="badge bg-warning">Active</span>'
                        }
                    </td>
                    <td>
                        ${!arrears.cleared_date ? 
                            `<input type="checkbox" class="arrears-checkbox" value="${arrears.id}">` : 
                            ''
                        }
                    </td>
                </tr>
            `;
                    tbody.append(row);
                });

                $('#studentSummary').show();
            }

            // Clear arrears functionality
            $(document).on('change', '.arrears-checkbox', function() {
                const checkedBoxes = $('.arrears-checkbox:checked');
                const studentId = $('#summary_student_id').val();

                if (checkedBoxes.length > 0) {
                    const arrearsIds = checkedBoxes.map(function() {
                        return this.value;
                    }).get();

                    $('#clearStudentId').val(studentId);
                    $('#clearArrearsIds').val(JSON.stringify(arrearsIds));
                }
            });

            // Show clear modal when checkboxes are selected
            $(document).on('change', '.arrears-checkbox', function() {
                const checkedBoxes = $('.arrears-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    $('#clearArrearsModal').modal('show');
                }
            });

            // Auto-show import statistics modal if available
            @if(session('import_stats'))
                $(document).ready(function() {
                    $('#importStatsModal').modal('show');
                });
            @endif
        });
    </script>
@endpush
