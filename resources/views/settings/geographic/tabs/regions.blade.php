<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Regions</h4>
        <div class="flex-shrink-0">
            <button onclick="openRegionModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Region
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="regions-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Region Name</th>
                        <th>Abbreviation</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Region Modal -->
<div class="modal fade" id="region-modal" tabindex="-1" aria-labelledby="region-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="region-modal-title">Add Region</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="region-form">
                <div class="modal-body">
                    <input type="hidden" id="region-id" name="id">
                    
                    <div class="mb-3">
                        <label for="region-name" class="form-label">Region Name <span class="text-danger">*</span></label>
                        <input type="text" id="region-name" name="region_name" class="form-control" required>
                        <div class="invalid-feedback" id="region-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="region-abbreviation" class="form-label">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" id="region-abbreviation" name="abbreviation" class="form-control" required>
                        <div class="invalid-feedback" id="region-abbreviation-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="region-description" class="form-label">Description</label>
                        <textarea id="region-description" name="description" class="form-control" rows="3"></textarea>
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
let regionsTable;
let editingRegionId = null;

$(document).ready(function() {
    regionsTable = $('#regions-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('geographic-settings.get-regions') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'region_name', name: 'region_name' },
            { data: 'abbreviation', name: 'abbreviation', width: '15%' },
            { data: 'description', name: 'description' },
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

function openRegionModal(id = null) {
    editingRegionId = id;
    const modalElement = document.getElementById('region-modal');
    const modal = new bootstrap.Modal(modalElement);
    const title = document.getElementById('region-modal-title');
    
    document.getElementById('region-form').reset();
    document.getElementById('region-id').value = '';
    clearRegionErrors();
    
    if (id) {
        title.textContent = 'Edit Region';
        fetch(`/geographic-settings/regions/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('region-id').value = data.id;
                document.getElementById('region-name').value = data.region_name;
                document.getElementById('region-abbreviation').value = data.abbreviation || '';
                document.getElementById('region-description').value = data.description || '';
            });
    } else {
        title.textContent = 'Add Region';
    }
    
    modal.show();
}

function closeRegionModal() {
    const modalElement = document.getElementById('region-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) modal.hide();
    editingRegionId = null;
    clearRegionErrors();
}

function clearRegionErrors() {
    ['region-name', 'region-abbreviation'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
        }
    });
}

$('#region-form').on('submit', function(e) {
    e.preventDefault();
    clearRegionErrors();
    
    const formData = {
        region_name: document.getElementById('region-name').value,
        abbreviation: document.getElementById('region-abbreviation').value,
        description: document.getElementById('region-description').value
    };
    
    const url = editingRegionId 
        ? `/geographic-settings/regions/${editingRegionId}`
        : '/geographic-settings/regions';
    const method = editingRegionId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('region-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            regionsTable.ajax.reload();
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

function deleteRegion(id) {
    deleteSettingsRecord(
        id,
        `/geographic-settings/regions/:id`,
        'Are you sure you want to delete this region?',
        regionsTable,
        'Are you sure?'
    );
}
</script>
