@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">
        @if (isset($branch_security))
            @include('settings.branch_security.edit_branch_security')
        @else
            @permission('add-branch-security')
                @include('settings.branch_security.add_branch_security')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Branch Securities List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a href="{{ route('branch-securities.index') }}" class="btn btn-success-new btn-label btn-sm">
                            <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reload
                        </a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_academic_year_id" name="s_academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="concession_id" class="form-label">Concessions Type</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->br_name . '[' . $branch->branch_code . ']' }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="branch-securities-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Academic Year</th>
                                <th>Branch ID</th>
                                <th>Branch</th>
                                <th>Amount</th>
                                <th>Created BY</th>
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
                                <th>Academic Year</th>
                                <th>Branch ID</th>
                                <th>Branch</th>
                                <th>Amount</th>
                                <th>Created BY</th>
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


@push('header_scripts')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#branch-securities-data-table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('branch-securities.index') }}",
                    data: function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.academic_year_id = $('#s_academic_year_id').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'academic_year.title',
                        name: 'academic_year.title',
                        'defaultContent': ''
                    },
                    {
                        data: 'branch_code',
                        name: 'branch_code',
                        'defaultContent': ''
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name',
                        'defaultContent': ''
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        'defaultContent': ''
                    },
                    {
                        data: 'user',
                        name: 'user',
                        'defaultContent': ''
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        'defaultContent': ''
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
        });

        $(document).on('change', '.filter', function() {
            $('#branch-securities-data-table').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#branch-securities-data-table').DataTable().ajax.reload(null, false).page('first');
            }
        });
    </script>
@endpush
