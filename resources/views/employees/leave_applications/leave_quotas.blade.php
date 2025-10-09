@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Assigned Leave Quotas List</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="leave_quotas_table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Leave&nbsp;Type</th>
                                <th>Leave Allowed</th>
                                <th>Leave Acquired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveQuotas as $key => $leaveQuota)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $leaveQuota->leaveType->name ?? '' }}</td>
                                    <td>{{ $leaveQuota->no_of_allowed_leaves ?? '' }}</td>
                                    <td>{{ $leaveQuota->no_of_balanced_leaves ?? '' }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Leave&nbsp;Type</th>
                                <th>Leave Allowed</th>
                                <th>Leave Acquired</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#leave_quotas_table').DataTable();
        });

        /** function to delete leave type*/
        $(document).on('click', '.delete-leave-quota', function() {
            let id = $(this).attr('data-id');
            let url = $(this).attr('data-route');

            Swal.fire({
                html: '<div class="mt-3">' +
                    '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                    '<div class="mt-4 pt-2 fs-15 mx-5">' +
                    '<h4>Are you sure?</h4>' +
                    '<p class="text-muted mx-4 mb-0">Are you Sure You want to Delete this Record ?</p>' +
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
                        type: 'POST',
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id
                        },
                        success: function() {
                            Swal.fire('Done!', '', 'success');
                            location.reload();
                        }
                    })
                }
            })
        })
    </script>
@endpush
