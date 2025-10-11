@extends('layouts.master')
@section('content')
@push('header_scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .category-icon {
        width: 48px;
        height: 48px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    
    .category-icon svg {
        width: 24px;
        height: 24px;
        color: #6b7280;
    }
    
    .search-input {
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px 16px 12px 44px;
        width: 100%;
        font-size: 14px;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .search-wrapper {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
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
    
    .section-card {
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
        margin-bottom: 16px;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }
    
    .empty-state-text {
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .empty-state-suggestion {
        color: #9ca3af;
        margin-bottom: 24px;
    }
    
    /* Validation error styling */
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
    
    /* Readonly field styling */
    .form-control[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    /* Regenerate button styling */
    #regenerateCode {
        border-left: none;
    }
    
    #regenerateCode:hover {
        background-color: #e9ecef;
    }
</style>
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">Asset Categories</h1>
                    <p class="text-muted">Organize assets into categories for better management and reporting.</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="fas fa-plus me-2"></i>Add Category
                </button>
            </div>

            <!-- Search Categories Section -->
            <div class="section-card">
                <h5 class="section-title">Search Categories</h5>
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchCategories" placeholder="Search by name, code, or description...">
                </div>
            </div>

            <!-- Categories List Section -->
            <div class="section-card">
                <h5 class="section-title">Categories (<span id="categoriesCount">0</span>)</h5>
                <div id="categoriesList">
                    @if($categories->count() > 0)
                        <!-- Categories List -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Parent Category</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <strong>{{ $category->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $category->code }}</span>
                                            </td>
                                            <td>
                                                @if($category->parent)
                                                    <span class="text-muted">{{ $category->parent->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($category->description)
                                                    {{ Str::limit($category->description, 50) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($category->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary edit-category" 
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}"
                                                    data-code="{{ $category->code }}"
                                                    data-parent="{{ $category->parent_id }}"
                                                    data-description="{{ $category->description }}"
                                                    data-active="{{ $category->is_active }}"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-category" 
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}"
                                                    data-has-children="{{ $category->children->count() > 0 ? 'true' : 'false' }}"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <div class="category-icon">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h5 class="empty-state-text">No categories found</h5>
                            <p class="empty-state-suggestion">Get started by creating a new asset category.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                <i class="fas fa-plus me-2"></i>Add Category
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Create New Asset Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCategoryForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categoryCode" class="form-label">Category Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="categoryCode" name="code" value="ACAT-{{ str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) }}" required readonly>
                                <button type="button" class="btn btn-outline-secondary" id="regenerateCode" title="Generate New Code">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="code-error"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="parentCategory" class="form-label">Parent Category</label>
                        <select class="form-select" id="parentCategory" name="parent_id">
                            <option value="">No Parent Category</option>
                            <!-- Parent categories will be populated dynamically -->
                        </select>
                        <div class="invalid-feedback" id="parent_id-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3"></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="categoryActive" name="is_active" checked>
                            <label class="form-check-label" for="categoryActive">
                                Active Category
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Asset Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editCategoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCategoryName" name="name" required>
                            <div class="invalid-feedback" id="edit-name-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editCategoryCode" class="form-label">Category Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCategoryCode" name="code" required readonly>
                            <div class="invalid-feedback" id="edit-code-error"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editParentCategory" class="form-label">Parent Category</label>
                        <select class="form-select" id="editParentCategory" name="parent_id">
                            <option value="">No Parent Category</option>
                            <!-- Parent categories will be populated dynamically -->
                        </select>
                        <div class="invalid-feedback" id="edit-parent_id-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editCategoryDescription" name="description" rows="3"></textarea>
                        <div class="invalid-feedback" id="edit-description-error"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editCategoryActive" name="is_active">
                            <label class="form-check-label" for="editCategoryActive">
                                Active Category
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('footer_scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchCategories').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        let matchFound = false;
        
        // Search through all table rows
        $('tbody tr').each(function() {
            const name = $(this).find('td:nth-child(1)').text().toLowerCase();
            const code = $(this).find('td:nth-child(2)').text().toLowerCase();
            const parent = $(this).find('td:nth-child(3)').text().toLowerCase();
            const description = $(this).find('td:nth-child(4)').text().toLowerCase();
            
            // Check if any field contains the search term
            if (name.includes(searchTerm) || 
                code.includes(searchTerm) || 
                parent.includes(searchTerm) || 
                description.includes(searchTerm)) {
                $(this).show();
                matchFound = true;
            } else {
                $(this).hide();
            }
        });
        
        // Show/hide empty state based on search results
        if (searchTerm && !matchFound) {
            // No matches found for search
            $('.table-responsive').hide();
            
            // Check if we already have a search empty state
            if ($('#searchEmptyState').length === 0) {
                // Create search empty state
                const emptyState = `
                <div id="searchEmptyState" class="empty-state">
                    <div class="category-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h5 class="empty-state-text">No matching categories found</h5>
                    <p class="empty-state-suggestion">Try adjusting your search criteria.</p>
                    <button type="button" class="btn btn-secondary" id="clearSearch">
                        <i class="fas fa-times me-2"></i>Clear Search
                    </button>
                </div>`;
                
                $('#categoriesList').append(emptyState);
            } else {
                // Show existing search empty state
                $('#searchEmptyState').show();
            }
        } else {
            // Show table if we have matches or no search term
            $('.table-responsive').show();
            $('#searchEmptyState').hide();
        }
        
        // Update the count of visible rows
        updateVisibleRowCount();
    });
    
    // Clear search button
    $(document).on('click', '#clearSearch', function() {
        $('#searchCategories').val('').trigger('input');
    });
    
    // Function to update the count of visible rows
    function updateVisibleRowCount() {
        const visibleRows = $('tbody tr:visible').length;
        $('#categoriesCount').text(visibleRows);
    }

    // Form submission
    $('#createCategoryForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: $('#categoryName').val(),
            code: $('#categoryCode').val(),
            parent_id: $('#parentCategory').val() || null,
            description: $('#categoryDescription').val(),
            is_active: $('#categoryActive').is(':checked')
        };

        console.log(formData);
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creating...');
        submitBtn.prop('disabled', true);

        // Clear previous validation errors
        clearValidationErrors();
        
        // Make API call to create category
        $.ajax({
            url: '{{ route("fixed-assets.categories.store") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Category created successfully!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Close modal
                    $('#createCategoryModal').modal('hide');
                    
                    // Reset form
                    $('#createCategoryForm')[0].reset();
                    
                    // Clear validation errors
                    clearValidationErrors();
                    
                    // Refresh the page to show the new category
                    window.location.reload();
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors(errors);
                } else {
                    // Other errors
                    let errorMessage = 'An error occurred while creating the category.';
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
                // Reset button state
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Load categories on page load
    updateCategoriesCount();
    loadParentCategories();
    
    // Regenerate code button
    $('#regenerateCode').on('click', function() {
        // Generate a new random code using AJAX to get a PHP-generated code
        $.ajax({
            url: '{{ route("fixed-assets.categories.generate-code") }}',
            method: 'GET',
            success: function(response) {
                $('#categoryCode').val(response.code);
            },
            error: function() {
                // Fallback to JavaScript if AJAX fails
                const randomNum = Math.floor(Math.random() * 99999) + 1;
                const code = 'ACAT-' + randomNum.toString().padStart(5, '0');
                $('#categoryCode').val(code);
            }
        });
    });
    
    // Generate new code when modal opens
    $('#createCategoryModal').on('show.bs.modal', function() {
        // Generate a new random code using AJAX to get a PHP-generated code
        $.ajax({
            url: '{{ route("fixed-assets.categories.generate-code") }}',
            method: 'GET',
            success: function(response) {
                $('#categoryCode').val(response.code);
            }
        });
    });
    
    // Reset form when modal is hidden
    $('#createCategoryModal').on('hidden.bs.modal', function() {
        $('#createCategoryForm')[0].reset();
        clearValidationErrors();
    });
    
    // Edit button click handler
    $(document).on('click', '.edit-category', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const code = $(this).data('code');
        const parent = $(this).data('parent');
        const description = $(this).data('description');
        const active = $(this).data('active');
        
        // Populate the edit form
        $('#editCategoryId').val(id);
        $('#editCategoryName').val(name);
        $('#editCategoryCode').val(code);
        $('#editCategoryDescription').val(description);
        $('#editCategoryActive').prop('checked', active == 1);
        
        // Load parent categories and set the selected option
        loadEditParentCategories(id, parent);
        
        // Show the modal
        $('#editCategoryModal').modal('show');
    });
    
    // Edit form submission
    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#editCategoryId').val();
        const formData = {
            name: $('#editCategoryName').val(),
            code: $('#editCategoryCode').val(),
            parent_id: $('#editParentCategory').val() || null,
            description: $('#editCategoryDescription').val(),
            is_active: $('#editCategoryActive').is(':checked')
        };
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        submitBtn.prop('disabled', true);
        
        // Clear previous validation errors
        clearEditValidationErrors();
        
        // Make API call to update category
        $.ajax({
            url: '{{ route("fixed-assets.categories.update", ["id" => "_id_"]) }}'.replace('_id_', id),
            method: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Category updated successfully!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Close modal
                    $('#editCategoryModal').modal('hide');
                    
                    // Refresh the page to show the updated category
                    window.location.reload();
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    displayEditValidationErrors(errors);
                } else {
                    // Other errors
                    let errorMessage = 'An error occurred while updating the category.';
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
                // Reset button state
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Delete button click handler
    $(document).on('click', '.delete-category', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const hasChildren = $(this).data('has-children') === 'true';
        
        let warningMessage = `Are you sure you want to delete the category "${name}"?`;
        
        // Add warning about child categories if applicable
        if (hasChildren) {
            warningMessage += '<br><br><strong class="text-danger">Warning:</strong> This category has child categories. If you delete it, all child categories will be updated to have no parent.';
        }
        
        // Show confirmation dialog
        Swal.fire({
            icon: 'warning',
            title: 'Delete Category?',
            html: warningMessage,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Make API call to delete category
                $.ajax({
                    url: '{{ route("fixed-assets.categories.destroy", ["id" => "_id_"]) }}'.replace('_id_', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Category has been deleted successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Refresh the page
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Show error message
                        let errorMessage = 'An error occurred while deleting the category.';
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
                });
            }
        });
    });
});

function loadCategories() {
    // Categories are already loaded from the controller
    console.log('Categories loaded from controller');
}

function updateCategoriesCount() {
    const count = {{ $categories->count() }};
    $('#categoriesCount').text(count);
}

function loadParentCategories() {
    // Load parent categories for the dropdown
    $.ajax({
        url: '{{ route("fixed-assets.categories.list") }}',
        method: 'GET',
        success: function(response) {
            const parentSelect = $('#parentCategory');
            parentSelect.find('option:not(:first)').remove();
            
            response.forEach(function(category) {
                parentSelect.append(`<option value="${category.id}">${category.name} (${category.code})</option>`);
            });
        },
        error: function(xhr) {
            console.error('Error loading parent categories:', xhr);
        }
    });
}

// Removed name-based code generation as requested

// Helper function to clear validation errors
function clearValidationErrors() {
    $('.invalid-feedback').hide();
    $('.form-control, .form-select').removeClass('is-invalid');
}

// Helper function to display validation errors
function displayValidationErrors(errors) {
    // Clear previous errors
    clearValidationErrors();
    
    // Display new errors
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

// Helper function to clear edit form validation errors
function clearEditValidationErrors() {
    $('#editCategoryModal .invalid-feedback').hide();
    $('#editCategoryModal .form-control, #editCategoryModal .form-select').removeClass('is-invalid');
}

// Helper function to display edit form validation errors
function displayEditValidationErrors(errors) {
    // Clear previous errors
    clearEditValidationErrors();
    
    // Display new errors
    Object.keys(errors).forEach(function(field) {
        const errorElement = $(`#edit-${field}-error`);
        const inputElement = $(`#editCategoryModal [name="${field}"]`);
        
        if (errorElement.length && inputElement.length) {
            errorElement.text(errors[field][0]);
            errorElement.show();
            inputElement.addClass('is-invalid');
        }
    });
}

// Function to load parent categories for the edit form
function loadEditParentCategories(categoryId, selectedParentId) {
    $.ajax({
        url: '{{ route("fixed-assets.categories.list") }}',
        method: 'GET',
        success: function(response) {
            const parentSelect = $('#editParentCategory');
            parentSelect.find('option:not(:first)').remove();
            
            response.forEach(function(category) {
                // Skip the current category to prevent circular reference
                if (category.id != categoryId) {
                    const option = `<option value="${category.id}">${category.name} (${category.code})</option>`;
                    parentSelect.append(option);
                }
            });
            
            // Set the selected parent if any
            if (selectedParentId) {
                parentSelect.val(selectedParentId);
            }
        },
        error: function(xhr) {
            console.error('Error loading parent categories:', xhr);
        }
    });
}
</script>
@endpush
@endsection