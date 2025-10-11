@extends('layouts.master')

@section('content')
    <div class="row">
        @if (isset($fee_period))
            @include('settings.fee_periods.edit_fee_period')
        @else
            @permission('add-fee-period')
                @include('settings.fee_periods.add_fee_period')
            @endpermission
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Fee Periods List</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}" {{ $loop->last ? 'selected' : '' }}>
                                            {{ $academic_year->title }}</option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>

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
                        {{-- <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="fee_period_id" >
                                <option value="">Please select</option>
                                @foreach ($fee_period as $fee_period)
                                    <option  value="{{ $fee_period->id }}">  {{ $fee_period->period_name }}</option>
                                @endforeach
                            </select>
                            <label for="fee_period_id" class="form-label">Fee Period</label>
                        </div>
                        </div> --}}
                    </div>
                    <table id="fee-period-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Academic Year</th>
                                <th>Branch</th>
                                <th>Fee Period</th>
                                <th>Description</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Valid Date</th>
                                <th>Arrears Date</th>
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
                                <th>Branch</th>
                                <th>Fee Period</th>
                                <th>Description</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Valid Date</th>
                                <th>Arrears Date</th>
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

            $('#fee-period-data-table').DataTable({
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
                ajax: {
                    url: "{{ route('fee-period.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        console.log("testing value", d);
                        d.branch_id = $('#branch_id').val();
                        d.academic_year_id = $('#academic_year_id').val();
                        d.fee_period_id = $('#fee_period_id').val();
                        // d.to_class_id = $('#to_class_id').val();
                        // d.fee_package_type_id = $('#fee_package_type_id').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'academic_year.title',
                        name: 'academic_year.title'
                    },
                    {
                        data: 'branch.br_name',
                        name: 'branch.br_name'
                    },

                    {
                        data: 'period_name',
                        name: 'period_name'
                    },
                    {
                        data: 'period_description',
                        name: 'period_description'
                    },

                    {
                        data: 'from_date',
                        name: 'from_date'
                    },
                    {
                        data: 'to_date',
                        name: 'to_date'
                    },
                    {
                        data: 'issue_date',
                        name: 'issue_date'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'valid_date',
                        name: 'valid_date'
                    },
                    {
                        data: 'arrears_date',
                        name: 'arrears_date'
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
            $(document).on('change', '.filter', function() {
                $('#fee-period-data-table').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
