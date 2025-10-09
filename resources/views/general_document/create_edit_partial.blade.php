<div class="live-preview">
    <form class="row g-3 needs-validation" method="POST" action="{{isset($general_document) ? route('general-document.update',$general_document->id) : route('general-document.store') }}" novalidate enctype="multipart/form-data">

        @if(isset($general_document))
            @method('PATCH')
        @endif

        @if(isset($student))
            <input type="hidden" name="student_id" value="{{$student->id}}">
        @endif

        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if($errors->has('document_name')) is-invalid @endif" id="document_name" name="document_name" placeholder="Please enter document name" value="{{ old('document_name') ? old('document_name') : (isset($general_document) ? $general_document->document_name : '')}}" required>
                <label for="document_name" class="form-label">Document Name <span class="text-danger">*</span></label>
                <div class="invalid-tooltip">
                    @if($errors->has('document_name'))
                        {{ $errors->first('document_name') }}
                    @else
                        Document Name is required!
                    @endif
                </div>
            </div>
        </div>
        @if(!isset($student))
            <div class="col-md-4 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="form-select" id="branch_id" name="branch_id">
                        <option value="">Please select</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{old('branch_id') == $branch->id ? 'selected' : (isset($general_document) && $general_document->branch_id == $branch->id ? 'selected' : '') }}>{{ $branch->br_name }}</option>
                        @endforeach
                    </select>
                    <label for="branch_id" class="form-label">Branch</label>
                </div>
            </div>
        @endif
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="form-select @if($errors->has('attachment_type_id')) is-invalid @endif" id="attachment_type_id" name="attachment_type_id" required>
                    <option value="">Please select</option>
                    @foreach($attachment_types as $type)
                        <option value="{{$type->id}}" {{old('attachment_type_id') == $type->id ? 'selected' : (isset($general_document) && $general_document->attachment_type_id == $type->id ? 'selected' : '') }} data-slug="{{$type->slug}}">{{$type->name}}</option>
                    @endforeach
                </select>
                <label for="attachment_type_id" class="form-label">Document For (Type) <span class="text-danger">*</span></label>
                <div class="invalid-tooltip">
                    @if($errors->has('attachment_type_id'))
                        {{ $errors->first('attachment_type_id') }}
                    @else
                        Document For (Type) is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-label-group in-border">
                <select class="form-select @if($errors->has('document_type')) is-invalid @endif" id="document_type" name="document_type" required>
                    <option value="">Please select</option>
                    <option value="file" {{isset($general_document) && $general_document->document_type == 'file' ? 'selected' : ''}}>File</option>
                    <option value="url" {{isset($general_document) && $general_document->document_type == 'url' ? 'selected' : ''}}>URL</option>
                </select>
                <label for="file" class="form-label">Document Type <span class="text-danger">*</span></label>
                <div class="invalid-tooltip">
                    @if($errors->has('document_type'))
                        {{ $errors->first('document_type') }}
                    @else
                        Document Type is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 attachment_div" style="display:{{isset($general_document) ? ($general_document['document_type'] == 'file' ? '' : 'none') : 'none'}}">
            <div class="form-label-group in-border">
                <input type="file" class="form-control @if($errors->has('file')) is-invalid @endif" id="file" name="file" placeholder="file">
                <label for="file" class="form-label"> Attachment</label>
                <div class="invalid-tooltip">
                    @if($errors->has('file'))
                        {{ $errors->first('file') }}
                    @else
                        Attachment is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 url_div" style="display:{{isset($general_document) ? ($general_document['document_type'] == 'url' ? '' : 'none') : 'none'}}">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if($errors->has('url')) is-invalid @endif" id="url" name="url" placeholder="Enter URL" value="{{isset($general_document) ? $general_document['file_name'] : ''}}">
                <label for="url" class="form-label"> URL</label>
                <div class="invalid-tooltip">
                    @if($errors->has('url'))
                        {{ $errors->first('url') }}
                    @else
                        URL is required!
                    @endif
                </div>
            </div>
        </div>
        @if(!isset($student))
            <div class="col-md-4 col-sm-12 booklist-div sow-div assessment-paper-div syllabus-div datesheet-div" style="display:{{isset($general_document) && in_array($general_document['attachment_type']['slug'],['booklist','scheme_of_work','assessment_paper_student_copy','assessment_paper_marking_key','syllabus','datesheet', 'admission_test']) ? '' : 'none'}}">
                <div class="form-label-group in-border">
                    <select class="form-select @if($errors->has('academic_year_id')) is-invalid @endif" id="academicYear" name="academic_year_id" aria-label="Academic year select">
                        <option value="">Please select a academic year</option>
                        @if(isset($academic_years))
                            @foreach ($academic_years as $academic_year)
                                <option value="{{ $academic_year->id }}" {{isset($general_document) && $general_document['academic_year_id'] == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                            @endforeach
                        @endif
                    </select>
                    <label for="academicYear" class="form-label">Academic Year</label>
                    <div class="invalid-tooltip">
                        @if($errors->has('academic_class_id'))
                            {{ $errors->first('academic_class_id') }}
                        @else
                            Academic year is required!
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="form-select @if($errors->has('state_id')) is-invalid @endif" id="state_id" name="state_id">
                        <option value="">Please select</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}" {{ isset($general_document) && $state->id == $general_document['state_id'] ? 'selected' : '' }}>{{ $state->state_name }}</option>
                        @endforeach
                    </select>
                    <label for="state_id" class="form-label">Province</label>
                    <div class="invalid-tooltip">
                        @if($errors->has('state_id'))
                            {{ $errors->first('state_id') }}
                        @else
                            Province is required!
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 sow-div assessment-paper-div syllabus-div datesheet-div" style="display:{{isset($general_document) && in_array($general_document['attachment_type']['slug'],['scheme_of_work','assessment_paper_student_copy','assessment_paper_marking_key','syllabus','datesheet','admission_test']) ? '' : 'none'}}">
                <div class="form-label-group in-border">
                    <select class="load-select form-select @if($errors->has('class_id')) is-invalid @endif"
                            data-target="subject_id"
                            data-url="{{ route('list-class-subjects') }}"
                            id="class_id" name="com_class_id">
                        <option value="">Please select a class</option>
                        @if(isset($classes))
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ isset($general_document) && $general_document['com_class_id'] == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <label for="class" class="form-label">Class</label>
                    <div class="invalid-tooltip">
                        @if($errors->has('class_id'))
                            {{ $errors->first('class_id') }}
                        @else
                            Class is required!
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12 sow-div assessment-paper-div" style="display:{{isset($general_document) && in_array($general_document['attachment_type']['slug'],['scheme_of_work','assessment_paper_student_copy','assessment_paper_marking_key', 'admission_test']) ? '' : 'none'}}">
                <div class="form-label-group in-border">
                    <select class="form-select @if($errors->has('subject_id')) is-invalid @endif" id="subject_id" name="subject_id" aria-label="Section select">
                        <option value="">Please select a subject</option>
                        @if(isset($subjects))
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $general_document['subject_id'] == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <label for="section" class="form-label">Subject</label>
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
                    <select class="form-select @if($errors->has('status')) is-invalid @endif" name="status" required>
                        <option value="">Please select</option>
                        <option value="active"  {{old('status') == 'active' ? 'selected' : (isset($general_document) && $general_document->status == 'active' ? 'selected' : '') }}>Active</option>
                        <option value="inactive"  {{old('status') == 'inactive' ? 'selected' : (isset($general_document) && $general_document->status == 'inactive' ? 'selected' : '') }}>In Active</option>
                    </select>
                    <label for="status" class="form-label">Status</label>
                    <div class="invalid-tooltip">
                        @if($errors->has('status'))
                            {{ $errors->first('status') }}
                        @else
                            Status is required!
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-12 col-sm-12">
            <div class="form-label-group in-border">
                <textarea class="form-control @if($errors->has('remarks')) is-invalid @endif" id="remarks" name="remarks" placeholder="remarks">{{ old('remarks') ? old('remarks') : (isset($general_document) ? $general_document->remarks : '')}}</textarea>
                <label for="details" class="form-label">Remarks</label>
                <div class="invalid-tooltip">
                    @if($errors->has('remarks'))
                        {{ $errors->first('remarks') }}
                    @endif
                </div>
            </div>
        </div>

        @csrf
        <div class="col-12 text-end">
            <button class="btn btn-primary" type="submit">Save Changes</button>
            <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
        </div>
    </form>
</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('change','#attachment_type_id',function(e){
                let selected_slug = $(this).find(':selected').data('slug');
                $('.booklist-div').hide();
                $('.sow-div').hide();
                $('.assessment-paper-div').hide();
                $('.datesheet-div').hide();
                $('.syllabus-div').hide();
                //$("#state_id").prop('required',false);
                $("#class_id").prop('required',false);
                $("#subject_id").prop('required',false);
                $("#academicYear").prop('required',false);

                if (selected_slug == 'booklist'){
                    $("#academicYear").prop('required',true);
                    //$("#state_id").prop('required',true);
                    //$("#class_id").prop('required',true);
                    $('.booklist-div').show();
                }
                else if(selected_slug == 'scheme_of_work' || selected_slug == 'admission_test'){
                    $("#academicYear").prop('required',true);
                    //$("#state_id").prop('required',true);
                    $("#class_id").prop('required',true);
                    //$("#subject_id").prop('required',true);
                    $('.sow-div').show();
                }
                else if(selected_slug == 'assessment_paper_student_copy' || selected_slug == 'assessment_paper_marking_key'){
                    $("#academicYear").prop('required',true);
                    //$("#state_id").prop('required',true);
                    $("#class_id").prop('required',true);
                    $("#subject_id").prop('required',true);
                    $('.assessment-paper-div').show();
                }
                else if(selected_slug == 'syllabus'){
                    $("#academicYear").prop('required',true);
                    //$("#state_id").prop('required',true);
                    $("#class_id").prop('required',true);
                    $('.syllabus-div').show();
                }
                else if(selected_slug == 'datesheet'){
                    $("#academicYear").prop('required',true);
                    //$("#state_id").prop('required',true);
                    $("#class_id").prop('required',true);
                    $('.datesheet-div').show();
                }
            });

            $(document).on('change','#document_type',function(e){
                $("#file").prop('required',false);
                $("#url").prop('required',false);
                $(".attachment_div").hide();
                $(".url_div").hide();
                let selected_val = $(this).val();

                if (selected_val == 'file'){
                    $("#file").prop('required',true);
                    $('.attachment_div').show();
                }
                else if(selected_val == 'url'){
                    $("#url").prop('required',true);
                    $('.url_div').show();
                }
            });
        });
    </script>
@endpush
