@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Activity Logs</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Activity Logs</li>
                </ol>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-filter-3-line me-1"></i> Filters
                </h5>
            </div>
            <div class="card-body">
                <form id="activity-logs-filter-form" class="row g-3">
                    <div class="col-md-3">
                        <label for="filter-user" class="form-label">User</label>
                        <select class="form-select" id="filter-user" name="user_id">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter-event" class="form-label">Event Type</label>
                        <select class="form-select" id="filter-event" name="event">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event }}">{{ ucfirst(str_replace('_', ' ', $event)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter-model-type" class="form-label">Model Type</label>
                        <select class="form-select" id="filter-model-type" name="model_type">
                            <option value="">All Models</option>
                            @foreach($modelTypes as $modelType)
                                @php
                                    $parts = explode('\\', $modelType);
                                    $displayName = end($parts);
                                @endphp
                                <option value="{{ $modelType }}">{{ $displayName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter-date-from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="filter-date-from" name="date_from">
                    </div>
                    <div class="col-md-3">
                        <label for="filter-date-to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="filter-date-to" name="date_to">
                    </div>
                    <div class="col-md-3">
                        <label for="filter-search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="filter-search" name="search" placeholder="Search...">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary me-2" onclick="applyFilters()">
                            <i class="ri-search-line me-1"></i> Apply Filters
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                            <i class="ri-refresh-line me-1"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DataTable Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-file-list-3-line me-1"></i> Activity Logs
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="activity-logs-table" class="table table-bordered table-striped table-hover dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Model</th>
                                <th>Old Values</th>
                                <th>New Values</th>
                                <th>IP Address</th>
                                <th>URL</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JSON View Modal -->
<div class="modal fade" id="json-view-modal" tabindex="-1" aria-labelledby="json-view-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="json-view-modal-label">View JSON Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Event:</strong> <span id="json-modal-event"></span><br>
                    <strong>Model:</strong> <span id="json-modal-model"></span>
                </div>
                <pre id="json-modal-content" class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyJsonToClipboard()">
                    <i class="ri-file-copy-line me-1"></i> Copy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
@endsection

@push('footer_scripts')
<script>
let activityLogsTable;

$(document).ready(function() {
    initializeActivityLogsTable();
    
    $('#activity-logs-filter-form input, #activity-logs-filter-form select').on('change', function() {
        if ($(this).attr('id') !== 'filter-search') {
            applyFilters();
        }
    });
    
    $('#filter-search').on('keyup', debounce(function() {
        applyFilters();
    }, 500));
});

function initializeActivityLogsTable() {
    activityLogsTable = $('#activity-logs-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        ajax: {
            url: "{{ route('activity-logs.data') }}",
            type: 'GET',
            data: function(d) {
                d.user_id = $('#filter-user').val();
                d.event = $('#filter-event').val();
                d.model_type = $('#filter-model-type').val();
                d.date_from = $('#filter-date-from').val();
                d.date_to = $('#filter-date-to').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', orderable: true },
            { data: 'user_name', name: 'user_name', orderable: false },
            { data: 'event_badge', name: 'event', orderable: true },
            { data: 'model_display', name: 'model_type', orderable: true },
            { data: 'old_values_btn', name: 'old_values_btn', orderable: false, searchable: false },
            { data: 'new_values_btn', name: 'new_values_btn', orderable: false, searchable: false },
            { data: 'ip_address', name: 'ip_address', orderable: true },
            { 
                data: 'url', 
                name: 'url', 
                orderable: false,
                render: function(data, type, row) {
                    if (data && data.length > 50) {
                        return '<span title="' + data + '">' + data.substring(0, 50) + '...</span>';
                    }
                    return data || '-';
                }
            },
            { data: 'created_at_formatted', name: 'created_at', orderable: true }
        ],
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            processing: "<span class='spinner-border spinner-border-sm me-2' role='status' aria-hidden='true'></span> Processing...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        drawCallback: function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}

function applyFilters() {
    if (activityLogsTable) {
        activityLogsTable.ajax.reload();
    }
}

function resetFilters() {
    $('#activity-logs-filter-form')[0].reset();
    if (activityLogsTable) {
        activityLogsTable.ajax.reload();
    }
}

function viewJsonData(id, type) {
    const url = "{{ route('activity-logs.json-data', ['id' => ':id', 'type' => ':type']) }}"
        .replace(':id', id)
        .replace(':type', type);
    
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const jsonContent = JSON.stringify(response.data, null, 2);
                $('#json-modal-content').text(jsonContent);
                
                const modelParts = response.model_type ? response.model_type.split('\\') : [];
                const modelName = modelParts.length > 0 ? modelParts[modelParts.length - 1] : '-';
                
                $('#json-modal-event').text(response.event ? response.event.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : '-');
                $('#json-modal-model').text(modelName);
                $('#json-view-modal-label').text('View ' + (type === 'old' ? 'Old' : 'New') + ' Values');
                
                const modal = new bootstrap.Modal(document.getElementById('json-view-modal'));
                modal.show();
            } else {
                showToast('Failed to load JSON data', 'error');
            }
        },
        error: function(xhr) {
            handleAjaxError(xhr);
        }
    });
}

function copyJsonToClipboard() {
    const jsonContent = $('#json-modal-content').text();
    navigator.clipboard.writeText(jsonContent).then(function() {
        showToast('JSON data copied to clipboard', 'success');
    }, function() {
        showToast('Failed to copy to clipboard', 'error');
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endpush

