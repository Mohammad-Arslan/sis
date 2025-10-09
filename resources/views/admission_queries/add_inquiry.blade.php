<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Admission Inquiry</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('admission-query.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="inquiry_type_id" name="inquiry_type_id">
                                <option value="">Please select</option>
                                @foreach ($inquirytypes as $inquirytype)
                                    @if($inquirytype->id != 1)
                                        <option value="{{ $inquirytype->id }}">{{ $inquirytype->type }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <label class="form-label">Inquiry Via <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('inquiry_type_id'))
                                {{ $errors->first('inquiry_type_id') }}
                                @else
                                inquiry type is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="source_id" name="source_id">
                                <option value="">Please select</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->source_name }}</option>
                                @endforeach
                            </select>
                            <label class="form-label">Source <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('source_id'))
                                {{ $errors->first('source_id') }}
                                @else
                                Source is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="city_id" name="city_id">
                                <option value="">Please select</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('city_id'))
                                {{ $errors->first('city_id') }}
                                @else
                                City is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="town_id" name="town_id">
                                <option value="">Please select</option>
                                @foreach ($towns as $town)
                                    <option value="{{ $town->id }}">{{ $town->town_name }}</option>
                                @endforeach
                            </select>
                            <label class="form-label">Town <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('town_id'))
                                {{ $errors->first('town_id') }}
                                @else
                                Town is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option value="">Please select</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branch_id" class="form-label">Branch</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('branch_id'))
                                {{ $errors->first('branch_id') }}
                                @else
                                Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="class_id" name="class_id">
                                <option value="">Please select</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('class_id'))
                                {{ $errors->first('class_id') }}
                                @else
                                Class is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control  @if($errors->has('student_name')) is-invalid @endif" name="student_name" id="student_name" placeholder="Please enter student name" value="{{old('student_name')}}" required>
                            <label class="form-label">Student Name <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('student_name'))
                                {{ $errors->first('student_name') }}
                                @else
                                Stdent name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control  @if($errors->has('parent_name')) is-invalid @endif" name="parent_name" id="parent_name" placeholder="Please enter father name" value="{{old('parent_name')}}" required>
                            <label class="form-label">Father Name <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('parent_name'))
                                {{ $errors->first('parent_name') }}
                                @else
                                Father name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control  @if($errors->has('student_age')) is-invalid @endif" name="student_age" id="student_age" placeholder="Please enter student age" value="{{old('student_age')}}" required>
                            <label for="student_age" class="form-label">Student Age <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('student_age'))
                                {{ $errors->first('student_age') }}
                                @else
                                Student age is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control  @if($errors->has('parent_contact')) is-invalid @endif" name="parent_contact" id="parent_contact" placeholder="Please enter contact number" value="{{old('parent_contact')}}" required>
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('parent_contact'))
                                {{ $errors->first('parent_contact') }}
                                @else
                                Contact number is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-label-group in-border">
                            <input type="email" class="form-control  @if($errors->has('parent_email')) is-invalid @endif" name="parent_email" id="parent_email" placeholder="example@email.com" value="{{old('parent_email')}}" required>
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('parent_email'))
                                {{ $errors->first('parent_email') }}
                                @else
                                Email is required!
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
