@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Attachments</li>
    </x-breadcrumb>
    @include('components.flash_message')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Class Timetable List</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <table id="brandingMarketingTable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                        <tr>
                            <th>Document Name</th>
                            {{--<th>Branch</th>
                            <th>Type</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Status</th>--}}
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Document Name</th>
                            {{--<th>Branch</th>
                            <th>Type</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Status</th>--}}
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

            $('#brandingMarketingTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                order: false,
                scrollX: true,
                language: {
                    search: "",
                    processing: `<img class='ucs_loader' src='{{asset('loader.gif')}}' />`,
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('general-document.class-timetable.index') }}",
                    dataType: "json",
                    method: 'GET'
                },
                columns: [
                    {
                        data: 'document_name',
                        name: 'document_name'
                    },
                    /*{
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'uploaded_by',
                        name: 'uploaded_by'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: "5%",
                        sClass: 'text-center'
                    },*/
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    },
                ],
            });
        });
    </script>
@endpush
