<div class="card-header d-flex justify-content-between">
    <h4 class="card-title mb-0 flex-grow-1">Students Information</h4>
    <div class="flex-shrink-0">
        <div class="form-check">
            <label for="selectAllStudents" class="d-flex align-items-center">
                <p class="text-muted m-0 pe-4 me-2">Select all students</p>
                <input class="form-check-input @if ($errors->has('student_ids.*')) is-invalid @endif" type="checkbox" id="selectAllStudents" style="font-size: 16px">
                <div class="invalid-tooltip">
                    @if ($errors->has('student_ids.*'))
                        {{ $errors->first('student_ids.*') }}
                    {{--@else
                        Class is required!--}}
                    @endif
                </div>
            </label>
        </div>
    </div>
</div>

<div class="card-body">

    <table id="students-list"
           class="list_student_table table table-bordered table-striped align-middle table-nowrap mb-0"
           style="width:100%">
        <thead>
        <tr>
            <th>ID</th>
            {{-- <th>Invoice no.</th> --}}
            <th>Roll Number</th>
            <th>Student</th>
            <th>Branch</th>
            <th>Class</th>
            <th>Section</th>
            <th>Invoice Status</th>
            <th>Promotion Status</th>
            <th>Select</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            {{-- <th>Invoice no.</th> --}}
            <th>Roll Number</th>
            <th>Student</th>
            <th>Branch</th>
            <th>Class</th>
            <th>Section</th>
            <th>Invoice Status</th>
            <th>Promotion Status</th>
            <th>Select</th>
        </tr>
        </tfoot>
    </table>

    <div class="col-12 mt-3 text-end">
        <button class="btn btn-primary" type="submit"
            {{ isset($studentTransferCase) && $studentTransferCase->status != 'PENDING' ? 'disabled' : '' }}>Submit
            form
        </button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</div>
