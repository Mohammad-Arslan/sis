<div x-data="subjectGroupCrud()" x-init="init()">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Subject Groups</h4>
            <div class="flex-shrink-0">
                <button @click="openModal()" class="btn btn-primary btn-sm">
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
                    <tbody>
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Subject Group Modal -->
    <div class="modal fade" 
         id="subject-group-modal" 
         tabindex="-1" 
         aria-labelledby="subject-group-modal-title" 
         aria-hidden="true"
         x-ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subject-group-modal-title" x-text="editingId ? 'Edit Subject Group' : 'Add Subject Group'"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="submitForm()" x-ref="form">
                    <div class="modal-body">
                        <input type="hidden" x-model="formData.id">
                        
                        <div class="mb-3">
                            <label for="subject-group-name" class="form-label">Subject Group Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="subject-group-name" 
                                   x-model="formData.subject_group_name"
                                   class="form-control"
                                   :class="{ 'is-invalid': errors.subject_group_name }"
                                   required>
                            <div class="invalid-feedback" x-show="errors.subject_group_name" x-text="errors.subject_group_name"></div>
                        </div>

                        <div class="mb-3">
                            <label for="subject-group-description" class="form-label">Description</label>
                            <textarea id="subject-group-description" 
                                      x-model="formData.description"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.description }"
                                      rows="3"
                                      maxlength="1000"></textarea>
                            <div class="invalid-feedback" x-show="errors.description" x-text="errors.description"></div>
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
function subjectGroupCrud() {
    return {
        subjectGroupsTable: null,
        editingId: null,
        loading: false,
        formData: {
            id: null,
            subject_group_name: '',
            description: '',
        },
        errors: {},

        init() {
            this.initDataTable();
        },

        initDataTable() {
            this.subjectGroupsTable = $('#subject-groups-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 10,
                ajax: {
                    url: "{{ route('subject-settings.get-subject-groups') }}",
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
        },

        openModal(id = null) {
            this.editingId = id;
            this.errors = {};
            this.resetForm();

            const modalElement = document.getElementById('subject-group-modal');
            const modal = new bootstrap.Modal(modalElement);

            if (id) {
                this.loadSubjectGroup(id);
            }

            modal.show();
        },

        async loadSubjectGroup(id) {
            try {
                const response = await fetch(`{{ url('subject-settings/subject-groups') }}/${id}`);
                const data = await response.json();

                this.formData = {
                    id: data.id,
                    subject_group_name: data.subject_group_name || '',
                    description: data.description || '',
                };
            } catch (error) {
                console.error('Error loading subject group:', error);
                this.showToast('Failed to load subject group data', 'error');
            }
        },

        async submitForm() {
            this.loading = true;
            this.errors = {};

            const url = this.editingId 
                ? `{{ url('subject-settings/subject-groups') }}/${this.editingId}`
                : '{{ route("subject-settings.store-subject-group") }}';
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
                    const modalElement = document.getElementById('subject-group-modal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    this.subjectGroupsTable.ajax.reload();
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
                subject_group_name: '',
                description: '',
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
function deleteSubjectGroup(id) {
    if (confirm('Are you sure you want to delete this subject group? This action cannot be undone.')) {
        fetch(`{{ url('subject-settings/subject-groups') }}/${id}`, {
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
                $('#subject-groups-table').DataTable().ajax.reload();
            } else {
                if (typeof showToast === 'function') {
                    showToast(data.message || 'Failed to delete subject group', 'error');
                } else {
                    alert(data.message || 'Failed to delete subject group');
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
