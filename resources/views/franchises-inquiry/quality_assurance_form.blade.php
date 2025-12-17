@extends('layouts.master')
@section('content')
    @include('components.flash_message')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Quality Assurance Application</h4>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="/application/qa_visit_form">QA Report</a>
                <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href="javascript:void(0);"><i
                        class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Download PDF</a>
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="qa_application_date" id="qa_application_date" data-provider="flatpickr" required>
                            <div class="invalid-tooltip">
                                Date is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select" id="visit_by" name="visit_by" required>
                                <option value="">Select</option>
                            </select>
                            <label class="form-label">Visit By</label>
                            <div class="invalid-tooltip">
                                Visit By is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select" id="qa_status" name="qa_status" required>
                                <option value="">Select</option>
                            </select>
                            <label class="form-label">QA Status</label>
                            <div class="invalid-tooltip">
                                QA Status is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="purpose" id="purpose" placeholder="Write Here..."></textarea>
                            <label class="form-label">Purpose</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="remarks" id="remarks" placeholder="Write Here..."></textarea>
                            <label class="form-label">Remarks</label>
                        </div>
                    </div>

                    <h5 class="fs-15 mb-3">Regional Head Section</h5>

                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select" id="regional_head" name="regional_head" required>
                                <option value="">Select</option>
                            </select>
                            <label class="form-label">Regional Head</label>
                            <div class="invalid-tooltip">
                                Regional Head is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="regional_head_sec_date" id="regional_head_sec_date" data-provider="flatpickr">
                            <div class="invalid-tooltip">
                                Date is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select" id="head_status" name="head_status" required>
                                <option value="">Select</option>
                            </select>
                            <label class="form-label">Head Status</label>
                            <div class="invalid-tooltip">
                                Head Status is required!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="regional_head_sec_remarks" id="regional_head_sec_remarks" placeholder="Write Here..."></textarea>
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
                        <th>Visit By</th>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Remarks</th>
                        <th>QA Status</th>
                        <th>Regional Head</th>
                        <th>Head Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Visit By</th>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Remarks</th>
                        <th>QA Status</th>
                        <th>Regional Head</th>
                        <th>Head Status</th>
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
