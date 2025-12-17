<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Languages</h4>
        <div class="flex-shrink-0">
            <button onclick="openLanguageModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Language
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="languages-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Language Name</th>
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

<!-- Language Modal -->
<div class="modal fade" id="language-modal" tabindex="-1" aria-labelledby="language-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="language-modal-title">Add Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="language-form">
                <div class="modal-body">
                    <input type="hidden" id="language-id" name="id">
                    
                    <div class="mb-3">
                        <label for="language-name" class="form-label">Language Name <span class="text-danger">*</span></label>
                        <input type="text" id="language-name" name="language_name" 
                               class="form-control" required>
                        <div class="invalid-feedback" id="language-name-error"></div>
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
let languagesTable;
let editingLanguageId = null;

$(document).ready(function() {
    languagesTable = $('#languages-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('academic-settings.get-languages') }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '5%', searchable: true },
            { data: 'language_name', name: 'language_name', searchable: true },
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

function openLanguageModal(id = null) {
    editingLanguageId = id;
    const modalElement = document.getElementById('language-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('language-form');
    const title = document.getElementById('language-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('language-id').value = '';
    clearLanguageErrors();
    
    if (id) {
        title.textContent = 'Edit Language';
        // Fetch language data
        fetch(`{{ url('academic-settings/languages') }}/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('language-id').value = data.id || '';
                document.getElementById('language-name').value = data.language_name || '';
            })
            .catch(error => {
                console.error('Error loading language data:', error);
                showToast('Failed to load language data. Please try again.', 'error');
            });
    } else {
        title.textContent = 'Add Language';
    }
    
    modal.show();
}

function closeLanguageModal() {
    const modalElement = document.getElementById('language-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingLanguageId = null;
    clearLanguageErrors();
}

function clearLanguageErrors() {
    ['language-name'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#language-form').on('submit', function(e) {
    e.preventDefault();
    clearLanguageErrors();
    
    const formData = {
        language_name: document.getElementById('language-name').value
    };
    
    const url = editingLanguageId 
        ? `{{ url('academic-settings/languages') }}/${editingLanguageId}`
        : '{{ route("academic-settings.store-language") }}';
    const method = editingLanguageId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('language-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            languagesTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'language-' + key.replace('_', '-') + '-error';
                    const inputId = 'language-' + key.replace('_', '-');
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

function deleteLanguage(id) {
    deleteSettingsRecord(
        id,
        `{{ url('academic-settings/languages') }}/:id`,
        'Are you sure you want to delete this language? This action cannot be undone.',
        languagesTable,
        'Are you sure?'
    );
}
</script>

