@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Students</h4>
                    <div class="flex-shrink-0">
                        <button type="button"
                            class="btn btn-sm btn-primary btn-label waves-effect waves-light import-leads-btn"
                            href=""><i class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</button>
                        @permission('export-student')
                            <a class="btn btn-sm btn-success-new btn-label waves-effect waves-light"
                                href="{{ route('export-students') }}">
                                <i class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export
                            </a>
                        @endpermission
                        @permission('add-student')
                            <a href="{{ route('students.create') . '?tab=personal' }}"
                                class="btn btn-success-new btn-label btn-sm">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Student
                            </a>
                        @endpermission
                        <button type="button"
                            class="btn btn-sm btn-warning btn-label waves-effect waves-light"
                            id="viewImportLogsBtn">
                            <i class="ri-file-list-3-line label-icon align-middle fs-16 me-2"></i> Logs
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        @role(auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales'))
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="region_id" name="region_id" placeholder="Region"
                                        disabled>
                                        <option value="">Please select</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}" selected>{{ $region->region_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="region_id" class="form-label">Region</label>
                                </div>
                            </div>
                        @endrole
                        @if (isSuperAdmin() || isHeadOfficeEmp())
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
                                <select class="filter form-select" id="class_id" name="class_id" placeholder="Class">
                                    <option value="">Please select</option>
                                    @if (!isSuperAdmin() && !isHeadOfficeEmp())
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->com_classes->id }}">
                                                {{ $class->com_classes->class_name }} </option>
                                        @endforeach
                                    @else
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="section_id" name="section_id"
                                    placeholder="Section">
                                    <option value="">Please select</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="section_id" class="form-label">Sections</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="gender" name="gender" placeholder="Gender">
                                    <option value="">Please Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <label for="gender" class="form-label">Gender</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="status_list" name="status">
                                    <option value="all">Status</option>
                                    <option value="on_roll">On Roll</option>
                                    <option value="registered">Registered</option>
                                    <option value="processing">Processing</option>
                                    <option value="left">Left</option>
                                    <option value="pass-out">Pass-Out</option>
                                    <option value="transferred">Transferred</option>
                                </select>
                                <label for="status" class="form-label">Status</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                </div>
                <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Academic Year</th>
                            <th>Branch ID</th>
                            <th>Branch</th>
                            <th>Reg ID / StuID </th>
                            <th>Name</th>
                            <th>CNIC</th>
                            <th>Gender</th>
                            <th>Class / Section</th>
                            <th>Admission WEF</th>
                            <th>Security</th>
                            <th>DOB</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Sr #</th>
                            <th>Academic Year</th>
                            <th>Branch ID</th>
                            <th>Branch</th>
                            <th>Reg ID / StuID </th>
                            <th>Name</th>
                            <th>CNIC</th>
                            <th>Gender</th>
                            <th>Class / Section</th>
                            <th>Admission WEF</th>
                            <th>Security</th>
                            <th>DOB</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include the statistics modal -->
@include('students.student_import_modal')

<!-- Modal for Import Logs -->
<div class="modal fade" id="importLogsModal" tabindex="-1" aria-labelledby="importLogsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="importLogsModalLabel">
          <i class="ri-file-list-3-line me-2"></i>Import Error Logs
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <!-- Summary Stats -->
        <div class="p-3 border-bottom bg-light">
          <div class="row">
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <div class="avatar-sm me-3">
                  <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                    <i class="ri-error-warning-line"></i>
                  </div>
                </div>
                <div>
                  <h6 class="mb-0" id="totalErrors">0</h6>
                  <small class="text-muted">Total Errors</small>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <div class="avatar-sm me-3">
                  <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                    <i class="ri-alert-line"></i>
                  </div>
                </div>
                <div>
                  <h6 class="mb-0" id="validationErrors">0</h6>
                  <small class="text-muted">Validation Errors</small>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <div class="avatar-sm me-3">
                  <div class="avatar-title bg-info-subtle text-info rounded-circle">
                    <i class="ri-information-line"></i>
                  </div>
                </div>
                <div>
                  <h6 class="mb-0" id="duplicateErrors">0</h6>
                  <small class="text-muted">Duplicate Errors</small>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <div class="avatar-sm me-3">
                  <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle">
                    <i class="ri-file-damage-line"></i>
                  </div>
                </div>
                <div>
                  <h6 class="mb-0" id="otherErrors">0</h6>
                  <small class="text-muted">Other Errors</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Scrollable Table Container -->
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
          <table class="table table-bordered table-hover mb-0" id="importLogsTable">
            <thead class="table-dark sticky-top">
              <tr>
                <th style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;">Type</th>
                <th style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;">Row</th>
                <th style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;">Field</th>
                <th style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;">Error</th>
                <th style="position: sticky; top: 0; z-index: 10; background: #343a40; color: white;">Value</th>
              </tr>
            </thead>
            <tbody id="importLogsTableBody">
              <!-- Logs will be loaded here -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ri-close-line me-1"></i>Close
        </button>
        <button type="button" class="btn btn-info" onclick="exportLogsToCSV()">
          <i class="ri-download-line me-1"></i>Export to CSV
        </button>
      </div>
    </div>
  </div>
