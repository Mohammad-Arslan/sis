<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Admission Inquiry</h4>
            <div class="flex-shrink-0">
                @permission('add-admission-inquiry')
                    <a href="{{ route('admission-query.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add Admission Inquiry
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('admission-query.update', $admission_query->id) }}"
                    method="post">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="inquiry_type_id" name="inquiry_type_id">
                                <option value="">Please select</option>
                                @foreach ($inquirytypes as $inquirytype)
                                    <option value="{{ $inquirytype->id }}" {{ $admission_query->inquiry_type_id == $inquirytype->id ? 'selected' : '' }}>{{ $inquirytype->type }}</option>
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
                                    <option value="{{ $source->id }}" {{ $admission_query->source_id == $source->id ? 'selected' : '' }}>{{ $source->source_name }}</option>
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
                                    <option value="{{ $city->id }}" {{ $admission_query->city_id == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
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
                                    <option value="{{ $town->id }}" {{ $admission_query->town_id == $town->id ? 'selected' : '' }}>{{ $town->town_name }}</option>
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
                                    <option value="{{ $branch->id }}" {{ $admission_query->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
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
                                    <option value="{{ $class->id }}" {{ $admission_query->class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
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
                            <input type="text" class="form-control  @if($errors->has('student_name')) is-invalid @endif" name="student_name" id="student_name" placeholder="Please enter student name" value="{{$admission_query->student_name}}" required>
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
                            <input type="text" class="form-control  @if($errors->has('parent_name')) is-invalid @endif" name="parent_name" id="parent_name" placeholder="Please enter father name" value="{{$admission_query->parent_name}}" required>
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
                            <input type="text" class="form-control  @if($errors->has('student_age')) is-invalid @endif" name="student_age" id="student_age" placeholder="Please enter student age" value="{{$admission_query->student_age}}" required>
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
                            <input type="text" class="form-control  @if($errors->has('parent_contact')) is-invalid @endif" name="parent_contact" id="parent_contact" placeholder="Please enter contact number" value="{{$admission_query->parent_contact}}" required>
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
                            <input type="email" class="form-control  @if($errors->has('parent_email')) is-invalid @endif" name="parent_email" id="parent_email" placeholder="example@email.com" value="{{$admission_query->parent_email}}" required>
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
                        <a href="{{ route('admission-query.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
