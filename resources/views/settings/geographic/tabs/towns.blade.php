<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Towns</h4>
        <div class="flex-shrink-0">
            <button onclick="openTownModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Town
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <select id="town-city-filter" class="form-select" style="max-width: 300px;" onchange="filterTowns()">
                <option value="">All Cities</option>
            </select>
        </div>

        <div class="table-responsive">
            <table id="towns-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Town Name</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Town Modal -->
<div class="modal fade" id="town-modal" tabindex="-1" aria-labelledby="town-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="town-modal-title">Add Town</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="town-form">
                <div class="modal-body">
                    <input type="hidden" id="town-id" name="id">
                    
                    <div class="mb-3">
                        <label for="town-city" class="form-label">City <span class="text-danger">*</span></label>
                        <select id="town-city" name="city_id" class="form-select" required>
                            <option value="">Select City</option>
                        </select>
                        <div class="invalid-feedback" id="town-city-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="town-name" class="form-label">Town Name <span class="text-danger">*</span></label>
                        <input type="text" id="town-name" name="town_name" class="form-control" required>
                        <div class="invalid-feedback" id="town-name-error"></div>
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
let townsTable;
let editingTownId = null;

$(document).ready(function() {
    loadAllCities();
    townsTable = $('#towns-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('geographic-settings.get-towns') }}",
            type: 'GET',
            data: function(d) {
                d.city_id = $('#town-city-filter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'town_name', name: 'town_name' },
            { data: 'city_name', name: 'city_name' },
            { data: 'state_name', name: 'state_name' },
            { data: 'country_name', name: 'country_name' },
            { data: 'created_at', name: 'created_at', width: '15%' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />"
        }
    });
});

function loadAllCities() {
    $.get('/geographic-settings/cities-data?length=10000')
        .done(function(response) {
            const cities = response.data || [];
            cities.forEach(city => {
                $('#town-city-filter').append(`<option value="${city.id}">${city.city_name}</option>`);
                $('#town-city').append(`<option value="${city.id}">${city.city_name}</option>`);
            });
        });
}

function filterTowns() {
    townsTable.ajax.reload();
}

function openTownModal(id = null) {
    editingTownId = id;
    const modalElement = document.getElementById('town-modal');
    const modal = new bootstrap.Modal(modalElement);
    const title = document.getElementById('town-modal-title');
    
    document.getElementById('town-form').reset();
    document.getElementById('town-id').value = '';
    clearTownErrors();
    
    if (id) {
        title.textContent = 'Edit Town';
        fetch(`/geographic-settings/towns/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('town-id').value = data.id;
                document.getElementById('town-name').value = data.town_name;
                $('#town-city').val(data.city_id);
            });
    } else {
        title.textContent = 'Add Town';
    }
    
    modal.show();
}

function closeTownModal() {
    const modalElement = document.getElementById('town-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) modal.hide();
    editingTownId = null;
    clearTownErrors();
}

function clearTownErrors() {
    ['town-name', 'town-city'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
        }
    });
}

$('#town-form').on('submit', function(e) {
    e.preventDefault();
    clearTownErrors();
    
    const formData = {
        town_name: document.getElementById('town-name').value,
        city_id: document.getElementById('town-city').value
    };
    
    const url = editingTownId 
        ? `/geographic-settings/towns/${editingTownId}`
        : '/geographic-settings/towns';
    const method = editingTownId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('town-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            townsTable.ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    const errorId = key.replace('_', '-') + '-error';
                    const inputId = key.replace('_', '-');
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

function deleteTown(id) {
    deleteSettingsRecord(
        id,
        `/geographic-settings/towns/:id`,
        'Are you sure you want to delete this town?',
        townsTable,
        'Are you sure?'
    );
}
</script>
