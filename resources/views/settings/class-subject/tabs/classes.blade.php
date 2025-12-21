<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Classes</h4>
        <div class="flex-shrink-0">
            <button onclick="openClassModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Class
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="classes-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Class Name</th>
                        <th>Abbreviation</th>
                        <th>Description</th>
                        <th>Attendance Type</th>
                        <th>Sort</th>
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

<!-- Class Modal -->
<div class="modal fade" id="class-modal" tabindex="-1" aria-labelledby="class-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="class-modal-title">Add Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="class-form">
                <div class="modal-body">
                    <input type="hidden" id="class-id" name="id">
                    
                    <div class="mb-3">
                        <label for="class-name" class="form-label">Class Name <span class="text-danger">*</span></label>
                        <input type="text" id="class-name" name="class_name" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="class-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="class-abbreviation" class="form-label">Abbreviation</label>
                        <input type="text" id="class-abbreviation" name="abbreviation" 
                               class="form-control">
                        <div class="invalid-feedback" id="class-abbreviation-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="class-description" class="form-label">Description</label>
                        <textarea id="class-description" name="description" 
                                  class="form-control" rows="3"></textarea>
                        <div class="invalid-feedback" id="class-description-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="class-attendance-type" class="form-label">Attendance Type</label>
                        <select id="class-attendance-type" name="attendance_type_id" class="form-select">
                            <option value="">Select Attendance Type</option>
                            @foreach($attendanceTypes as $attendanceType)
                                <option value="{{ $attendanceType->id }}">{{ $attendanceType->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="class-attendance-type-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="class-sort" class="form-label">Sort</label>
                        <input type="number" id="class-sort" name="sort" 
                               class="form-control" min="0">
                        <div class="invalid-feedback" id="class-sort-error"></div>
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
let classesTable;
let editingClassId = null;

$(document).ready(function() {
    classesTable = $('#classes-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('class-subject-settings.get-classes') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'class_name', name: 'class_name' },
            { data: 'abbreviation', name: 'abbreviation', width: '10%' },
            { data: 'description', name: 'description' },
            { data: 'attendance_type_name', name: 'attendance_type_name', width: '15%' },
            { data: 'sort', name: 'sort', width: '8%' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='loading loading-spinner loading-lg'></span>"
        }
    });
});

function openClassModal(id = null) {
    editingClassId = id;
    const modalElement = document.getElementById('class-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('class-form');
    const title = document.getElementById('class-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('class-id').value = '';
    clearClassErrors();
    
    if (id) {
        title.textContent = 'Edit Class';
        // Fetch class data
        fetch(`/class-subject-settings/classes/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('class-id').value = data.id;
                document.getElementById('class-name').value = data.class_name || '';
                document.getElementById('class-abbreviation').value = data.abbreviation || '';
                document.getElementById('class-description').value = data.description || '';
                document.getElementById('class-attendance-type').value = data.attendance_type_id || '';
                document.getElementById('class-sort').value = data.sort || '';
            })
            .catch(error => {
                console.error('Error fetching class:', error);
                showToast('Failed to load class data', 'error');
            });
    } else {
        title.textContent = 'Add Class';
    }
    
    modal.show();
}

function closeClassModal() {
    const modalElement = document.getElementById('class-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingClassId = null;
    clearClassErrors();
}

function clearClassErrors() {
    ['class-name', 'class-abbreviation', 'class-description', 'class-attendance-type', 'class-sort'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#class-form').on('submit', function(e) {
    e.preventDefault();
    clearClassErrors();
    
    const formData = {
        class_name: document.getElementById('class-name').value,
        abbreviation: document.getElementById('class-abbreviation').value || null,
        description: document.getElementById('class-description').value || null,
        attendance_type_id: document.getElementById('class-attendance-type').value || null,
        sort: document.getElementById('class-sort').value || null
    };
    
    const url = editingClassId 
        ? `/class-subject-settings/classes/${editingClassId}`
        : '/class-subject-settings/classes';
    const method = editingClassId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('class-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            classesTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'class-' + key.replace('_', '-') + '-error';
                    const inputId = 'class-' + key.replace('_', '-');
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

function deleteClass(id) {
    deleteSettingsRecord(
        id,
        `/class-subject-settings/classes/:id`,
        'Are you sure you want to delete this class?',
        classesTable,
        'Are you sure?'
    );
}
</script>

