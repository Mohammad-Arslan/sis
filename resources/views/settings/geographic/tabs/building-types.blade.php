<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Building Types</h4>
        <div class="flex-shrink-0">
            <button onclick="openBuildingTypeModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Building Type
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="building-types-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type Name</th>
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

<!-- Building Type Modal -->
<div class="modal fade" id="building-type-modal" tabindex="-1" aria-labelledby="building-type-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="building-type-modal-title">Add Building Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="building-type-form">
                <div class="modal-body">
                    <input type="hidden" id="building-type-id" name="id">
                    
                    <div class="mb-3">
                        <label for="building-type-name" class="form-label">Type Name <span class="text-danger">*</span></label>
                        <input type="text" id="building-type-name" name="type_name" class="form-control" required>
                        <div class="invalid-feedback" id="building-type-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="building-type-description" class="form-label">Description</label>
                        <textarea id="building-type-description" name="type_description" class="form-control" rows="3"></textarea>
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
let buildingTypesTable;
let editingBuildingTypeId = null;

$(document).ready(function() {
    buildingTypesTable = $('#building-types-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('geographic-settings.get-building-types') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'type_name', name: 'type_name' },
            { data: 'type_description', name: 'type_description' },
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

function openBuildingTypeModal(id = null) {
    editingBuildingTypeId = id;
    const modalElement = document.getElementById('building-type-modal');
    const modal = new bootstrap.Modal(modalElement);
    const title = document.getElementById('building-type-modal-title');
    
    document.getElementById('building-type-form').reset();
    document.getElementById('building-type-id').value = '';
    clearBuildingTypeErrors();
    
    if (id) {
        title.textContent = 'Edit Building Type';
        fetch(`/geographic-settings/building-types/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('building-type-id').value = data.id;
                document.getElementById('building-type-name').value = data.type_name;
                document.getElementById('building-type-description').value = data.type_description || '';
            });
    } else {
        title.textContent = 'Add Building Type';
    }
    
    modal.show();
}

function closeBuildingTypeModal() {
    const modalElement = document.getElementById('building-type-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) modal.hide();
    editingBuildingTypeId = null;
    clearBuildingTypeErrors();
}

function clearBuildingTypeErrors() {
    const errorElement = document.getElementById('building-type-name-error');
    const inputElement = document.getElementById('building-type-name');
    if (errorElement) errorElement.textContent = '';
    if (inputElement) {
        inputElement.classList.remove('is-invalid');
    }
}

$('#building-type-form').on('submit', function(e) {
    e.preventDefault();
    clearBuildingTypeErrors();
    
    const formData = {
        type_name: document.getElementById('building-type-name').value,
        type_description: document.getElementById('building-type-description').value
    };
    
    const url = editingBuildingTypeId 
        ? `/geographic-settings/building-types/${editingBuildingTypeId}`
        : '/geographic-settings/building-types';
    const method = editingBuildingTypeId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('building-type-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            buildingTypesTable.ajax.reload();
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

function deleteBuildingType(id) {
    if (!confirm('Are you sure you want to delete this building type?')) return;
    
    $.ajax({
        url: `/geographic-settings/building-types/${id}`,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            showToast(response.message, 'success');
            buildingTypesTable.ajax.reload();
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
}
</script>
