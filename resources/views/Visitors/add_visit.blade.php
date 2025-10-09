<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Visit</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('visitDetail.store') }}" method="post">
                    @csrf
                    @if(isSuperAdmin())
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('user_id')) is-invalid @endif" id="employee" name="user_id" aria-label="Employee select" required>
                                <option value="">Please select</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->user_id }}" {{ old("user_id") == $employee->user_id ? 'selected' : '' }}>{{ $employee->user->name.' ['.$employee->department->department_name.']' }}</option>
                                @endforeach
                            </select>
                            <label for="employee" class="form-label">Employee *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('user_id'))
                                {{ $errors->first('user_id') }}
                                @else
                                Employee is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                        <input type="hidden" id="employee" name="user_id" value="{{Auth::user()->id}}" />
                        <input type="hidden" class="form-control  " name="req" id="req" value="my">
                    @endif
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('campus_office_id')) is-invalid @endif" id="campus_type" name="campus_office_id" aria-label="Campus Office select" required>
                                <option value="">Please select</option>
                                @foreach ($campus_types as $campus_type)
                                <option value="{{ $campus_type->id }}" {{ old("campus_office_id") == $campus_type->id ? 'selected' : '' }}>{{ $campus_type->type }}</option>
                                @endforeach
                            </select>
                            <label for="campus_type" class="form-label">Campus/Office Type *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('campus_office_id'))
                                {{ $errors->first('campus_office_id') }}
                                @else
                                campus/office is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('branch_id')) is-invalid @endif" id="branch_campus" name="branch_id" aria-label="Branch select" required>
                                <option value="">Please select</option>
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old("branch_id") == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branch_campus" class="form-label">Campus Name *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('branch_id'))
                                {{ $errors->first('branch_id') }}
                                @else
                                Campus name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('from_city_id')) is-invalid @endif" id="from_city" name="from_city_id" aria-label="From City select" required>
                                <option value="">Please select</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ old("from_city_id") == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <label for="from_city" class="form-label">From Location *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('from_city_id'))
                                {{ $errors->first('from_city_id') }}
                                @else
                                From location is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('to_city_id')) is-invalid @endif" id="to_location" name="to_city_id" aria-label="To City select" required>
                                <option value="">Please select</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ old("to_city_id") == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <label for="to_location" class="form-label">To Location *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('to_city_id'))
                                {{ $errors->first('to_city_id') }}
                                @else
                                To location is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('total_duration')) is-invalid @endif" id="t_duration" name="total_duration" aria-label="Duration select" required>
                                <option value="">Please select</option>
                                <option value="0.25 Day" {{ old("total_duration") == '0.25 Day' ? 'selected' : '' }}>0.25 Day</option>
                                <option value="0.5 Day" {{ old("total_duration") == '0.5 Day' ? 'selected' : '' }}>0.5 Day</option>
                                <option value="1 Day" {{ old("total_duration") == '1 Day' ? 'selected' : '' }}>1 Day</option>
                                <option value="2 Days" {{ old("total_duration") == '2 Days' ? 'selected' : '' }}>2 Days</option>
                                <option value="3 Days" {{ old("total_duration") == '3 Days' ? 'selected' : '' }}>3 Days</option>
                                <option value="4 Days" {{ old("total_duration") == '4 Days' ? 'selected' : '' }}>4 Days</option>
                                <option value="5 Days" {{ old("total_duration") == '5 Days' ? 'selected' : '' }}>5 Days</option>
                            </select>
                            <label for="t_duration" class="form-label">No. of Hours / Days *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('total_duration'))
                                {{ $errors->first('total_duration') }}
                                @else
                                No. of Hours / Days is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('travel_on')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('travel_on') }}" name="travel_on" id="travel_date">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="travel_on" class="form-label">Travel On Date</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('travel_on'))
                                {{ $errors->first('travel_on') }}
                                @else
                                Travel On is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('return_on')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('return_on') }}" name="return_on" id="return_date">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="return_on" class="form-label">Return On Date</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('return_on'))
                                {{ $errors->first('return_on') }}
                                @else
                                Return On is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('travel_mode')) is-invalid @endif" id="t_mode" name="travel_mode" aria-label="Campus Office select" required>
                                <option value="">Please select</option>
                                <option value="Own" {{ old("travel_mode") == 'Own' ? 'selected' : '' }}>Own</option>
                                <option value="By Car" {{ old("travel_mode") == 'By Car' ? 'selected' : '' }}>By Car</option>
                                <option value="By Train" {{ old("travel_mode") == 'By Train' ? 'selected' : '' }}>By Train</option>
                                <option value="By Bus" {{ old("travel_mode") == 'By Bus' ? 'selected' : '' }}>By Bus</option>
                                <option value="By Air" {{ old("travel_mode") == 'By Air' ? 'selected' : '' }}>By Air</option>
                            </select>
                            <label for="t_mode" class="form-label">Travel Mode *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('travel_mode'))
                                {{ $errors->first('travel_mode') }}
                                @else
                                Travel mode is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" placeholder="Enter purpose" name="purpose" id="purpose" rows="2" maxlength="50" required></textarea>
                            <label for="purpose" class="form-label">Purpose *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('purpose'))
                                {{ $errors->first('purpose') }}
                                @else
                                Purpose is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" placeholder="Enter remarks" name="remarks" id="remarks" rows="2" maxlength="50"></textarea>
                            <label for="remarks" class="form-label">Remarks</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('remarks'))
                                {{ $errors->first('remarks') }}
                                @else
                                Purpose is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
