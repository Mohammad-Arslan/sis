<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Challan</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('beams-challans.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="load-select-filter form-select mb-3" id="academic_year_id"
                                name="academic_year_id" data-target="branch_id"
                                data-url="{{ route('list-academic-branches') }}" required>
                                <option value="" disabled selected>Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        @if (old('academic_year_id')) {{ 'selected' }} @endif>
                                        {{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic Year <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('academic_year_id'))
                                    {{ $errors->first('academic_year_id') }}
                                @else
                                    Academic year is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select
                                class="load-select-filter form-select mb-3 @if ($errors->has('branch_id')) is-invalid @endif"
                                id="branch_id" data-target="class_id"
                                data-url="{{ route('list-academic-branch-classes') }}" name="branch_id" required>
                                <option value="" disabled selected>Please Select a Branch</option>

                            </select>
                            <label for="branch_id" class="form-label">Select Branch <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('branch_id'))
                                    {{ $errors->first('branch_id') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select
                                class="load-select-filter form-select mb-3 @if ($errors->has('class_id')) is-invalid @endif"
                                id="class_id" data-target="section_id"
                                data-url="{{ route('list-academic-branch-class-sections') }}" name="class_id" required>
                                <option value="" disabled selected>Choose a Class</option>

                            </select>
                            <label for="class_id" class="form-label">Select Class <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('class_id'))
                                    {{ $errors->first('class_id') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3 @if ($errors->has('section_id')) is-invalid @endif"
                                id="section_id" name="section_id" required>
                                <option value="" disabled selected>Select a Section</option>

                            </select>
                            <label for="section_id" class="form-label">Select Section <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('section_id'))
                                    {{ $errors->first('section_id') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3 @if ($errors->has('challan_month')) is-invalid @endif"
                                id="challan_month" name="challan_month" required>
                                <option value="" disabled selected>Pick a month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                            <label for="challan_month" class="form-label">Select Month <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('challan_month'))
                                    {{ $errors->first('challan_month') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="file"
                                class="form-control @if ($errors->has('challan_pdf')) is-invalid @endif"
                                id="challan_pdf" name="challan_pdf" accept="application/pdf" required>
                            <label for="challan_pdf" class="form-label">Challan PDF <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('challan_pdf'))
                                    {{ $errors->first('challan_pdf') }}
                                @else
                                    Challan PDF is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

@push('header_scripts')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush
