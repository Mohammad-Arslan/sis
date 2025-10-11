@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Withdrawal Form</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <form class="row g-3 needs-validation" novalidate method="POST"
                            action="{{ route('student-withdrawal.update', $studentWithdrawal->id) }}">
                            @method('PUT')

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control load-student @if ($errors->has('student_roll_no')) is-invalid @endif"
                                        id="studentRollNo" name="student_roll_no" placeholder="Please enter first name"
                                        value="{{ $studentWithdrawal->student->roll_no }}" data-url="{{ route('get-student') }}" required>
                                    <label for="studentRollNo" class="form-label">Student Id <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('student_roll_no'))
                                            {{ $errors->first('student_roll_no') }}
                                        @else
                                            Student Id is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('student_name')) is-invalid @endif"
                                        id="studentName" name="student_name" placeholder="Please enter student name"
                                        readonly value="{{ $studentWithdrawal->student->first_name . ' ' . $studentWithdrawal->student->last_name }}">
                                    <label for="studentName" class="form-label">Student Name</label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('student_name'))
                                            {{ $errors->first('student_name') }}
                                        @else
                                            Student Name is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select @if ($errors->has('withdrawal_reason_id')) is-invalid @endif"
                                        id="withdrawal_reason_id" name="withdrawal_reason_id"
                                        aria-label="withdrawal reason select" required>
                                        <option value="">Please select a Withdrawal Reason</option>
                                        @foreach ($reasons as $reason)
                                            <option value="{{ $reason->id }}"
                                                {{ $studentWithdrawal->withdrawal_reason_id == $reason->id ? 'selected' : '' }}>
                                                {{ $reason->withdrawal_reason }}</option>
                                        @endforeach
                                    </select>
                                    <label for="withdrawal_reason_id" class="form-label">Withdrawal Reason <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('withdrawal_reason_id'))
                                            {{ $errors->first('withdrawal_reason_id') }}
                                        @else
                                            Withdrawal Reason is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('withdrawal_wef')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                            value="{{ $studentWithdrawal->withdrawal_wef }}" name="withdrawal_wef" id="withdrawalWef"
                                            required>
                                        <label for="withdrawalWef" class="form-label">Withdrawal WEF <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('withdrawal_wef'))
                                                {{ $errors->first('withdrawal_wef') }}
                                            @else
                                                Withdrawal WEF is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('application_date')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                            value="{{ $studentWithdrawal->application_date }}" name="application_date"
                                            id="applicationDate" required>
                                        <label for="applicationDate" class="form-label">Application Date <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('application_date'))
                                                {{ $errors->first('application_date') }}
                                            @else
                                                Application Date is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('last_day_at')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                            value="{{ $studentWithdrawal->last_day_at }}" name="last_day_at" id="lastDayAt" required>
                                        <label for="lastDayAt" class="form-label">Last Day <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('last_day_at'))
                                                {{ $errors->first('last_day_at') }}
                                            @else
                                                Last Day is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="libraryClearance"
                                        name="library_clearance" {{ $studentWithdrawal->library_clearance == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="libraryClearance">
                                        Library Clearance
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('clearance_amount')) is-invalid @endif"
                                        id="clearanceAmount" name="clearance_amount"
                                        placeholder="Please enter clearance amount" value="{{ $studentWithdrawal->clearance_amount }}"
                                        required>
                                    <label for="clearanceAmount" class="form-label">Clearance Amount <span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('clearance_amount'))
                                            {{ $errors->first('clearance_amount') }}
                                        @else
                                            Clearance Amount is required!
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="student_id" value="{{ $studentWithdrawal->student_id }}" />

                            @csrf
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                                <a href="{{ route('student-withdrawal.index') }}"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).on('input', '.load-student', onStudentChange)

        function onStudentChange() {
            var value = $(this).val();
            var url = $(this).data('url');

            $.ajax({
                url: url + '?id=' + value,
                type: "GET",
                cache: false,
                success: function(data) {
                    if (data.status !== 404) {
                        console.log(data.student_name);
                        $('input[name="student_id"]').val(data.id)
                        $('input[name="student_name"]').val(
                            `${data.first_name} ${data.middle_name ? `${data.middle_name} ` : ''}${data.last_name}`
                        )
                    }
                }
            })

        }
    </script>
@endpush
