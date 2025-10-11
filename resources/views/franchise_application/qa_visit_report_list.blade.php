@extends('layouts.master')
@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('franchise-applications.index')}}">Franchise Application List</a></li>
        <li class="breadcrumb-item"><a href="{{route('franchise-application-qa.create',$franchise_application['id'])}}">Create QA Application</a></li>
        <li class="breadcrumb-item active">List QA Applications</li>
    </x-breadcrumb>

<div class="col-lg-12">
	<div class="card">
			<div class="card-header align-items-center d-flex">
					<h4 class="card-title mb-0 flex-grow-1">QA Application List</h4>
					<div class="flex-shrink-0">
							<!-- Buttons with Label -->
							{{-- <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
											class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a> --}}
							{{-- <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
											class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a> --}}
					</div>
			</div><!-- end card header -->

			<div class="card-body">
					<table id="qa-visit-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
								 style="width:100%">
							<thead>
							<tr>
									<th>ID</th>
									<th>Client Name</th>
									<th>Sales Representative</th>
									<th>QA Representative</th>
									<th>QA Status</th>
									<th>Proposed Location</th>
									<th>Client Contact</th>
									<th>Visit Purpose</th>
									<th>Visit Date</th>
									<th>Regional Head</th>
									<th>Regional Status</th>
									<th>Created At</th>
									<th>Action</th>
							</tr>
							</thead>
							<tbody>

							</tbody>
							<tfoot>
							<tr>
									<th>ID</th>
									<th>Client Name</th>
									<th>Sales Representative</th>
									<th>QA Representative</th>
									<th>QA Status</th>
									<th>Proposed Location</th>
									<th>Client Contact</th>
									<th>Visit Purpose</th>
									<th>Visit Date</th>
									<th>Regional Head</th>
									<th>Regional Status</th>
									<th>Created At</th>
									<th>Action</th>
							</tr>
							</tfoot>
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

            $('#qa-visit-data-table').DataTable({
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
                ajax: "{{ route('franchise-application-qa.index', [ 'franchise_application' => $franchise_application['id']]) }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'sales_rep.user.name',
                        name: 'sales_rep.user.name'
                    },
                    {
                        data: 'qa_rep.user.name',
                        name: 'qa_rep.user.name'
                    },
                    {
                        data: 'qa_status',
                        name: 'qa_status'
                    },
                    {
                        data: 'proposed_location',
                        name: 'proposed_location'
                    },
                    {
                        data: 'client_contact',
                        name: 'client_contact'
                    },
                    {
                        data: 'visit_purpose',
                        name: 'visit_purpose'
                    },
                    {
                        data: 'visit_date',
                        name: 'visit_date'
                    },
                    {
                        data: 'regional_head.user.name',
                        name: 'regional_head.user.name'
                    },
                    {
                        data: 'head_status',
                        name: 'head_status'
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
    </script>
@endpush
