<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Academic Years</h4>
        <div class="flex-shrink-0">
            <button onclick="openAcademicYearModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Academic Year
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="academic-years-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Status</th>
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

<!-- Academic Year Modal -->
<div class="modal fade" id="academic-year-modal" tabindex="-1" aria-labelledby="academic-year-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="academic-year-modal-title">Add Academic Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="academic-year-form">
                <div class="modal-body">
                    <input type="hidden" id="academic-year-id" name="id">
                    
                    <div class="mb-3">
                        <label for="academic-year-title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" id="academic-year-title" name="title" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="academic-year-title-error"></div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="academic-year-active" name="active" value="1">
                            <label class="form-check-label" for="academic-year-active">
                                Set as Active Academic Year
                            </label>
                        </div>
                        <small class="text-muted">Only one academic year can be active at a time.</small>
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
let academicYearsTable;
let editingAcademicYearId = null;

$(document).ready(function() {
    academicYearsTable = $('#academic-years-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('academic-settings.get-academic-years') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%', searchable: true },
            { data: 'title', name: 'title', searchable: true },
            { data: 'active_status', name: 'active', width: '10%' },
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

function openAcademicYearModal(id = null) {
    editingAcademicYearId = id;
    const modalElement = document.getElementById('academic-year-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('academic-year-form');
    const title = document.getElementById('academic-year-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('academic-year-id').value = '';
    document.getElementById('academic-year-active').checked = false;
    clearAcademicYearErrors();
    
    if (id) {
        title.textContent = 'Edit Academic Year';
        // Fetch academic year data
        fetch(`{{ url('academic-settings/academic-years') }}/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('academic-year-id').value = data.id || '';
                document.getElementById('academic-year-title').value = data.title || '';
                document.getElementById('academic-year-active').checked = data.active == 1 || data.active === true;
            })
            .catch(error => {
                console.error('Error loading academic year data:', error);
                showToast('Failed to load academic year data. Please try again.', 'error');
            });
    } else {
        title.textContent = 'Add Academic Year';
    }
    
    modal.show();
}

function closeAcademicYearModal() {
    const modalElement = document.getElementById('academic-year-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingAcademicYearId = null;
    clearAcademicYearErrors();
}

function clearAcademicYearErrors() {
    ['academic-year-title'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#academic-year-form').on('submit', function(e) {
    e.preventDefault();
    clearAcademicYearErrors();
    
    const formData = {
        title: document.getElementById('academic-year-title').value,
        active: document.getElementById('academic-year-active').checked ? 1 : 0
    };
    
    const url = editingAcademicYearId 
        ? `{{ url('academic-settings/academic-years') }}/${editingAcademicYearId}`
        : '{{ route("academic-settings.store-academic-year") }}';
    const method = editingAcademicYearId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('academic-year-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            academicYearsTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'academic-year-' + key.replace('_', '-') + '-error';
                    const inputId = 'academic-year-' + key.replace('_', '-');
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

function deleteAcademicYear(id) {
    if (!confirm('Are you sure you want to delete this academic year? This action cannot be undone.')) {
        return;
    }
    
    $.ajax({
        url: `{{ url('academic-settings/academic-years') }}/${id}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            academicYearsTable.ajax.reload();
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
}
</script>

