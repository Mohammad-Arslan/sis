@extends('layouts.master')
@section('content')
<div class="container-fluid py-4" style="background: #f8f9fb; min-height: 90vh;">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        <i class="ri-bank-line text-primary me-2"></i> Income Tax Slabs
                    </h4>
                    <div class="flex-shrink-0">
                        <button class="btn btn-primary px-4" id="addDefinitionBtn">
                            <i class="ri-add-line me-1"></i> Add New Slab
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="alert-area"></div>
                    <div class="table-responsive">
                        <table id="income-tax-slab-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fiscal Year</th>
                                    <th>Min Salary</th>
                                    <th>Max Salary</th>
                                    <th>Tax %</th>
                                    <th>Fixed Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Fiscal Year</th>
                                    <th>Min Salary</th>
                                    <th>Max Salary</th>
                                    <th>Tax %</th>
                                    <th>Fixed Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="incomeTaxSlabModal" tabindex="-1" role="dialog" aria-labelledby="incomeTaxSlabModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content rounded-3">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold" id="incomeTaxSlabModalLabel">
            <i class="ri-bank-line text-primary me-2"></i> Income Tax Slab
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-2" id="modalFormBody">
        <!-- Form will be loaded here via AJAX -->
      </div>
    </div>
  </div>
</div>

@push('footer_scripts')
<script type="text/javascript">
$(document).ready(function() {
    // DataTable initialization
    $('#income-tax-slab-table').DataTable({
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
        ajax: "{{ route('settings.income-tax-slabs.index') }}",
        columns: [
            { data: 'id', name: 'id', width: "5%" },
            { data: 'fiscal_year', name: 'fiscal_year' },
            { data: 'min_salary', name: 'min_salary' },
            { data: 'max_salary', name: 'max_salary' },
            { data: 'tax_percent', name: 'tax_percent' },
            { data: 'fixed_amount', name: 'fixed_amount' },
            { data: 'status', name: 'status', width: "10%", orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: "15%", sClass: "text-center" },
        ]
    });

    // Open modal for create
    $('#addDefinitionBtn').click(function() {
        $.get("{{ route('settings.income-tax-slabs.create') }}", function(data) {
            $('#modalFormBody').html(data);
            $('#incomeTaxSlabModal').modal('show');
        });
    });

    // Open modal for edit (delegated)
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get(`/settings/income-tax-slabs/${id}/edit`, function(data) {
            $('#modalFormBody').html(data);
            $('#incomeTaxSlabModal').modal('show');
        });
    });

    // Submit form (create/update)
    $('#incomeTaxSlabModal').on('submit', '#incomeTaxSlabForm', function(e) {
        e.preventDefault();
        var form = $(this);
        var id = form.find('input[name="id"]').val();
        var method = id ? 'PATCH' : 'POST';
        var url = id ? `/settings/income-tax-slabs/${id}` : `{{ route('settings.income-tax-slabs.store') }}`;
        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),
            success: function(res) {
                $('#incomeTaxSlabModal').modal('hide');
                showAlert('success', 'Saved successfully!');
                $('#income-tax-slab-table').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger">';
                $.each(errors, function(key, val) { errorHtml += val[0] + '<br>'; });
                errorHtml += '</div>';
                $('#modalFormBody').find('.form-errors').html(errorHtml);
            }
        });
    });

    // Delete (delegated, with SweetAlert)
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = `/settings/income-tax-slabs/${id}`;
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>Are you sure?</h4>' +
                '<p class="mx-4 mb-0 text-muted">Are you Sure You want to Delete this Record ?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    success: function(res) {
                        $('#income-tax-slab-table').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success !</h4>' +
                                '<p class="text-muted mx-4 mb-0">Record has been successfully deleted.</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    },
                    error: function() {
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Error !</h4>' +
                                '<p class="text-muted mx-4 mb-0">An error occurred while deleting the record.</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    }
                });
            }
        });
    });

    // Show alert
    function showAlert(type, msg) {
        $('#alert-area').html(`<div class="alert alert-${type} mb-3">${msg}</div>`);
        setTimeout(() => { $('#alert-area').html(''); }, 2000);
    }
});
</script>
@endpush
@endsection 