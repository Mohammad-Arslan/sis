@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Add Tax Type</h4>
                    <!-- <div class="flex-shrink-0">
                                <div class="form-check form-switch form-switch-right form-switch-md">
                                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                </div>
                            </div> -->
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <form class="row g-3 needs-validation" method="POST" action="{{ route('tax-type.store') }}"
                            novalidate>
                            <div class="col-md-4 col-sm-12 mt-4">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Tax Name"
                                        value="{{ old('name') }}" required>
                                    <label for="name" class="form-label">Name *</label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('name'))
                                            {{ $errors->first('name') }}
                                        @else
                                            Name is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 mt-4">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control" id="description" name="description"
                                        placeholder="Tax Description" value="{{ old('desciption') }}">
                                    <label for="description" class="form-label">Description</label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('description'))
                                            {{ $errors->first('description') }}
                                        @else
                                            Description is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @csrf
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                                <button type="button"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Tax Type List</h4>
                    <!-- <div class="flex-shrink-0">
                                <div class="form-check form-switch form-switch-right form-switch-md">
                                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                </div>
                            </div> -->
                </div><!-- end card header -->

                <div class="card-body">

                    <table id="tax-type-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tax Type</th>
                                <th>Description</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tax Type</th>
                                <th>Description</th>
                                <th>Created At</th>
                            </tr>
                        </tfoot>
                    </table>


                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#tax-type-datatable').DataTable({
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
                ajax: "{{ route('tax-type.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
