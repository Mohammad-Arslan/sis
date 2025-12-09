<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Cities</h4>
        <div class="flex-shrink-0">
            <button onclick="openCityModal()" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add City
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3 d-flex gap-3">
            <select id="city-country-filter" class="form-select" style="max-width: 300px;" onchange="loadCityStates()">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                @endforeach
            </select>
            <select id="city-state-filter" class="form-select" style="max-width: 300px;" onchange="filterCities()">
                <option value="">All States</option>
            </select>
        </div>

        <div class="table-responsive">
            <table id="cities-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>City Name</th>
                        <th>Abbreviation</th>
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

<!-- City Modal -->
<div class="modal fade" id="city-modal" tabindex="-1" aria-labelledby="city-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="city-modal-title">Add City</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="city-form">
                <div class="modal-body">
                    <input type="hidden" id="city-id" name="id">
                    
                    <div class="mb-3">
                        <label for="city-country" class="form-label">Country <span class="text-danger">*</span></label>
                        <select id="city-country" name="country_id" class="form-select" onchange="loadCityStatesForForm()" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="city-state" class="form-label">State <span class="text-danger">*</span></label>
                        <select id="city-state" name="state_id" class="form-select" required>
                            <option value="">Select State</option>
                        </select>
                        <div class="invalid-feedback" id="city-state-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="city-name" class="form-label">City Name <span class="text-danger">*</span></label>
                        <input type="text" id="city-name" name="city_name" class="form-control" required>
                        <div class="invalid-feedback" id="city-name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="city-abbreviation" class="form-label">Abbreviation <span class="text-danger">*</span></label>
                        <input type="text" id="city-abbreviation" name="abbreviation" class="form-control" required>
                        <div class="invalid-feedback" id="city-abbreviation-error"></div>
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
let citiesTable;
let editingCityId = null;

$(document).ready(function() {
    citiesTable = $('#cities-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('geographic-settings.get-cities') }}",
            type: 'GET',
            data: function(d) {
                d.country_id = $('#city-country-filter').val();
                d.state_id = $('#city-state-filter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '5%' },
            { data: 'city_name', name: 'city_name' },
            { data: 'abbreviation', name: 'abbreviation', width: '10%' },
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

function loadCityStates() {
    const countryId = $('#city-country-filter').val();
    $('#city-state-filter').html('<option value="">All States</option>');
    
    if (countryId) {
        $.get(`/geographic-settings/states-by-country?country_id=${countryId}`)
            .done(function(states) {
                states.forEach(state => {
                    $('#city-state-filter').append(`<option value="${state.id}">${state.state_name}</option>`);
                });
            });
    }
    filterCities();
}

function loadCityStatesForForm() {
    const countryId = $('#city-country').val();
    $('#city-state').html('<option value="">Select State</option>');
    
    if (countryId) {
        $.get(`/geographic-settings/states-by-country?country_id=${countryId}`)
            .done(function(states) {
                states.forEach(state => {
                    $('#city-state').append(`<option value="${state.id}">${state.state_name}</option>`);
                });
            });
    }
}

function filterCities() {
    citiesTable.ajax.reload();
}

function openCityModal(id = null) {
    editingCityId = id;
    const modalElement = document.getElementById('city-modal');
    const modal = new bootstrap.Modal(modalElement);
    const title = document.getElementById('city-modal-title');
    
    document.getElementById('city-form').reset();
    document.getElementById('city-id').value = '';
    clearCityErrors();
    
    if (id) {
        title.textContent = 'Edit City';
        fetch(`/geographic-settings/cities/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('city-id').value = data.id;
                document.getElementById('city-name').value = data.city_name;
                document.getElementById('city-abbreviation').value = data.abbreviation;
                $('#city-country').val(data.states?.country_id || '').trigger('change');
                setTimeout(() => {
                    $('#city-state').val(data.state_id);
                }, 500);
            });
    } else {
        title.textContent = 'Add City';
    }
    
    modal.show();
}

function closeCityModal() {
    const modalElement = document.getElementById('city-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) modal.hide();
    editingCityId = null;
    clearCityErrors();
}

function clearCityErrors() {
    ['city-name', 'city-abbreviation', 'city-state'].forEach(id => {
        const errorElement = document.getElementById(id + '-error');
        const inputElement = document.getElementById(id);
        if (errorElement) errorElement.textContent = '';
        if (inputElement) {
            inputElement.classList.remove('is-invalid');
        }
    });
}

$('#city-form').on('submit', function(e) {
    e.preventDefault();
    clearCityErrors();
    
    const formData = {
        city_name: document.getElementById('city-name').value,
        abbreviation: document.getElementById('city-abbreviation').value,
        state_id: document.getElementById('city-state').value
    };
    
    const url = editingCityId 
        ? `/geographic-settings/cities/${editingCityId}`
        : '/geographic-settings/cities';
    const method = editingCityId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            showToast(response.message, 'success');
            const modalElement = document.getElementById('city-modal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            citiesTable.ajax.reload();
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

function deleteCity(id) {
    deleteSettingsRecord(
        id,
        `/geographic-settings/cities/:id`,
        'Are you sure you want to delete this city?',
        citiesTable,
        'Are you sure?'
    );
}
</script>
