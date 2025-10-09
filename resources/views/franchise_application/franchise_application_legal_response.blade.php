@extends('layouts.master')
@section('content')
    @include('components.flash_message')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('franchise-applications.index') }}">Franchise Application List</a></li>
        <li class="breadcrumb-item active">{{ isset($franchiseApplicationLa) ? 'Edit' : 'Create' }} Legal Application</li>
    </x-breadcrumb>
    @if(auth()->user()->hasPermission('add-franchise-application-legal') || (isset($franchiseApplicationLa) && auth()->user()->hasPermission('edit-franchise-application-legal')))
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Review By Legal Application</h4>

                @if ($franchise_application['franchise_application_qa'] && auth()->user()->hasPermission('download-pdf-franchise-app-qa'))
                    <a href="{{ route('franchise-application-qa.create_pdf', $franchise_application['franchise_application_qa']['id']) }}"
                       class="btn btn-sm btn-primary pull-right">
                        Download QA Application PDF
                    </a>
                @endif
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <form class="row g-2 needs-validation"
                          action="{{ isset($franchiseApplicationLa) ? route('franchise-application-la.update', $franchiseApplicationLa->id) : route('franchise-application-la.store') }}"
                          method="POST" novalidate>
                        @csrf

                        @if (isset($franchiseApplicationLa))
                            @method('PATCH')
                        @endif

                        <input type="hidden" name="franchise_application_id" value="{{ $franchise_application_id }}">
                        <div class="col-md-6">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="form-control" name="review_date" id="review_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{isset($franchiseApplicationLa) ? $franchiseApplicationLa->review_date : date('Y-m-d')}}" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label class="form-label">Review Date</label>
                                <div class="invalid-tooltip">
                                    Date is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="dd_status" name="status" required>
                                    <option value="">Select</option>
                                    <option value="pending"
                                        {{ isset($franchiseApplicationLa) && $franchiseApplicationLa->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="need_recommendation"
                                        {{ isset($franchiseApplicationLa) && $franchiseApplicationLa->status == 'need_recommendation' ? 'selected' : '' }}>
                                        Need Recommendation</option>
                                        <option value="need_discussion"
                                        {{ isset($franchiseApplicationLa) && $franchiseApplicationLa->status == 'need_discussion' ? 'selected' : '' }}>
                                        Need Discussion</option>
                                    <option value="ready_for_mou"
                                        {{ isset($franchiseApplicationLa) && $franchiseApplicationLa->status == 'ready_for_mou' ? 'selected' : '' }}>
                                        Ready for MOU/FA</option>
                                </select>
                                <label class="form-label">Status</label>
                                <div class="invalid-tooltip">
                                    Status is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="review_by" name="review_by" required>
                                    <option value="">Select</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user['user_id'] }}"
                                            {{ isset($franchiseApplicationLa) && $franchiseApplicationLa->review_by == $user['user_id'] ? 'selected' : '' }}>
                                            {{ $user['user']['name'] }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Review By</label>
                                <div class="invalid-tooltip">
                                    Review By is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-group in-border">
                                <select class="load-select form-select" id="forwarded_to" name="forwarded_to" required>
                                    <option value="">Select</option>
                                    @foreach ($forwarded_to as $forwarded)
                                        <option value="{{ $forwarded['user_id'] }}"
                                            {{ isset($franchiseApplicationLa) && $franchiseApplicationLa->forwarded_to == $forwarded['user_id'] ? 'selected' : '' }}>
                                            {{ $forwarded['user']['name'] }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Forwarded To</label>
                                <div class="invalid-tooltip">
                                    Forwarded To is required!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="form-control" name="forwarded_date" id="forwarded_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{isset($franchiseApplicationLa) ? $franchiseApplicationLa->forwarded_date : date('Y-m-d')}}" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label class="form-label">Forwarded Date</label>
                                <div class="invalid-tooltip">
                                    Forwarded Date is required!
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="remarks" id="remarks" placeholder="Write Here..." required>{{isset($franchiseApplicationLa) ? $franchiseApplicationLa->remarks : ''}}</textarea>
                            <label class="form-label">Remarks</label>
                            <div class="invalid-tooltip">
                                Remarks are required!
                            </div>
                        </div>
                    </div> --}}
                        @if(auth()->user()->hasPermission('add-franchise-application-legal') || (isset($franchiseApplicationLa) && auth()->user()->hasPermission('update-franchise-app-legal')))
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
                <h4 class="card-title mb-0 flex-grow-1">Review By Legal Application List</h4>
                <div class="flex-shrink-0">
                    <!-- Buttons with Label -->
                    <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                            class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <table id="legal-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th>Review By</th>
                        <th>Review Date</th>
                        <th>Status</th>
                        <th>Forwarded To</th>
                        <th>Forwarded Date</th>
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
                        <th>Forwarded To</th>
                        <th>Forwarded Date</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @include('franchise_application.franchise_application_documents')
    </div>

@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#legal-data-table').DataTable({
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
                    url: "{{ route('franchise-application-la.index') }}",
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
                        data: 'forwarded',
                        name: 'forwarded'
                    },
                    {
                        data: 'forwarded_date',
                        name: 'forwarded_date'
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


            // $('#documents-data-table').DataTable({
            //     processing: true,
            //     searching: false,
            //     serverSide: false,
            //     responsive: true,
            //     bLengthChange: false,
            //     ordering: true,
            //     pageLength: 10,
            //     scrollX: true,
            // });

        });
    </script>
@endpush
