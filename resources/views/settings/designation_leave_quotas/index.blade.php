@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">

        @if (isset($leaveQuota))
            @include('settings.designation_leave_quotas.edit_leave_quota')
        @else
            @permission('add-designation-leave-quota')
                @include('settings.designation_leave_quotas.add_leave_quota')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Designation Leave Quotas List</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        @role('super_admin')
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="desig_id" name="desig_id" placeholder="Designation">
                                    <option value="">Please select</option>
                                    @forelse(designations() as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <label for="desig_id" class="form-label">Designation</label>
                            </div>
                        </div>
                        @endrole
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="leavetype_id" name="leavetype_id" placeholder="Leave Type">
                                    <option value="">Please select</option>
                                    @forelse(leaveTypes() as $leaveType)
                                        <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <label for="leavetype_id" class="form-label">Leave Type</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="leave_quotas_table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Designation&nbsp;Name</th>
                                <th>Leave&nbsp;Type</th>
                                <th>Leave Allowed</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Designation&nbsp;Name</th>
                                <th>Leave&nbsp;Type</th>
                                <th>Leave Allowed</th>
                                <th>Created At</th>
                                <th>Action</th>
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
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#leave_quotas_table').dataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
            language: {
                search: "",
                processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                searchPlaceholder: "Search..."
            },
            ajax: {
                    url: "{{ route('designationLeaveQuota.index') }}",
                    data: function(d) {
                        d.desig_id = $('#desig_id').val();
                        d.leavetype_id = $('#leavetype_id').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'designation_name',
                    name: 'designation_name',
                    width: "5%"
                },
                {
                    data: 'leave_type',
                    name: 'leave_type'
                },
                {
                    data: 'no_of_allowed_leaves',
                    name: 'no_of_allowed_leaves'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    width: "15%"
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "5%",
                    sClass: 'text-center'
                }
            ]
        });

        });

        $(document).on('change', '.filter', function() {
            $('#leave_quotas_table').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#leave_quotas_table').DataTable().ajax.reload(null, false).page('first');
            }
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
