<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">States</h4>
        <div class="flex-shrink-0">
            <button onclick="openStateModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add State
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <select id="state-country-filter" class="form-select" style="max-width: 300px;" onchange="filterStates()">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-responsive">
            <table id="states-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>State Name</th>
                        <th>Country</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- State Modal -->
<div class="modal fade" id="state-modal" tabindex="-1" aria-labelledby="state-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="state-modal-title">Add State</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="state-form">
                <div class="modal-body">
                    <input type="hidden" id="state-id" name="id">
                    
                    <div class="mb-3">
                        <label for="state-country" class="form-label">Country <span class="text-danger">*</span></label>
                        <select id="state-country" name="country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="state-country-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="state-name" class="form-label">State Name <span class="text-danger">*</span></label>
                        <input type="text" id="state-name" name="state_name" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="state-name-error"></div>
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
let statesTable;
let editingStateId = null;

$(document).ready(function() {
    statesTable = $('#states-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('geographic-settings.get-states') }}",
            type: 'GET',
            data: function(d) {
                d.country_id = $('#state-country-filter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'state_name', name: 'state_name' },
            { data: 'country_name', name: 'country_name' },
            { data: 'created_at', name: 'created_at', width: '15%' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />"
        }
    });
});

function filterStates() {
    statesTable.ajax.reload();
}

function openStateModal(id = null) {
    editingStateId = id;
    const modalElement = document.getElementById('state-modal');
    const modal = new bootstrap.Modal(modalElement);
    const title = document.getElementById('state-modal-title');
    
    document.getElementById('state-form').reset();
    document.getElementById('state-id').value = '';
    clearStateErrors();
    
    if (id) {
        title.textContent = 'Edit State';
        fetch(`/geographic-settings/states/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('state-id').value = data.id;
                document.getElementById('state-name').value = data.state_name;
                document.getElementById('state-country').value = data.country_id;
            });
    } else {
        title.textContent = 'Add State';
    }
    
    modal.show();
}

function closeStateModal() {
    const modalElement = document.getElementById('state-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) modal.hide();
    editingStateId = null;
    clearStateErrors();
}

function clearStateErrors() {
    ['state-name', 'state-country'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
        }
    });
}

$('#state-form').on('submit', function(e) {
    e.preventDefault();
    clearStateErrors();
    
    const formData = {
        state_name: document.getElementById('state-name').value,
        country_id: document.getElementById('state-country').value
    };
    
    const url = editingStateId 
        ? `/geographic-settings/states/${editingStateId}`
        : '/geographic-settings/states';
    const method = editingStateId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('state-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            statesTable.ajax.reload();
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

function deleteState(id) {
    deleteSettingsRecord(
        id,
        `/geographic-settings/states/:id`,
        'Are you sure you want to delete this state?',
        statesTable,
        'Are you sure?'
    );
}
</script>
