@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">IPG Billing (Payments)</h4>
                    <div class="flex-shrink-0">
                        {{-- @permission('export-student')
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href="{{ route('export-students') }}">
                            <i class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export
                        </a>
                        @endpermission --}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @if(isSuperAdmin() || isHeadOfficeEmp())
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="branch_id" name="branch_id" placeholder="Branch">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name. ' ('.$branch->branch_code.')' }}</option>
                                        @endforeach
                                    </select>
                                    <label for="designation_id" class="form-label">Branch</label>
                                </div>
                            </div>
                        @endrole
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="class_id" name="class_id"
                                    placeholder="Class">
                                    <option value="">Please select</option>
                                    @if(!isSuperAdmin() && !isHeadOfficeEmp()){
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->com_classes->id }}">{{ $class->com_classes->class_name }} </option>
                                        @endforeach
                                    @else
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="section_id" name="section_id" placeholder="Section">
                                    <option value="">Please select</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="section_id" class="form-label">Sections</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="gender" name="gender" placeholder="Gender">
                                    <option value="">Please Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <label for="gender" class="form-label">Gender</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="status_list" name="status">
                                    <option value="all">Status</option>
                                    <option value="on_roll">On Roll</option>
                                    <option value="registered">Registered</option>
                                    <option value="processing">Processing</option>
                                    <option value="left">Left</option>
                                    <option value="pass-out">Pass-Out</option>
                                    <option value="transferred">Transferred</option>
                                </select>
                                <label for="status" class="form-label">Status</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Order ID</th>
                                <th>Invoice No.</th>
                                <th>Student</th>
                                <th>Branch</th>
                                <th>Class / Section</th>
                                <th>Discountable</th>
                                <th>Non Refundable</th>
                                <th>Sibling Discount %</th>
                                <th>Concession Type</th>
                                <th>Concession %</th>
                                <th>Concession Discount</th>
                                <th>Royalty %</th>
                                <th>Royalty Amount</th>
                                <th>Total After Royalty</th>
                                <th>Arrears</th>
                                <th>Paid Amount</th>
                                <th>Paid Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr #</th>
                                <th>Order ID</th>
                                <th>Invoice No.</th>
                                <th>Student</th>
                                <th>Branch</th>
                                <th>Class / Section</th>
                                <th>Discountable</th>
                                <th>Non Refundable</th>
                                <th>Sibling Discount %</th>
                                <th>Concession Type</th>
                                <th>Concession %</th>
                                <th>Concession Discount</th>
                                <th>Royalty %</th>
                                <th>Royalty Amount</th>
                                <th>Total After Royalty</th>
                                <th>Arrears</th>
                                <th>Paid Amount</th>
                                <th>Paid Date</th>
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

            $('#example').DataTable({
                searching: false,
                retrieve: true,
                serverSide: true,
                processing: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                ajax: {
                    url: "{{ route('ipg-billing.index') }}",
                    data: function(d) {
                        d.gender = $('#gender').val();
                        d.section_id = $('#section_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.class_id = $('#class_id').val();
                        d.status = $('#status_list').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'order_id',
                        name: 'order_id',
                        'defaultContent': ''
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no',
                        'defaultContent': ''
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        'defaultContent': ''
                    },
                    {
                        data: 'branch',
                        name: 'branch',
                        'defaultContent': ''
                    },
                    {
                        data: 'class_section',
                        name: 'class_section',
                        'defaultContent': ''
                    },
                    {
                        data: 'discountable_charges',
                        name: 'discountable_charges',
                        'defaultContent': ''
                    },
                    {
                        data: 'non_refundable_charges',
                        name: 'non_refundable_charges',
                        'defaultContent': ''
                    },
                    {
                        data: 'sibling_discount_percentage', //sibling_discount_percentage
                        name: 'sibling_discount_percentage',
                        'defaultContent': ''
                    },
                    {
                        data: 'concession_type',
                        name: 'concession_type',
                        width: "5%",
                        'defaultContent': ''
                    },
                    {
                        data: 'concession_percentage',
                        name: 'concession_percentage',
                        'defaultContent': ''
                    },
                    {
                        data: 'concession_discount',
                        name: 'concession_discount',
                        'defaultContent': ''
                    },
                    {
                        data: 'royalty_percentage',
                        name: 'royalty_percentage',
                        'defaultContent': ''
                    },
                    {
                        data: 'royalty_amount',
                        name: 'royalty_amount',
                        'defaultContent': ''
                    },
                    {
                        data: 'total_after_royalty',
                        name: 'total_after_royalty',
                        'defaultContent': ''
                    },
                    {
                        data: 'arrears',
                        name: 'arrears',
                        'defaultContent': ''
                    },
                    {
                        data: 'billing_amount',
                        name: 'billing_amount',
                        'defaultContent': ''
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%",
                        'defaultContent': ''
                    }
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#example').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#example').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush
