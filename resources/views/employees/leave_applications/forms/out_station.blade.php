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
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="category" name="category" aria-label="category to select" required>
                <option value="">Please select</option>
                <option value="Full Day">Full Day</option>
                <option value="Half Day">Half Day</option>
                <option value="Quarter">Quarter</option>
            </select>
            <label for="category" class="form-label">Category</label>

            <div class="invalid-tooltip">
                Category is required!
            </div>
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

        $('#from_date, #to_date').flatpickr({
            dateFormat: 'd M, Y',
            altFormat: 'Y-m-d',
            defaultDate: 'today'
        })
    })


</script>
