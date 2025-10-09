<input type="hidden" value="{{ $applicationTypeID ?? '' }}" name="application_type_id">
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control" name="application_date" id="application_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="dateOfBirth" class="form-label">Application Date</label>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('from_date')) is-invalid @endif" name="from_date" id="from_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="from_date" class="form-label">From Date</label>

        </div>
        <div class="invalid-tooltip">
            @if($errors->has('from_date'))
                {{ $errors->first('from_date') }}
            @else
                From Date is required!
            @endif
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('to_date')) is-invalid @endif" name="to_date" id="to_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="to_date" class="form-label">To Date</label>

        </div>
        <div class="invalid-tooltip">
            @if($errors->has('to_date'))
                {{ $errors->first('to_date') }}
            @else
                To Date is required!
            @endif
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control" id="num_of_days" name="num_of_days" placeholder="First Name" value="1"  readonly>
            <label for="num_of_days" class="form-label">Number of Days</label>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="time" class="form-control" name="departure_time" id="departure_time">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-time-line"></i>
            </div>
            <label for="departure_time" class="form-label">Departure Time</label>

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

        let url_params = getUrlVars();
        let period_date = 'today';
        let time_out = '10:00';
        let from_date = null;
        let to_date = null;
        /*check if url contains date then auto pick calendar date*/
        if (typeof url_params['date'] !== 'undefined' && url_params['date'] != '') {
            from_date = url_params['date'];
            to_date = url_params['date'];

            $('#from_date').flatpickr({
                dateFormat: 'Y-m-d',
                defaultDate: from_date,
            })

            $('#to_date').flatpickr({
                dateFormat: 'Y-m-d',
                defaultDate: to_date,
            })
        }
        else
        {
            $('#from_date').flatpickr({
                dateFormat: 'd M, Y',
                defaultDate: period_date,
            })

            $('#to_date').flatpickr({
                dateFormat: 'd M, Y',
                defaultDate: period_date,
            })
        }

        /*check if url contains time then auto pick calendar time*/
        if (typeof url_params['time_out'] !== 'undefined' && url_params['time_out'] != '') {
            time_out = url_params['time_out'];
        }

        $('#application_date').flatpickr({
            dateFormat: 'd M, Y',
            altFormat: 'Y-m-d',
            defaultDate: 'today',
            enable: ["today"]
        })

        $('#departure_time').flatpickr({
            noCalendar: true,
            enableTime: true,
            time_24hr: true,
            defaultDate: time_out
        })
    })

</script>
