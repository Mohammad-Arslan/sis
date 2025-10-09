<input type="hidden" value="{{ $applicationTypeID ?? '' }}" name="application_type_id">
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control" name="application_date" id="application_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="application_date" class="form-label">Application Date</label>

        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control" name="attendance_not_marked_date" id="attendance_not_marked_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="attendance_not_marked_date" class="form-label">Application not Marked Date</label>

        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="category" name="category" aria-label="category to select" required>
                <option value="">Please select</option>
                <option value="IN" selected>IN</option>
                <option value="OUT">OUT</option>
                <option value="BOTH">BOTH</option>
            </select>
            <label for="category" class="form-label">Category</label>

            <div class="invalid-tooltip">
                Category is required!
            </div>
        </div>
    </div>

</div>


<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('arrival_time')) is-invalid @endif" name="arrival_time" id="arrival_time">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="arrival_time" class="form-label">IN Time</label>

        </div>
        <div class="invalid-tooltip">
            @if($errors->has('arrival_time'))
                {{ $errors->first('arrival_time') }}
            @else
                Arrival Time is required!
            @endif
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('departure_time')) is-invalid @endif" name="departure_time" id="departure_time" readonly>
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="departure_time" class="form-label">OUT Time</label>

        </div>
        <div class="invalid-tooltip">
            @if($errors->has('departure_time'))
                {{ $errors->first('departure_time') }}
            @else
                Departure Time is required!
            @endif
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="forward_to" name="forward_to" aria-label="Forward to select" required>
                <option value="">Please select</option>
                @forelse(ReportingTo() as $ReportTo)
                    <option value="{{ $ReportTo->id }}">{{ $ReportTo->name }}</option>
                @empty
                @endforelse
            </select>
            <label for="forward_to" class="form-label">Forward to</label>

            <div class="invalid-tooltip">
                @if($errors->has('forward_to'))
                    {{ $errors->first('forward_to') }}
                @else
                    Forward to is required!
                @endif
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-label-group in-border">
            <textarea class="form-control" name="reason"></textarea>
            <label for="reason" class="form-label">Reason</label>
        </div>
    </div>
</div>


<button type="submit" class="btn btn-primary btn-sm">Submit</button>
@role('super_admin|network_associate')
<a href="{{ route('employees.create') }}?tab=basic_info" type="button" class="btn btn-danger btn-sm">cancel</a>
@endrole
<script>
    $(document).ready(function () {
        $('#application_date').flatpickr({
            dateFormat: 'd M, Y',
            altFormat: 'Y-m-d',
            defaultDate: 'today',
            enable: ["today"]
        })

        $('#attendance_not_marked_date').flatpickr({
            dateFormat: 'd M, Y',
            defaultDate: 'today'
        })

        $('#arrival_time').flatpickr({
            noCalendar: true,
            enableTime: true,
            time_24hr: true,
            defaultDate: '10:00'
        })
    })

    $(document).on('change', '#category', function () {
        let category = $(this).val();
        if (category == 'OUT') {
            $('#departure_time').flatpickr({
                noCalendar: true,
                enableTime: true,
                time_24hr: true,
                defaultDate: '17:00'
            })

            let arrival_time = $('#arrival_time');
            arrival_time.flatpickr().destroy();
            arrival_time.attr('readonly', true);
            arrival_time.val('');
        } else if (category == 'IN') {
            $('#arrival_time').flatpickr({
                noCalendar: true,
                enableTime: true,
                time_24hr: true,
                defaultDate: '10:00'
            })

            let departure_time = $('#departure_time');
            departure_time.flatpickr().destroy();
            departure_time.attr('readonly', true);
            departure_time.val('');
        } else {
            $('#arrival_time').flatpickr({
                noCalendar: true,
                enableTime: true,
                time_24hr: true,
                defaultDate: '10:00'
            })

            $('#departure_time').flatpickr({
                noCalendar: true,
                enableTime: true,
                time_24hr: true,
                defaultDate: '17:00'
            })
        }
    })

</script>
