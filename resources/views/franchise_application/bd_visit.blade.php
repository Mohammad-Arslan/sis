@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('franchise-applications.index')}}">Franchise Application List</a></li>
        <li class="breadcrumb-item active">{{isset($franchise_application_bd_visit) ? 'Edit' : 'Create'}} BD Visit</li>
    </x-breadcrumb>
    @include('components.flash_message')
@if (isset($franchise_application_bd_visit))
@include('franchise_application.bd_visit_form_edit')
@else
    @permission('add-franchise-application-bd')
        @include('franchise_application.bd_visit_form')
    @endpermission
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">BD Visit Application List</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">
            	<table id="bdVisitList" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
			        <thead>
			            <tr>
			                <th>ID</th>
			                <th>Visit Date</th>
			                <th>BD Representative</th>
			                <th>BD Status</th>
                            <th>Approved By</th>
			                <th>Site Address</th>
			                <th>Site Purpose</th>
			                <th>Forward To</th>
			                <th>Remarks</th>
                            <th>Created At</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tbody>
			        </tbody>
			        <tfoot>
			            <tr>
			                <th>ID</th>
			                <th>Visit Date</th>
			                <th>BD Representative</th>
			                <th>BD Status</th>
                            <th>Approved By</th>
			                <th>Site Address</th>
			                <th>Site Purpose</th>
			                <th>Forward To</th>
			                <th>Remarks</th>
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

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#bdVisitList').DataTable({
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
                ajax: "{{ route('franchise-application-bd.index', [ 'franchise_application' => $franchise_application['id']]) }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'visit_date',
                        name: 'visit_date'
                    },
                    {
                        data: 'visit_by.user.name',
                        name: 'visit_by.user.name'
                    },
                    {
                        data: 'bd_status',
                        name: 'bd_status'
                    },
                    {
                        data: 'approved_by.user.name',
                        name: 'approved_by.user.name'
                    },
                    {
                        data: 'site_address',
                        name: 'site_address'
                    },
                    {
                        data: 'site_purpose',
                        name: 'site_purpose'
                    },
                    {
                        data: 'forward_to.user.name',
                        name: 'forward_to.user.name'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
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
                        width: "5%"
                    }
                ]
            });
        });

        function setSchoolConfiguration(id)
        {
            $("#school_configuration").val(id);
        }
    </script>
@endpush
