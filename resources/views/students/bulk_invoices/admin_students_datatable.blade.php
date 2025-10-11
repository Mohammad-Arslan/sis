<div class="col-lg-12 col-md-12 col-sm-12">
    <form id="bulk-invoices-status-form" class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0 flex-grow-1">Generate Invoices</h4>
            <div class="flex-shrink-0">
                <div class="form-check">
                    <label for="selectAllStudents" class="d-flex align-items-center">
                        <p class="text-muted m-0 pe-4 me-2">Select all students</p>
                        <input class="form-check-input" type="checkbox" id="selectAllStudents" style="font-size: 16px">
                    </label>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-12 mt-4">
                    <div class="form-label-group in-border">
                        <select class="load-select form-select @if ($errors->has('branch_id')) is-invalid @endif"
                            id="branch_id" name="branch_id" data-target="academic_year_id"
                            data-url="{{ route('list-academic-years') }}" aria-label="Country select" required>
                            <option value="">Please select</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ $branch->id == old('branch_id') ? 'selected' : '' }}>
                                    {{ $branch->br_name }}</option>
                            @endforeach
                        </select>
                        <label for="branch" class="form-label">Branch</label>
                        <div class="invalid-tooltip">
                            @if ($errors->has('branch_id'))
                                {{ $errors->first('branch_id') }}
                            @else
                                Branch is required!
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mt-4">
                    <div class="form-label-group in-border">
                        <select class="form-select @if ($errors->has('academic_year_id')) is-invalid @endif"
                            id="academic_year_id" name="academic_year_id" aria-label="Country select">
                            <option value="">Please select</option>
                            {{-- @foreach ($academic_years as $academic_year)
                                <option value="{{ $academic_year->id }}"
                                    {{ $academic_year->id == old('academic_year_id') ? 'selected' : '' }}>
                                    {{ $academic_year->title }}</option>
                            @endforeach --}}
                        </select>
                        <label for="branch" class="form-label">Academic year</label>
                        <div class="invalid-tooltip">
                            @if ($errors->has('academic_year_id'))
                                {{ $errors->first('sacademic_year_id') }}
                            @else
                                Academic year is required!
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-4 text-end">
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_data mt-1">Fetch
                        Data</a>
                </div>
            </div>
            <div class="fetched_data_div row"></div>
            <table id="branches-students-list"
                class="table table-bordered table-striped align-middle table-nowrap mb-0 mt-3" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice no.</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Invoice Status</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Invoice no.</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Invoice Status</th>
                        <th>Select</th>
                    </tr>
                </tfoot>
            </table>
            <div class="col-12 mt-3 text-end">
                <button type="submit" class="btn btn-primary me-2">Mark As Paid</button>
                {{-- <button class="btn btn-success me-2">Preview</button> --}}
                {{-- <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button> --}}
            </div>
        </div>
    </form>
</div>
