<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Add New Class Subject</h4>
            <!-- <div class="flex-shrink-0">
              <div class="form-check form-switch form-switch-right form-switch-md">
                  <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                  <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
              </div>
          </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" action="{{ isset($classSubject) ? route('class-subjects.update',$classSubject['id']) : route('class-subjects.store') }}" method="POST"  novalidate>

                    @if(isset($classSubject))
                        @method('PATCH')
                    @endif

                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('branch_id')) is-invalid @endif" id="branch"
                                name="branch_id" data-target="class_id" data-url="{{ route('list-branch-classes') }}" aria-label="Branch select">
                                <option value="">Please select a branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{(isset($classSubject) && $classSubject['branch_id'] == $branch->id) || old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branch" class="form-label">Branch <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('branch_id'))
                                    {{ $errors->first('branch_id') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('class_id')) is-invalid @endif" id="class_id"
                                name="class_id" aria-label="Class select" required>
                                <option value="">Please select a class</option>
                                @foreach ($com_classes as $class)
                                    <option value="{{ $class->id }}" {{(isset($classSubject) && $classSubject['class_id'] == $class->id) || old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('class_id'))
                                    {{ $errors->first('class_id') }}
                                @else
                                    Class is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('subject_id')) is-invalid @endif" id="subject"
                                name="subject_id" aria-label="Branch select" required>
                                <option value="">Please select a subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{(isset($classSubject) && $classSubject['subject_id'] == $subject->id) || old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('subject_id'))
                                    {{ $errors->first('subject_id') }}
                                @else
                                    Subject is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('state_id')) is-invalid @endif" id="state"
                                name="state_id" aria-label="State select">
                                <option value="">Please select a state</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{(isset($classSubject) && $classSubject['state_id'] == $state->id) || old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('state_id'))
                                    {{ $errors->first('state_id') }}
                                @else
                                    State is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                        <button type="button"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
