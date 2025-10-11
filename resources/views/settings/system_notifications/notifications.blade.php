@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header align-items-center d-flex">
                <h4 class="mb-0 card-title flex-grow-1">Announcement</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('system-notifications.create') }}" class="btn btn-success btn-label btn-sm">
                        <i class="align-middle ri-add-fill label-icon fs-16 me-2"></i> Create
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- @role('super_admin') --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="branch_id" placeholder="Section">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"> {{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="branch_id" class="form-label">Branch</label>
                                </div>
                            </div>
                        {{-- @endrole --}}
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('notification_type')) is-invalid @endif"
                                    id="notification_type" name="notification_type" aria-label="Notification Type select"
                                    required>
                                    <option value="">Please select a Announcement type</option>
                                    <option value="SMS" {{ old('notification_type') == 'SMS' ? 'selected' : '' }}>SMS
                                    </option>
                                    <option value="Email" {{ old('notification_type') == 'Email' ? 'selected' : '' }}>
                                        Email</option>
                                    <option value="Push Notification"
                                        {{ old('notification_type') == 'Push Notification' ? 'selected' : '' }}>
                                        Push Notification</option>
                                </select>
                                <label for="notification_type" class="form-label">Announcement Type <span
                                        class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select @if ($errors->has('audience')) is-invalid @endif"
                                    id="audience" name="audience" aria-label="Audience select" required>
                                    <option value="">Please select a audience</option>
                                    {{-- @role('super_admin') --}}
                                        <option value="NWA" {{ old('audience') == 'NWA' ? 'selected' : '' }}>NWA
                                        </option>
                                    {{-- @endrole --}}
                                    <option value="Parents" {{ old('audience') == 'Parents' ? 'selected' : '' }}>Parents
                                    </option>
                                    <option value="Employees" {{ old('audience') == 'Employees' ? 'selected' : '' }}>
                                        Employees</option>
                                </select>
                                <label for="audience" class="form-label">Audience <span class="text-danger">*</span></label>

                            </div>

                            {{-- <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id"  placeholder="Section">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option  value="{{ $branch->id }}">  {{ $branch->br_name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div> --}}
                        </div>
                        <table id="system-notifications-data-table"
                            class="table mb-0 align-middle table-bordered table-striped table-nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Announcement Type</th>
                                    <th>Audience</th>
                                    <th>Branch</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Announcement Type</th>
                                    <th>Audience</th>
                                    <th>Branch</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>Created By</th>
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

                $('#system-notifications-data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    bLengthChange: false,
                    pageLength: 10,
                    scrollX: true,
                    language: {
                        search: "",
                        processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                        searchPlaceholder: "Search..."
                    },
                    // ajax: "{{ route('system-notifications.index') }}",
                    ajax: {
                        url: "{{ route('system-notifications.index') }}",
                        dataType: "json",
                        method: 'GET',
                        data: function(d) {
                            d.audience = $('#audience').val();
                            d.notification_type = $('#notification_type').val();
                            d.branch_id = $('#branch_id').val();
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            width: "5%"
                        },
                        {
                            data: 'notification_type',
                            name: 'notification_type'
                        },
                        {
                            data: 'audience',
                            name: 'audience'
                        },
                        {
                            data: 'branch.br_name',
                            name: 'branch.br_name',
                            defaultContent: "Null"
                        },
                        {
                            data: 'country.country_name',
                            name: 'country.country_name',
                            defaultContent: "Null"
                        },
                        {
                            data: 'state.state_name',
                            name: 'state.state_name',
                            defaultContent: "Null"
                        },
                        {
                            data: 'created_by.name',
                            name: 'created_by.name',
                            defaultContent: "Null"
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

                $("#notification_type").change(function() {
                    var selected_option = $('#notification_type').val();
                    if (selected_option == 'SMS' || selected_option == 'Push Notification') {
                        document.getElementById("message_div").style.display = "block";
                        document.getElementById('email_body_div').style.display = "none";
                    }
                    if (selected_option == 'Email') {
                        document.getElementById("message_div").style.display = "none";
                        document.getElementById('email_body_div').style.display = "block";
                    }
                    if (selected_option == 'SMS, Email, Push Notification') {
                        document.getElementById("message_div").style.display = "block";
                        document.getElementById('email_body_div').style.display = "block";
                    }
                });
                $(document).on('change', '.filter', function() {
                    $('#system-notifications-data-table').DataTable().ajax.reload(null, false);
                });
            });
        </script>
    @endpush
