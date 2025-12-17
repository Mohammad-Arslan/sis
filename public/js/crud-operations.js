/**
 * Global CRUD Operations
 * Reusable functions for DataTables, AJAX CRUD, and modal handling
 * Used throughout the application for consistent CRUD operations
 */

// Global toast notification function
window.showToast = function(message, type = 'success') {
    // Use SweetAlert2 if available, otherwise use Bootstrap alert
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type === 'success' ? 'success' : 'error',
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    } else {
        const toast = document.createElement('div');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        toast.className = `alert ${alertClass} alert-dismissible fade show`;
        toast.innerHTML = `
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.getElementById('toast-container');
        if (container) {
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    }
};

// Global function to handle AJAX errors
window.handleAjaxError = function(xhr) {
    let message = 'An error occurred. Please try again.';
    
    if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
        const errors = Object.values(xhr.responseJSON.errors).flat();
        message = errors.join(', ');
    }
    
    showToast(message, 'error');
};

/**
 * Initialize DataTable for settings
 * @param {string} tableId - ID of the table element
 * @param {string} ajaxUrl - URL for DataTable AJAX
 * @param {Array} columns - DataTable columns configuration
 * @param {Object} options - Additional DataTable options
 * @returns {Object} DataTable instance
 */
window.initSettingsDataTable = function(tableId, ajaxUrl, columns, options = {}) {
    const defaultOptions = {
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: ajaxUrl,
            type: 'GET'
        },
        columns: columns,
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='loading loading-spinner loading-lg'></span>"
        }
    };

    return $(tableId).DataTable({ ...defaultOptions, ...options });
};

/**
 * Open modal for add/edit
 * @param {string} modalId - ID of the modal element
 * @param {string} formId - ID of the form element
 * @param {string} titleId - ID of the title element
 * @param {string} addTitle - Title for add mode
 * @param {string} editTitle - Title for edit mode
 * @param {Function} fetchData - Function to fetch data for edit mode
 * @param {Function} clearErrors - Function to clear form errors
 * @param {string|null} editId - ID of record to edit (null for add mode)
 */
window.openSettingsModal = function(modalId, formId, titleId, addTitle, editTitle, fetchData, clearErrors, editId = null) {
    const modalElement = document.getElementById(modalId);
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById(formId);
    const title = document.getElementById(titleId);
    
    // Reset form
    form.reset();
    clearErrors();
    
    if (editId) {
        title.textContent = editTitle;
        fetchData(editId)
            .then(data => {
                // Populate form fields
                Object.keys(data).forEach(key => {
                    const input = document.getElementById(key.replace(/_/g, '-'));
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = data[key];
                        } else {
                            input.value = data[key] || '';
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading data:', error);
                showToast('Failed to load data. Please try again.', 'error');
            });
    } else {
        title.textContent = addTitle;
    }
    
    modal.show();
};

/**
 * Close modal
 * @param {string} modalId - ID of the modal element
 * @param {Function} clearErrors - Function to clear form errors
 */
window.closeSettingsModal = function(modalId, clearErrors) {
    const modalElement = document.getElementById(modalId);
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    if (clearErrors) {
        clearErrors();
    }
};

/**
 * Clear form errors
 * @param {Array} fieldIds - Array of field IDs to clear errors for
 * @param {string} prefix - Prefix for field IDs (optional)
 */
window.clearFormErrors = function(fieldIds, prefix = '') {
    fieldIds.forEach(id => {
        const fullId = prefix ? `${prefix}-${id}` : id;
        const errorElement = document.getElementById(`${fullId}-error`);
        const inputElement = document.getElementById(fullId);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
};

/**
 * Display validation errors
 * @param {Object} errors - Validation errors object
 * @param {string} prefix - Prefix for field IDs
 */
window.displayValidationErrors = function(errors, prefix = '') {
    Object.keys(errors).forEach(key => {
        const fieldId = prefix ? `${prefix}-${key.replace(/_/g, '-')}` : key.replace(/_/g, '-');
        const errorId = `${fieldId}-error`;
        const errorElement = document.getElementById(errorId);
        const inputElement = document.getElementById(fieldId);
        
        if (errorElement) {
            errorElement.textContent = errors[key][0];
        }
        if (inputElement) {
            inputElement.classList.add('is-invalid');
        }
    });
};

/**
 * Submit form via AJAX
 * @param {string} formId - ID of the form element
 * @param {string} storeUrl - URL for creating new record
 * @param {string} updateUrl - URL template for updating (with :id placeholder)
 * @param {Function} getFormData - Function to get form data
 * @param {Function} clearErrors - Function to clear form errors
 * @param {string|null} editId - ID of record being edited (null for create)
 * @param {Object} dataTable - DataTable instance to reload after success
 * @param {string} modalId - ID of modal to close after success
 */
window.submitSettingsForm = function(formId, storeUrl, updateUrl, getFormData, clearErrors, editId, dataTable, modalId) {
    const form = document.getElementById(formId);
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        
        const formData = getFormData();
        const url = editId ? updateUrl.replace(':id', editId) : storeUrl;
        const method = editId ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast(response.message, 'success');
                const modalElement = document.getElementById(modalId);
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
                if (dataTable) dataTable.ajax.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    displayValidationErrors(xhr.responseJSON.errors, formId.replace('-form', ''));
                } else {
                    handleAjaxError(xhr);
                }
            }
        });
    });
};

/**
 * Delete record via AJAX with SweetAlert2 confirmation
 * @param {number} id - ID of record to delete
 * @param {string} deleteUrl - URL template for deletion (with :id placeholder)
 * @param {string} confirmMessage - Confirmation message
 * @param {Object} dataTable - DataTable instance to reload after success
 * @param {string} title - Optional title for the confirmation dialog (default: "Are you sure?")
 */
window.deleteSettingsRecord = function(id, deleteUrl, confirmMessage, dataTable, title = 'Are you sure?') {
    // Use SweetAlert2 if available, otherwise fall back to confirm
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>' + title + '</h4>' +
                '<p class="mx-4 mb-0 text-muted">' + confirmMessage + '</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {
            if (result.isConfirmed) {
                performDelete(id, deleteUrl, dataTable);
            }
        });
    } else {
        // Fallback to native confirm if Swal is not available
        if (!confirm(title + '\n\n' + confirmMessage)) {
            return;
        }
        performDelete(id, deleteUrl, dataTable);
    }
};

/**
 * Perform the actual delete operation
 * @param {number} id - ID of record to delete
 * @param {string} deleteUrl - URL template for deletion (with :id placeholder)
 * @param {Object} dataTable - DataTable instance to reload after success
 */
function performDelete(id, deleteUrl, dataTable) {
    $.ajax({
        url: deleteUrl.replace(':id', id),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Show success message with SweetAlert2 if available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    html: '<div class="mt-3">' +
                        '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                        '<div class="mt-4 pt-2 fs-15">' +
                        '<h4>Success !</h4>' +
                        '<p class="text-muted mx-4 mb-0">' + (response.message || 'Record has been successfully deleted.') + '</p>' +
                        '</div></div>',
                    showCancelButton: !0,
                    showConfirmButton: !1,
                    cancelButtonClass: "btn btn-primary w-xs mb-1",
                    cancelButtonText: "Okay",
                    buttonsStyling: !1,
                    showCloseButton: !0
                });
            } else {
                showToast(response.message || 'Record has been successfully deleted.', 'success');
            }
            if (dataTable) dataTable.ajax.reload();
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
}

/**
 * Restore soft-deleted record via AJAX with SweetAlert2 confirmation
 * This handler works with elements having class 'restore-record'
 * Required data attributes:
 *   - data-id: ID of record to restore
 *   - data-url: URL for restore action
 *   - data-table: DataTable ID to reload after success (optional)
 */
$(document).on('click', '.restore-record', function(e) {
    e.preventDefault();
    
    const button = $(this);
    const id = button.data('id');
    const url = button.data('url');
    const tableId = button.data('table');
    
    // Use SweetAlert2 if available, otherwise fall back to confirm
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#0ab39c" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>Restore Record?</h4>' +
                '<p class="mx-4 mb-0 text-muted">Do you want to restore this record? It will be available again in the system.</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Restore It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                performRestore(id, url, tableId, button);
            }
        });
    } else {
        // Fallback to native confirm if Swal is not available
        if (!confirm('Do you want to restore this record? It will be available again in the system.')) {
            return;
        }
        performRestore(id, url, tableId, button);
    }
});

/**
 * Perform the actual restore operation
 * @param {number} id - ID of record to restore
 * @param {string} url - URL for restore action
 * @param {string} tableId - DataTable ID to reload after success (optional)
 * @param {jQuery} button - Button element to disable during request
 */
function performRestore(id, url, tableId, button) {
    // Disable button during request
    button.prop('disabled', true);
    
    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Reload DataTable if specified
                if (tableId && $('#' + tableId).length) {
                    $('#' + tableId).DataTable().ajax.reload(null, false);
                }
                
                // Show success message with SweetAlert2 if available
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: '<div class="mt-3">' +
                            '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                            '<div class="mt-4 pt-2 fs-15">' +
                            '<h4>Restored!</h4>' +
                            '<p class="text-muted mx-4 mb-0">' + (response.message || 'Record has been successfully restored.') + '</p>' +
                            '</div></div>',
                        showCancelButton: !0,
                        showConfirmButton: !1,
                        cancelButtonClass: "btn btn-primary w-xs mb-1",
                        cancelButtonText: "Okay",
                        buttonsStyling: !1,
                        showCloseButton: !0
                    });
                } else {
                    showToast(response.message || 'Record has been successfully restored.', 'success');
                }
            } else {
                showToast(response.message || 'Failed to restore record.', 'error');
                button.prop('disabled', false);
            }
        },
        error: function(xhr) {
            handleAjaxError(xhr);
            button.prop('disabled', false);
        }
    });
}

/**
 * Force delete (permanently delete) soft-deleted record via AJAX with SweetAlert2 confirmation
 * This handler works with elements having class 'force-delete-record'
 * Required data attributes:
 *   - data-id: ID of record to delete
 *   - data-url: URL for force delete action
 *   - data-table: DataTable ID to reload after success (optional)
 *   - data-model-type: Model type for display purposes (optional)
 */
$(document).on('click', '.force-delete-record', function(e) {
    e.preventDefault();
    
    const button = $(this);
    const id = button.data('id');
    const url = button.data('url');
    const tableId = button.data('table');
    const modelType = button.data('model-type') || 'record';
    
    // Use SweetAlert2 if available, otherwise fall back to confirm
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>Permanently Delete?</h4>' +
                '<p class="mx-4 mb-0 text-muted">This action cannot be undone! The record will be permanently deleted from the system.</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-danger w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete Permanently!',
            cancelButtonClass: 'btn btn-secondary w-xs mb-1',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                performForceDelete(id, url, tableId, button);
            }
        });
    } else {
        // Fallback to native confirm if Swal is not available
        if (!confirm('This action cannot be undone! Do you want to permanently delete this record?')) {
            return;
        }
        performForceDelete(id, url, tableId, button);
    }
});

/**
 * Perform the actual force delete operation
 * @param {number} id - ID of record to delete
 * @param {string} url - URL for force delete action
 * @param {string} tableId - DataTable ID to reload after success (optional)
 * @param {jQuery} button - Button element to disable during request
 */
function performForceDelete(id, url, tableId, button) {
    // Disable button during request
    button.prop('disabled', true);
    
    $.ajax({
        url: url,
        type: 'DELETE',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Reload DataTable if specified
                if (tableId && $('#' + tableId).length) {
                    $('#' + tableId).DataTable().ajax.reload(null, false);
                }
                
                // Show success message with SweetAlert2 if available
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: '<div class="mt-3">' +
                            '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                            '<div class="mt-4 pt-2 fs-15">' +
                            '<h4>Deleted!</h4>' +
                            '<p class="text-muted mx-4 mb-0">' + (response.message || 'Record has been permanently deleted.') + '</p>' +
                            '</div></div>',
                        showCancelButton: !0,
                        showConfirmButton: !1,
                        cancelButtonClass: "btn btn-primary w-xs mb-1",
                        cancelButtonText: "Okay",
                        buttonsStyling: !1,
                        showCloseButton: !0
                    });
                } else {
                    showToast(response.message || 'Record has been permanently deleted.', 'success');
                }
            } else {
                showToast(response.message || 'Failed to delete record.', 'error');
                button.prop('disabled', false);
            }
        },
        error: function(xhr) {
            handleAjaxError(xhr);
            button.prop('disabled', false);
        }
    });
}

// Adjust DataTables when tabs are switched
$(document).ready(function() {
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});

