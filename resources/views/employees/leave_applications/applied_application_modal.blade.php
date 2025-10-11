<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">{{ $leaveApplication->leaveApplicationType->name }} Application</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                    <div class="live-preview">
                        <div class="row gy-4">
                            <div class="col-xxl-3 col-md-6">
                                <div>
                                    <label for="basiInput" class="form-label">Application Date</label>
                                    <input type="text" class="form-control" id="application_date" value="{{ $leaveApplication->application_date ?? '' }}" readonly>
                                </div>
                            </div><!--end col-->
                            <div class="col-xxl-3 col-md-6">
                                <div>
                                    @if($leaveApplication->status == '1')
                                        @php $status = 'Approved'; @endphp
                                    @elseif($leaveApplication->status == '2')
                                        @php $status = 'Denied'; @endphp
                                    @elseif($leaveApplication->status == '3')
                                        @php $status = 'Cancelled' @endphp
                                    @else
                                        @php $status = 'Pending'; @endphp
                                    @endif
                                    <label for="basiInput" class="form-label">Status</label>
                                    <input type="text" class="form-control" id="application_date" value="{{ $status ?? '' }}" readonly>
                                </div>
                            </div><!--end col-->
                            @if($leaveApplication->leaveApplicationType->name == 'Leave')
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">From Date</label>
                                        <input type="text" class="form-control" id="from_date" value="{{ $leaveApplication->from_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">To Date</label>
                                        <input type="text" class="form-control" id="to_date" value="{{ $leaveApplication->to_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Num of Days</label>
                                        <input type="text" class="form-control" id="num_of_days" value="{{ $leaveApplication->num_of_days ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Category</label>
                                        <input type="text" class="form-control" id="category" value="{{ $leaveApplication->category ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">With Pay</label>
                                        <input type="text" class="form-control" id="with_pay" value="{{ $leaveApplication->with_pay == 1 ? 'Yes' : 'No' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Type</label>
                                        <input type="text" class="form-control" id="leave_type_id" value="{{ $leaveApplication->leaveType->name ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-12 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">reason</label>
                                        <textarea class="form-control" id="reason" readonly>{{ $leaveApplication->reason ?? '' }}</textarea>
                                    </div>
                                </div><!--end col-->
                            @elseif($leaveApplication->leaveApplicationType->name == 'Out Station')
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">From Date</label>
                                        <input type="text" class="form-control" id="from_date" value="{{ $leaveApplication->from_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">To Date</label>
                                        <input type="text" class="form-control" id="to_date" value="{{ $leaveApplication->to_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Num of Days</label>
                                        <input type="text" class="form-control" id="num_of_days" value="{{ $leaveApplication->num_of_days ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Category</label>
                                        <input type="text" class="form-control" id="category" value="{{ $leaveApplication->category ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-12 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">reason</label>
                                        <textarea class="form-control" id="reason" readonly>{{ $leaveApplication->reason ?? '' }}</textarea>
                                    </div>
                                </div><!--end col-->
                            @elseif($leaveApplication->leaveApplicationType->name == 'Toil')
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="adjustment_date" class="form-label">Adjustment Date</label>
                                        <input type="text" class="form-control" id="adjustment_date" value="{{ $leaveApplication->adjustment_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="off_day_work_date" class="form-label">Off Day Work Date</label>
                                        <input type="text" class="form-control" id="off_day_work_date" value="{{ $leaveApplication->off_day_work_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Category</label>
                                        <input type="text" class="form-control" id="category" value="{{ $leaveApplication->category ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-12 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">reason</label>
                                        <textarea class="form-control" id="reason" readonly>{{ $leaveApplication->reason ?? '' }}</textarea>
                                    </div>
                                </div><!--end col-->
                            @elseif($leaveApplication->leaveApplicationType->name == 'Late Arrival')
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">From Date</label>
                                        <input type="text" class="form-control" id="from_date" value="{{ $leaveApplication->from_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">To Date</label>
                                        <input type="text" class="form-control" id="to_date" value="{{ $leaveApplication->to_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Num of Days</label>
                                        <input type="text" class="form-control" id="num_of_days" value="{{ $leaveApplication->num_of_days ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="arrival_time" class="form-label">Arrival Time</label>
                                        <input type="text" class="form-control" id="arrival_time" value="{{ $leaveApplication->arrival_time ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-12 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">reason</label>
                                        <textarea class="form-control" id="reason" readonly>{{ $leaveApplication->reason ?? '' }}</textarea>
                                    </div>
                                </div><!--end col-->
                            @elseif($leaveApplication->leaveApplicationType->name == 'Early Leaving')
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">From Date</label>
                                        <input type="text" class="form-control" id="from_date" value="{{ $leaveApplication->from_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">To Date</label>
                                        <input type="text" class="form-control" id="to_date" value="{{ $leaveApplication->to_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Num of Days</label>
                                        <input type="text" class="form-control" id="num_of_days" value="{{ $leaveApplication->num_of_days ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="departure_time" class="form-label">Departure Time</label>
                                        <input type="text" class="form-control" id="departure_time" value="{{ $leaveApplication->departure_time ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-12 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">reason</label>
                                        <textarea class="form-control" id="reason" readonly>{{ $leaveApplication->reason ?? '' }}</textarea>
                                    </div>
                                </div><!--end col-->
                            @elseif($leaveApplication->leaveApplicationType->name == 'Attendance not Marked')
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="attendance_not_marked_date" class="form-label">Attendance not Marked Date</label>
                                        <input type="text" class="form-control" id="attendance_not_marked_date" value="{{ $leaveApplication->attendance_not_marked_date ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label">Category</label>
                                        <input type="text" class="form-control" id="category" value="{{ $leaveApplication->category ?? '' }}" readonly>
                                    </div>
                                </div><!--end col-->
                                @if($leaveApplication->arrival_time != null)
                                    <div class="col-xxl-3 col-md-6">
                                        <div>
                                            <label for="arrival_time" class="form-label">IN Time</label>
                                            <input type="text" class="form-control" id="arrival_time" value="{{ $leaveApplication->arrival_time ?? '' }}" readonly>
                                        </div>
                                    </div><!--end col-->
                                @endif
                                @if($leaveApplication->departure_time != null)
                                    <div class="col-xxl-3 col-md-6">
                                        <div>
                                            <label for="departure_time" class="form-label">OUT Time</label>
                                            <input type="text" class="form-control" id="departure_time" value="{{ $leaveApplication->departure_time ?? '' }}" readonly>
                                        </div>
                                    </div><!--end col-->
                                @endif
                                <div class="col-xxl-12 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">reason</label>
                                        <textarea class="form-control" id="reason" readonly>{{ $leaveApplication->reason ?? '' }}</textarea>
                                    </div>
                                </div><!--end col-->
                            @endif

                        </div><!--end row-->
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
