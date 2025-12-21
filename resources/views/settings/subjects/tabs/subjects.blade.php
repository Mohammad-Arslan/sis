<div x-data="subjectCrud()" x-init="init()">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Subjects</h4>
            <div class="flex-shrink-0">
                <button @click="openModal()" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Add Subject
                </button>
            </div>
        </div>
        <div class="card-body">
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

    <!-- Subject Modal -->
    <div class="modal fade" 
         id="subject-modal" 
         tabindex="-1" 
         aria-labelledby="subject-modal-title" 
         aria-hidden="true"
         x-ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subject-modal-title" x-text="editingId ? 'Edit Subject' : 'Add Subject'"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="submitForm()" x-ref="form">
                    <div class="modal-body">
                        <input type="hidden" x-model="formData.id">
                        
                        <div class="mb-3">
                            <label for="subject-name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="subject-name" 
                                   x-model="formData.subject_name"
                                   class="form-control"
                                   :class="{ 'is-invalid': errors.subject_name }"
                                   required>
                            <div class="invalid-feedback" x-show="errors.subject_name" x-text="errors.subject_name"></div>
                        </div>

                        <div class="mb-3">
                            <label for="subject-abbreviation" class="form-label">Abbreviation</label>
                            <input type="text" 
                                   id="subject-abbreviation" 
                                   x-model="formData.abbreviation"
                                   class="form-control"
                                   :class="{ 'is-invalid': errors.abbreviation }"
                                   maxlength="50">
                            <div class="invalid-feedback" x-show="errors.abbreviation" x-text="errors.abbreviation"></div>
                        </div>

                        <div class="mb-3">
                            <label for="subject-language" class="form-label">Language <span class="text-danger">*</span></label>
                            <select id="subject-language" 
                                    x-model="formData.language_id"
                                    class="form-select"
                                    :class="{ 'is-invalid': errors.language_id }"
                                    required>
                                <option value="">Select Language</option>
                                <template x-for="language in languages" :key="language.id">
                                    <option :value="language.id" x-text="language.language_name"></option>
                                </template>
                            </select>
                            <div class="invalid-feedback" x-show="errors.language_id" x-text="errors.language_id"></div>
                        </div>

                        <div class="mb-3">
                            <label for="subject-group" class="form-label">Subject Group</label>
                            <select id="subject-group" 
                                    x-model="formData.subject_group_id"
                                    class="form-select"
                                    :class="{ 'is-invalid': errors.subject_group_id }">
                                <option value="">Select Subject Group</option>
                                <template x-for="group in subjectGroups" :key="group.id">
                                    <option :value="group.id" x-text="group.subject_group_name"></option>
                                </template>
                            </select>
                            <div class="invalid-feedback" x-show="errors.subject_group_id" x-text="errors.subject_group_id"></div>
                        </div>

                        <div class="mb-3">
                            <label for="subject-type" class="form-label">Subject Type <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="subject-type" 
                                   x-model="formData.subject_type"
                                   class="form-control"
                                   :class="{ 'is-invalid': errors.subject_type }"
                                   required>
                            <div class="invalid-feedback" x-show="errors.subject_type" x-text="errors.subject_type"></div>
                        </div>

                        <div class="mb-3">
                            <label for="subject-is-academic" class="form-label">Is Academic <span class="text-danger">*</span></label>
                            <select id="subject-is-academic" 
                                    x-model="formData.is_academic"
                                    class="form-select"
                                    :class="{ 'is-invalid': errors.is_academic }"
                                    required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <div class="invalid-feedback" x-show="errors.is_academic" x-text="errors.is_academic"></div>
                        </div>

                        <div class="mb-3">
                            <label for="subject-sort-no" class="form-label">Sort Number</label>
                            <input type="number" 
                                   id="subject-sort-no" 
                                   x-model="formData.sort_no"
                                   class="form-control"
                                   :class="{ 'is-invalid': errors.sort_no }"
                                   min="0">
                            <div class="invalid-feedback" x-show="errors.sort_no" x-text="errors.sort_no"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" :disabled="loading">
                            <span x-show="loading" class="spinner-border spinner-border-sm me-2"></span>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function subjectCrud() {
    return {
        subjectsTable: null,
        editingId: null,
        loading: false,
        languages: @json($languages),
        subjectGroups: @json($subjectGroups),
        formData: {
            id: null,
            subject_name: '',
            abbreviation: '',
            language_id: '',
            subject_group_id: '',
            subject_type: '',
            is_academic: '1',
            sort_no: null,
        },
        errors: {},

        init() {
            this.initDataTable();
        },

        initDataTable() {
            this.subjectsTable = $('#subjects-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 10,
                ajax: {
                    url: "{{ route('subject-settings.get-subjects') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id', width: '5%' },
                    { data: 'subject_name', name: 'subject_name' },
                    { data: 'abbreviation', name: 'abbreviation' },
                    { data: 'is_academic_display', name: 'is_academic', orderable: false, searchable: false },
                    { data: 'language_name', name: 'language.language_name' },
                    { data: 'subject_group_name', name: 'subject_group.subject_group_name' },
                    { data: 'subject_type', name: 'subject_type' },
                    { data: 'created_at', name: 'created_at', width: '15%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    processing: "<span class='loading loading-spinner loading-lg'></span>"
                }
            });
        },

        openModal(id = null) {
            this.editingId = id;
            this.errors = {};
            this.resetForm();

            const modalElement = document.getElementById('subject-modal');
            const modal = new bootstrap.Modal(modalElement);

            if (id) {
                this.loadSubject(id);
            }

            modal.show();
        },

        async loadSubject(id) {
            try {
                const response = await fetch(`{{ url('subject-settings/subjects') }}/${id}`);
                const data = await response.json();

                this.formData = {
                    id: data.id,
                    subject_name: data.subject_name || '',
                    abbreviation: data.abbreviation || '',
                    language_id: data.language_id || '',
                    subject_group_id: data.subject_group_id || '',
                    subject_type: data.subject_type || '',
                    is_academic: data.is_academic ? '1' : '0',
                    sort_no: data.sort_no || null,
                };
            } catch (error) {
                console.error('Error loading subject:', error);
                this.showToast('Failed to load subject data', 'error');
            }
        },

        async submitForm() {
            this.loading = true;
            this.errors = {};

            const url = this.editingId 
                ? `{{ url('subject-settings/subjects') }}/${this.editingId}`
                : '{{ route("subject-settings.store-subject") }}';
            const method = this.editingId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.showToast(data.message, 'success');
                    const modalElement = document.getElementById('subject-modal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    this.subjectsTable.ajax.reload();
                    this.resetForm();
                } else {
                    if (response.status === 422 && data.errors) {
                        this.errors = data.errors;
                    } else {
                        this.showToast(data.message || 'An error occurred', 'error');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                this.showToast('An error occurred. Please try again.', 'error');
            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            this.formData = {
                id: null,
                subject_name: '',
                abbreviation: '',
                language_id: '',
                subject_group_id: '',
                subject_type: '',
                is_academic: '1',
                sort_no: null,
            };
            this.editingId = null;
        },

        showToast(message, type = 'success') {
            if (typeof showToast === 'function') {
                showToast(message, type);
            } else {
                alert(message);
            }
        }
    };
}

// Global function for delete button
function deleteSubject(id) {
    if (confirm('Are you sure you want to delete this subject? This action cannot be undone.')) {
        fetch(`{{ url('subject-settings/subjects') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof showToast === 'function') {
                    showToast(data.message, 'success');
                }
                $('#subjects-table').DataTable().ajax.reload();
            } else {
                if (typeof showToast === 'function') {
                    showToast(data.message || 'Failed to delete subject', 'error');
                } else {
                    alert(data.message || 'Failed to delete subject');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showToast === 'function') {
                showToast('An error occurred. Please try again.', 'error');
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    }
}
</script>
