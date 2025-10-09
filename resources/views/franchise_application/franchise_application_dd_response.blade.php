@extends('layouts.master')
@section('content')
    @include('components.flash_message')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('franchise-applications.index') }}">Franchise Application List</a></li>
        <li class="breadcrumb-item active">{{ isset($franchiseApplicationDd) ? 'Edit' : 'Create' }} Deputy Director
            Application</li>
    </x-breadcrumb>

    @if(auth()->user()->hasPermission('add-franchise-application-dd') || (isset($franchiseApplicationLa) && auth()->user()->hasPermission('edit-franchise-application-dd')))
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Deputy Director Application List</h4>

                @if ($franchise_application['franchise_application_qa'] && auth()->user()->hasPermission('download-pdf-franchise-app-qa'))
                    <a href="{{ route('franchise-application-qa.create_pdf', $franchise_application['franchise_application_qa']['id']) }}"
                       class="btn btn-sm btn-primary">
                        Download QA Application PDF
                    </a>
                @endif
                &nbsp;
                <a href="{{ route('franchise-application-iasf-form', $franchise_application_id) }}"
                   class="btn btn-sm btn-primary pull-right ml-3">
                    View IASF Report
                </a>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <form class="row g-2 needs-validation"
                          action="{{ isset($franchiseApplicationDd) ? route('franchise-application-dd.update', $franchiseApplicationDd->id) : route('franchise-application-dd.store') }}"
                          method="POST" novalidate>
                        @csrf

                        @if (isset($franchiseApplicationDd))
                            @method('PATCH')
                        @endif

                        <input type="hidden" name="franchise_application_id" value="{{ $franchise_application_id }}">
                        <div class="col-md-4">
                            <div class="input-group form-label-group in-border">
                                <input type="date" class="form-control" name="review_date" id="review_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{isset($franchiseApplicationDd) ? $franchiseApplicationDd->review_date : date('Y-m-d')}}" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label class="form-label">Review Date</label>
                                <div class="invalid-tooltip">
                                    Date is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="review_by" name="review_by" required>
                                    <option value="">Select</option>
                                    @foreach ($review_by as $user)
                                        <option value="{{ $user['user_id'] }}"
                                            {{ isset($franchiseApplicationDd) && $franchiseApplicationDd->review_by == $user['user_id'] ? 'selected' : '' }}>
                                            {{ $user['preferred_name'] }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Review By</label>
                                <div class="invalid-tooltip">
                                    Review By is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="dd_status" name="status" required>
                                    <option value="pending"
                                        {{ isset($franchiseApplicationDd) && $franchiseApplicationDd->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="approved"
                                        {{ isset($franchiseApplicationDd) && $franchiseApplicationDd->status == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="not_approved"
                                        {{ isset($franchiseApplicationDd) && $franchiseApplicationDd->status == 'not_approved' ? 'selected' : '' }}>
                                        Not Approved</option>
                                </select>
                                <label class="form-label">Status</label>
                                <div class="invalid-tooltip">
                                    Status is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-label-group in-border">
                                <textarea class="form-control" name="remarks" id="remarks" placeholder="Write Here..."
                                          required>{{ isset($franchiseApplicationDd) ? $franchiseApplicationDd->remarks : '' }}</textarea>
                                <label class="form-label">Remarks</label>
                                <div class="invalid-tooltip">
                                    Remarks are required!
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->hasPermission('add-franchise-application-dd') || (isset($franchiseApplicationLa) && auth()->user()->hasPermission('update-franchise-application-dd')))
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    @endif
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Deputy Director Application List</h4>
                <div class="flex-shrink-0">
                    <!-- Buttons with Label -->
                    <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                            class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <table id="dd-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Review By</th>
                            <th>Review Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Review By</th>
                            <th>Review Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#dd-data-table').DataTable({
                processing: true,
                searching: false,
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
                    url: "{{ route('franchise-application-dd.index') }}",
                    data: function(d) {
                        d.franchise_application_id = {{ $franchise_application_id }};
                    },
                    dataType: "json",
                    method: 'GET'
                },
                columns: [{
                        data: 'review_by',
                        name: 'review_by'
                    },
                    {
                        data: 'review_date',
                        name: 'review_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                        width: "5%",
                        sClass: 'text-center'
                    },
                ],
            });

        });
    </script>
@endpush
