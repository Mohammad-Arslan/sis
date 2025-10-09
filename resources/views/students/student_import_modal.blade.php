<div id="importLeadsModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Import Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <!-- Laravel Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <div id="alert-container"></div>
                <div class="row">
                    <div class="col-xxl-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="row g-3 needs-validation import-leads-form"
                                    id="leadImportForm" action="{{ route('import-students') }}"
                                    method="POST" enctype="multipart/form-data" novalidate>
                                    @csrf
                                    <div class="col-12">
                                        <label for="file" class="form-label">Select File (.xlsx or .csv)</label>
                                        <input class="form-control" type="file" name="file"
                                            id="file" accept=".xlsx,.csv" required>
                                        <div class="invalid-feedback">
                                            Please select a valid .xlsx or .csv file.
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar (hidden by default) -->
                                    <div class="col-12 mt-3" id="uploadProgress" style="display: none;">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                 role="progressbar" 
                                                 style="width: 0%" 
                                                 id="progressBar">0%</div>
                                        </div>
                                        <small class="text-muted mt-1" id="progressText">Preparing upload...</small>
                                    </div>
                                    
                                    <div class="col-12 text-end">
                                        <button class="btn btn-primary" type="submit" id="uploadBtn">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Statistics Modal -->
<div id="importStatsModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="importStatsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="importStatsModalLabel">
                    <i class="ri-check-line me-2"></i>Import Completed Successfully
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success border-0">
                            <div class="d-flex align-items-center">
                                <i class="ri-check-circle-fill fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-1">Import Process Completed!</h6>
                                    <p class="mb-0" id="successMessage">Your student data has been processed successfully.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3">
                    <!-- Total Processed -->
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                        <i class="ri-file-list-3-line"></i>
                                    </div>
                                </div>
                                <h4 class="mb-1" id="totalProcessed">0</h4>
                                <p class="text-muted mb-0">Total Records</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Successfully Imported -->
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                        <i class="ri-user-add-line"></i>
                                    </div>
                                </div>
                                <h4 class="mb-1 text-success" id="importedCount">0</h4>
                                <p class="text-muted mb-0">New Students</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Skipped Records -->
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                        <i class="ri-skip-forward-line"></i>
                                    </div>
                                </div>
                                <h4 class="mb-1 text-warning" id="skippedCount">0</h4>
                                <p class="text-muted mb-0">Skipped (Existing)</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Summary -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-light">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="ri-information-line me-2"></i>Import Summary
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title bg-success-subtle text-success rounded">
                                                        <i class="ri-check-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Successfully Imported</h6>
                                                <p class="text-muted mb-0">
                                                    <span id="importedCountText">0</span> new students have been added to the system
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title bg-warning-subtle text-warning rounded">
                                                        <i class="ri-information-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Skipped Records</h6>
                                                <p class="text-muted mb-0">
                                                    <span id="skippedCountText">0</span> existing students were skipped (CNIC already exists)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Information -->
                                <div class="alert alert-info border-0 mt-3">
                                    <div class="d-flex">
                                        <i class="ri-lightbulb-line fs-4 me-3 mt-1"></i>
                                        <div>
                                            <h6 class="alert-heading">What happened?</h6>
                                            <ul class="mb-0 ps-3">
                                                <li>New students with unique CNIC numbers were successfully imported</li>
                                                <li>Students with existing CNIC numbers were automatically skipped to prevent duplicates</li>
                                                <li>All data was validated for accuracy and completeness</li>
                                                <li>The student list has been updated with new records</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Close
                </button>
                <button type="button" class="btn btn-info" onclick="downloadImportReport()">
                    <i class="ri-download-line me-1"></i>Download Report
                </button>
                <button type="button" class="btn btn-primary" onclick="refreshStudentTable()">
                    <i class="ri-refresh-line me-1"></i>Refresh Student List
                </button>
            </div>
        </div>
    </div>
</div>
