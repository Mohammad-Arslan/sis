@extends('layouts.master')
@section('content')
    @push('header_scripts')
    <style>
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 4px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }
        
        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }
        
        /* Simple form styling - removed complex floating labels */
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
        
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .table-responsive {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .sticky-top {
            z-index: 1020;
        }
        
        .badge {
            font-size: 0.75em;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        

    </style>
    @endpush
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Student List</a></li>
        <li class="breadcrumb-item active">Bulk Challan Generation</li>
    </x-breadcrumb>

    @include('components.flash_message')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="mb-0 card-title flex-grow-1">Bulk Challan Generation</h4>
                </div>

                <div class="card-body">
                    <form id="bulkChallanForm" method="POST" action="{{ route('generate-simple-bulk-challans') }}">
                        @csrf
                        
                        <!-- Step 1: Select Branch -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                                <select class="form-select @error('branch_id') is-invalid @enderror" 
                                        id="branch_id" name="branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" 
                                                {{ $branch->id == get_branch_id() ? 'selected' : '' }}>
                                            {{ $branch->br_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                                <select class="form-select @error('academic_year_id') is-invalid @enderror" 
                                        id="academic_year_id" name="academic_year_id" required>
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" 
                                                {{ $year->active ? 'selected' : '' }}>
                                            {{ $year->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 2: Select Fee Package -->
                        <div class="row mb-4" id="feePackageSection" style="display: none;">
                            <div class="col-md-6">
                                <label for="fee_package_id" class="form-label">Fee Package <span class="text-danger">*</span></label>
                                <select class="form-select @error('fee_package_id') is-invalid @enderror" 
                                        id="fee_package_id" name="fee_package_id" required>
                                    <option value="">Select Fee Package</option>
                                </select>
                                @error('fee_package_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fee_period_id" class="form-label">Fee Period <span class="text-danger">*</span></label>
                                <select class="form-select @error('fee_period_id') is-invalid @enderror" 
                                        id="fee_period_id" name="fee_period_id" required>
                                    <option value="">Select Fee Period</option>
                                </select>
                                @error('fee_period_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 3: Select Classes and Sections -->
                        <div class="row mb-4" id="classesSection" style="display: none;">
                            <div class="col-md-6">
                                <label for="class_id" class="form-label">Classes (Optional)</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" name="class_id">
                                    <option value="">Select Classes (Optional)</option>
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="section_id" class="form-label">Sections (Optional)</label>
                                <select class="form-select @error('section_id') is-invalid @enderror" 
                                        id="section_id" name="section_id">
                                    <option value="">Select Sections (Optional)</option>
                                </select>
                                @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 3: Select Students -->
                        <div class="row mb-4" id="studentsSection" style="display: none;">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Select Students</h5>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">Select All</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Deselect All</button>
                                    </div>
                                </div>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped">
                                        <thead class="sticky-top bg-white">
                                            <tr>
                                                <th width="50">
                                                    <input type="checkbox" id="selectAllStudents">
                                                </th>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Status</th>
                                                <th>Current Package</th>
                                            </tr>
                                        </thead>
                                        <tbody id="studentsTableBody">
                                            <!-- Students will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Fee Package Charges -->
                        <div class="row mb-4" id="chargesSection" style="display: none;">
                            <div class="col-12">
                                <h5>Fee Package Charges (All charges will be included)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="50">
                                                    <span class="text-muted">✓</span>
                                                </th>
                                                <th>Charge Type</th>
                                                <th>Description</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="chargesTableBody">
                                            <!-- Charges will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Summary and Submit -->
                        <div class="row mb-4" id="summarySection" style="display: none;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-3 text-center">
                                                <div class="border-end">
                                                    <h4 class="text-primary mb-1" id="selectedStudentsCount">0</h4>
                                                    <small class="text-muted">Selected Students</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="border-end">
                                                    <h4 class="text-info mb-1" id="selectedChargesCount">0</h4>
                                                    <small class="text-muted">Package Charges</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="border-end">
                                                    <h4 class="text-success mb-1" id="totalAmount">Rs. 0.00</h4>
                                                    <small class="text-muted">Total Amount</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <button type="button" class="btn btn-primary btn-lg" id="generateBtn">
                                                    <i class="fas fa-file-invoice me-2"></i>Generate Challans
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5>Generating Challans...</h5>
                    <p class="text-muted">Please wait while we process your request. Do not close this window.</p>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                    </div>
                    <small class="text-muted" id="progressText">Processing batch 1 of 1...</small>
                </div>
            </div>
        </div>
    </div>



    @push('footer_scripts')
    <script>
        // Global error handler
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
            alert('JavaScript error: ' + e.error.message);
        });
        
        $(document).ready(function() {
            let selectedStudents = [];
            let selectedCharges = [];
            let feePackageCharges = [];
            
            // Prevent loading modal from being closed during processing
            $('#loadingModal').on('hide.bs.modal', function (e) {
                if (typeof isProcessing !== 'undefined' && isProcessing) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Initialize the form on page load
            initializeForm();

            // Load fee packages when branch and academic year are selected
            $('#branch_id, #academic_year_id').change(function() {
                const branchId = $('#branch_id').val();
                const academicYearId = $('#academic_year_id').val();
                
                if (branchId && academicYearId) {
                    loadFeePackagesForBranch(branchId, academicYearId);
                    loadFeePeriods(academicYearId);
                    $('#feePackageSection').show();
                } else {
                    $('#feePackageSection').hide();
                    $('#classesSection').hide();
                    $('#studentsSection').hide();
                    $('#chargesSection').hide();
                    $('#summarySection').hide();
                }
            });

            // Load classes when fee package is selected
            $('#fee_package_id').change(function() {
                const feePackageId = $(this).val();
                
                if (feePackageId) {
                    loadClassesForFeePackage(feePackageId);
                    loadCharges(feePackageId);
                    $('#classesSection').show();
                } else {
                    $('#classesSection').hide();
                    $('#studentsSection').hide();
                    $('#chargesSection').hide();
                    $('#summarySection').hide();
                }
            });

            // Load sections when classes are selected
            $('#class_id').change(function() {
                const selectedClass = $(this).val(); // Single class ID
                const branchId = $('#branch_id').val();
                
                if (selectedClass && branchId) {
                    loadSectionsForClass(selectedClass, branchId);
                } else {
                    $('#section_id').html('<option value="">Select Sections (Optional)</option>');
                }
                
                // Reload students if all required fields are available
                const feePackageId = $('#fee_package_id').val();
                const academicYearId = $('#academic_year_id').val();
                const feePeriodId = $('#fee_period_id').val();
                const sectionId = $('#section_id').val();
                
                if (feePackageId && academicYearId && feePeriodId && branchId) {
                    loadStudents(feePackageId, academicYearId, feePeriodId, branchId, selectedClass, sectionId);
                }
            });

            // Load fee periods when fee period dropdown changes
            $('#fee_period_id').change(function() {
                const feePackageId = $('#fee_package_id').val();
                const academicYearId = $('#academic_year_id').val();
                const feePeriodId = $(this).val();
                const branchId = $('#branch_id').val();
                const classId = $('#class_id').val(); // Single class ID
                const sectionId = $('#section_id').val(); // Single section ID
                
                if (feePackageId && academicYearId && feePeriodId && branchId) {
                    loadStudents(feePackageId, academicYearId, feePeriodId, branchId, classId, sectionId);
                }
            });

            // Load students when section is selected
            $('#section_id').change(function() {
                const feePackageId = $('#fee_package_id').val();
                const academicYearId = $('#academic_year_id').val();
                const feePeriodId = $('#fee_period_id').val();
                const branchId = $('#branch_id').val();
                const classId = $('#class_id').val(); // Single class ID
                const sectionId = $(this).val(); // Single section ID
                
                if (feePackageId && academicYearId && feePeriodId && branchId) {
                    loadStudents(feePackageId, academicYearId, feePeriodId, branchId, classId, sectionId);
                }
            });

            // Load students when section is selected
            $('#section_id').change(function() {
                const feePackageId = $('#fee_package_id').val();
                const academicYearId = $('#academic_year_id').val();
                const feePeriodId = $('#fee_period_id').val();
                const branchId = $('#branch_id').val();
                const classId = $('#class_id').val(); // Single class ID
                const sectionId = $(this).val(); // Single section ID
                
                if (feePackageId && academicYearId && feePeriodId && branchId) {
                    loadStudents(feePackageId, academicYearId, feePeriodId, branchId, classId, sectionId);
                }
            });

            function loadFeePackagesForBranch(branchId, academicYearId) {
                console.log('Loading fee packages for branch:', branchId, 'academic year:', academicYearId);
                
                if (!academicYearId) {
                    console.log('No academic year selected, skipping fee package load');
                    return;
                }
                
                $.ajax({
                    url: '{{ route("get-fee-packages") }}',
                    method: 'GET',
                    data: {
                        academic_year_id: academicYearId,
                        branch_id: branchId
                    },
                    success: function(response) {
                        console.log('Fee packages response:', response);
                        if (response.success) {
                            let options = '<option value="">Select Fee Package</option>';
                            
                            response.fee_packages.forEach(function(package) {
                                options += `<option value="${package.id}" data-classes="${package.classes}">
                                    ${package.package_name} (${package.fee_package_type.name})
                                </option>`;
                            });
                            
                            $('#fee_package_id').html(options);
                            console.log('Fee packages loaded successfully:', response.fee_packages.length, 'packages');
                        } else {
                            console.error('Error loading fee packages:', response.error);
                            $('#fee_package_id').html('<option value="">Error loading fee packages</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error loading fee packages:', {xhr, status, error});
                        $('#fee_package_id').html('<option value="">Error loading fee packages</option>');
                    }
                });
            }

            function loadClassesForFeePackage(feePackageId) {
                console.log('Loading classes for fee package:', feePackageId);
                $.ajax({
                    url: '{{ route("get-classes-for-fee-package") }}',
                    method: 'GET',
                    data: {
                        fee_package_id: feePackageId
                    },
                    success: function(response) {
                        console.log('Classes response:', response);
                        if (response.success) {
                            let options = '<option value="">Select Classes (Optional)</option>';
                            
                            response.classes.forEach(function(classItem) {
                                options += `<option value="${classItem.id}">${classItem.class_name}</option>`;
                            });
                            
                            $('#class_id').html(options);
                            $('#classesSection').show();
                            console.log('Classes loaded successfully:', response.classes.length, 'classes');
                        } else {
                            console.error('Error loading classes:', response.error);
                            alert('Error loading classes: ' + (response.error || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error loading classes:', {xhr, status, error});
                        alert('Error loading classes');
                    }
                });
            }

            function loadSectionsForClass(classId, branchId) {
                if (!branchId) {
                    branchId = $('#branch_id').val();
                }
                console.log('Loading sections for class:', classId, 'branch:', branchId);
                $.ajax({
                    url: '{{ route("get-sections-for-class") }}',
                    method: 'GET',
                    data: {
                        class_id: classId,
                        branch_id: branchId
                    },
                    success: function(response) {
                        console.log('Sections response:', response);
                        if (response.success) {
                            let options = '<option value="">Select Sections (Optional)</option>';
                            response.sections.forEach(function(section) {
                                options += `<option value="${section.id}">${section.section_name}</option>`;
                            });
                            $('#section_id').html(options);
                            console.log('Sections loaded successfully:', response.sections.length, 'sections');
                        } else {
                            console.error('Error loading sections:', response.error);
                            alert('Error loading sections: ' + (response.error || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error loading sections:', {xhr, status, error});
                        alert('Error loading sections');
                    }
                });
            }

            function loadFeePeriods(academicYearId) {
                const branchId = $('#branch_id').val();
                
                $.ajax({
                    url: '{{ route("get-enhanced-fee-periods") }}',
                    method: 'GET',
                    data: {
                        academic_year_id: academicYearId,
                        branch_id: branchId
                    },
                    success: function(response) {
                        if (response.success) {
                            const periods = response.fee_periods || response;
                            let options = '<option value="">Select Fee Period</option>';
                            
                            periods.forEach(function(period) {
                                options += `<option value="${period.id}">${period.period_name} (${period.from_date} - ${period.to_date})</option>`;
                            });
                            
                            $('#fee_period_id').html(options);
                        } else {
                            $('#fee_period_id').html('<option value="">Error loading fee periods</option>');
                            console.error('Error loading fee periods:', response.error || 'Unknown error');
                        }
                    },
                    error: function() {
                        $('#fee_period_id').html('<option value="">Error loading fee periods</option>');
                        console.error('Error loading fee periods');
                    }
                });
            }

            function loadStudents(feePackageId, academicYearId, feePeriodId, branchId, classId, sectionId) {
                // Show loading indicator
                $('#studentsTableBody').html('<tr><td colspan="7" class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> Loading students...</td></tr>');
                $('#studentsSection').show();
                
                const requestData = {
                    fee_package_id: feePackageId,
                    academic_year_id: academicYearId,
                    fee_period_id: feePeriodId,
                    branch_id: branchId
                };
                
                // Add class and section filters if provided
                if (classId) {
                    requestData.class_id = classId;
                }
                if (sectionId) {
                    requestData.section_id = sectionId;
                }
                
                // Debug: Log the request data
                console.log('loadStudents Debug - Request Data:', requestData);
                
                $.ajax({
                    url: '{{ route("get-students-for-fee-package") }}',
                    method: 'GET',
                    data: requestData,
                    timeout: 30000, // 30 seconds timeout for large datasets
                    success: function(response) {
                        console.log('Students response:', response); // Debug log
                        
                        // Debug: Log the debug info if available
                        if (response.debug_info) {
                            console.log('Debug Info:', response.debug_info);
                        }
                        
                        let rows = '';
                        selectedStudents = [];
                        
                        // Check if response is successful
                        if (response.success === false) {
                            $('#studentsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error: ' + (response.message || 'Failed to load students') + '</td></tr>');
                            return;
                        }
                        
                        // Extract students from the response
                        let students = [];
                        
                        if (response.students && typeof response.students === 'object') {
                            // Handle object format where students are keyed by index
                            students = Object.values(response.students);
                        } else if (response.students && Array.isArray(response.students)) {
                            students = response.students;
                        } else if (response.data && Array.isArray(response.data)) {
                            students = response.data;
                        } else if (Array.isArray(response)) {
                            students = response;
                        } else {
                            console.error('Unexpected response structure:', response);
                            $('#studentsTableBody').html('<tr><td colspan="7" class="text-center text-warning">Unexpected response format. Please check console for details.</td></tr>');
                            return;
                        }
                        
                        if (students.length === 0) {
                            $('#studentsTableBody').html('<tr><td colspan="7" class="text-center text-muted">No students found for the selected criteria</td></tr>');
                            return;
                        }
                        
                        // Process students in batches to avoid browser freezing
                        const batchSize = 100;
                        let currentBatch = 0;
                        
                        function processBatch() {
                            const start = currentBatch * batchSize;
                            const end = Math.min(start + batchSize, students.length);
                            
                            for (let i = start; i < end; i++) {
                                const student = students[i];
                                
                                // Handle different student object structures
                                const studentId = student.id || student.student_id || 'N/A';
                                const firstName = student.first_name || '';
                                const middleName = student.middle_name || '';
                                const lastName = student.last_name || '';
                                const fullName = `${firstName} ${middleName} ${lastName}`.trim();
                                
                                // Handle nested class/section data
                                let className = 'N/A';
                                let sectionName = 'N/A';
                                
                                if (student.active_class) {
                                    if (student.active_class.branch_class_sections) {
                                        className = student.active_class.branch_class_sections.com_classes?.class_name || 'N/A';
                                        sectionName = student.active_class.branch_class_sections.sections?.section_name || 'N/A';
                                    }
                                }
                                
                                const status = student.status || 'processing';
                                const currentPackage = student.current_package || 'N/A';
                                const hasActivePackage = student.has_active_package || false;
                                const needsPackageAssignment = student.needs_package_assignment || false;
                                
                                // Create package status badge
                                let packageBadge = '';
                                if (needsPackageAssignment) {
                                    packageBadge = '<span class="badge bg-warning"></span>';
                                } else if (hasActivePackage) {
                                    packageBadge = '<span class="badge bg-success"></span>';
                                } else {
                                    packageBadge = '<span class="badge bg-secondary">None</span>';
                                }
                                
                                rows += `
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="student-checkbox" value="${studentId}" 
                                                   data-name="${fullName}">
                                        </td>
                                        <td>${studentId}</td>
                                        <td>${fullName}</td>
                                        <td>${className}</td>
                                        <td>${sectionName}</td>
                                        <td>
                                            <span class="badge bg-${getStatusBadgeColor(status)}">
                                                ${status.charAt(0).toUpperCase() + status.slice(1)}
                                            </span>
                                        </td>
                                        <td>${packageBadge} ${currentPackage}</td>
                                    </tr>
                                `;
                            }
                            
                            currentBatch++;
                            
                            if (end < students.length) {
                                // Process next batch after a small delay
                                setTimeout(processBatch, 10);
                            } else {
                                // All batches processed
                                $('#studentsTableBody').html(rows);
                                updateSummary();
                                console.log(`Loaded ${students.length} students successfully`);
                            }
                        }
                        
                        // Start processing batches
                        processBatch();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        let errorMessage = 'Failed to load students';
                        
                        if (status === 'timeout') {
                            errorMessage = 'Request timed out. Please try again.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        $('#studentsTableBody').html(`<tr><td colspan="7" class="text-center text-danger">Error: ${errorMessage}</td></tr>`);
                    }
                });
            }

            function loadCharges(feePackageId) {
                $.ajax({
                    url: '{{ route("get-fee-package-charges") }}',
                    method: 'GET',
                    data: {
                        fee_package_id: feePackageId
                    },
                    success: function(response) {
                        console.log('Charges response:', response); // Debug log
                        
                        let rows = '';
                        feePackageCharges = response.charges || [];
                        // Automatically select all package charges
                        selectedCharges = feePackageCharges.map(charge => ({
                            id: charge.id,
                            amount: parseFloat(charge.amount)
                        }));
                        
                        if (!response.success) {
                            $('#chargesTableBody').html('<tr><td colspan="4" class="text-center text-danger">Error: ' + (response.message || 'Failed to load charges') + '</td></tr>');
                            $('#chargesSection').show();
                            return;
                        }
                        
                        if (feePackageCharges.length === 0) {
                            $('#chargesTableBody').html('<tr><td colspan="4" class="text-center text-muted">No charges found for this fee package</td></tr>');
                            $('#chargesSection').show();
                            return;
                        }
                        
                        feePackageCharges.forEach(function(charge) {
                            rows += `
                                <tr>
                                    <td>
                                        <input type="checkbox" class="charge-checkbox" value="${charge.id}" 
                                               data-amount="${charge.amount}" checked disabled>
                                    </td>
                                    <td>${charge.fee_charges_type?.name || 'N/A'}</td>
                                    <td>${charge.fee_charges_type?.description || 'N/A'}</td>
                                    <td class="text-end">Rs. ${parseFloat(charge.amount).toLocaleString('en-IN')}</td>
                                </tr>
                            `;
                        });
                        
                        $('#chargesTableBody').html(rows);
                        $('#chargesSection').show();
                        updateSummary();
                    },
                    error: function() {
                        alert('Error loading charges');
                    }
                });
            }

            // Select/Deselect all students
            $('#selectAll').click(function() {
                $('.student-checkbox').prop('checked', true);
                updateSelectedStudents();
            });

            $('#deselectAll').click(function() {
                $('.student-checkbox').prop('checked', false);
                updateSelectedStudents();
            });

            // Select/Deselect all students using header checkbox
            $('#selectAllStudents').change(function() {
                $('.student-checkbox').prop('checked', $(this).is(':checked'));
                updateSelectedStudents();
            });



            // Update selected students when checkboxes change
            $(document).on('change', '.student-checkbox', function() {
                updateSelectedStudents();
            });



            function updateSelectedStudents() {
                selectedStudents = [];
                $('.student-checkbox:checked').each(function() {
                    selectedStudents.push({
                        id: $(this).val(),
                        name: $(this).data('name')
                    });
                });
                updateSummary();
            }

            function updateSelectedCharges() {
                // Always use all package charges
                selectedCharges = feePackageCharges.map(charge => ({
                    id: charge.id,
                    amount: parseFloat(charge.amount)
                }));
                updateSummary();
            }

            function updateSummary() {
                const studentsCount = selectedStudents.length;
                const chargesCount = feePackageCharges.length;
                const totalAmount = feePackageCharges.reduce((sum, charge) => sum + parseFloat(charge.amount), 0) * studentsCount;
                
                $('#selectedStudentsCount').text(studentsCount);
                $('#selectedChargesCount').text(chargesCount);
                $('#totalAmount').text('Rs. ' + totalAmount.toLocaleString('en-IN'));
                
                if (studentsCount > 0 && chargesCount > 0) {
                    $('#summarySection').show();
                } else {
                    $('#summarySection').hide();
                }
            }

            function getStatusBadgeColor(status) {
                switch(status) {
                    case 'on_roll': return 'success';
                    case 'registered': return 'primary';
                    case 'processing': return 'warning';
                    case 'left': return 'danger';
                    default: return 'secondary';
                }
            }

            // Direct click handler for generate button
            $('#generateBtn').on('click', function(e) {
                console.log('Generate button clicked directly');
                e.preventDefault();
                
                // Prevent multiple submissions
                if ($(this).prop('disabled')) {
                    return;
                }
                
                console.log('Selected students:', selectedStudents);
                console.log('Package charges:', feePackageCharges);
                
                if (selectedStudents.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Students Selected',
                        text: 'Please select at least one student to generate challans.',
                        confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                        confirmButtonText: 'Okay',
                        buttonsStyling: false,
                        showCloseButton: true
                    });
                    return;
                }
                
                // Validate required fields
                const requiredFields = ['fee_package_id', 'academic_year_id', 'fee_period_id', 'branch_id'];
                let missingFields = [];
                
                requiredFields.forEach(function(field) {
                    if (!$('#' + field).val()) {
                        missingFields.push(field.replace('_', ' '));
                    }
                });
                
                if (missingFields.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Required Fields',
                        text: 'Please fill in all required fields: ' + missingFields.join(', '),
                        confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                        confirmButtonText: 'Okay',
                        buttonsStyling: false,
                        showCloseButton: true
                    });
                    return;
                }
                
                // Calculate total amount using all package charges
                const totalAmount = feePackageCharges.reduce((sum, charge) => sum + parseFloat(charge.amount), 0) * selectedStudents.length;
                
                Swal.fire({
                    html: '<div class="mt-3">' +
                        '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                        '<div class="pt-2 mx-5 mt-4 fs-15">' +
                        '<h4>Confirm Challan Generation</h4>' +
                        '<p class="mx-4 mb-0 text-muted">Are you sure you want to generate <strong>' + selectedStudents.length + '</strong> challans with a total amount of <strong>Rs. ' + totalAmount.toLocaleString('en-IN') + '</strong>?</p>' +
                        '</div>' +
                        '</div>',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, Generate Challans!',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    cancelButtonText: 'Cancel',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // Batch the students into smaller chunks (e.g., 100 students per batch)
                        const batchSize = 100;
                        let batches = [];
                        for (let i = 0; i < selectedStudents.length; i += batchSize) {
                            batches.push(selectedStudents.slice(i, i + batchSize));
                        }

                        let currentBatch = 0;
                        let totalBatches = batches.length;
                        let createdChallans = [];
                        let skippedStudents = [];
                        let failedStudents = [];
                        let isProcessing = true;

                        // Disable the generate button to prevent multiple submissions
                        $('#generateBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');

                        // Show loading modal
                        $('#loadingModal').modal('show');
                        updateProgress(0, totalBatches);

                        // Start processing batches
                        processBatch(currentBatch);

                        function processBatch(batchIndex) {
                            // Check if all batches are processed at the beginning
                            if (batchIndex >= batches.length) {
                                // All batches processed
                                isProcessing = false;
                                $('#loadingModal').modal('hide');
                                
                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Challan Generation Complete!',
                                    text: `Successfully processed ${selectedStudents.length} students.`,
                                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                                    confirmButtonText: 'Okay',
                                    buttonsStyling: false,
                                    showCloseButton: true
                                }).then(function() {
                                    // Reset the generate button
                                    $('#generateBtn').prop('disabled', false).html('<i class="fas fa-file-invoice me-2"></i>Generate Challans');
                                });
                                return;
                            }

                            const batch = batches[batchIndex];
                            
                            // Prepare form data for this batch
                            const formData = new FormData();
                            formData.append('fee_package_id', $('#fee_package_id').val());
                            formData.append('academic_year_id', $('#academic_year_id').val());
                            formData.append('fee_period_id', $('#fee_period_id').val());
                            formData.append('branch_id', $('#branch_id').val());
                            
                            // Add students for this batch
                            batch.forEach(function(student) {
                                formData.append('students[]', student.id);
                            });

                            // Update progress
                            updateProgress(batchIndex, totalBatches);

                            $.ajax({
                                url: '{{ route("generate-simple-bulk-challans") }}',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                timeout: 30000, // 30 second timeout
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                                                 success: function(response) {
                                     console.log('Batch response:', response);
                                     
                                     if (response.success) {
                                         // Process successful results
                                         if (response.created_challans && Array.isArray(response.created_challans)) {
                                             createdChallans = createdChallans.concat(response.created_challans);
                                         }
                                         if (response.skipped_students && Array.isArray(response.skipped_students)) {
                                             skippedStudents = skippedStudents.concat(response.skipped_students);
                                         }
                                         if (response.failed_students && Array.isArray(response.failed_students)) {
                                             failedStudents = failedStudents.concat(response.failed_students);
                                         }
                                     } else {
                                         // If batch failed, add all students to failed list
                                         batch.forEach(function(student) {
                                             failedStudents.push({
                                                 student_id: student.id,
                                                 name: student.name,
                                                 reason: response.message || 'Batch processing failed'
                                             });
                                         });
                                     }
                                     
                                     currentBatch++;
                                     // Add a small delay before processing next batch to prevent overwhelming the server
                                     setTimeout(function() {
                                         if (isProcessing) {
                                             processBatch(currentBatch);
                                         }
                                     }, 100);
                                 },
                                                                 error: function(xhr, status, error) {
                                     console.error('Batch error:', {xhr, status, error});
                                     
                                     // If batch failed, add all students to failed list
                                     batch.forEach(function(student) {
                                         failedStudents.push({
                                             student_id: student.id,
                                             name: student.name,
                                             reason: 'Network error or server timeout: ' + (xhr.responseJSON?.message || error || 'Unknown error')
                                         });
                                     });
                                     
                                     currentBatch++;
                                     // Add a small delay before processing next batch to prevent overwhelming the server
                                     setTimeout(function() {
                                         if (isProcessing) {
                                             processBatch(currentBatch);
                                         }
                                     }, 100);
                                 },
                                timeout: function() {
                                    console.error('Batch timeout');
                                    
                                    // If batch failed, add all students to failed list
                                    batch.forEach(function(student) {
                                        failedStudents.push({
                                            student_id: student.id,
                                            name: student.name,
                                            reason: 'Request timeout - server took too long to respond'
                                        });
                                    });
                                    
                                    currentBatch++;
                                    // Add a small delay before processing next batch to prevent overwhelming the server
                                    setTimeout(function() {
                                        if (isProcessing) {
                                            processBatch(currentBatch);
                                        }
                                    }, 100);
                                }
                            });
                        }

                        function updateProgress(currentBatch, totalBatches) {
                            const progress = Math.round((currentBatch / totalBatches) * 100);
                            $('#progressBar').css('width', progress + '%');
                            $('#progressText').text('Processing batch ' + (currentBatch + 1) + ' of ' + totalBatches + '...');
                        }


                    } else {
                        console.log('User cancelled the operation');
                    }
                });
            });

            // Form submission handler (removed to avoid conflicts)
            $('#bulkChallanForm').submit(function(e) {
                e.preventDefault();
                // Prevent form submission, use button click handler instead
                return false;
            });

            // Initialize tooltips and other UI enhancements
            $(function () {
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                // Add loading states to buttons (excluding generate button)
                $('.btn').not('#generateBtn').on('click', function() {
                    if (!$(this).hasClass('btn-loading')) {
                        $(this).addClass('btn-loading').prop('disabled', true);
                        setTimeout(() => {
                            $(this).removeClass('btn-loading').prop('disabled', false);
                        }, 2000);
                    }
                });
            });


            
            // Initialize form on page load
            function initializeForm() {
                const feePackageId = $('#fee_package_id').val();
                const academicYearId = $('#academic_year_id').val();
                const branchId = $('#branch_id').val();
                
                // If academic year and branch are selected on page load, load fee packages and periods
                if (academicYearId && branchId) {
                    loadFeePackagesForBranch(branchId, academicYearId);
                    loadFeePeriods(academicYearId);
                    $('#feePackageSection').show();
                }
                
                // If fee package is selected on page load, show classes section
                if (feePackageId) {
                    loadClassesForFeePackage(feePackageId);
                    loadCharges(feePackageId);
                    $('#classesSection').show();
                }
            }
        });
    </script>
    @endpush
@endsection 