@extends('layouts.master')
@section('content')
@push('header_scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .supplier-icon {
        width: 48px;
        height: 48px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    
    .supplier-icon svg {
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
    
    /* Form styling */
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

    /* Category badges styling */
    .category-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #e5e7eb;
        border-radius: 4px;
        font-size: 12px;
        margin: 2px;
    }
</style>
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">Suppliers</h1>
                    <p class="text-muted">Manage supplier information and compliance details.</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                    <i class="fas fa-plus me-2"></i>Add Supplier
                </button>
            </div>

            <!-- Search Suppliers Section -->
            <div class="section-card">
                <h5 class="section-title">Search Suppliers</h5>
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchSuppliers" placeholder="Search by name, code, contact person, or email...">
                </div>
            </div>

            <!-- Suppliers List Section -->
            <div class="section-card">
                <h5 class="section-title">Suppliers (<span id="suppliersCount">0</span>)</h5>
                <div id="suppliersList">
                    @if($suppliers->count() > 0)
                        <!-- Suppliers List -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Contact Person</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Categories</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $supplier)
                                        <tr>
                                            <td>
                                                <strong>{{ $supplier->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $supplier->code }}</span>
                                            </td>
                                            <td>
                                                {{ $supplier->contact_person ?: '-' }}
                                            </td>
                                            <td>
                                                {{ $supplier->email ?: '-' }}
                                            </td>
                                            <td>
                                                {{ $supplier->phone ?: '-' }}
                                            </td>
                                            <td>
                                                @if($supplier->categories()->count() > 0)
                                                    @foreach($supplier->categories() as $category)
                                                        <span class="category-badge">{{ $category->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($supplier->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary edit-supplier" 
                                                    data-id="{{ $supplier->id }}"
                                                    data-name="{{ $supplier->name }}"
                                                    data-code="{{ $supplier->code }}"
                                                    data-contact="{{ $supplier->contact_person }}"
                                                    data-email="{{ $supplier->email }}"
                                                    data-phone="{{ $supplier->phone }}"
                                                    data-address="{{ $supplier->address }}"
                                                    data-tax="{{ $supplier->tax_id }}"
                                                    data-bank="{{ $supplier->bank_details }}"
                                                    data-compliance="{{ $supplier->compliance_certificates }}"
                                                    data-categories="{{ json_encode($supplier->category_ids) }}"
                                                    data-active="{{ $supplier->is_active }}"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-supplier" 
                                                    data-id="{{ $supplier->id }}"
                                                    data-name="{{ $supplier->name }}"
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
                            <div class="supplier-icon">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                            <h5 class="empty-state-text">No suppliers found</h5>
                            <p class="empty-state-suggestion">Get started by adding a new supplier.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                                <i class="fas fa-plus me-2"></i>Add Supplier
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSupplierModalLabel">Create New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSupplierForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="supplierName" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplierName" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="supplierCode" class="form-label">Supplier Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="supplierCode" name="code" required readonly>
                                <button type="button" class="btn btn-outline-secondary" id="regenerateCode" title="Generate New Code">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="code-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contactPerson" name="contact_person" required>
                            <div class="invalid-feedback" id="contact_person-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                            <div class="invalid-feedback" id="phone-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="taxId" class="form-label">Tax ID</label>
                            <input type="text" class="form-control" id="taxId" name="tax_id">
                            <div class="invalid-feedback" id="tax_id-error"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                        <div class="invalid-feedback" id="address-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="bankDetails" class="form-label">Bank Details</label>
                        <textarea class="form-control" id="bankDetails" name="bank_details" rows="2"></textarea>
                        <div class="invalid-feedback" id="bank_details-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="complianceCertificates" class="form-label">Compliance Certificates</label>
                        <textarea class="form-control" id="complianceCertificates" name="compliance_certificates" rows="2"></textarea>
                        <div class="invalid-feedback" id="compliance_certificates-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories</label>
                        <select class="form-select" id="categories" name="category_ids[]" multiple>
                            <!-- Categories will be populated dynamically -->
                        </select>
                        <div class="invalid-feedback" id="category_ids-error"></div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="supplierActive" name="is_active" checked>
                            <label class="form-check-label" for="supplierActive">
                                Active Supplier
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSupplierForm">
                <div class="modal-body">
                    <input type="hidden" id="editSupplierId" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editSupplierName" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editSupplierName" name="name" required>
                            <div class="invalid-feedback" id="edit-name-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editSupplierCode" class="form-label">Supplier Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editSupplierCode" name="code" required readonly>
                            <div class="invalid-feedback" id="edit-code-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editContactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editContactPerson" name="contact_person" required>
                            <div class="invalid-feedback" id="edit-contact_person-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                            <div class="invalid-feedback" id="edit-email-error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editPhone" name="phone" required>
                            <div class="invalid-feedback" id="edit-phone-error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editTaxId" class="form-label">Tax ID</label>
                            <input type="text" class="form-control" id="editTaxId" name="tax_id">
                            <div class="invalid-feedback" id="edit-tax_id-error"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Address</label>
                        <textarea class="form-control" id="editAddress" name="address" rows="2"></textarea>
                        <div class="invalid-feedback" id="edit-address-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editBankDetails" class="form-label">Bank Details</label>
                        <textarea class="form-control" id="editBankDetails" name="bank_details" rows="2"></textarea>
                        <div class="invalid-feedback" id="edit-bank_details-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editComplianceCertificates" class="form-label">Compliance Certificates</label>
                        <textarea class="form-control" id="editComplianceCertificates" name="compliance_certificates" rows="2"></textarea>
                        <div class="invalid-feedback" id="edit-compliance_certificates-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editCategories" class="form-label">Categories</label>
                        <select class="form-select" id="editCategories" name="category_ids[]" multiple>
                            <!-- Categories will be populated dynamically -->
                        </select>
                        <div class="invalid-feedback" id="edit-category_ids-error"></div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editSupplierActive" name="is_active">
                            <label class="form-check-label" for="editSupplierActive">
                                Active Supplier
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('footer_scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for categories
    $('#categories, #editCategories').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Select categories'
    });

    // Search functionality
    $('#searchSuppliers').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        let matchFound = false;
        
        // Search through all table rows
        $('tbody tr').each(function() {
            const name = $(this).find('td:nth-child(1)').text().toLowerCase();
            const code = $(this).find('td:nth-child(2)').text().toLowerCase();
            const contact = $(this).find('td:nth-child(3)').text().toLowerCase();
            const email = $(this).find('td:nth-child(4)').text().toLowerCase();
            const phone = $(this).find('td:nth-child(5)').text().toLowerCase();
            
            if (name.includes(searchTerm) || 
                code.includes(searchTerm) || 
                contact.includes(searchTerm) || 
                email.includes(searchTerm) ||
                phone.includes(searchTerm)) {
                $(this).show();
                matchFound = true;
            } else {
                $(this).hide();
            }
        });
        
        // Show/hide empty state based on search results
        if (searchTerm && !matchFound) {
            $('.table-responsive').hide();
            
            if ($('#searchEmptyState').length === 0) {
                const emptyState = `
                <div id="searchEmptyState" class="empty-state">
                    <div class="supplier-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <h5 class="empty-state-text">No matching suppliers found</h5>
                    <p class="empty-state-suggestion">Try adjusting your search criteria.</p>
                    <button type="button" class="btn btn-secondary" id="clearSearch">
                        <i class="fas fa-times me-2"></i>Clear Search
                    </button>
                </div>`;
                
                $('#suppliersList').append(emptyState);
            } else {
                $('#searchEmptyState').show();
            }
        } else {
            $('.table-responsive').show();
            $('#searchEmptyState').hide();
        }
        
        updateVisibleRowCount();
    });
    
    // Clear search button
    $(document).on('click', '#clearSearch', function() {
        $('#searchSuppliers').val('').trigger('input');
    });
    
    // Function to update the count of visible rows
    function updateVisibleRowCount() {
        const visibleRows = $('tbody tr:visible').length;
        $('#suppliersCount').text(visibleRows);
    }

    // Form submission
    $('#createSupplierForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: $('#supplierName').val(),
            code: $('#supplierCode').val(),
            contact_person: $('#contactPerson').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val(),
            tax_id: $('#taxId').val(),
            bank_details: $('#bankDetails').val(),
            compliance_certificates: $('#complianceCertificates').val(),
            category_ids: $('#categories').val(),
            is_active: $('#supplierActive').is(':checked')
        };

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creating...');
        submitBtn.prop('disabled', true);

        // Clear previous validation errors
        clearValidationErrors();
        
        // Make API call to create supplier
        $.ajax({
            url: '{{ route("fixed-assets.suppliers.store") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Supplier created successfully!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $('#createSupplierModal').modal('hide');
                    $('#createSupplierForm')[0].reset();
                    clearValidationErrors();
                    window.location.reload();
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors(errors);
                } else {
                    let errorMessage = 'An error occurred while creating the supplier.';
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

    // Load suppliers on page load
    updateSuppliersCount();
    loadCategories();
    
    // Regenerate code button
    $('#regenerateCode').on('click', function() {
        $.ajax({
            url: '{{ route("fixed-assets.suppliers.generate-code") }}',
            method: 'GET',
            success: function(response) {
                $('#supplierCode').val(response.code);
            },
            error: function() {
                const randomNum = Math.floor(Math.random() * 99999) + 1;
                const code = 'SUP-' + randomNum.toString().padStart(5, '0');
                $('#supplierCode').val(code);
            }
        });
    });
    
    // Generate new code when modal opens
    $('#createSupplierModal').on('show.bs.modal', function() {
        $.ajax({
            url: '{{ route("fixed-assets.suppliers.generate-code") }}',
            method: 'GET',
            success: function(response) {
                $('#supplierCode').val(response.code);
            }
        });
    });
    
    // Reset form when modal is hidden
    $('#createSupplierModal').on('hidden.bs.modal', function() {
        $('#createSupplierForm')[0].reset();
        clearValidationErrors();
        $('#categories').val(null).trigger('change');
    });
    
    // Edit button click handler
    $(document).on('click', '.edit-supplier', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const code = $(this).data('code');
        const contact = $(this).data('contact');
        const email = $(this).data('email');
        const phone = $(this).data('phone');
        const address = $(this).data('address');
        const tax = $(this).data('tax');
        const bank = $(this).data('bank');
        const compliance = $(this).data('compliance');
        const categories = $(this).data('categories');
        const active = $(this).data('active');
        
        // Populate the edit form
        $('#editSupplierId').val(id);
        $('#editSupplierName').val(name);
        $('#editSupplierCode').val(code);
        $('#editContactPerson').val(contact);
        $('#editEmail').val(email);
        $('#editPhone').val(phone);
        $('#editAddress').val(address);
        $('#editTaxId').val(tax);
        $('#editBankDetails').val(bank);
        $('#editComplianceCertificates').val(compliance);
        $('#editSupplierActive').prop('checked', active == 1);
        
        // Set categories
        $('#editCategories').val(categories).trigger('change');
        
        // Show the modal
        $('#editSupplierModal').modal('show');
    });
    
    // Edit form submission
    $('#editSupplierForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#editSupplierId').val();
        const formData = {
            name: $('#editSupplierName').val(),
            code: $('#editSupplierCode').val(),
            contact_person: $('#editContactPerson').val(),
            email: $('#editEmail').val(),
            phone: $('#editPhone').val(),
            address: $('#editAddress').val(),
            tax_id: $('#editTaxId').val(),
            bank_details: $('#editBankDetails').val(),
            compliance_certificates: $('#editComplianceCertificates').val(),
            category_ids: $('#editCategories').val(),
            is_active: $('#editSupplierActive').is(':checked')
        };
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        submitBtn.prop('disabled', true);
        
        // Clear previous validation errors
        clearEditValidationErrors();
        
        // Make API call to update supplier
        $.ajax({
            url: '{{ route("fixed-assets.suppliers.update", ["id" => "_id_"]) }}'.replace('_id_', id),
            method: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Supplier updated successfully!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $('#editSupplierModal').modal('hide');
                    window.location.reload();
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    displayEditValidationErrors(errors);
                } else {
                    let errorMessage = 'An error occurred while updating the supplier.';
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
    
    // Delete button click handler
    $(document).on('click', '.delete-supplier', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            icon: 'warning',
            title: 'Delete Supplier?',
            html: `Are you sure you want to delete the supplier "${name}"?`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("fixed-assets.suppliers.destroy", ["id" => "_id_"]) }}'.replace('_id_', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Supplier has been deleted successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while deleting the supplier.';
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
    $.ajax({
        url: '{{ route("fixed-assets.suppliers.categories") }}',
        method: 'GET',
        success: function(response) {
            const categorySelects = $('#categories, #editCategories');
            categorySelects.find('option').remove();
            
            response.forEach(function(category) {
                categorySelects.append(`<option value="${category.id}">${category.name} (${category.code})</option>`);
            });
        },
        error: function(xhr) {
            console.error('Error loading categories:', xhr);
        }
    });
}

function updateSuppliersCount() {
    const count = {{ $suppliers->count() }};
    $('#suppliersCount').text(count);
}

// Helper function to clear validation errors
function clearValidationErrors() {
    $('.invalid-feedback').hide();
    $('.form-control, .form-select').removeClass('is-invalid');
}

// Helper function to display validation errors
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

// Helper function to clear edit form validation errors
function clearEditValidationErrors() {
    $('#editSupplierModal .invalid-feedback').hide();
    $('#editSupplierModal .form-control, #editSupplierModal .form-select').removeClass('is-invalid');
}

// Helper function to display edit form validation errors
function displayEditValidationErrors(errors) {
    clearEditValidationErrors();
    
    Object.keys(errors).forEach(function(field) {
        const errorElement = $(`#edit-${field}-error`);
        const inputElement = $(`#editSupplierModal [name="${field}"]`);
        
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