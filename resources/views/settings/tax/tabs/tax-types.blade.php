<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Tax Types</h4>
        <div class="flex-shrink-0">
            <button onclick="openTaxTypeModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Tax Type
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tax-types-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created At</th>
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

<!-- Tax Type Modal -->
<div class="modal fade" id="tax-type-modal" tabindex="-1" aria-labelledby="tax-type-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tax-type-modal-title">Add Tax Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tax-type-form">
                <div class="modal-body">
                    <input type="hidden" id="tax-type-id" name="id">
                    
                    <div class="mb-3">
                        <label for="tax-type-name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" id="tax-type-name" name="name" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="tax-type-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="tax-type-description" class="form-label">Description</label>
                        <textarea id="tax-type-description" name="description" 
                                  class="form-control" rows="3"></textarea>
                        <div class="invalid-feedback" id="tax-type-description-error"></div>
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
let taxTypesTable;
let editingTaxTypeId = null;

$(document).ready(function() {
    taxTypesTable = $('#tax-types-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('tax-settings.get-tax-types') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at', width: '15%' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='loading loading-spinner loading-lg'></span>"
        }
    });
});

function openTaxTypeModal(id = null) {
    editingTaxTypeId = id;
    const modalElement = document.getElementById('tax-type-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('tax-type-form');
    const title = document.getElementById('tax-type-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('tax-type-id').value = '';
    clearTaxTypeErrors();
    
    if (id) {
        title.textContent = 'Edit Tax Type';
        // Fetch tax type data
        fetch(`{{ url('tax-settings/tax-types') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('tax-type-id').value = data.id;
                document.getElementById('tax-type-name').value = data.name || '';
                document.getElementById('tax-type-description').value = data.description || '';
            })
            .catch(error => {
                console.error('Error:', error);
                handleAjaxError({ responseJSON: { message: 'Failed to load tax type data' } });
            });
    } else {
        title.textContent = 'Add Tax Type';
    }
    
    modal.show();
}

function closeTaxTypeModal() {
    const modalElement = document.getElementById('tax-type-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingTaxTypeId = null;
    clearTaxTypeErrors();
}

function clearTaxTypeErrors() {
    ['tax-type-name', 'tax-type-description'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#tax-type-form').on('submit', function(e) {
    e.preventDefault();
    clearTaxTypeErrors();
    
    const formData = {
        name: document.getElementById('tax-type-name').value,
        description: document.getElementById('tax-type-description').value
    };
    
    const url = editingTaxTypeId 
        ? `{{ url('tax-settings/tax-types') }}/${editingTaxTypeId}`
        : '{{ route("tax-settings.store-tax-type") }}';
    const method = editingTaxTypeId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('tax-type-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            taxTypesTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'tax-type-' + key.replace('_', '-') + '-error';
                    const inputId = 'tax-type-' + key.replace('_', '-');
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

function deleteTaxType(id) {
    deleteSettingsRecord(
        id,
        `{{ url('tax-settings/tax-types') }}/:id`,
        'Are you sure you want to delete this tax type? This action cannot be undone.',
        taxTypesTable,
        'Are you sure?'
    );
}
</script>

