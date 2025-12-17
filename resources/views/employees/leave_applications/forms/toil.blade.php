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

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('adjustment_date')) is-invalid @endif" name="adjustment_date" id="adjustment_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="from_date" class="form-label">Adjustment Date</label>

        </div>
        <div class="invalid-tooltip">
            @if($errors->has('adjustment_date'))
                {{ $errors->first('adjustment_date') }}
            @else
                Adjustment Date is required!
            @endif
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('off_day_work_date')) is-invalid @endif" name="off_day_work_date" id="off_day_work_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="from_date" class="form-label">Off Day Work Date</label>

        </div>
        <div class="invalid-tooltip">
            @if($errors->has('off_day_work_date'))
                {{ $errors->first('off_day_work_date') }}
            @else
                Off Day Work is required!
            @endif
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="category" name="category" aria-label="category to select" required>
                <option value="">Please select</option>
                <option value="Full Day Toil">Full Day Toil</option>
                <option value="Half Day Toil">Half Day Toil</option>
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

        $('#adjustment_date, #off_day_work_date').flatpickr({
            dateFormat: 'd M, Y',
            defaultDate: 'today'
        })
    })

</script>
