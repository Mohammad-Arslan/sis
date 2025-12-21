<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Subjects</h4>
        <div class="flex-shrink-0">
            <button onclick="openSubjectModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Subject
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <select id="subject-group-filter" class="form-select" style="max-width: 300px;" onchange="filterSubjects()">
                <option value="">All Subject Groups</option>
                @foreach($subjectGroups as $subjectGroup)
                    <option value="{{ $subjectGroup->id }}">{{ $subjectGroup->subject_group_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-responsive">
            <table id="subjects-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject Name</th>
                        <th>Abbreviation</th>
                        <th>Academic</th>
                        <th>Language</th>
                        <th>Subject Group</th>
                        <th>Subject Type</th>
                        <th>Sort No</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Subject Modal -->
<div class="modal fade" id="subject-modal" tabindex="-1" aria-labelledby="subject-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subject-modal-title">Add Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subject-form">
                <div class="modal-body">
                    <input type="hidden" id="subject-id" name="id">
                    
                    <div class="mb-3">
                        <label for="subject-name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" id="subject-name" name="subject_name" class="form-control" required>
                        <div class="invalid-feedback" id="subject-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject-abbreviation" class="form-label">Abbreviation</label>
                        <input type="text" id="subject-abbreviation" name="abbreviation" class="form-control" maxlength="50">
                        <div class="invalid-feedback" id="subject-abbreviation-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject-language-id" class="form-label">Language <span class="text-danger">*</span></label>
                        <select id="subject-language-id" name="language_id" class="form-select" required>
                            <option value="">Select Language</option>
                            @foreach($languages as $language)
                                <option value="{{ $language->id }}">{{ $language->language_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="subject-language-id-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject-group-id" class="form-label">Subject Group</label>
                        <select id="subject-group-id" name="subject_group_id" class="form-select">
                            <option value="">Select Subject Group</option>
                            @foreach($subjectGroups as $subjectGroup)
                                <option value="{{ $subjectGroup->id }}">{{ $subjectGroup->subject_group_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="subject-group-id-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject-type" class="form-label">Subject Type <span class="text-danger">*</span></label>
                        <input type="text" id="subject-type" name="subject_type" class="form-control" required>
                        <div class="invalid-feedback" id="subject-type-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject-is-academic" class="form-label">Is Academic <span class="text-danger">*</span></label>
                        <select id="subject-is-academic" name="is_academic" class="form-select" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <div class="invalid-feedback" id="subject-is-academic-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject-sort-no" class="form-label">Sort Number</label>
                        <input type="number" id="subject-sort-no" name="sort_no" class="form-control" min="0">
                        <div class="invalid-feedback" id="subject-sort-no-error"></div>
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
let subjectsTable;
let editingSubjectId = null;

$(document).ready(function() {
    subjectsTable = $('#subjects-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('class-subject-settings.get-subjects') }}",
            type: 'GET',
            data: function(d) {
                d.subject_group_id = $('#subject-group-filter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'subject_name', name: 'subject_name' },
            { data: 'abbreviation', name: 'abbreviation' },
            { data: 'is_academic_display', name: 'is_academic', orderable: false, searchable: false },
            { data: 'language_name', name: 'language_name' },
            { data: 'subject_group_name', name: 'subject_group_name' },
            { data: 'subject_type', name: 'subject_type' },
            { data: 'sort_no', name: 'sort_no', width: '8%' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='loading loading-spinner loading-lg'></span>"
        }
    });
});

function filterSubjects() {
    subjectsTable.ajax.reload();
}

function openSubjectModal(id = null) {
    editingSubjectId = id;
    const modalElement = document.getElementById('subject-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('subject-form');
    const title = document.getElementById('subject-modal-title');
    
    form.reset();
    document.getElementById('subject-id').value = '';
    clearSubjectErrors();
    
    if (id) {
        title.textContent = 'Edit Subject';
        fetch(`/class-subject-settings/subjects/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('subject-id').value = data.id;
                document.getElementById('subject-name').value = data.subject_name || '';
                document.getElementById('subject-abbreviation').value = data.abbreviation || '';
                document.getElementById('subject-language-id').value = data.language_id || '';
                document.getElementById('subject-group-id').value = data.subject_group_id || '';
                document.getElementById('subject-type').value = data.subject_type || '';
                document.getElementById('subject-is-academic').value = data.is_academic ? '1' : '0';
                document.getElementById('subject-sort-no').value = data.sort_no || '';
            })
            .catch(error => {
                console.error('Error fetching subject:', error);
                showToast('Failed to load subject data', 'error');
            });
    } else {
        title.textContent = 'Add Subject';
    }
    
    modal.show();
}

function clearSubjectErrors() {
    ['subject-name', 'subject-abbreviation', 'subject-language-id', 'subject-group-id', 'subject-type', 'subject-is-academic', 'subject-sort-no'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
        }
    });
}

$('#subject-form').on('submit', function(e) {
    e.preventDefault();
    clearSubjectErrors();
    
    const formData = {
        subject_name: document.getElementById('subject-name').value,
        abbreviation: document.getElementById('subject-abbreviation').value || null,
        language_id: document.getElementById('subject-language-id').value,
        subject_group_id: document.getElementById('subject-group-id').value || null,
        subject_type: document.getElementById('subject-type').value,
        is_academic: document.getElementById('subject-is-academic').value === '1',
        sort_no: document.getElementById('subject-sort-no').value || null
    };
    
    const url = editingSubjectId 
        ? `/class-subject-settings/subjects/${editingSubjectId}`
        : '/class-subject-settings/subjects';
    const method = editingSubjectId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('subject-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            subjectsTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'subject-' + key.replace('_', '-') + '-error';
                    const inputId = 'subject-' + key.replace('_', '-');
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

function deleteSubject(id) {
    deleteSettingsRecord(
        id,
        `/class-subject-settings/subjects/:id`,
        'Are you sure you want to delete this subject?',
        subjectsTable,
        'Are you sure?'
    );
}
</script>

