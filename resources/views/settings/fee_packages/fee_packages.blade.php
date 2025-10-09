@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Fee Packages List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <!-- <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a> -->
                        @permission('add-fee-package')
                            <a href="{{ route('fee-packages.create') }}" class="btn btn-success-new btn-label btn-sm">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Fee Package
                            </a>
                        @endpermission
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" name="academic_year_id">
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
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="fee_package_type_id">
                                    <option value="">Please select</option>
                                    @foreach ($fee_package_type as $fee_package_type)
                                        <option value="{{ $fee_package_type->id }}"> {{ $fee_package_type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="fee_package_type_id" class="form-label">Fee Package Type</label>
                            </div>
                        </div>

                    </div>
                    <table id="fee-packages-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Academic Year</th>
                                <th>Branch Name</th>
                                <th>Fee Package Type</th>
                                <th>Fee Packages</th>
                                <th>Description</th>
                                <th>From Class</th>
                                <th>To Class</th>
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
                                <th>Branch Name</th>
                                <th>Fee Package Type</th>
                                <th>Fee Packages</th>
                                <th>Description</th>
                                <th>From Class</th>
                                <th>To Class</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="feeChargesModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomInModalLabel">Fee Charges</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody"></div>
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

            $('#fee-packages-data-table').DataTable({
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
                    url: "{{ route('fee-packages.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.academic_year_id = $('#academic_year_id').val();
                        // d.from_class_id = $('#from_class_id').val();
                        // d.to_class_id = $('#to_class_id').val();
                        d.fee_package_type_id = $('#fee_package_type_id').val();
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
                        data: 'fee_package_type.name',
                        name: 'fee_package_type.name'
                    },
                    {
                        data: 'package_name',
                        name: 'package_name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'from_class_id.class_name',
                        name: 'from_class_id.class_name'
                    },
                    {
                        data: 'to_class_id.class_name',
                        name: 'to_class_id.class_name'
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

            $(document).on('click', '.popup', function(e) {
                // var class_group_id = this.id;
                var url = "{{ route('fee-packages-fee-charges.index') }}";
                $.ajax({

                    url: url,
                    type: "GET",
                    data: {
                        'id': this.id,
                        'academic_year_id': $('#academic_year_id').val()
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data)
                        $('#feeChargesModal').modal('show');
                        $('#modalBody').html(data).show();
                    },

                });
            });

            $(document).on('click', '.form-check-input', function(e) {

                var fee_charge_id = $(this).val();
                var fee_package_id = $(this).data('fee_package_id');

                console.log(fee_charge_id, fee_package_id);
                var url = "{{ route('fee-packages-fee-charges.store') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        'fee_charge_id': fee_charge_id,
                        'fee_package_id': fee_package_id
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {},

                });

            });
            $(document).on('change', '.filter', function() {
                $('#fee-packages-data-table').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
