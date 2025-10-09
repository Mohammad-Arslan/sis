@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Daily Generator Information</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="generator-info-form" novalidate>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <select class="form-select" id="branch_id" name="branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                                <div class="invalid-tooltip">Branch is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="date" class="form-control" id="date_refueling" name="date_refueling" required>
                                <label for="date_refueling" class="form-label">Date (Re-Fueling)</label>
                                <div class="invalid-tooltip">Date (Re-Fueling) is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="number" step="0.01" class="form-control" id="quantity_liter" name="quantity_liter" placeholder="Quantity(Liter)" required>
                                <label for="quantity_liter" class="form-label">Quantity(Liter)</label>
                                <div class="invalid-tooltip">Quantity(Liter) is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="verified_by" name="verified_by" placeholder="Verified By" required>
                                <label for="verified_by" class="form-label">Verified By</label>
                                <div class="invalid-tooltip">Verified By is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="generator_capacity" name="generator_capacity" placeholder="100 KVA" required>
                                <label for="generator_capacity" class="form-label">Generator Capacity</label>
                                <div class="invalid-tooltip">Generator Capacity is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="time" class="form-control" id="starting_time" name="starting_time" placeholder="Starting Time">
                                <label for="starting_time" class="form-label">Starting Time</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="time" class="form-control" id="end_time" name="end_time" placeholder="End Time">
                                <label for="end_time" class="form-label">End Time</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="reading" name="reading" placeholder="Reading">
                                <label for="reading" class="form-label">Reading</label>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button type="button" class="btn btn-light bg-gradient waves-effect waves-light" onclick="resetForm()">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Daily Generator Information List</h4>
            </div>
            <div class="card-body">
                <table id="generator-info-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Branch</th>
                            <th>Date (Re-Fueling)</th>
                            <th>Quantity(Liter)</th>
                            <th>Verified By</th>
                            <th>Generator Capacity</th>
                            <th>Starting Time</th>
                            <th>End Time</th>
                            <th>Reading</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#generator-info-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('generator-info.index') }}",
                columns: [
                    { data: 'id', name: 'id', width: "5%" },
                    { data: 'branch_name', name: 'branch_name' },
                    { 
                        data: 'date_refueling', 
                        name: 'date_refueling',
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                return date.toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: '2-digit'
                                });
                            }
                            return '';
                        }
                    },
                    { data: 'quantity_liter', name: 'quantity_liter' },
                    { data: 'verified_by', name: 'verified_by' },
                    { data: 'generator_capacity', name: 'generator_capacity' },
                    { data: 'starting_time', name: 'starting_time' },
                    { data: 'end_time', name: 'end_time' },
                    { data: 'reading', name: 'reading' },
                    { 
                        data: 'created_at', 
                        name: 'created_at', 
                        width: "15%",
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                return date.toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: '2-digit',
                                    hour: 'numeric',
                                    minute: '2-digit',
                                    hour12: true
                                });
                            }
                            return '';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Form submission
            $('#generator-info-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var url = "{{ route('generator-info.store') }}";
                var method = 'POST';
                
                if ($('#generator-info-form').data('edit-id')) {
                    url = "{{ url('generator-info') }}/" + $('#generator-info-form').data('edit-id');
                    method = 'PUT';
                }

                // Add CSRF token
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                // Add method override for PUT requests
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: 'POST', // Always use POST for Laravel method spoofing
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#generator-info-datatable').DataTable().ajax.reload();
                        resetForm();
                        Swal.fire('Success!', response.success, 'success');
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        }
                    }
                });
            });

            // Edit functionality
            $(document).on('click', '.edit', function() {
                var id = $(this).data('id');
                $.get("{{ url('generator-info') }}/" + id + "/edit", function(data) {
                    $('#branch_id').val(data.branch_id);
                    // Convert date to YYYY-MM-DD format for HTML date input
                    if (data.date_refueling) {
                        var date = new Date(data.date_refueling);
                        var formattedDate = date.getFullYear() + '-' + 
                            String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                            String(date.getDate()).padStart(2, '0');
                        $('#date_refueling').val(formattedDate);
                    }
                    $('#quantity_liter').val(data.quantity_liter);
                    $('#verified_by').val(data.verified_by);
                    $('#generator_capacity').val(data.generator_capacity);
                    $('#starting_time').val(data.starting_time);
                    $('#end_time').val(data.end_time);
                    $('#reading').val(data.reading);
                    $('#generator-info-form').data('edit-id', id);
                });
            });

            // Delete functionality
            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('generator-info') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#generator-info-datatable').DataTable().ajax.reload();
                                Swal.fire('Deleted!', response.success, 'success');
                            }
                        });
                    }
                });
            });
        });

        function resetForm() {
            $('#generator-info-form')[0].reset();
            $('#generator-info-form').removeData('edit-id');
        }
    </script>
@endpush
