@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Franchise Application List </h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('franchises.create') }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Application
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter load-select form-select" id="state_id" name="state_id"
                                    placeholder="state" data-target="city_id" data-url="{{ route('list-cities') }}"
                                    aria-label="State select" required>
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="state_id" class="form-label">State/Province</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('city')) is-invalid @endif"
                                    id="city" name="city_id" aria-label="City select" required>
                                    <option value="">Please select</option>
                                    @if (old('state_id'))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->city_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="city" class="form-label">City</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('city_id'))
                                        {{ $errors->first('city_id') }}
                                    @else
                                        City is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example" id="source_id"
                                    name="source_id" required>
                                    <option value="">Please select</option>
                                    @foreach ($sources as $source)
                                        <option value="{{ $source->id }}"
                                            {{ old('source_id') == $source->id ? 'selected' : '' }}>
                                            {{ $source->source_name }}</option>
                                    @endforeach
                                </select>
                                <label for="source_id" class="form-label">Source </label>
                                <div class="invalid-tooltip">
                                    Where did you hear about us?
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" aria-label="form-select-sm example"
                                    id="applicationStatus" name="application_status" required>
                                    <option value="">Please select</option>
                                    <option value="P">Pending</option>
                                    <option value="A">Approved</option>
                                    <option value="R">Rejected</option>
                                </select>
                                <label for="applicationStatus" class="form-label">Application Status </label>
                            </div>
                        </div>
                        <!-- <div class="col-md-2 col-sm-12">
                <div class="input-group form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('from_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('from_date') }}" name="from_date" id="from_date" required>
                <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
                </div>
                <label for="from_date" class="form-label">From Date</label>
                <div class="invalid-tooltip">
                @if ($errors->has('from_date'))
    {{ $errors->first('from_date') }}
@else
    Setup Date is required!
    @endif
                </div>
                </div>
                </div>

                <div class="col-md-2 col-sm-12">
                <div class="input-group form-label-group in-border">
                <input type="text" class="filter form-control @if ($errors->has('to_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('to_date') }}" name="to_date" id="to_date">
                <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
                </div>
                <label for="to_date" class="form-label">To Date</label>
                <div class="invalid-tooltip">
                @if ($errors->has('to_date'))
    {{ $errors->first('to_date') }}
@else
    To Date is required!
    @endif
                </div>
                </div>
                </div> -->

                        <div class="col-md-4 col-sm-12">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="filter form-control" data-provider="flatpickr"
                                    data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ old('date_range') }}"
                                    name="date_range" id="date_range" data-range-date="true">
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label for="date_range" class="form-label">Date Range</label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="search-box">
                                <input id="myInput" type="text" placeholder="Search.." class="form-control">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                    </div>

                    <table id="data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>CNIC</th>
                                <th>Province</th>
                                <th>City</th>
                                {{-- <th>Address</th> --}}
                                <th>Source</th>
                                <th>Application Status</th>
								<th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>CNIC</th>
                                <th>Province</th>
                                <th>City</th>
                                {{-- <th>Address</th> --}}
                                <th>Source</th>
                                <th>Application Status</th>
								<th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('franchises-inquiry.modals.upload_document_modal')





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
            $('#data-table').DataTable({
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
                    url: "{{ route('franchises.index') }}",
                    data: function(d) {
                        d.state_id = $('#state_id').val();
                        d.city_id = $('#city').val();
                        d.source_id = $('#source_id').val();
                        d.status = $('#applicationStatus').val();
                        // d.from_date = $('#from_date').val();
                        // d.to_date = $('#to_date').val();
                        d.date_range = $('#date_range').val();
                        d.searchTerm = $('#myInput').val().toLowerCase();
                    }
                },
                columns: [{
                        data: 'created_at',
                        name: 'created_at',
                        width: "10%",
                        orderable: true
                    },
                    {
                        data: 'appl_name',
                        name: 'appl_name',
                        width: "15%"
                    },
                    {
                        data: 'CNIC',
                        name: 'CNIC',
                        width: "15%"
                    },
                    {
                        data: 'states.state_name',
                        name: 'states.state_name',
                        width: "10%"
                    },
                    {
                        data: 'cities.city_name',
                        name: 'cities.city_name',
                        width: "10%"
                    },
                    /*{data: 'personal_address', name: 'personal_address', width: "20%"},*/
                    {
                        data: 'source.source_name',
                        name: 'source.source_name',
                        width: "5%"
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: "5%"
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
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#data-table').DataTable().ajax.reload(null, false);
        });

        $(document).on("keyup", '#myInput', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 2 || value.length == 0) {
                $('#data-table').DataTable().ajax.reload(null, false);
            }
        });

        $(document).on('click', '.uploadDocument', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            var inquiry_id = $(this).data('inquiry_id');
            $.ajax({
                url: "{{ route('get-franchise-applications-docs') }}?inquiry_id=" + inquiry_id,
                type: "GET",
                cache: false,
                success: function(data) {
                    let tbody = '';
                    if (data == '' || data == undefined || data == null) {
                        tbody +=
                            '<tr><td></td><td></td><td>No details available</td><td></td><td></td></tr>';
                    } else {
                        $.each(data, function(k, v) {
                            let path = 'uploads/franchise_applications_attachments/' + v
                                .file_name;
                            let src = '';
                            if (v.type == 'pdf') {
                                src = 'uploads/franchise_applications_attachments/file.png';
                            } else {
                                src = 'uploads/franchise_applications_attachments/' + v
                                    .file_name;
                            }

                            console.log(path);
                            tbody += '<tr><td><a href="' + path +
                                '" class="avatar-group-item" target="_blank">' + v.file_name +
                                '</td><td>' + v.user.name + '</td><td>' + v.uploaded_date +
                                '</td><td>' + v.details +
                                '</td><td class="text-center"><a href="javascript:void(0);" class="link-danger fs-15 remove_attachement " data-id="' +
                                v.id + '" data-inquiry_id="' + v.inquiry_id +
                                '"><i class="ri-delete-bin-line"></i></a></td></tr>';
                        });
                    }
                    $("#uploadDocumentModal .modal-body #uploadDocumentForm #inquiryId").val(
                    inquiry_id);
                    $("#uploadDocumentModal .modal-body #schoolBuildingTable .upload_docs").html(tbody);

                    $(target).modal('show');

                },
                error: function() {},
                beforeSend: function() {
                    $('.message').html('');
                    $('.message').removeClass('link-success');
                    $('.message').removeClass('link-danger');
                },
                complete: function() {}
            });

        });
    </script>
@endpush
