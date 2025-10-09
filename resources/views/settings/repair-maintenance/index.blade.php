@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Repair & Maintenance</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="repair-maintenance-form" novalidate>
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
                                <input type="text" class="form-control" id="nature_of_job" name="nature_of_job" placeholder="Nature of Job" required>
                                <label for="nature_of_job" class="form-label">Nature of Job</label>
                                <div class="invalid-tooltip">Nature of Job is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="locations" name="locations" placeholder="Locations" required>
                                <label for="locations" class="form-label">Locations</label>
                                <div class="invalid-tooltip">Locations is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="name_of_reported_dep" name="name_of_reported_dep" placeholder="Name of Reported Dep(HO)" required>
                                <label for="name_of_reported_dep" class="form-label">Name of Reported Dep(HO)</label>
                                <div class="invalid-tooltip">Name of Reported Dep(HO) is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks" required>
                                <label for="remarks" class="form-label">Remarks</label>
                                <div class="invalid-tooltip">Remarks is required!</div>
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
                <h4 class="card-title mb-0 flex-grow-1">Repair & Maintenance List</h4>
            </div>
            <div class="card-body">
                <table id="repair-maintenance-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Branch</th>
                            <th>Nature of Job</th>
                            <th>Locations</th>
                            <th>Name of Reported Dep(HO)</th>
                            <th>Remarks</th>
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

            $('#repair-maintenance-datatable').DataTable({
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
                ajax: "{{ route('repair-maintenance.index') }}",
                columns: [
                    { data: 'id', name: 'id', width: "5%" },
                    { data: 'branch_name', name: 'branch_name' },
                    { data: 'nature_of_job', name: 'nature_of_job' },
                    { data: 'locations', name: 'locations' },
                    { data: 'name_of_reported_dep', name: 'name_of_reported_dep' },
                    { data: 'remarks', name: 'remarks' },
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
            $('#repair-maintenance-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var url = "{{ route('repair-maintenance.store') }}";
                var method = 'POST';
                
                if ($('#repair-maintenance-form').data('edit-id')) {
                    url = "{{ url('repair-maintenance') }}/" + $('#repair-maintenance-form').data('edit-id');
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
                        $('#repair-maintenance-datatable').DataTable().ajax.reload();
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
                $.get("{{ url('repair-maintenance') }}/" + id + "/edit", function(data) {
                    $('#branch_id').val(data.branch_id);
                    $('#nature_of_job').val(data.nature_of_job);
                    $('#locations').val(data.locations);
                    $('#name_of_reported_dep').val(data.name_of_reported_dep);
                    $('#remarks').val(data.remarks);
                    $('#repair-maintenance-form').data('edit-id', id);
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
                            url: "{{ url('repair-maintenance') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#repair-maintenance-datatable').DataTable().ajax.reload();
                                Swal.fire('Deleted!', response.success, 'success');
                            }
                        });
                    }
                });
            });
        });

        function resetForm() {
            $('#repair-maintenance-form')[0].reset();
            $('#repair-maintenance-form').removeData('edit-id');
        }
    </script>
@endpush
