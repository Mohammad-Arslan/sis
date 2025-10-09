<table id="bank-account-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Bank Name</th>
        <th>Branch Code</th>
        <th>Branch Address</th>
        <th>Account Title</th>
        <th>Account Number</th>
        <th>IBAN</th>
        <th>Is Default</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
    <tr>
        <th>ID</th>
        <th>Bank Name</th>
        <th>Branch Code</th>
        <th>Branch Address</th>
        <th>Account Title</th>
        <th>Account Number</th>
        <th>IBAN</th>
        <th>Is Default</th>
        <th>Action</th>
    </tr>
    </tfoot>
</table>

@push('footer_scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#bank-account-data-table').DataTable({
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
                ajax: "{{ route('bank-accounts.index', $route_parameters) }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'bank_name',
                        name: 'bank_name'
                    },
                    {
                        data: 'branch_code',
                        name: 'branch_code'
                    },
                    {
                        data: 'branch_address',
                        name: 'branch_address'
                    },
                    {
                        data: 'account_title',
                        name: 'account_title'
                    },
                    {
                        data: 'account_no',
                        name: 'account_no'
                    },
                    {
                        data: 'IBAN',
                        name: 'IBAN'
                    },
                    {
                        data: 'is_default',
                        name: 'is_default'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            $(document).on('click', '.set-is-default', function(e) {
                e.preventDefault();

                var row_id = $(this).val();
                Swal.fire({
                    html: '<div class="mt-3">' +
                        /*'<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +*/
                        '<div class="mt-4 pt-2 fs-15 mx-5">' +
                        '<h4>Are you sure?</h4>' +
                        '<p class="text-muted mx-4 mb-0">Are you Sure You want to make this as Default Bank ?</p>' +
                        '</div>' +
                        '</div>',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, Make It Default!',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {

                    if (result.isConfirmed) {

                        //get index route and concate the id and send it on put request to make the update route
                        let route = '{{route('make-bank-default')}}';

                        $.ajax({
                            url:route,
                            type: "POST",
                            data: {
                                bank_account_id: row_id
                            },
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            cache: false,
                            success: function(data) {
                                $('#bank-account-data-table').DataTable().ajax.reload(null, false);
                            },
                            error: function() {

                            },
                            beforeSend: function() {

                            },
                            complete: function() {

                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
