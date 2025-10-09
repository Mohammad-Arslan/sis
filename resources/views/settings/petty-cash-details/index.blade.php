@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Petty Cash Details</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" id="petty-cash-details-form" novalidate>
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
                                <input type="number" step="0.01" class="form-control" id="opening_balance" name="opening_balance" placeholder="0.00" required>
                                <label for="opening_balance" class="form-label">Opening Balance</label>
                                <div class="invalid-tooltip">Opening Balance is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="number" step="0.01" class="form-control" id="amount_received" name="amount_received" placeholder="Amount Received" required>
                                <label for="amount_received" class="form-label">Amount Received</label>
                                <div class="invalid-tooltip">Amount Received is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="number" step="0.01" class="form-control" id="expense" name="expense" placeholder="0.00" required>
                                <label for="expense" class="form-label">Expense</label>
                                <div class="invalid-tooltip">Expense is required!</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="number" step="0.01" class="form-control" id="closing_balance" name="closing_balance" placeholder="0.00" required>
                                <label for="closing_balance" class="form-label">Closing Balance</label>
                                <div class="invalid-tooltip">Closing Balance is required!</div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="details_purpose" name="details_purpose" placeholder="Details/Purpose" required>
                                <label for="details_purpose" class="form-label">Details/Purpose</label>
                                <div class="invalid-tooltip">Details/Purpose is required!</div>
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
                <h4 class="card-title mb-0 flex-grow-1">Petty Cash Details List</h4>
            </div>
            <div class="card-body">
                <table id="petty-cash-details-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Branch</th>
                            <th>Opening Balance</th>
                            <th>Amount Received</th>
                            <th>Expense</th>
                            <th>Closing Balance</th>
                            <th>Details/Purpose</th>
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

            $('#petty-cash-details-datatable').DataTable({
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
                ajax: "{{ route('petty-cash-details.index') }}",
                columns: [
                    { data: 'id', name: 'id', width: "5%" },
                    { data: 'branch_name', name: 'branch_name' },
                    { data: 'opening_balance', name: 'opening_balance' },
                    { data: 'amount_received', name: 'amount_received' },
                    { data: 'expense', name: 'expense' },
                    { data: 'closing_balance', name: 'closing_balance' },
                    { data: 'details_purpose', name: 'details_purpose' },
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
            $('#petty-cash-details-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var url = "{{ route('petty-cash-details.store') }}";
                var method = 'POST';
                
                if ($('#petty-cash-details-form').data('edit-id')) {
                    url = "{{ url('petty-cash-details') }}/" + $('#petty-cash-details-form').data('edit-id');
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
                        $('#petty-cash-details-datatable').DataTable().ajax.reload();
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
                $.get("{{ url('petty-cash-details') }}/" + id + "/edit", function(data) {
                    $('#branch_id').val(data.branch_id);
                    $('#opening_balance').val(data.opening_balance);
                    $('#amount_received').val(data.amount_received);
                    $('#expense').val(data.expense);
                    $('#closing_balance').val(data.closing_balance);
                    $('#details_purpose').val(data.details_purpose);
                    $('#petty-cash-details-form').data('edit-id', id);
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
                            url: "{{ url('petty-cash-details') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#petty-cash-details-datatable').DataTable().ajax.reload();
                                Swal.fire('Deleted!', response.success, 'success');
                            }
                        });
                    }
                });
            });
        });

        function resetForm() {
            $('#petty-cash-details-form')[0].reset();
            $('#petty-cash-details-form').removeData('edit-id');
        }
    </script>
@endpush
