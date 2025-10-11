@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Branch list</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('branches.create') }}?tab=home" class="btn btn-success-new btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Branch
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="company_id" name="company_id" placeholder="Company">
                                    <option value="">Please select</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                                <label for="company_id" class="form-label">Company</label>
                            </div>
                        </div>
                        {{-- <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="nwa_id" name="nwa_id"
                                    placeholder="Network Assosiate">
                                    <option value="">Please select</option>
                                </select>
                                <label for="nwa_id" class="form-label">Network Assosiate</label>
                            </div>
                        </div> --}}

                        <div class="col-md-2 col-sm-12">
                            <div class="filter form-label-group in-border">
                                <select class="form-select" id="region_id" name="region_id" aria-label="Region select"
                                    placeholder="Region">
                                    <option value="">Please select</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                    @endforeach
                                </select>
                                <label for="region_id" class="form-label">Region</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="filter form-label-group in-border">
                                <select class="form-select" id="state_id" name="state_id" aria-label="State select">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="region_id" class="form-label">Province</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="myInput" type="text" placeholder="Search.." class="form-control">
                                <label for="myInput" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>


                    <table id="data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                {{-- <th>Branch ID</th>
			                <th>Company Name</th> --}}
                                <th>Branch ID</th>
                                <th>Branch Name</th>
                                <th>Build Purpose</th>
                                <th>Website</th>
                                <th>Province</th>
                                <th>Region</th>
                                <th>School Type</th>
                                {{-- <th>NWA Name</th>
                                <th>NWA Email</th>
                                <th>NWA Contact No</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                {{-- <th>Branch ID</th>
			                <th>Company</th> --}}
                                <th>Branch ID</th>
                                <th>Branch Name</th>
                                <th>Build Purpose</th>
                                <th>Website</th>
                                <th>Province</th>
                                <th>Region</th>
                                <th>School Type</th>
                                {{-- <th>NWA Name</th>
                                <th>NWA Email</th>
                                <th>NWA Contact No</th> --}}
                                <th>Actions</th>
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
            $('#data-table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    //search: "",
                    //searchPlaceholder: "Search...",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                },
                ajax: {
                    url: "{{ route('branches.index') }}",
                    data: function(d) {
                        d.company_id = $('#company_id').val();
                        d.region_id = $('#region_id').val();
                        d.nwa_id = $('#nwa_id').val();
                        d.state_id = $('#state_id').val();
                        d.searchTerm = $('#myInput').val().toLowerCase();
                    }
                },
                columns: [
                    /*{data: 'id', name: 'id', width: "5%",orderable: true},
                    {data: 'company.company_name', name: 'company.company_name'},*/
                    {
                        data: 'branch_code',
                        name: 'branch_code'
                    },
                    {
                        data: 'br_name',
                        name: 'br_name'
                    },
                    {
                        data: 'build_purpose',
                        name: 'build_purpose'
                    },
                    {
                        data: 'website',
                        name: 'website'
                    },
                    {
                        data: 'contact_information.state.state_name',
                        name: 'contact_information.state.state_name',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'region.region_name',
                        name: 'region.region_name'
                    },
                    {
                        data: 'class_group.name',
                        name: 'class_group.name'
                    },
                    // {
                    //     data: 'nwa.user.name',
                    //     name: 'nwa.user.name',
                    //     width: "10%",
                    //     'defaultContent': '<i>-</i>'
                    // },
                    // {
                    //     data: 'nwa.user.email',
                    //     name: 'nwa.user.email',
                    //     width: "10%",
                    //     'defaultContent': '<i>-</i>'
                    // },
                    // {
                    //     data: 'nwa.contact_information.mobile',
                    //     name: 'nwa.contact_information.mobile',
                    //     width: "10%",
                    //     'defaultContent': '<i>-</i>'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    },
                    /*{
                        data: 'setup_date',
                        name: 'setup_date',
                        width: "8%"
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: "5%",
                        sClass: 'text-center'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },*/
                ],
                order: [
                    [0, "desc"]
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#data-table').DataTable().ajax.reload(null, false);
        });

        $(document).on('change', '#company_id', function(e) {

            $.ajax({

                url: "{{ route('list-network-associates') }}?id=" + $(this).val(),
                type: "GET",
                cache: false,
                success: function(data) {

                    var options = `<option value="">Please select</option>`;

                    if (data) {
                        console.log(data)
                        $.each(data, function(index, value) {
                            options += '<option value="' + value.id + '">' + value.user.name +
                                '</option>';
                        });
                    }

                    $('#nwa_id').html(options).attr('disabled', false);
                },
                error: function() {

                },
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                }
            });
        });

        $(document).on("keyup", '#myInput', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 1 || value.length == 0) {
                $('#data-table').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush
