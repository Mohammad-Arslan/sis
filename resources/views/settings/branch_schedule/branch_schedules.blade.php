@extends('layouts.master')
@section('content')
@include('components.flash_message')

    <div class="row">

        @if (isset($branchWorkingShift))
            @include('settings.branch_schedule.edit_branch_working_schedule')
        @else
            @permission('add-working-shift')
                @include('settings.branch_schedule.add_branch_working_schedule')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Branch Schedule List</h4>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-md btn-success btn-icon waves-effect waves-light show-modal" data-url="{{route('view-branch-schedule')}}" data-target="#branchScheduleModal"><i class="mdi mdi-calendar-account-outline"></i></button>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <table id="branch-schedule-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Term</th>
                                <th>Staff</th>
                                <th>Shift Day</th>
                                <th>Shift Timing</th>
                                <th>Schedule Type</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Term</th>
                                <th>Staff</th>
                                <th>Shift Day</th>
                                <th>Shift Timing</th>
                                <th>Schedule Type</th>
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

            $("#working_day_id").select2( {
                theme: "bootstrap-5",
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                placeholder: $( this ).data( 'placeholder' ),
                closeOnSelect: false,
            } );

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });
            $('#branch-schedule-data-table').DataTable({
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
                ajax: "{{ route('branch-working-shift.index') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'staff.type_name',
                        name: 'staff.type_name'
                    },
                    {
                        data: 'day.name',
                        name: 'day.name'
                    },
                    {
                        data: 'shift_timing',
                        name: 'shift_timing'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                        sClass: "text-center"
                    },
                ]
            });


        });


    </script>
@endpush
