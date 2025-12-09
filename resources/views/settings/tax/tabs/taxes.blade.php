<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Taxes</h4>
        <div class="flex-shrink-0">
            <button onclick="openTaxModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Tax
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filter-tax-type" class="form-label">Filter by Tax Type</label>
                <select id="filter-tax-type" class="form-select">
                    <option value="">All Tax Types</option>
                    @foreach($taxTypes as $taxType)
                        <option value="{{ $taxType->id }}">{{ $taxType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter-state" class="form-label">Filter by State</label>
                <select id="filter-state" class="form-select">
                    <option value="">All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button onclick="clearFilters()" class="btn btn-secondary btn-sm">
                    <i class="ri-refresh-line me-1"></i> Clear Filters
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="taxes-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tax Type</th>
                        <th>State</th>
                        <th>Tax %</th>
                        <th>Active From</th>
                        <th>Active Till</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTable will populate this -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tax Modal -->
<div class="modal fade" id="tax-modal" tabindex="-1" aria-labelledby="tax-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tax-modal-title">Add Tax</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tax-form">
                <div class="modal-body">
                    <input type="hidden" id="tax-id" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax-tax-type-id" class="form-label">Tax Type <span class="text-danger">*</span></label>
                            <select id="tax-tax-type-id" name="tax_type_id" class="form-select" required>
                                <option value="">Please select a Tax Type</option>
                                @foreach($taxTypes as $taxType)
                                    <option value="{{ $taxType->id }}">{{ $taxType->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="tax-tax-type-id-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tax-state-id" class="form-label">State <span class="text-danger">*</span></label>
                            <select id="tax-state-id" name="state_id" class="form-select" required>
                                <option value="">Please select a State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="tax-state-id-error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax-percentage" class="form-label">Tax Percentage (%) <span class="text-danger">*</span></label>
                            <input type="number" id="tax-percentage" name="tax_percentage" 
                                   class="form-control" step="0.01" min="0" max="100" required>
                            <div class="invalid-feedback" id="tax-percentage-error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax-active-from" class="form-label">Active From <span class="text-danger">*</span></label>
                            <input type="text" id="tax-active-from" name="active_from" 
                                   class="form-control" data-provider="flatpickr" 
                                   data-date-format="Y-m-d" data-altFormat="d-m-Y" required>
                            <div class="invalid-feedback" id="tax-active-from-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tax-active-till" class="form-label">Active Till <span class="text-danger">*</span></label>
                            <input type="text" id="tax-active-till" name="active_till" 
                                   class="form-control" data-provider="flatpickr" 
                                   data-date-format="Y-m-d" data-altFormat="d-m-Y" required>
                            <div class="invalid-feedback" id="tax-active-till-error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let taxesTable;
let editingTaxId = null;

$(document).ready(function() {
    taxesTable = $('#taxes-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('tax-settings.get-taxes') }}",
            type: 'GET',
            data: function(d) {
                d.tax_type_id = $('#filter-tax-type').val();
                d.state_id = $('#filter-state').val();
            }
        },
        columns: [
            { data: 'id', name: 'taxes.id', width: '5%', searchable: true },
            { data: 'tax_type_name', name: 'tax_type_name', searchable: true },
            { data: 'state_name', name: 'state_name', searchable: true },
            { data: 'tax_percentage_formatted', name: 'tax_percentage', width: '10%', searchable: true },
            { data: 'active_from_formatted', name: 'active_from', width: '12%', searchable: true },
            { data: 'active_till_formatted', name: 'active_till', width: '12%', searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='loading loading-spinner loading-lg'></span>"
        }
    });

    // Filter change handlers
    $('#filter-tax-type, #filter-state').on('change', function() {
        taxesTable.ajax.reload();
    });
});

function clearFilters() {
    $('#filter-tax-type').val('');
    $('#filter-state').val('');
    taxesTable.ajax.reload();
}

function openTaxModal(id = null) {
    editingTaxId = id;
    const modalElement = document.getElementById('tax-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('tax-form');
    const title = document.getElementById('tax-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('tax-id').value = '';
    clearTaxErrors();
    
    // Reinitialize flatpickr
    if (typeof flatpickr !== 'undefined') {
        $('#tax-active-from, #tax-active-till').flatpickr({
            dateFormat: 'Y-m-d',
            altFormat: 'd-m-Y'
        });
    }
    
    if (id) {
        title.textContent = 'Edit Tax';
        // Fetch tax data
        fetch(`{{ url('tax-settings/taxes') }}/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('tax-id').value = data.id || '';
                document.getElementById('tax-tax-type-id').value = data.tax_type_id || '';
                document.getElementById('tax-state-id').value = data.state_id || '';
                document.getElementById('tax-percentage').value = data.tax_percentage || '';
                
                // Set dates (should be in Y-m-d format from API)
                if (data.active_from) {
                    document.getElementById('tax-active-from').value = data.active_from;
                }
                if (data.active_till) {
                    document.getElementById('tax-active-till').value = data.active_till;
                }
            })
            .catch(error => {
                console.error('Error loading tax data:', error);
                showToast('Failed to load tax data. Please try again.', 'error');
            });
    } else {
        title.textContent = 'Add Tax';
    }
    
    modal.show();
}

function closeTaxModal() {
    const modalElement = document.getElementById('tax-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingTaxId = null;
    clearTaxErrors();
}

function clearTaxErrors() {
    ['tax-tax-type-id', 'tax-state-id', 'tax-percentage', 'tax-active-from', 'tax-active-till'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#tax-form').on('submit', function(e) {
    e.preventDefault();
    clearTaxErrors();
    
    const formData = {
        tax_type_id: document.getElementById('tax-tax-type-id').value,
        state_id: document.getElementById('tax-state-id').value,
        tax_percentage: document.getElementById('tax-percentage').value,
        active_from: document.getElementById('tax-active-from').value,
        active_till: document.getElementById('tax-active-till').value
    };
    
    const url = editingTaxId 
        ? `{{ url('tax-settings/taxes') }}/${editingTaxId}`
        : '{{ route("tax-settings.store-tax") }}';
    const method = editingTaxId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('tax-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            taxesTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    // Map field names to input IDs
                    const fieldMap = {
                        'tax_type_id': 'tax-tax-type-id',
                        'state_id': 'tax-state-id',
                        'tax_percentage': 'tax-percentage',
                        'active_from': 'tax-active-from',
                        'active_till': 'tax-active-till'
                    };
                    const inputId = fieldMap[key] || 'tax-' + key.replace('_', '-');
                    const errorId = inputId + '-error';
                    const errorElement = document.getElementById(errorId);
                    const inputElement = document.getElementById(inputId);
                    if (errorElement) {
                        errorElement.textContent = errors[key][0];
                    }
                    if (inputElement) {
                        inputElement.classList.add('is-invalid');
                    }
                });
            } else {
                handleAjaxError(xhr);
            }
        }
    });
});

function deleteTax(id) {
    deleteSettingsRecord(
        id,
        `{{ url('tax-settings/taxes') }}/:id`,
        'Are you sure you want to delete this tax? This action cannot be undone.',
        taxesTable,
        'Are you sure?'
    );
}
</script>

