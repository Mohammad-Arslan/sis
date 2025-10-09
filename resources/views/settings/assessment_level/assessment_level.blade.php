<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ isset($assessmentLevel) ? 'Update' : 'Add' }} Assessment Level
            </h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" method="POST"
                    action="{{ isset($assessmentLevel) ? route('assessment-level.update', $assessmentLevel['id']) : route('assessment-level.store') }}"
                    novalidate>
                    @if (isset($assessmentLevel))
                        @method('PATCH')
                    @endif
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" id="name" name="name"
                                placeholder="Assessment Level Name"
                                value="{{ old('name') ? old('name') : (isset($assessmentLevel) ? $assessmentLevel->name : '') }}"
                                required>
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('name'))
                                    {{ $errors->first('name') }}
                                @else
                                    Name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('sort_no')) is-invalid @endif" name="sort_no" id="subjectAbbr"
                                placeholder="Please enter sort_no"
                                value="{{ old('sort_no') ? old('sort_no') : (isset($assessmentLevel) ? $assessmentLevel->sort_no : '') }}">
                            <label for="subjectAbbr" class="form-label">Sort No</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('sort_no'))
                                    {{ $errors->first('sort_no') }}
                                @else
                                    Sort No is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('has_parent')) is-invalid @endif"
                                id="has_parent" name="has_parent" required>
                                <option value="">Please select</option>
                                <option value="1"
                                    {{ isset($assessmentLevel) && $assessmentLevel['parent_id'] ? 'selected' : '' }}>Yes
                                </option>
                                <option value="0"
                                    {{ isset($assessmentLevel) && !$assessmentLevel['parent_id'] ? 'selected' : '' }}>No
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
                    <div class="col-md-6 col-sm-12 parent-div"
                        style="display: {{ isset($assessmentLevel) && $assessmentLevel['parent_id'] ? '' : 'none' }}">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('parent_id')) is-invalid @endif"
                                id="parent_id" name="parent_id">
                                <option value="">Please select a Level</option>
                                @foreach ($assessment_levels as $assessment_level)
                                    <option value="{{ $assessment_level->id }}"
                                        {{ old('parent_id') == $assessment_level->id ? 'selected' : (isset($assessmentLevel) && $assessmentLevel['parent_id'] == $assessment_level->id ? 'selected' : '') }}>
                                        {{ $assessment_level->name }}</option>
                                @endforeach
                            </select>
                            <label for="parent_id" class="form-label">Assessment Level <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('parent_id'))
                                    {{ $errors->first('parent_id') }}
                                @else
                                    Assessment Level is required when Type Has Parent is Yes!
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
                                    {{ old('status') == 'active' ? 'selected' : (isset($assessmentLevel) && $assessmentLevel->status == 'active' ? 'selected' : '') }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('status') == 'inactive' ? 'selected' : (isset($assessmentLevel) && $assessmentLevel->status == 'inactive' ? 'selected' : '') }}>
                                    In Active</option>
                            </select>
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('status'))
                                    {{ $errors->first('status') }}
                                @else
                                    Status is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="description" placeholder="Enter description here...">{{ old('description') ? old('description') : (isset($assessmentLevel) ? $assessmentLevel->description : '') }}</textarea>
                            <label for="Description" class="form-label">Description</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('description'))
                                    {{ $errors->first('description') }}
                                @else
                                    Description is required!
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
            // Initialize on page load
            if ($('#has_parent').val() == '1') {
                $('.parent-div').show();
                $("#parent_id").prop('required', true);
            } else {
                $('.parent-div').hide();
                $("#parent_id").prop('required', false).val('');
            }

            $('#has_parent').on('change', function() {
                if ($(this).val() == '1') {
                    $('.parent-div').show();
                    $("#parent_id").prop('required', true);
                } else {
                    $('.parent-div').hide();
                    $("#parent_id").prop('required', false).val('');
                }
            });

            // Form validation
            $('form').on('submit', function(e) {
                if ($('#has_parent').val() == '1' && $('#parent_id').val() == '') {
                    e.preventDefault();
                    $('#parent_id').addClass('is-invalid');
                    $('.parent-div .invalid-tooltip').text('Please select an Assessment Level.');
                    $('.parent-div .invalid-tooltip').show();
                }
            });
        });
    </script>
@endpush
