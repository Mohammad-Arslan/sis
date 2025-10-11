@extends('layouts.master')
@section('content')
@include('components.flash_message')

<div class="row">
    <div class="col-lg-12">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Search Applied Leaves List </h4>
            @role('super_admin')
            <div class="flex-shrink-0">
                <a href="{{ route('employees.create') }}?tab=basic_info" class="btn btn-success btn-label btn-sm">
                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back
                </a>
            </div>
            @endrole
        </div>
        <div class="card">
            <div class="card-body">
                <table id="application-listing-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                    <thead>
                    <tr>
                        <th>Application&nbspDate</th>
                        <th>Application&nbspType</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->leaveApplications as $leaveApplication)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($leaveApplication->application_date)->format('d-m-Y') }}</td>
                                <td>{{ $leaveApplication->leaveApplicationType->name }}</td>
                                <td>
                                    @if($leaveApplication->status == '1')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leaveApplication->status == '2')
                                        <span class="badge bg-danger">Denied</span>
                                    @elseif($leaveApplication->status == '3')
                                        <span class="badge bg-dark">Cancelled</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{$leaveApplication->created_at}}</td>
                                <td class=" text-center">
                                    <button type="button" class="btn btn-sm btn-info btn-icon waves-effect waves-light application-details" data-application-id="{{ $leaveApplication->id }}" data-route="{{ route('leave.application.details') }}"><i class="mdi mdi-account"></i></button>
                                    @if($leaveApplication->status == 0 && $employee->user_id == auth()->user()->id)
                                        <button type="button" class="btn btn-sm btn-danger btn-icon waves-effect waves-light application-status-change" data-status="3" data-application-id="{{ $leaveApplication->id }}" data-route="{{ route('leave.application.status-update') }}"><i class="mdi mdi-close"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
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

@push('footer_scripts')
    <script>
        $(document).ready(function () {
            $('#application-listing-table').dataTable()
        })

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
    </script>
@endpush
