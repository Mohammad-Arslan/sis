<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Countries</h4>
        <div class="flex-shrink-0">
            <button onclick="openCountryModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Country
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="countries-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Country Name</th>
                        <th>Abbreviation</th>
                        <th>Country Code</th>
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

<!-- Country Modal -->
<div class="modal fade" id="country-modal" tabindex="-1" aria-labelledby="country-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="country-modal-title">Add Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="country-form">
                <div class="modal-body">
                    <input type="hidden" id="country-id" name="id">
                    
                    <div class="mb-3">
                        <label for="country-name" class="form-label">Country Name <span class="text-danger">*</span></label>
                        <input type="text" id="country-name" name="country_name" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="country-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="country-abbreviation" class="form-label">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" id="country-abbreviation" name="abbreviation" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="country-abbreviation-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="country-code" class="form-label">Country Code <span class="text-danger">*</span></label>
                        <input type="text" id="country-code" name="country_code" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="country-code-error"></div>
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
let countriesTable;
let editingCountryId = null;

$(document).ready(function() {
    countriesTable = $('#countries-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('geographic-settings.get-countries') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'country_name', name: 'country_name' },
            { data: 'abbreviation', name: 'abbreviation', width: '15%' },
            { data: 'country_code', name: 'country_code', width: '15%' },
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

function openCountryModal(id = null) {
    editingCountryId = id;
    const modalElement = document.getElementById('country-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('country-form');
    const title = document.getElementById('country-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('country-id').value = '';
    clearCountryErrors();
    
    if (id) {
        title.textContent = 'Edit Country';
        // Fetch country data
        fetch(`/geographic-settings/countries/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('country-id').value = data.id;
                document.getElementById('country-name').value = data.country_name;
                document.getElementById('country-abbreviation').value = data.abbreviation;
                document.getElementById('country-code').value = data.country_code;
            });
    } else {
        title.textContent = 'Add Country';
    }
    
    modal.show();
}

function closeCountryModal() {
    const modalElement = document.getElementById('country-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingCountryId = null;
    clearCountryErrors();
}

function clearCountryErrors() {
    ['country-name', 'country-abbreviation', 'country-code'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#country-form').on('submit', function(e) {
    e.preventDefault();
    clearCountryErrors();
    
    const formData = {
        country_name: document.getElementById('country-name').value,
        abbreviation: document.getElementById('country-abbreviation').value,
        country_code: document.getElementById('country-code').value
    };
    
    const url = editingCountryId 
        ? `/geographic-settings/countries/${editingCountryId}`
        : '/geographic-settings/countries';
    const method = editingCountryId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('country-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            countriesTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = key.replace('_', '-') + '-error';
                    const inputId = key.replace('_', '-');
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

function deleteCountry(id) {
    if (!confirm('Are you sure you want to delete this country?')) {
        return;
    }
    
    $.ajax({
        url: `/geographic-settings/countries/${id}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            countriesTable.ajax.reload();
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
}
</script>

