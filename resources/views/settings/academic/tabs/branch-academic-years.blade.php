<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Branch Academic Years</h4>
        <div class="flex-shrink-0">
            <button onclick="openBranchAcademicYearModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Branch Academic Year
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filter-branch" class="form-label">Filter by Branch</label>
                <select id="filter-branch" class="form-select">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter-academic-year" class="form-label">Filter by Academic Year</label>
                <select id="filter-academic-year" class="form-select">
                    <option value="">All Academic Years</option>
                    @foreach($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}">{{ $academicYear->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button onclick="clearFilters()" class="btn btn-secondary btn-sm">
                    <i class="ri-refresh-line me-1"></i> Clear Filters
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="branch-academic-years-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Branch</th>
                        <th>Academic Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
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

<!-- Branch Academic Year Modal -->
<div class="modal fade" id="branch-academic-year-modal" tabindex="-1" aria-labelledby="branch-academic-year-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="branch-academic-year-modal-title">Add Branch Academic Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="branch-academic-year-form">
                <div class="modal-body">
                    <input type="hidden" id="branch-academic-year-id" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="branch-academic-year-branch-id" class="form-label">Branch <span class="text-danger">*</span></label>
                            <select id="branch-academic-year-branch-id" name="branch_id" class="form-select" required>
                                <option value="">Please select a Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="branch-academic-year-branch-id-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="branch-academic-year-academic-year-id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                            <select id="branch-academic-year-academic-year-id" name="academic_year_id" class="form-select" required>
                                <option value="">Please select an Academic Year</option>
                                @foreach($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}">{{ $academicYear->title }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="branch-academic-year-academic-year-id-error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="branch-academic-year-start-date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="text" id="branch-academic-year-start-date" name="start_date" 
                                   class="form-control" data-provider="flatpickr" 
                                   data-date-format="Y-m-d" data-altFormat="d-m-Y" required>
                            <div class="invalid-feedback" id="branch-academic-year-start-date-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="branch-academic-year-end-date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="text" id="branch-academic-year-end-date" name="end_date" 
                                   class="form-control" data-provider="flatpickr" 
                                   data-date-format="Y-m-d" data-altFormat="d-m-Y" required>
                            <div class="invalid-feedback" id="branch-academic-year-end-date-error"></div>
                        </div>
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
let branchAcademicYearsTable;
let editingBranchAcademicYearId = null;

$(document).ready(function() {
    branchAcademicYearsTable = $('#branch-academic-years-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('academic-settings.get-branch-academic-years') }}",
            type: 'GET',
            data: function(d) {
                d.branch_id = $('#filter-branch').val();
                d.academic_year_id = $('#filter-academic-year').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '5%', searchable: true },
            { data: 'br_name', name: 'br_name', searchable: true },
            { data: 'academic_year_title', name: 'academic_year_title', searchable: true },
            { data: 'start_date_formatted', name: 'start_date', width: '12%' },
            { data: 'end_date_formatted', name: 'end_date', width: '12%' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='loading loading-spinner loading-lg'></span>"
        }
    });

    // Filter change handlers
    $('#filter-branch, #filter-academic-year').on('change', function() {
        branchAcademicYearsTable.ajax.reload();
    });
});

function clearFilters() {
    $('#filter-branch').val('');
    $('#filter-academic-year').val('');
    branchAcademicYearsTable.ajax.reload();
}

function openBranchAcademicYearModal(id = null) {
    editingBranchAcademicYearId = id;
    const modalElement = document.getElementById('branch-academic-year-modal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('branch-academic-year-form');
    const title = document.getElementById('branch-academic-year-modal-title');
    
    // Reset form
    form.reset();
    document.getElementById('branch-academic-year-id').value = '';
    clearBranchAcademicYearErrors();
    
    // Reinitialize flatpickr
    if (typeof flatpickr !== 'undefined') {
        $('#branch-academic-year-start-date, #branch-academic-year-end-date').flatpickr({
            dateFormat: 'Y-m-d',
            altFormat: 'd-m-Y'
        });
    }
    
    if (id) {
        title.textContent = 'Edit Branch Academic Year';
        // Fetch branch academic year data
        fetch(`{{ url('academic-settings/branch-academic-years') }}/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('branch-academic-year-id').value = data.id || '';
                document.getElementById('branch-academic-year-branch-id').value = data.branch_id || '';
                document.getElementById('branch-academic-year-academic-year-id').value = data.academic_year_id || '';
                
                if (data.start_date) {
                    document.getElementById('branch-academic-year-start-date').value = data.start_date;
                }
                if (data.end_date) {
                    document.getElementById('branch-academic-year-end-date').value = data.end_date;
                }
            })
            .catch(error => {
                console.error('Error loading branch academic year data:', error);
                showToast('Failed to load branch academic year data. Please try again.', 'error');
            });
    } else {
        title.textContent = 'Add Branch Academic Year';
    }
    
    modal.show();
}

function closeBranchAcademicYearModal() {
    const modalElement = document.getElementById('branch-academic-year-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
    editingBranchAcademicYearId = null;
    clearBranchAcademicYearErrors();
}

function clearBranchAcademicYearErrors() {
    ['branch-academic-year-branch-id', 'branch-academic-year-academic-year-id', 'branch-academic-year-start-date', 'branch-academic-year-end-date'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.remove('input-error');
        }
    });
}

$('#branch-academic-year-form').on('submit', function(e) {
    e.preventDefault();
    clearBranchAcademicYearErrors();
    
    const formData = {
        branch_id: document.getElementById('branch-academic-year-branch-id').value,
        academic_year_id: document.getElementById('branch-academic-year-academic-year-id').value,
        start_date: document.getElementById('branch-academic-year-start-date').value,
        end_date: document.getElementById('branch-academic-year-end-date').value
    };
    
    const url = editingBranchAcademicYearId 
        ? `{{ url('academic-settings/branch-academic-years') }}/${editingBranchAcademicYearId}`
        : '{{ route("academic-settings.store-branch-academic-year") }}';
    const method = editingBranchAcademicYearId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('branch-academic-year-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            branchAcademicYearsTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const fieldMap = {
                        'branch_id': 'branch-academic-year-branch-id',
                        'academic_year_id': 'branch-academic-year-academic-year-id',
                        'start_date': 'branch-academic-year-start-date',
                        'end_date': 'branch-academic-year-end-date'
                    };
                    const inputId = fieldMap[key] || 'branch-academic-year-' + key.replace('_', '-');
                    const errorId = inputId + '-error';
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

function deleteBranchAcademicYear(id) {
    if (!confirm('Are you sure you want to delete this branch academic year? This action cannot be undone.')) {
        return;
    }
    
    $.ajax({
        url: `{{ url('academic-settings/branch-academic-years') }}/${id}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast(response.message, 'success');
            branchAcademicYearsTable.ajax.reload();
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
}
</script>

