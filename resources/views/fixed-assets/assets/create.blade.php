@extends('layouts.master')
@section('content')
@push('header_scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .form-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    
    .form-control[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    #regenerateTag {
        border-left: none;
    }
    
    #regenerateTag:hover {
        background-color: #e9ecef;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }

    /* Image Upload Styles */
    .image-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f9fafb;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-upload-area:hover {
        border-color: #3b82f6;
        background: #f0f9ff;
    }

    .image-upload-area.dragover {
        border-color: #3b82f6;
        background: #f0f9ff;
    }

    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .image-preview-item {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e5e7eb;
    }

    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview-item .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-preview-item .remove-image:hover {
        background: rgba(220, 53, 69, 1);
    }

    .upload-icon {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 10px;
    }

    .upload-text {
        color: #6b7280;
        font-size: 14px;
    }
</style>
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">Add New Asset</h1>
                    <p class="text-muted">Register a new asset in the system with complete details.</p>
                </div>
                <div>
                    <a href="{{ route('fixed-assets.assets.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Assets
                    </a>
                </div>
            </div>

            <form id="createAssetForm" enctype="multipart/form-data">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h5 class="section-title">Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="assetName" class="form-label">Asset Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="assetName" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="assetTag" class="form-label">Asset Tag <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="assetTag" name="asset_tag" required readonly>
                                <button type="button" class="btn btn-outline-secondary" id="regenerateTag" title="Generate New Tag">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="asset_tag-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->code }})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="category_id-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <select class="form-select" id="supplier" name="supplier_id">
                                <option value="">Select supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->code }})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="supplier_id-error"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                </div>

                <!-- Technical Details Section -->
                <div class="form-section">
                    <h5 class="section-title">Technical Details</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="serialNumber" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serialNumber" name="serial_number">
                            <div class="invalid-feedback" id="serial_number-error"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model">
                            <div class="invalid-feedback" id="model-error"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand">
                            <div class="invalid-feedback" id="brand-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Information Section -->
                <div class="form-section">
                    <h5 class="section-title">Purchase Information</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="purchaseDate" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="purchaseDate" name="purchase_date">
                            <div class="invalid-feedback" id="purchase_date-error"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="purchasePrice" class="form-label">Purchase Price</label>
                            <input type="number" class="form-control" id="purchasePrice" name="purchase_price" step="0.01" min="0">
                            <div class="invalid-feedback" id="purchase_price-error"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="warrantyEndDate" class="form-label">Warranty End Date</label>
                            <input type="date" class="form-control" id="warrantyEndDate" name="warranty_end_date">
                            <div class="invalid-feedback" id="warranty_end_date-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Status & Assignment Section -->
                <div class="form-section">
                    <h5 class="section-title">Status & Assignment</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="condition" class="form-label">Condition <span class="text-danger">*</span></label>
                            <select class="form-select" id="condition" name="condition" required>
                                <option value="">Select condition</option>
                                @foreach($conditions as $condition)
                                    <option value="{{ $condition['value'] }}">{{ $condition['label'] }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="condition-error"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="asset_status" name="status" required>
                                <option value="">Select status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="status-error"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="currentBranch" class="form-label">Current Branch <span class="text-danger">*</span></label>
                            <select class="form-select" id="currentBranch" name="current_branch_id" required>
                                <option value="">Select branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->br_name }} ({{ $branch->branch_code }})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="current_branch_id-error"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="currentDepartment" class="form-label">Current Department</label>
                            <select class="form-select" id="currentDepartment" name="current_department_id">
                                <option value="">Select department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="current_department_id-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="assignedTo" class="form-label">Assigned To</label>
                            <select class="form-select" id="assignedTo" name="assigned_to_user_id">
                                <option value="">Select user (Choose department/branch first)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            data-department="{{ $user->employee->department->department_name ?? '' }}"
                                            data-branch="{{ $user->employee->branch->br_name ?? '' }}">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                        @if($user->employee && $user->employee->department)
                                            - {{ $user->employee->department->department_name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="assigned_to_user_id-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="imageUpload" class="form-label">Asset Images</label>
                            <div class="image-upload-area" id="imageUploadArea">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">
                                    <strong>Click to upload</strong> or drag and drop<br>
                                    <small>PNG, JPG, JPEG up to 5MB each (Max 5 images)</small>
                                </div>
                                <input type="file" id="imageUpload" name="images[]" multiple accept="image/*" style="display: none;">
                            </div>
                            <div class="image-preview-container" id="imagePreviewContainer"></div>
                            <div class="invalid-feedback" id="images-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-section">
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-secondary me-3">Reset</button>
                        <button type="submit" class="btn btn-primary">Create Asset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('footer_scripts')
<script>
$(document).ready(function() {
    // Generate asset tag on page load
    generateAssetTag();
    
    // Regenerate tag button
    $('#regenerateTag').on('click', function() {
        generateAssetTag();
    });

    // Image upload functionality
    setupImageUpload();
    
    // Dynamic user filtering based on department and branch
    setupDynamicUserFiltering();
    
    // Form submission
    $('#createAssetForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        
        // Add all form fields
        formData.append('name', $('#assetName').val());
        formData.append('asset_tag', $('#assetTag').val());
        formData.append('description', $('#description').val());
        formData.append('category_id', $('#category').val());
        formData.append('supplier_id', $('#supplier').val());
        formData.append('serial_number', $('#serialNumber').val());
        formData.append('model', $('#model').val());
        formData.append('brand', $('#brand').val());
        formData.append('purchase_date', $('#purchaseDate').val());
        formData.append('purchase_price', $('#purchasePrice').val());
        formData.append('warranty_end_date', $('#warrantyEndDate').val());
        formData.append('condition', $('#condition').val());
        formData.append('status', $('#asset_status').val());
        formData.append('current_branch_id', $('#currentBranch').val());
        formData.append('current_department_id', $('#currentDepartment').val());
        formData.append('assigned_to_user_id', $('#assignedTo').val());

        // Add images
        if (window.selectedFiles && window.selectedFiles.length > 0) {
            window.selectedFiles.forEach(function(file) {
                formData.append('images[]', file);
            });
        }

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creating...');
        submitBtn.prop('disabled', true);

        // Clear previous validation errors
        clearValidationErrors();
        
        // Make API call to create asset
        $.ajax({
            url: '{{ route("fixed-assets.assets.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Asset created successfully!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    window.location.href = '{{ route("fixed-assets.assets.index") }}';
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors(errors);
                } else {
                    let errorMessage = 'An error occurred while creating the asset.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
});

function setupImageUpload() {
    const uploadArea = $('#imageUploadArea');
    const fileInput = $('#imageUpload');
    const previewContainer = $('#imagePreviewContainer');
    const maxFiles = 5;
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    
    // Store selected files globally so form submission can access it
    window.selectedFiles = [];

    // Click to upload
    uploadArea.on('click', function(e) {
        // Prevent triggering when clicking on child elements
        if (e.target === this || $(e.target).hasClass('upload-icon') || $(e.target).hasClass('upload-text')) {
            fileInput.click();
        }
    });

    // Drag and drop
    uploadArea.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    uploadArea.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    uploadArea.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    // File input change
    fileInput.on('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (window.selectedFiles.length + files.length > maxFiles) {
            Swal.fire({
                icon: 'error',
                title: 'Too many files',
                text: `You can only upload up to ${maxFiles} images.`,
                confirmButtonText: 'OK'
            });
            return;
        }

        Array.from(files).forEach(file => {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file type',
                    text: 'Please select only image files.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validate file size
            if (file.size > maxFileSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File too large',
                    text: 'Each image must be less than 5MB.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Add file to selected files array
            window.selectedFiles.push(file);

            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileIndex = window.selectedFiles.length - 1;
                const previewItem = $(`
                    <div class="image-preview-item" data-file-index="${fileIndex}">
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-image" title="Remove image" data-file-index="${fileIndex}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);

                previewContainer.append(previewItem);
            };
            reader.readAsDataURL(file);
        });

        // Clear the file input
        fileInput.val('');
    }

    // Handle remove image clicks using event delegation
    previewContainer.on('click', '.remove-image', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const fileIndex = parseInt($(this).data('file-index'));
        const previewItem = $(this).closest('.image-preview-item');
        
        // Remove file from array
        window.selectedFiles.splice(fileIndex, 1);
        
        // Remove preview
        previewItem.remove();
        
        // Update file indices for remaining previews
        previewContainer.find('.image-preview-item').each(function(index) {
            $(this).attr('data-file-index', index);
            $(this).find('.remove-image').attr('data-file-index', index);
        });
    });

    // Prevent click on preview items from triggering file input
    previewContainer.on('click', '.image-preview-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
}

function setupDynamicUserFiltering() {
    const departmentSelect = $('#currentDepartment');
    const branchSelect = $('#currentBranch');
    const userSelect = $('#assignedTo');

    function updateUsers() {
        const departmentId = departmentSelect.val();
        const branchId = branchSelect.val();

        // Clear current users
        userSelect.find('option:not(:first)').remove();

        if (departmentId || branchId) {
            $.ajax({
                url: '{{ route("fixed-assets.assets.users-by-department-branch") }}',
                method: 'GET',
                data: {
                    department_id: departmentId,
                    branch_id: branchId
                },
                success: function(response) {
                    if (response.length === 0) {
                        userSelect.append('<option value="" disabled>No employees found for selected department/branch</option>');
                    } else {
                        response.forEach(function(user) {
                            const displayName = `${user.first_name} ${user.last_name}`;
                            const departmentName = user.employee && user.employee.department ? 
                                ` - ${user.employee.department.department_name}` : '';
                            
                            userSelect.append(`<option value="${user.id}">${displayName}${departmentName}</option>`);
                        });
                    }
                },
                error: function() {
                    console.error('Failed to load users');
                    userSelect.append('<option value="" disabled>Error loading employees</option>');
                }
            });
        } else {
            // Reset to default message when no department/branch is selected
            userSelect.html('<option value="">Select user (Choose department/branch first)</option>');
        }
    }

    // Update users when department or branch changes
    departmentSelect.on('change', updateUsers);
    branchSelect.on('change', updateUsers);
}

function generateAssetTag() {
    $.ajax({
        url: '{{ route("fixed-assets.assets.generate-tag") }}',
        method: 'GET',
        success: function(response) {
            $('#assetTag').val(response.asset_tag);
        },
        error: function() {
            const randomNum = Math.floor(Math.random() * 999999) + 1;
            const tag = 'AST-' + randomNum.toString().padStart(6, '0');
            $('#assetTag').val(tag);
        }
    });
}

function clearValidationErrors() {
    $('.invalid-feedback').hide();
    $('.form-control, .form-select').removeClass('is-invalid');
}

function displayValidationErrors(errors) {
    clearValidationErrors();
    
    Object.keys(errors).forEach(function(field) {
        const errorElement = $(`#${field}-error`);
        const inputElement = $(`[name="${field}"]`);
        
        if (errorElement.length && inputElement.length) {
            errorElement.text(errors[field][0]);
            errorElement.show();
            inputElement.addClass('is-invalid');
        }
    });
}
</script>
@endpush
@endsection
