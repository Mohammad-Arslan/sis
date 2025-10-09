<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add New Dependent</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('employee-dependent.store') }}" method="post">
                    @csrf
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="dependent_name" name="dependent_name" placeholder="Please enter dependent name" value="{{ old('dependent_name') }}" required>
                            <label for="dependent_name" class="form-label">Dependent Name *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('dependent_name'))
                                {{ $errors->first('dependent_name') }}
                                @else
                                Dependent name is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('dependent_relationship')) is-invalid @endif" id="dependent_relationship" name="dependent_relationship" aria-label="Select Relationship" required>
                                <option value="">Please select</option>
                                <option value="Spouse" {{ old('dependent_relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                <option value="Father" {{ old('dependent_relationship') == 'Father' ? 'selected' : '' }}>Father</option>
                                <option value="Mother" {{ old('dependent_relationship') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                <option value="Child" {{ old('dependent_relationship') == 'Child' ? 'selected' : '' }}>Child</option>
                            </select>
                            <label for="dependent_relationship" class="form-label">Relationship *</label>

                            <div class="invalid-tooltip">
                                @if($errors->has('dependent_relationship'))
                                {{ $errors->first('dependent_relationship') }}
                                @else
                                Relationship is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('dependent_cnic')) is-invalid @endif" id="dependent_cnic" name="dependent_cnic" placeholder="Please enter cnic (e.g., 12345-1234567-1)" value="{{ old('dependent_cnic') }}" required>
                            <label for="dependent_cnic" class="form-label">CNIC *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('dependent_cnic'))
                                {{ $errors->first('dependent_cnic') }}
                                @else
                                CNIC is required in format: 12345-1234567-1 and must be unique
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('dependent_dob')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" data-deafult-date="" value="{{ old('dependent_dob') }}" name="dependent_dob" id="dependent_dob">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="dependent_dob" class="form-label">DOB *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('dependent_dob'))
                                {{ $errors->first('dependent_dob') }}
                                @else
                                DOB is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        @if(isset($employee->id))
                            <input type="hidden" name="employee_id"  value="{{ $employee->id }}" id="employee_id">
                        @elseif (isset($employee[0]->id))
                            <input type="hidden" name="employee_id"  value="{{ $employee[0]->id }}" id="employee_id">
                        @endif
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
