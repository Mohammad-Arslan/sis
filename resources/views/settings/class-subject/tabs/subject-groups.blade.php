<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Subject Groups</h4>
        <div class="flex-shrink-0">
            <button onclick="openSubjectGroupModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Subject Group
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="subject-groups-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject Group Name</th>
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

<!-- Subject Group Modal -->
<div class="modal fade" id="subject-group-modal" tabindex="-1" aria-labelledby="subject-group-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subject-group-modal-title">Add Subject Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subject-group-form">
                <div class="modal-body">
                    <input type="hidden" id="subject-group-id" name="id">
                    
                    <div class="mb-3">
                        <label for="subject-group-name" class="form-label">Subject Group Name <span class="text-danger">*</span></label>
                        <input type="text" id="subject-group-name" name="subject_group_name" class="form-control" required>
                        <div class="invalid-feedback" id="subject-group-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject-group-description" class="form-label">Description</label>
                        <textarea id="subject-group-description" name="description" class="form-control" rows="3" maxlength="1000"></textarea>
                        <div class="invalid-feedback" id="subject-group-description-error"></div>
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
let subjectGroupsTable;
let editingSubjectGroupId = null;

$(document).ready(function() {
    subjectGroupsTable = $('#subject-groups-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('class-subject-settings.get-subject-groups') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'subject_group_name', name: 'subject_group_name' },
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

function openSubjectGroupModal(id = null) {
    editingSubjectGroupId = id;
    const modalElement = document.getElementById('subject-group-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('subject-group-form');
    const title = document.getElementById('subject-group-modal-title');
    
    form.reset();
    document.getElementById('subject-group-id').value = '';
    clearSubjectGroupErrors();
    
    if (id) {
        title.textContent = 'Edit Subject Group';
        fetch(`/class-subject-settings/subject-groups/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('subject-group-id').value = data.id;
                document.getElementById('subject-group-name').value = data.subject_group_name || '';
                document.getElementById('subject-group-description').value = data.description || '';
            })
            .catch(error => {
                console.error('Error fetching subject group:', error);
                showToast('Failed to load subject group data', 'error');
            });
    } else {
        title.textContent = 'Add Subject Group';
    }
    
    modal.show();
}

function clearSubjectGroupErrors() {
    ['subject-group-name', 'subject-group-description'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
        }
    });
}

$('#subject-group-form').on('submit', function(e) {
    e.preventDefault();
    clearSubjectGroupErrors();
    
    const formData = {
        subject_group_name: document.getElementById('subject-group-name').value,
        description: document.getElementById('subject-group-description').value || null
    };
    
    const url = editingSubjectGroupId 
        ? `/class-subject-settings/subject-groups/${editingSubjectGroupId}`
        : '/class-subject-settings/subject-groups';
    const method = editingSubjectGroupId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('subject-group-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            subjectGroupsTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'subject-group-' + key.replace('_', '-') + '-error';
                    const inputId = 'subject-group-' + key.replace('_', '-');
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

function deleteSubjectGroup(id) {
    deleteSettingsRecord(
        id,
        `/class-subject-settings/subject-groups/:id`,
        'Are you sure you want to delete this subject group?',
        subjectGroupsTable,
        'Are you sure?'
    );
}
</script>