</div>

@endsection


@push('header_scripts')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
<style>
    /* Custom styles for import logs modal */
    #importLogsModal .modal-dialog {
        max-width: 95%;
    }
    
    #importLogsModal .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    #importLogsModal .sticky-top th {
        position: sticky !important;
        top: 0 !important;
        z-index: 10 !important;
        background: #343a40 !important;
        color: white !important;
        border-bottom: 2px solid #495057 !important;
    }
    
    #importLogsModal .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    #importLogsModal .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    #importLogsModal .table-info {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
    
    #importLogsModal .table-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    #importLogsModal .table-secondary {
        background-color: rgba(108, 117, 125, 0.1) !important;
    }
    
    #importLogsModal .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
    }
    
    #importLogsModal code {
        background-color: #f8f9fa;
        padding: 0.2em 0.4em;
        border-radius: 0.25rem;
        font-size: 0.875em;
    }
    
    #importLogsModal .avatar-sm {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    #importLogsModal .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 1rem;
    }
    
    /* Scrollbar styling */
    #importLogsModal .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    #importLogsModal .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    #importLogsModal .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    #importLogsModal .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#example').DataTable({
            searching: false,
            retrieve: true,
            serverSide: true,
            processing: true,
            language: {
                search: "",
                processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                searchPlaceholder: "Search..."
            },
            responsive: true,
            bLengthChange: false,
            pageLength: 10,
            scrollX: true,
            ajax: {
                url: "{{ route('students.index') }}",
                data: function(d) {
                    d.academic_year_id = $('#academic_year_id').val();
                    d.gender = $('#gender').val();
                    d.section_id = $('#section_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.region_id = $('#region_id').val();
                    d.class_id = $('#class_id').val();
                    d.status = $('#status_list').val();
                    d.searchName = $('#mySearch').val().toLowerCase();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_Row_Index',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'academic_year_id',
                    name: 'academic_year_id'
                },
                {
                    data: 'branch.branch_code',
                    name: 'branch.branch_code'
                },
                {
                    data: 'branch.br_name',
                    name: 'branch.br_name'
                },
                {
                    data: 'reg_roll_no',
                    name: 'reg_roll_no',
                    width: "5%"
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'gender',
                    name: 'gender'
                },
                {
                    data: 'class_section', //class_section
                    name: 'class_section'
                },
                {
                    data: 'admission_wef', //admission wef
                    name: 'admission_wef',
                    width: "5%"
                },
                {
                    data: 'security', //admission wef
                    name: 'security',
                    width: "5%"
                },
                {
                    data: 'date_of_birth',
                    name: 'date_of_birth'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                /*{
                    data: 'gender',
                    name: 'gender'
                },
                {
                    data: 'religion.religion_name',
                    name: 'religion.religion_name'
                },
                {
                    data: 'city.city_name',
                    name: 'city.city_name'
                },*/
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
                    width: "5%"
                }
            ]
        });

        $('#leadImportForm').on('submit', function(event) {
            event.preventDefault();
            const form = $(this);
            const submitBtn = $('#uploadBtn');
            const alertContainer = $('#alert-container');

            // Debug: Log the file input and its value
            const fileInput = form.find('input[type="file"]')[0];
            console.log('File input:', fileInput);
            console.log('Selected file:', fileInput && fileInput.files[0]);

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
                        
                        if (result.queued) {
                            $('#progressText').text('Import queued successfully!');
                            
                            // Show queued message
                            alertContainer.html(`
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <strong>Import Queued:</strong><br>
                                    <div>${result.message}</div>
                                    <div class="mt-2">
                                        <small>You can close this window. The import will continue in the background.</small>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                            
                            // Close modal after 3 seconds
                            setTimeout(() => {
                                $('#importLeadsModal').modal('hide');
                                // Refresh the table to show any completed imports
                                $('#example').DataTable().ajax.reload();
                            }, 3000);
                            
                        } else if (result.success || result.imported_count > 0 || result.skipped_count > 0) {
                            $('#progressText').text('Import completed successfully!');
                            $('#importLeadsModal').modal('hide');
                            
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
                        } else {
                            // If no successful import, show error message
                            alertContainer.html(`
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Import Warning:</strong><br>
                                    <div>No records were imported. Please check your file and try again.</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                            $('#importLeadsModal').modal('show');
                        }
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
                        } else if (xhr.responseJSON?.message) {
                            // General error message from server
                            errorHtml = `<div>${xhr.responseJSON.message}</div>`;
                        } else {
                            // Generic error
                            errorHtml = `<div>An error occurred: ${xhr.responseJSON?.errors?.[0] || xhr.statusText || 'Unknown error'}</div>`;
                        }
                        
                        // Display error message and keep modal open
                        alertContainer.html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Import Error:</strong><br>
                                ${errorHtml}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                        
                        // Ensure the import modal stays open and stats modal doesn't show
                        $('#importLeadsModal').modal('show');
                        
                        // Scroll to the error message for better visibility
                        setTimeout(() => {
                            alertContainer[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                        
                        // Add a small delay to ensure the modal is fully visible before scrolling
                        setTimeout(() => {
                            if (alertContainer.find('.alert').length > 0) {
                                alertContainer.find('.alert')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }, 300);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.html('Upload');
                        
                        // Only reset progress if there were no errors (errors are handled in error callback)
                        setTimeout(() => {
                            $('#uploadProgress').hide();
                            $('#progressBar').css('width', '0%').text('0%');
                            $('#progressText').text('Preparing upload...');
                        }, 2000);
                    }
                });
            } catch (e) {
                console.error('AJAX error:', e);
                alert('A JavaScript error occurred: ' + e.message);
            }
        });
    });

    $(document).on('change', '.filter', function() {
        $('#example').DataTable().ajax.reload(null, false).page('first');
    });

    $(document).on("keyup", '#mySearch', function() {
        var value = $(this).val().toLowerCase();
        if (value.length > 0 || value.length == 0) {
            $('#example').DataTable().ajax.reload(null, false);
        }
    });
    $(document).on('click', '.import-leads-btn', function() {
        // Clear previous errors and alerts
        $('#alert-container').empty();
        $('.import-leads-form').removeClass('was-validated');
        $('.import-leads-form')[0].reset();
        $('.error-row').remove();
        
        // Reset progress bar
        $('#uploadProgress').hide();
        $('#progressBar').css('width', '0%').text('0%');
        $('#progressText').text('Preparing upload...');
        
        // Reset submit button
        $('#uploadBtn').prop('disabled', false).html('Upload');
        
        // Show the modal
        $('#importLeadsModal').modal('show');
    });
    
    // Handle modal close events to ensure proper cleanup
    $('#importLeadsModal').on('hidden.bs.modal', function() {
        // Clear any remaining error messages when modal is closed
        $('#alert-container').empty();
        $('#uploadProgress').hide();
        $('#progressBar').css('width', '0%').text('0%');
        $('#progressText').text('Preparing upload...');
        $('#uploadBtn').prop('disabled', false).html('Upload');
    });



    // Function to refresh the student table
    function refreshStudentTable() {
        $('#example').DataTable().ajax.reload();
        $('#importStatsModal').modal('hide');
    }



    // Function to download import report
    function downloadImportReport() {
        const importedCount = $('#importedCount').text();
        const skippedCount = $('#skippedCount').text();
        const totalProcessed = $('#totalProcessed').text();
        const timestamp = new Date().toLocaleString();
        
        const reportContent = `Student Import Report
Generated on: ${timestamp}

Summary:
- Total Records Processed: ${totalProcessed}
- Successfully Imported: ${importedCount}
- Skipped (Existing): ${skippedCount}

Details:
- New students with unique CNIC numbers were successfully imported
- Students with existing CNIC numbers were automatically skipped to prevent duplicates
- All data was validated for accuracy and completeness
- The student list has been updated with new records

This report was generated automatically by the Supernova SIS system.`;

        const blob = new Blob([reportContent], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `student_import_report_${new Date().toISOString().split('T')[0]}.txt`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }

    $('#viewImportLogsBtn').on('click', function() {
        $.get('/student-import-log', function(data) {
            try {
                // Split by lines, parse each as JSON
                const lines = data.trim().split('\n');
                let entries = [];
                let totalErrors = 0;
                let validationErrors = 0;
                let duplicateErrors = 0;
                let otherErrors = 0;
                
                // Parse all entries first
                for (const line of lines) {
                    if (!line.trim()) continue;
                    let entry = null;
                    try {
                        let jsonStart = line.indexOf('{');
                        if (jsonStart > 0) {
                            entry = JSON.parse(line.slice(jsonStart));
                        } else {
                            entry = JSON.parse(line);
                        }
                        entries.push(entry);
                        totalErrors++;
                        
                        // Categorize errors
                        const errorType = entry.type?.toLowerCase() || '';
                        const errorMessage = (entry.errors && typeof entry.errors === 'string') ? 
                            entry.errors.toLowerCase() : '';
                        
                        if (errorType.includes('validation') || errorMessage.includes('validation') || 
                            errorMessage.includes('required') || errorMessage.includes('invalid')) {
                            validationErrors++;
                        } else if (errorType.includes('duplicate') || errorMessage.includes('duplicate') || 
                                   errorMessage.includes('already exists') || errorMessage.includes('unique')) {
                            duplicateErrors++;
                        } else {
                            otherErrors++;
                        }
                    } catch (e) {
                        continue;
                    }
                }
                
                // Update summary stats
                $('#totalErrors').text(totalErrors);
                $('#validationErrors').text(validationErrors);
                $('#duplicateErrors').text(duplicateErrors);
                $('#otherErrors').text(otherErrors);
                
                // Generate table rows with color coding
                let tableBody = '';
                entries.forEach((entry, index) => {
                    let errorHtml = '';
                    if (Array.isArray(entry.errors)) {
                        errorHtml = entry.errors.join('<br>');
                    } else if (typeof entry.errors === 'object' && entry.errors !== null) {
                        for (const [field, messages] of Object.entries(entry.errors)) {
                            errorHtml += `<b>${field}:</b> ${messages.join('<br>')}<br>`;
                        }
                    } else if (typeof entry.errors === 'string') {
                        errorHtml = entry.errors;
                    }
                    
                    // Determine row color based on error type
                    let rowClass = '';
                    const errorType = entry.type?.toLowerCase() || '';
                    const errorMessage = (entry.errors && typeof entry.errors === 'string') ? 
                        entry.errors.toLowerCase() : '';
                    
                    if (errorType.includes('validation') || errorMessage.includes('validation') || 
                        errorMessage.includes('required') || errorMessage.includes('invalid')) {
                        rowClass = 'table-warning'; // Yellow for validation errors
                    } else if (errorType.includes('duplicate') || errorMessage.includes('duplicate') || 
                               errorMessage.includes('already exists') || errorMessage.includes('unique')) {
                        rowClass = 'table-info'; // Blue for duplicate errors
                    } else if (errorType.includes('critical') || errorMessage.includes('critical') || 
                               errorMessage.includes('fatal')) {
                        rowClass = 'table-danger'; // Red for critical errors
                    } else {
                        rowClass = 'table-secondary'; // Gray for other errors
                    }
                    
                    tableBody += `<tr class="${rowClass}">
                        <td>
                            <span class="badge ${getBadgeClass(entry.type)}">${entry.type || 'Unknown'}</span>
                        </td>
                        <td><strong>${entry.row || 'N/A'}</strong></td>
                        <td><code>${entry.field || 'N/A'}</code></td>
                        <td>${errorHtml}</td>
                        <td>
                            <span class="text-muted">${entry.value || 'N/A'}</span>
                        </td>
                    </tr>`;
                });
                
                $('#importLogsTableBody').html(tableBody);
                $('#importLogsModal').modal('show');
                
            } catch (e) {
                $('#importLogsTableBody').html('<tr><td colspan="5" class="text-center text-danger">Failed to parse log file.</td></tr>');
                $('#importLogsModal').modal('show');
            }
        }).fail(function() {
            $('#importLogsTableBody').html('<tr><td colspan="5" class="text-center text-danger">Could not load log file.</td></tr>');
            $('#importLogsModal').modal('show');
        });
    });
    
    // Helper function to get badge class based on error type
    function getBadgeClass(errorType) {
        if (!errorType) return 'bg-secondary';
        
        const type = errorType.toLowerCase();
        if (type.includes('validation')) return 'bg-warning text-dark';
        if (type.includes('duplicate')) return 'bg-info text-dark';
        if (type.includes('critical') || type.includes('fatal')) return 'bg-danger';
        if (type.includes('warning')) return 'bg-warning text-dark';
        if (type.includes('info')) return 'bg-info text-dark';
        return 'bg-secondary';
    }
    
    // Function to export logs to CSV
    function exportLogsToCSV() {
        const table = document.getElementById('importLogsTable');
        const rows = table.querySelectorAll('tbody tr');
        
        let csvContent = 'Type,Row,Field,Error,Value\n';
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = [];
            
            cells.forEach((cell, index) => {
                let cellText = cell.textContent.trim();
                // Remove badge text and get clean content
                if (index === 0) {
                    cellText = cellText.replace(/^\s*\w+\s*$/, ''); // Remove badge text
                }
                // Escape quotes and wrap in quotes if contains comma
                if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                    cellText = '"' + cellText.replace(/"/g, '""') + '"';
                }
                rowData.push(cellText);
            });
            
            csvContent += rowData.join(',') + '\n';
        });
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `import_logs_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush
