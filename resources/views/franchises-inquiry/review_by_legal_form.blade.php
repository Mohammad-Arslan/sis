@extends('layouts.master')
@section('content')
    @include('components.flash_message')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Review By Legal Application</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-2 needs-validation" novalidate>
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <label class="form-label">Review Date</label>
                            <input type="date" class="form-control" name="review_date" id="review_date" data-provider="flatpickr" required>
                            <div class="invalid-tooltip">
                                Date is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select" id="review_by" name="review_by" required>
                                <option value="">Select</option>
                            </select>
                            <label class="form-label">Review By</label>
                            <div class="invalid-tooltip">
                                Review By is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="remarks" id="remarks" placeholder="Write Here..."></textarea>
                            <label class="form-label">Remarks</label>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Application List</h4>
                <div class="flex-shrink-0">
                    <!-- Buttons with Label -->
                    <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                            class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                    <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                            class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <table id="city-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th>Review By</th>
                        <th>Review Date</th>
                        <th>Remarks</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Review By</th>
                        <th>Review Date</th>
                        <th>Remarks</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">

    </script>
@endpush
