@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Add Tax</h4>
                    <!-- <div class="flex-shrink-0">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                                </div>
                                            </div> -->
                </div><!-- end card header -->

                <div class="card-body">
                    {{-- <div class="live-preview"> --}}
                    <form class="row g-3 needs-validation" method="POST" action="{{ route('tax.store') }}" novalidate>
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <select class="form-select @if ($errors->has('tax_type_id')) is-invalid @endif"
                                    id="schoolType" name="tax_type_id" aria-label="select" required>
                                    <option value="">Please select a Tax Type</option>
                                    @foreach ($tax_types as $tax_type)
                                        <option value="{{ $tax_type->id }}"
                                            {{ old('tax_type_id') == $tax_type->id ? 'selected' : '' }}>
                                            {{ $tax_type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="schoolType" class="form-label">Tax Type</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('tax_type_id'))
                                        {{ $errors->first('tax_type_id') }}
                                    @else
                                        Sales Representative is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- </div> --}}
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <select class="form-select @if ($errors->has('state_id')) is-invalid @endif" id="stateId"
                                    name="state_id" aria-label="select" required>
                                    <option value="">Please select a state</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                            {{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="stateId" class="form-label">State</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('state_id'))
                                        {{ $errors->first('state_id') }}
                                    @else
                                        Sales Representative is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- </div> --}}
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" max="99" id="taxPercentage"
                                    name="tax_percentage" placeholder="Tax Percentage" value="{{ old('tax_percentage') }}"
                                    required>
                                <label for="tax_percentage" class="form-label">Tax (%)</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('tax_percentage'))
                                        {{ $errors->first('tax_percentage') }}
                                    @else
                                        Tax Percentage is required and should be less than 99!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="form-control @if ($errors->has('active_from')) is-invalid @endif"
                                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                    data-deafult-date="" value="{{ old('active_from') }}" name="active_from"
                                    id="activeFrom" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label for="activeFrom" class="form-label">Active From</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('active_from'))
                                        {{ $errors->first('active_from') }}
                                    @else
                                        Active from is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-4">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="form-control @if ($errors->has('active_till')) is-invalid @endif"
                                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                    data-deafult-date="" value="{{ old('active_till') }}" name="active_till"
                                    id="activeTill" required>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label for="activeTill" class="form-label">Active to</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('active_till'))
                                        {{ $errors->first('active_till') }}
                                    @else
                                        Active to is required!
                                    @endif
                                </div>
                            </div>
                        </div>

                        @csrf
                        <div class="col-12 text-end">
                            <button class="btn btn-primary" type="submit">Submit form</button>
                            <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Tax List</h4>
                <!-- <div class="flex-shrink-0">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                                </div>
                                            </div> -->
            </div><!-- end card header -->

            <div class="card-body">

                <table id="tax-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tax Type</th>
                            <th>Tax %</th>
                            <th>State</th>
                            <th>Active from</th>
                            <th>Active till</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Tax Type</th>
                            <th>Tax %</th>
                            <th>State</th>
                            <th>Active from</th>
                            <th>Active till</th>
                            <th>Created At</th>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
    </div>
    </div>

    {{-- <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Tax List</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">

            	<table id="tax-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
			        <thead>
			            <tr>
			                <th>ID</th>
			                <th>Tax Type</th>
			                <th>Tax %</th>
                            <th>State</th>
                            <th>Active from</th>
                            <th>Active till</th>
			            </tr>
			        </thead>
			        <tbody>
			        </tbody>
			        <tfoot>
			            <tr>
			                <th>ID</th>
			                <th>Tax Type</th>
			                <th>Tax %</th>
                            <th>State</th>
                            <th>Active from</th>
                            <th>Active till</th>
			            </tr>
			        </tfoot>
			    </table>


            </div>
        </div>
    </div> --}}
    </div>
@endsection

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#tax-datatable').DataTable({
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
                ajax: "{{ route('tax.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'tax_type.name',
                        name: 'tax_type.name'
                    },
                    {
                        data: 'tax_percentage',
                        name: 'tax_percentage'
                    },
                    {
                        data: 'state.state_name',
                        name: 'state.state_name'
                    },
                    {
                        data: 'active_from',
                        name: 'active_from'
                    },
                    {
                        data: 'active_till',
                        name: 'active_till'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                ]
            });
        });
    </script>
@endpush
