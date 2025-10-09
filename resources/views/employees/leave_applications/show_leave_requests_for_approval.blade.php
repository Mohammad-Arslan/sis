@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">For Approval Leave Requests</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="leave_requests_table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Application&nbspDate</th>
                                <th>Application&nbspType</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $key => $leaveRequest)
                                <tr>
                                    <td>{{ get_user_detail($leaveRequest->employee->user_id) }}</td>
                                    <td>{{ date('d-M-Y',strtotime($leaveRequest->application_date)) ?? '' }}</td>
                                    <td>{{ $leaveRequest->leaveApplicationType->name ?? '' }}</td>
                                    <td>
                                        @if($leaveRequest->status == '1')
                                        <span class="badge bg-success">Approved</span>
                                        @elseif($leaveRequest->status == '2')
                                            <span class="badge bg-danger">Denied</span>
                                        @elseif($leaveRequest->status == '3')
                                            <span class="badge bg-dark">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ date('d-M-Y, h:i:s',strtotime($leaveRequest->created_at)) ?? '' }}</td>
                                    <td class=" text-center">
                                        <button type="button" title="View Details" class="btn btn-sm btn-info btn-icon waves-effect waves-light application-details" data-application-id="{{ $leaveRequest->id }}" data-route="{{ route('leave.application.details') }}"><i class="mdi mdi-account"></i></button>
                                        @if($leaveRequest->status == 0)
                                            <button type="button" title="Denie" class="btn btn-sm btn-danger btn-icon waves-effect waves-light application-status-change" data-status="2" data-application-id="{{ $leaveRequest->id }}" data-route="{{ route('leave.application.status-update') }}"><i class="mdi mdi-close"></i></button>
                                            <button type="button" title="Approve" class="btn btn-sm btn-success btn-icon waves-effect waves-light application-status-approve" data-status="1" data-application-id="{{ $leaveRequest->id }}" data-route="{{ route('leave.application.status-approve') }}"><i class="mdi mdi-book-check-outline"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Employee</th>
                                <th>Application&nbspDate</th>
                                <th>Application&nbspType</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="leaveApplicationDetailModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

        <div class="application-details-modal-area">

        </div>
    </div><!-- /.modal -->
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#leave_requests_table').DataTable();
        });

        /** show application details in modal popup*/
        $(document).on('click', '.application-details', function (e) {
            e.preventDefault();

            let url = $(this).attr('data-route');
            let application_id = $(this).attr('data-application-id');

            $.ajax({
                type : 'POST',
                url : url,
                data : {
                    "_token" : "{{ csrf_token() }}",
                    application_id
                },
                success:function (response) {
                    $('.application-details-modal-area').html(response);
                    $('#leaveApplicationDetailModal').modal('show');
                }
            })
        });

        /** change status of leave application*/
        $(document).on('click', '.application-status-change', function (e) {
            e.preventDefault();
            let url = $(this).attr('data-route');
            let application_id = $(this).attr('data-application-id');
            let status = $(this).attr('data-status');

            Swal.fire({
                icon: 'question',
                title: 'Do you want to perform this action?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            application_id,
                            status
                        },
                        success:function (response) {
                            Swal.fire('Done!', '', 'success')
                            location.reload();
                        }
                    })
                }
            })


        })

        /** approve status of leave application*/
        $(document).on('click', '.application-status-approve', function (e) {
            e.preventDefault();
            let url = $(this).attr('data-route');
            let application_id = $(this).attr('data-application-id');
            let status = $(this).attr('data-status');

            Swal.fire({
                icon: 'question',
                title: 'Do you want to approve this request?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'POST',
                        url : url,
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            application_id,
                            status
                        },
                        success:function (response) {
                            Swal.fire('Done!', '', 'success')
                            location.reload();
                        }
                    })
                }
            })


        })

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
