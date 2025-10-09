<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ isset($skill) ? 'Update' : 'Add' }} Skill</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" method="POST"
                    action="{{ isset($skill) ? route('skill.update', $skill['id']) : route('skill.store') }}" novalidate>
                    @if (isset($skill))
                        @method('PATCH')
                    @endif

                    <div class="col-md-12 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="title" id="title" placeholder="Enter title here...">{{ old('title') ? old('title') : (isset($skill) ? $skill->title : '') }}</textarea>
                            <label for="title" class="form-label">Title</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('title'))
                                    {{ $errors->first('title') }}
                                @else
                                    Title is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select
                                class="load-select load-select-skills form-select @if ($errors->has('class_id')) is-invalid @endif"
                                data-target="subject_id" data-target2="parent_id" data-url2="{{ route('list-skills') }}"
                                data-url="{{ route('get-class-subjects-by-classID') }}" id="class_id" name="class_id"
                                required>
                                <option value="">Please select a class</option>
                                @if (isset($com_classes))
                                    @foreach ($com_classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ isset($skill) && $skill['class_id'] == $class->id ? 'selected' : '' }}>
                                            {{ $class['class_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
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
                            <select class="form-select @if ($errors->has('subject_id')) is-invalid @endif"
                                id="subject_id" name="subject_id">
                                <option value="">Please select a Subject</option>
                                @if (isset($skill))
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                            {{ isset($skill) && $skill['subject_id'] == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Subject <span class="text-danger">*</span></label>
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
                            <select class="form-select @if ($errors->has('term_id')) is-invalid @endif"
                                id="term_id" name="term_id" required>
                                <option value="">Please select a Term</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}"
                                        {{ isset($skill) && $skill['term_id'] == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Term <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('term_id'))
                                    {{ $errors->first('term_id') }}
                                @else
                                    Term is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('has_parent')) is-invalid @endif"
                                id="has_parent" name="has_parent" required>
                                <option value="">Please select</option>
                                <option value="1" {{ isset($skill) && $skill['parent_id'] ? 'selected' : '' }}>Yes
                                </option>
                                <option value="0" {{ isset($skill) && !$skill['parent_id'] ? 'selected' : '' }}>No
                                </option>
                            </select>
                            <label for="section" class="form-label">Type Has Parent? <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('has_parent'))
                                    {{ $errors->first('has_parent') }}
                                @else
                                    Has Parent is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('type')) is-invalid @endif"
                                id="type" name="type" required>
                                <option value="">Please select</option>
                                <option value="title"
                                    {{ isset($skill) && $skill['type'] == 'title' ? 'selected' : '' }}>Title</option>
                                <option value="grade"
                                    {{ isset($skill) && $skill['type'] == 'grade' ? 'selected' : '' }}>Grade</option>
                                <option value="checkbox"
                                    {{ isset($skill) && $skill['type'] == 'checkbox' ? 'selected' : '' }}>Checkbox
                                </option>
                            </select>
                            <label for="section" class="form-label">Skill Type <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('type'))
                                    {{ $errors->first('type') }}
                                @else
                                    Skill Type is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control" id="sort_no" name="sort_no"
                                value="{{ isset($skill) ? $skill['sort_no'] : '' }}">
                            <label for="section" class="form-label">Sort No</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('sort_no'))
                                    {{ $errors->first('sort_no') }}
                                @else
                                    Sort No is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 parent-div"
                        style="display: {{ isset($skill) && $skill['parent_id'] ? '' : 'none' }}">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('parent_id')) is-invalid @endif"
                                id="parent_id" name="parent_id">
                                <option value="">Please select a Skill</option>
                                @foreach ($skills as $active_skill)
                                    <option value="{{ $active_skill->id }}"
                                        {{ isset($skill) && $skill['parent_id'] == $active_skill->id ? 'selected' : '' }}>
                                        {{ $active_skill->title }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Skill <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('parent_id'))
                                    {{ $errors->first('parent_id') }}
                                @else
                                    Skill is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('language')) is-invalid @endif"
                                name="language" required>
                                <option value="english"
                                    {{ old('language') == 'english' ? 'selected' : (isset($skill) && $skill->language == 'active' ? 'selected' : '') }}>
                                    English</option>
                                <option value="urdu"
                                    {{ old('language') == 'urdu' ? 'selected' : (isset($skill) && $skill->language == 'inactive' ? 'selected' : '') }}>
                                    Urdu</option>
                            </select>
                            <label for="language" class="form-label">Language <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('language'))
                                    {{ $errors->first('language') }}
                                @else
                                    Language is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('status')) is-invalid @endif"
                                name="status" required>
                                <option value="">Please select</option>
                                <option value="active"
                                    {{ old('status') == 'active' ? 'selected' : (isset($skill) && $skill->status == 'active' ? 'selected' : '') }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('status') == 'inactive' ? 'selected' : (isset($skill) && $skill->status == 'inactive' ? 'selected' : '') }}>
                                    In Active</option>
                            </select>
                            <label for="status" class="form-label">Status <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('status'))
                                    {{ $errors->first('status') }}
                                @else
                                    Status is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    @csrf
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


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#has_parent').on('change', function() {
                if ($(this).val() == '1') {
                    $('.parent-div').show();
                    $("#parent_id").prop('required', true);
                } else {
                    $('.parent-div').hide();
                    $("#parent_id").prop('required', false).val('');
                }
            });
        });
    </script>
@endpush
