<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Class Subjects</h4>
        <div class="flex-shrink-0">
            <button onclick="openClassSubjectModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Class Subject
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filter-class" class="form-label">Filter by Class</label>
                <select id="filter-class" class="form-select" onchange="filterClassSubjects()">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-subject" class="form-label">Filter by Subject</label>
                <select id="filter-subject" class="form-select" onchange="filterClassSubjects()">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-branch" class="form-label">Filter by Branch</label>
                <select id="filter-branch" class="form-select" onchange="filterClassSubjects()">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-state" class="form-label">Filter by State</label>
                <select id="filter-state" class="form-select" onchange="filterClassSubjects()">
                    <option value="">All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mt-2 d-flex align-items-end">
                <button onclick="clearClassSubjectFilters()" class="btn btn-secondary btn-sm">
                    <i class="ri-refresh-line me-1"></i> Clear
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="class-subjects-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Branch</th>
                        <th>State</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Class Subject Modal -->
<div class="modal fade" id="class-subject-modal" tabindex="-1" aria-labelledby="class-subject-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="class-subject-modal-title">Add Class Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="class-subject-form">
                <div class="modal-body">
                    <input type="hidden" id="class-subject-id" name="id">
                    
                    <div class="mb-3">
                        <label for="class-subject-class-id" class="form-label">Class <span class="text-danger">*</span></label>
                        <select id="class-subject-class-id" name="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="class-subject-class-id-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="class-subject-subject-id" class="form-label">Subject <span class="text-danger">*</span></label>
                        <select id="class-subject-subject-id" name="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="class-subject-subject-id-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="class-subject-branch-id" class="form-label">Branch</label>
                        <select id="class-subject-branch-id" name="branch_id" class="form-select">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="class-subject-branch-id-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="class-subject-state-id" class="form-label">State</label>
                        <select id="class-subject-state-id" name="state_id" class="form-select">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="class-subject-state-id-error"></div>
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
let classSubjectsTable;
let editingClassSubjectId = null;

$(document).ready(function() {
    classSubjectsTable = $('#class-subjects-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('class-subject-settings.get-class-subjects') }}",
            type: 'GET',
            data: function(d) {
                d.class_id = $('#filter-class').val();
                d.subject_id = $('#filter-subject').val();
                d.branch_id = $('#filter-branch').val();
                d.state_id = $('#filter-state').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'class_name', name: 'class_name' },
            { data: 'subject_name', name: 'subject_name' },
            { data: 'branch_name', name: 'branch_name' },
            { data: 'state_name', name: 'state_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='loading loading-spinner loading-lg'></span>"
        }
    });
});

function filterClassSubjects() {
    classSubjectsTable.ajax.reload();
}

function clearClassSubjectFilters() {
    $('#filter-class').val('');
    $('#filter-subject').val('');
    $('#filter-branch').val('');
    $('#filter-state').val('');
    classSubjectsTable.ajax.reload();
}

function openClassSubjectModal(id = null) {
    editingClassSubjectId = id;
    const modalElement = document.getElementById('class-subject-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('class-subject-form');
    const title = document.getElementById('class-subject-modal-title');
    
    form.reset();
    document.getElementById('class-subject-id').value = '';
    clearClassSubjectErrors();
    
    if (id) {
        title.textContent = 'Edit Class Subject';
        fetch(`/class-subject-settings/class-subjects/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('class-subject-id').value = data.id;
                document.getElementById('class-subject-class-id').value = data.class_id || '';
                document.getElementById('class-subject-subject-id').value = data.subject_id || '';
                document.getElementById('class-subject-branch-id').value = data.branch_id || '';
                document.getElementById('class-subject-state-id').value = data.state_id || '';
            })
            .catch(error => {
                console.error('Error fetching class subject:', error);
                showToast('Failed to load class subject data', 'error');
            });
    } else {
        title.textContent = 'Add Class Subject';
    }
    
    modal.show();
}

function clearClassSubjectErrors() {
    ['class-subject-class-id', 'class-subject-subject-id', 'class-subject-branch-id', 'class-subject-state-id'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
        }
    });
}

$('#class-subject-form').on('submit', function(e) {
    e.preventDefault();
    clearClassSubjectErrors();
    
    const formData = {
        class_id: document.getElementById('class-subject-class-id').value,
        subject_id: document.getElementById('class-subject-subject-id').value,
        branch_id: document.getElementById('class-subject-branch-id').value || null,
        state_id: document.getElementById('class-subject-state-id').value || null
    };
    
    const url = editingClassSubjectId 
        ? `/class-subject-settings/class-subjects/${editingClassSubjectId}`
        : '/class-subject-settings/class-subjects';
    const method = editingClassSubjectId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('class-subject-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            classSubjectsTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = 'class-subject-' + key.replace('_', '-') + '-error';
                    const inputId = 'class-subject-' + key.replace('_', '-');
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

function deleteClassSubject(id) {
    deleteSettingsRecord(
        id,
        `/class-subject-settings/class-subjects/:id`,
        'Are you sure you want to delete this class subject?',
        classSubjectsTable,
        'Are you sure?'
    );
}
</script>

