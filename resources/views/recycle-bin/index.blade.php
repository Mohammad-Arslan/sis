@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="ri-delete-bin-7-line me-2"></i> Recycle Bin
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Recycle Bin</li>
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
                <form id="recycle-bin-filter-form" class="row g-3">
                    <div class="col-md-3">
                        <label for="filter-model-type" class="form-label">Model Type</label>
                        <select class="form-select" id="filter-model-type" name="model_type">
                            <option value="">All Models</option>
                            @foreach($models as $modelClass => $modelName)
                                <option value="{{ $modelClass }}">{{ $modelName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter-date-from" class="form-label">Deleted From</label>
                        <input type="date" class="form-control" id="filter-date-from" name="date_from">
                    </div>
                    <div class="col-md-3">
                        <label for="filter-date-to" class="form-label">Deleted To</label>
                        <input type="date" class="form-control" id="filter-date-to" name="date_to">
                    </div>
                    <div class="col-md-3">
                        <label for="filter-search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="filter-search" name="search" placeholder="Search records...">
                    </div>
                    <div class="col-md-12 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-primary" onclick="applyFilters()">
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
                    <i class="ri-file-list-3-line me-1"></i> Deleted Records
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="recycle-bin-table" class="table table-bordered table-striped table-hover dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Model Type</th>
                                <th>Record Name</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
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
@endsection

@push('footer_scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    let recycleBinTable;

    $(document).ready(function() {
        // Initialize date pickers with constraints
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#filter-date-from", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        const dateToPicker = document.getElementById('filter-date-to');
                        if (dateToPicker && dateToPicker._flatpickr) {
                            dateToPicker._flatpickr.set('minDate', selectedDates[0]);
                        }
                    }
                }
            });

            flatpickr("#filter-date-to", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        const dateFromPicker = document.getElementById('filter-date-from');
                        if (dateFromPicker && dateFromPicker._flatpickr) {
                            dateFromPicker._flatpickr.set('maxDate', selectedDates[0]);
                        }
                    }
                }
            });
        }

        initializeRecycleBinTable();

        // Filter event handlers
        $('#recycle-bin-filter-form select').on('change', function() {
            applyFilters();
        });

        $('#filter-date-from, #filter-date-to').on('change', function() {
            applyFilters();
        });

        $('#filter-search').on('keyup', debounce(function() {
            applyFilters();
        }, 500));
    });

    function applyFilters() {
        if (recycleBinTable) {
            recycleBinTable.draw();
        }
    }

    function resetFilters() {
        $('#filter-model-type').val('').trigger('change');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        $('#filter-search').val('');
        
        if (typeof flatpickr !== 'undefined') {
            const dateFromPicker = document.getElementById('filter-date-from');
            const dateToPicker = document.getElementById('filter-date-to');
            if (dateFromPicker && dateFromPicker._flatpickr) {
                dateFromPicker._flatpickr.clear();
            }
            if (dateToPicker && dateToPicker._flatpickr) {
                dateToPicker._flatpickr.clear();
            }
        }
        
        applyFilters();
    }

    function initializeRecycleBinTable() {
        recycleBinTable = $('#recycle-bin-table').DataTable({
            processing: true,
            serverSide: false, // Using client-side processing for now
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url: "{{ route('recycle-bin.data') }}",
                type: 'GET',
                data: function(d) {
                    d.model_type = $('#filter-model-type').val();
                    d.date_from = $('#filter-date-from').val();
                    d.date_to = $('#filter-date-to').val();
                    d.search = {
                        value: $('#filter-search').val()
                    };
                }
            },
            columns: [
                { data: 'id', name: 'id', orderable: true },
                { data: 'model_display', name: 'model_display', orderable: true },
                { data: 'record_name', name: 'record_name', orderable: false },
                { data: 'deleted_at', name: 'deleted_at', orderable: true },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[3, 'desc']], // Sort by deleted_at descending
            language: {
                search: "",
                searchPlaceholder: "Search...",
                processing: "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading..."
            }
        });
    }

    // Debounce function
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    }
</script>
@endpush

