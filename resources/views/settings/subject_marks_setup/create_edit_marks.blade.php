<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{isset($subjectMarksSetup) ? 'Update' : 'Add'}} Subject Marks</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" method="POST" action="{{ isset($subjectMarksSetup) ? route('subject-marks-setup.update',$subjectMarksSetup['id']) : route('subject-marks-setup.store') }}" novalidate>
                    @if(isset($subjectMarksSetup))
                        @method('PATCH')
                    @endif
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('academic_year_id')) is-invalid @endif" id="academic_year_id" name="academic_year_id" required>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['academic_year_id'] == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Academic Year <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('academic_year_id'))
                                    {{ $errors->first('academic_year_id') }}
                                @else
                                    Academic Year is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if($errors->has('branch_id')) is-invalid @endif" data-target="class_id" data-url="{{ route('list-branch-classes') }}" id="branch_id" name="branch_id" aria-label="Branch select" required>
                                <option value="">Please select</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['branch_id'] == $branch->id ? 'selected' : '' }} >{{ $branch->br_name . ' ('.$branch->branch_code.')' }}</option>
                                @endforeach
                            </select>
                            <label for="branchId" class="form-label">Branch <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('branch_id'))
                                    {{ $errors->first('branch_id') }}
                                @else
                                    Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if($errors->has('class_id')) is-invalid @endif" data-target="subject_id" data-url="{{isset($subjectMarksSetup) ? route('get-class-subjects',$subjectMarksSetup['branch_id']) : ''}}" id="class_id" name="class_id" required>
                                <option value="">Please select a class</option>
                                @if(isset($branch_classes))
                                    @foreach ($branch_classes as $class)
                                        <option value="{{ $class->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['class_id'] == $class->class_id ? 'selected' : '' }}>{{ $class['com_classes']['class_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
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
                            <select class="form-select @if($errors->has('subject_id')) is-invalid @endif" id="subject_id" name="subject_id" aria-label="Select Subject">
                                <option value="">Please select a subject</option>
                                @if(isset($subjects))
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['subject_id'] == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="sectionID" class="form-label">Subject <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('subject_id'))
                                    {{ $errors->first('subject_id') }}
                                @else
                                    Subject is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('term_id')) is-invalid @endif" id="term_id" name="term_id" required>
                                <option value="">Please select a Term</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['term_id'] == $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Term <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('term_id'))
                                    {{ $errors->first('term_id') }}
                                @else
                                    Term is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">

                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if($errors->has('assessment_level_one_id')) is-invalid @endif" data-target="assessment_level_two_id" data-url="{{ 'get-assessment-level-child' }}" id="assessment_level_one_id" name="assessment_level_one_id" required>
                                <option value="">Please select a Level 1</option>
                                @foreach ($assessment_level_one as $level_one)
                                    <option value="{{ $level_one->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['assessment_level_one_id'] == $level_one->id ? 'selected' : '' }}>{{ $level_one->name }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Level 1 <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('assessment_level_one_id'))
                                    {{ $errors->first('assessment_level_one_id') }}
                                @else
                                    Level 1 is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if($errors->has('assessment_level_two_id')) is-invalid @endif" data-target="assessment_level_three_id" data-url="{{ 'get-assessment-level-child' }}" id="assessment_level_two_id" name="assessment_level_two_id" required>
                                <option value="">Please select a Level 2</option>
                                @if(isset($assessment_level_two))
                                    @foreach ($assessment_level_two as $level_two)
                                        <option value="{{ $level_two->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['assessment_level_two_id'] == $level_two->id ? 'selected' : '' }}>{{ $level_two->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Level 2 <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('assessment_level_two_id'))
                                    {{ $errors->first('assessment_level_two_id') }}
                                @else
                                    Level 2 is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('assessment_level_three_id')) is-invalid @endif" id="assessment_level_three_id" name="assessment_level_three_id">
                                <option value="">Please select a Level 3</option>
                                @if(isset($assessment_level_three))
                                    @foreach ($assessment_level_three as $level_three)
                                        <option value="{{ $level_three->id }}" {{ isset($subjectMarksSetup) && $subjectMarksSetup['assessment_level_three_id'] == $level_three->id ? 'selected' : '' }}>{{ $level_three->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Level 3 </label>
                            <div class="invalid-tooltip">
                                @if($errors->has('assessment_level_three_id'))
                                    {{ $errors->first('assessment_level_three_id') }}
                                @else
                                    Level 3 is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control" id="marks" name="marks" placeholder="Marks" value="{{isset($subjectMarksSetup) ? $subjectMarksSetup['marks'] : ''}}" required>
                            <label for="marks" class="form-label">Marks <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('marks'))
                                    {{ $errors->first('marks') }}
                                @else
                                    Marks is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" id="address" name="remarks" rows="2" placeholder="Remarks">{{old('remarks') ? old('remarks') : (isset($subjectMarksSetup['remarks']) ? $subjectMarksSetup['remarks'] : '')}}</textarea>
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
                    @csrf
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#branch_id').on('change',function (){
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-subjects/'+$(this).val();
                $('#class_id').data('url',route);
            });
        });
    </script>
@endpush
