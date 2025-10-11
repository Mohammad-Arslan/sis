<form class="needs-validation" action="{{ isset($branch_royalty) ? route('branch-royalty.update',$branch_royalty['id']) : route('branch-royalty.store') }}" method="POST" novalidate>
    @csrf
    @if (isset($branch_royalty))
        @method('PATCH')
    @endif

    <input type="hidden" name="updated_by" value="{{ isset(auth()->user()->employee) ? auth()->user()->employee->id : null }}" />
    <input type="hidden" name="branch_id" value="{{$branch->id}}">
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <input type="text" class="form-control {{$errors->has('royalty_rate') ? 'is-invalid' : ''}}" name="royalty_rate"
                       id="royalty_rate" value="{{old('royalty_rate') ? old('royalty_rate') : (isset($branch_royalty['royalty_rate']) ? $branch_royalty['royalty_rate'] : '')}}" required>
                <label for="royalty_rate" class="form-label">Rate (%)</label>
                <div class="invalid-tooltip">
                    @if($errors->has('royalty_rate'))
                        {{ $errors->first('royalty_rate') }}
                    @else
                        Rate is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group form-label-group in-border">
                <input type="text" class="form-control {{$errors->has('with_effect_from') ? 'is-invalid' : ''}}" name="with_effect_from" id="with_effect_from" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{old('with_effect_from') ? old('with_effect_from') : (isset($branch_royalty['with_effect_from']) ? $branch_royalty['with_effect_from'] : '')}}" required>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                <label class="form-label">WEF</label>
                <div class="invalid-tooltip">
                    @if($errors->has('with_effect_from'))
                        {{ $errors->first('with_effect_from') }}
                    @else
                        WEF is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group form-label-group in-border">
                <input type="text" class="form-control {{$errors->has('closing_date') ? 'is-invalid' : ''}}" name="closing_date" id="closing_date" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{old('closing_date') ? old('closing_date') : (isset($branch_royalty['closing_date']) ? $branch_royalty['closing_date'] : '')}}" required>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                <label class="form-label">Closing Date</label>
                <div class="invalid-tooltip">
                    @if($errors->has('closing_date'))
                        {{ $errors->first('closing_date') }}
                    @else
                        Closing Date is required!
                    @endif
                </div>
            </div>
        </div>
        {{--<div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <input type="number" class="form-control {{$errors->has('sales_tax') ? 'is-invalid' : ''}}" name="sales_tax"
                       id="sales_tax" value="{{old('sales_tax') ? old('sales_tax') : (isset($branch_royalty['sales_tax']) ? $branch_royalty['sales_tax'] : '')}}" required>
                <label for="sales_tax" class="form-label">Sales Tax (%)</label>
                <div class="invalid-tooltip">
                    @if($errors->has('sales_tax'))
                        {{ $errors->first('sales_tax') }}
                    @else
                        Sales Tax is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <input type="number" class="form-control {{$errors->has('fed') ? 'is-invalid' : ''}}" name="fed"
                       id="fed" value="{{old('fed') ? old('fed') : (isset($branch_royalty['fed']) ? $branch_royalty['fed'] : '')}}" required>
                <label for="fed" class="form-label">FED (%)</label>
                <div class="invalid-tooltip">
                    @if($errors->has('fed'))
                        {{ $errors->first('fed') }}
                    @else
                        FED is required!
                    @endif
                </div>
            </div>
        </div>--}}
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="form-label-group in-border">
                <textarea class="form-control" id="address" name="remarks" rows="2" placeholder="Remarks">{{old('remarks') ? old('remarks') : (isset($branch_royalty['remarks']) ? $branch_royalty['remarks'] : '')}}</textarea>
                <label for="remarks" class="form-label">Remarks</label>
                <div class="invalid-tooltip">
                    @if($errors->has('remarks'))
                        {{ $errors->first('remarks') }}
                    @else
                        Remarks is required!
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
