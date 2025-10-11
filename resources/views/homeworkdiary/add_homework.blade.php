<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Homework</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('homeWorkDiary.store') }}" method="post">
                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id" name="academic_year_id" aria-label="Academic Year select" required>
                                <option value="">Please select</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}" {{ old("academic_year_id") == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic Year *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('academic_year_id'))
                                {{ $errors->first('academic_year_id') }}
                                @else
                                Academic Year is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(isHeadOfficeEmp() || isSuperAdmin())
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('state_id')) is-invalid @endif" id="state_id" name="state_id" aria-label="Province select" required>
                                <option value="">Please select</option>
                                @foreach ($states as $state)
                                        <option value="{{ $state->id }}" {{ old("state_id") == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                    @endforeach
                            </select>
                            <label for="state_id" class="form-label">Province *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('state_id'))
                                {{ $errors->first('state_id') }}
                                @else
                                Province is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if($errors->has('branch_id')) is-invalid @endif" data-target="class_id" data-url="{{ route('get-branch-classes') }}" id="branch_id" name="branch_id" aria-label="Branch select" required>
                                <option value="">Please select</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old("branch_id") == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branch_id" class="form-label">Branch *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('branch_id'))
                                {{ $errors->first('branch_id') }}
                                @else
                                Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="branch_id" id="branch_id" value="{{ get_branch_id() }}" />
                        <input type="hidden" name="state_id" id="state_id" value="{{ get_branch_state_id(0) }}" />
                    @endif
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select" id="class_id" name="class_id"
                                data-target="section_id"
                                data-url="{{ route('list-class-sections')}}"
                                aria-label="Classes select" required>
                                <option value="">Please select</option>
                                @foreach ($comclasses as $comclass)
                                    <option value="{{ $comclass['id'] }}" {{ old("class_id") == $comclass['id'] ? 'selected' : '' }}>{{ $comclass['class_name'] }}</option>
                                @endforeach
                            </select>
                            <label for="class_id" class="form-label">Class *</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('class_id'))
                                {{ $errors->first('class_id') }}
                                @else
                                Class is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="section_id" name="section_id" aria-label="Section select">
                                <option value="">Please select</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" {{ old("section_id") == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                                @endforeach
                            </select>
                            <label for="section_id" class="form-label">Section</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('homework_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('homework_date') }}" name="homework_date" id="return_date">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="homework_date" class="form-label">Date</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('homework_date'))
                                {{ $errors->first('homework_date') }}
                                @else
                                Date is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" placeholder="Enter remarks" name="remarks" id="remarks" rows="2" maxlength="50"></textarea>
                            <label for="remarks" class="form-label">Remarks</label>
                            {{-- <div class="invalid-tooltip">
                                @if($errors->has('remarks'))
                                {{ $errors->first('remarks') }}
                                @else
                                Remarks is required!
                                @endif
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <input type="hidden" id="created_by" name="created_by" value="{{Auth::user()->id}}" />
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
