@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Franchise Inquiry List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('franchises-inquiry.index') }}" class="btn btn-info btn-label btn-sm">
                            <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                        </a>
                        <a href="{{ route('franchises-inquiry.create') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-article-fill label-icon align-middle fs-16 me-2"></i> Add New Franchise Inquiry
                        </a>
                        <a href="javascript:;" data-action="{{ route('franchise-inquiry.campaign') }}" onclick="copyMyURL('{{route('franchise-inquiry.campaign')}}')" class="btn btn-warning btn-label btn-sm">
                            <i class="ri-clipboard-line label-icon align-middle fs-16 me-2"></i> Copy Campaign Url
                        </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="source_id" name="source_id" placeholder="Source">
                                    <option value="">Please select</option>
                                    @foreach ($sources as $source)
                                        <option value="{{ $source->id }}">{{ $source->source_name }}</option>
                                    @endforeach
                                </select>
                                <label for="source_id" class="form-label">Source</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="city_id" name="city_id" placeholder="City">
                                    <option value="">Please select</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                <label for="city_id" class="form-label">City</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="led_franchise" name="led_franchise" placeholder="Led Franchise">
                                    <option value="">Please select</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                </select>
                                <label for="led_franchise" class="form-label">Led Franchise</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="created_by" name="created_by" placeholder="Created By">
                                    <option value="">Please select</option>
                                    <option value="system">System</option>
                                    <option value="campaign">Campaign</option>
                                </select>
                                <label for="created_by" class="form-label">Created By</label>
                            </div>
                        </div>

                        @role('super_admin')
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                        @endrole

                    </div>
                    <table id="franchise-inquiries-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                           style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>CNIC</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Source</th>
                            <th>City</th>
                            <th>Current Occupation</th>
                            <th>Led Franchise</th>
                            <th>Initiated By</th>
                            <th>Created By</th>
                            <th>Last Updated By</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>CNIC</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Source</th>
                            <th>City</th>
                            <th>Current Occupation</th>
                            <th>Led Franchise</th>
                            <th>Initiated By</th>
                            <th>Created By</th>
                            <th>Last Updated By</th>
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


            $('#franchise-inquiries-data-table').DataTable({
                searching: false,
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
                ajax: {
                    url : "{{ route('franchises-inquiry.index') }}",
                    data: function(d) {
                        d.source_id = $('#source_id').val();
                        d.city_id = $('#city_id').val();
                        d.led_franchise = $('#led_franchise').val();
                        d.created_by = $('#created_by').val();
                        @role('super_admin')
                            d.searchName = $('#mySearch').val().toLowerCase();
                        @endrole
                    }
                },
                columns: [{
                    data: 'id',
                    name: 'id',
                    width: "5%"
                },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'CNIC',
                        name: 'CNIC'
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'contact_no_1',
                        name: 'contact_no',
                    },
                    {
                        data: 'source.source_name',
                        name: 'source',
                    },
                    {
                        data: 'city.city_name',
                        name: 'city',
                    },
                    {
                        data: 'current_occupation',
                        name: 'current_occupation',
                    },
                    {
                        data: 'led_franchise',
                        name: 'led_franchise',
                    },
                    {
                        data: 'initiated_by',
                        name: 'initiated_by',
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                    },
                    {
                        data: 'last_updated_by',
                        name: 'last_updated_by',
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
                ],

            });
        });

        $(document).on('change', '.filter', function() {
            $('#franchise-inquiries-data-table').DataTable().ajax.reload(null, false).page('first');
        });
        @role('super_admin')
            $(document).on("keyup", '#mySearch', function() {
                var value = $(this).val().toLowerCase();
                if (value.length > 0 || value.length == 0) {
                    $('#franchise-inquiries-data-table').DataTable().ajax.reload(null, false).page('first');
                }
            });
        @endrole


        function copyMyURL(val) {
            navigator.clipboard.writeText(val);
        }

    </script>
@endpush
