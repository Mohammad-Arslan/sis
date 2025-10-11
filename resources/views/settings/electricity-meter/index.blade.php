@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Daily Electricity Meter Reading</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="electricity-meter-form" novalidate>
                        <div class="col-md-4 col-sm-12 mt-4">
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
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="opening_day_reading" name="opening_day_reading" placeholder="Opening Day Reading" required>
                                <label for="opening_day_reading" class="form-label">Opening Day Reading</label>
                                <div class="invalid-tooltip">Opening Day Reading is required!</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="closing_day_reading" name="closing_day_reading" placeholder="Closing Day Reading">
                                <label for="closing_day_reading" class="form-label">Closing Day Reading</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="remark" name="remark" placeholder="Remark(if any)">
                                <label for="remark" class="form-label">Remark(if any)</label>
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
                <h4 class="card-title mb-0 flex-grow-1">Daily Electricity Meter Reading List</h4>
            </div>
            <div class="card-body">
                <table id="electricity-meter-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Branch</th>
                            <th>Opening Day Reading</th>
                            <th>Closing Day Reading</th>
                            <th>Remark</th>
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

            $('#electricity-meter-datatable').DataTable({
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
                ajax: "{{ route('electricity-meter.index') }}",
                columns: [
                    { data: 'id', name: 'id', width: "5%" },
                    { data: 'branch_name', name: 'branch_name' },
                    { data: 'opening_day_reading', name: 'opening_day_reading' },
                    { data: 'closing_day_reading', name: 'closing_day_reading' },
                    { data: 'remark', name: 'remark' },
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
            $('#electricity-meter-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var url = "{{ route('electricity-meter.store') }}";
                var method = 'POST';
                
                if ($('#electricity-meter-form').data('edit-id')) {
                    url = "{{ url('electricity-meter') }}/" + $('#electricity-meter-form').data('edit-id');
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
                        $('#electricity-meter-datatable').DataTable().ajax.reload();
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
                $.get("{{ url('electricity-meter') }}/" + id + "/edit", function(data) {
                    $('#branch_id').val(data.branch_id);
                    $('#opening_day_reading').val(data.opening_day_reading);
                    $('#closing_day_reading').val(data.closing_day_reading);
                    $('#remark').val(data.remark);
                    $('#electricity-meter-form').data('edit-id', id);
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
                            url: "{{ url('electricity-meter') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#electricity-meter-datatable').DataTable().ajax.reload();
                                Swal.fire('Deleted!', response.success, 'success');
                            }
                        });
                    }
                });
            });
        });

        function resetForm() {
            $('#electricity-meter-form')[0].reset();
            $('#electricity-meter-form').removeData('edit-id');
        }
    </script>
@endpush
