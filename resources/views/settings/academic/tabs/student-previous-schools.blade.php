<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Student Previous Schools</h4>
        <div class="flex-shrink-0">
            <button onclick="openStudentPreviousSchoolModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Previous School
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="student-previous-schools-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>School Name</th>
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

<!-- Student Previous School Modal -->
<div class="modal fade" id="student-previous-school-modal" tabindex="-1" aria-labelledby="student-previous-school-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="student-previous-school-modal-title">Add Student Previous School</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="student-previous-school-form">
                <div class="modal-body">
                    <input type="hidden" id="student-previous-school-id" name="id">
                    
                    <div class="mb-3">
                        <label for="student-previous-school-name" class="form-label">School Name <span class="text-danger">*</span></label>
                        <input type="text" id="student-previous-school-name" name="school_name" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="student-previous-school-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="student-previous-school-description" class="form-label">Description</label>
                        <textarea id="student-previous-school-description" name="description" 
                                  class="form-control" rows="3"></textarea>
                        <div class="invalid-feedback" id="student-previous-school-description-error"></div>
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
let studentPreviousSchoolsTable;
let editingStudentPreviousSchoolId = null;

$(document).ready(function() {
    studentPreviousSchoolsTable = $('#student-previous-schools-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('academic-settings.get-student-previous-schools') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%', searchable: true },
            { data: 'school_name', name: 'school_name', searchable: true },
            { data: 'description', name: 'description', searchable: true },
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

function openStudentPreviousSchoolModal(id = null) {
    editingStudentPreviousSchoolId = id;
    const modalElement = document.getElementById('student-previous-school-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('student-previous-school-form');
    const title = document.getElementById('student-previous-school-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('student-previous-school-id').value = '';
    clearStudentPreviousSchoolErrors();
    
    if (id) {
        title.textContent = 'Edit Student Previous School';
        // Fetch student previous school data
        fetch(`{{ url('academic-settings/student-previous-schools') }}/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('student-previous-school-id').value = data.id || '';
                document.getElementById('student-previous-school-name').value = data.school_name || '';
                document.getElementById('student-previous-school-description').value = data.description || '';
            })
            .catch(error => {
                console.error('Error loading student previous school data:', error);
                showToast('Failed to load student previous school data. Please try again.', 'error');
            });
    } else {
        title.textContent = 'Add Student Previous School';
    }
    
    modal.show();
}

function closeStudentPreviousSchoolModal() {
    const modalElement = document.getElementById('student-previous-school-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingStudentPreviousSchoolId = null;
    clearStudentPreviousSchoolErrors();
}

function clearStudentPreviousSchoolErrors() {
    ['student-previous-school-name', 'student-previous-school-description'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#student-previous-school-form').on('submit', function(e) {
    e.preventDefault();
    clearStudentPreviousSchoolErrors();
    
    const formData = {
        school_name: document.getElementById('student-previous-school-name').value,
        description: document.getElementById('student-previous-school-description').value
    };
    
    const url = editingStudentPreviousSchoolId 
        ? `{{ url('academic-settings/student-previous-schools') }}/${editingStudentPreviousSchoolId}`
        : '{{ route("academic-settings.store-student-previous-school") }}';
    const method = editingStudentPreviousSchoolId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('student-previous-school-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            studentPreviousSchoolsTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'student-previous-school-' + key.replace('_', '-') + '-error';
                    const inputId = 'student-previous-school-' + key.replace('_', '-');
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

function deleteStudentPreviousSchool(id) {
    if (!confirm('Are you sure you want to delete this student previous school? This action cannot be undone.')) {
        return;
    }
    
    $.ajax({
        url: `{{ url('academic-settings/student-previous-schools') }}/${id}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            studentPreviousSchoolsTable.ajax.reload();
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
}
</script>

