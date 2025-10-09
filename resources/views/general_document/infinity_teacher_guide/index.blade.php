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
                    <h4 class="card-title mb-0 flex-grow-1">Infinity Teacher Guide List</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        {{--<div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id_filter">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select filter" id="state_id">
                                    <option value="">Please select a Province</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="term_id" class="form-label">Province</label>
                            </div>
                        </div>--}}
                    </div>
                    <table id="infinityTeacherTable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
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

            $('#infinityTeacherTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                order: false,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('general-document.infinity-teacher-guide.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.state_id = $('#state_id').val();
                    }
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

            $(document).on('change', '.filter', function() {
                $('#infinityTeacherTable').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
