@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Calls Details Of HM + Admin</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="calls-details-form" novalidate>
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
                                <input type="text" class="form-control" id="caller_name" name="caller_name" placeholder="Caller Name" required>
                                <label for="caller_name" class="form-label">Caller Name</label>
                                <div class="invalid-tooltip">Caller Name is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" required>
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <div class="invalid-tooltip">Phone Number is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="time" class="form-control" id="start_time" name="start_time" placeholder="00:00:00" required>
                                <label for="start_time" class="form-label">Start Time</label>
                                <div class="invalid-tooltip">Start Time is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="time" class="form-control" id="end_time" name="end_time" placeholder="00:00:00" required>
                                <label for="end_time" class="form-label">End Time</label>
                                <div class="invalid-tooltip">End Time is required!</div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="call_purpose" name="call_purpose" placeholder="Call Purpose" required>
                                <label for="call_purpose" class="form-label">Call Purpose</label>
                                <div class="invalid-tooltip">Call Purpose is required!</div>
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
                <h4 class="card-title mb-0 flex-grow-1">Calls Details List</h4>
            </div>
            <div class="card-body">
                <table id="calls-details-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Branch</th>
                            <th>Caller Name</th>
                            <th>Phone Number</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Call Purpose</th>
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

            $('#calls-details-datatable').DataTable({
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
                ajax: "{{ route('calls-details.index') }}",
                columns: [
                    { data: 'id', name: 'id', width: "5%" },
                    { data: 'branch_name', name: 'branch_name' },
                    { data: 'caller_name', name: 'caller_name' },
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'start_time', name: 'start_time' },
                    { data: 'end_time', name: 'end_time' },
                    { data: 'call_purpose', name: 'call_purpose' },
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
            $('#calls-details-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var url = "{{ route('calls-details.store') }}";
                var method = 'POST';
                
                if ($('#calls-details-form').data('edit-id')) {
                    url = "{{ url('calls-details') }}/" + $('#calls-details-form').data('edit-id');
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
                        $('#calls-details-datatable').DataTable().ajax.reload();
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
                $.get("{{ url('calls-details') }}/" + id + "/edit", function(data) {
                    $('#branch_id').val(data.branch_id);
                    $('#caller_name').val(data.caller_name);
                    $('#phone_number').val(data.phone_number);
                    $('#start_time').val(data.start_time);
                    $('#end_time').val(data.end_time);
                    $('#call_purpose').val(data.call_purpose);
                    $('#calls-details-form').data('edit-id', id);
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
                            url: "{{ url('calls-details') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#calls-details-datatable').DataTable().ajax.reload();
                                Swal.fire('Deleted!', response.success, 'success');
                            }
                        });
                    }
                });
            });
        });

        function resetForm() {
            $('#calls-details-form')[0].reset();
            $('#calls-details-form').removeData('edit-id');
        }
    </script>
@endpush
