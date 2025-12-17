<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ isset($gradingCriteria) ? 'Update' : 'Add' }} Grading Criteria
            </h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" method="POST"
                    action="{{ isset($gradingCriteria) ? route('grading-criteria.update', $gradingCriteria['id']) : route('grading-criteria.store') }}"
                    novalidate>
                    @if (isset($gradingCriteria))
                        @method('PATCH')
                    @endif
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                value="{{ old('title') ? old('title') : (isset($gradingCriteria) ? $gradingCriteria->title : '') }}"
                                required>
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
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
                            <input type="text" class="form-control" id="grading_key" name="grading_key"
                                placeholder="Grading Key"
                                value="{{ old('grading_key') ? old('grading_key') : (isset($gradingCriteria) ? $gradingCriteria->grading_key : '') }}"
                                required>
                            <label for="grading_key" class="form-label">Grading Key <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('grading_key'))
                                    {{ $errors->first('grading_key') }}
                                @else
                                    Grading Key is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select select2 @if ($errors->has('class_id')) is-invalid @endif"
                                id="class_id" name="class_ids[]" multiple="multiple" required>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ isset($gradingCriteria) && $gradingCriteria['classes'] && in_array($class->id, $gradingCriteria['classes']->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $class->class_name }}</option>
                                @endforeach
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
                            <input type="number" class="form-control" id="starting_percentage"
                                name="starting_percentage" placeholder="Starting Percentage"
                                value="{{ old('starting_percentage') ? old('starting_percentage') : (isset($gradingCriteria) ? $gradingCriteria->starting_percentage : '') }}"
                                required>
                            <label for="starting_percentage" class="form-label">Starting Percentage <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('starting_percentage'))
                                    {{ $errors->first('starting_percentage') }}
                                @else
                                    Starting Percentage is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control" id="ending_percentage" name="ending_percentage"
                                placeholder="Ending Percentage"
                                value="{{ old('ending_percentage') ? old('ending_percentage') : (isset($gradingCriteria) ? $gradingCriteria->ending_percentage : '') }}"
                                required>
                            <label for="ending_percentage" class="form-label">Ending Percentage <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('ending_percentage'))
                                    {{ $errors->first('ending_percentage') }}
                                @else
                                    Ending Percentage is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('status')) is-invalid @endif"
                                name="status" required>
                                <option value="">Please select</option>
                                <option value="active"
                                    {{ old('status') == 'active' ? 'selected' : (isset($gradingCriteria) && $gradingCriteria->status == 'active' ? 'selected' : '') }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('status') == 'inactive' ? 'selected' : (isset($gradingCriteria) && $gradingCriteria->status == 'inactive' ? 'selected' : '') }}>
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

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="Description" placeholder="Enter description here...">{{ old('description') ? old('description') : (isset($gradingCriteria) ? $gradingCriteria->description : '') }}</textarea>
                            <label for="Description" class="form-label">Description</label>
                        </div>
                    </div>
                    @csrf
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                        <a href="{{ route('grading-criteria.index') }}" 
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $(".select2").select2({
                placeholder: $(this).data('placeholder'),
            });

            $('#has_parent').on('change', function() {
                if ($(this).val() == '1') {
                    $('.parent-div').show();
                    $("#parent_id").prop('required', true);
                } else {
                    $('.parent-div').hide();
                    $("#parent_id").prop('required', false).val('0');
                }
            });
        });
    </script>
@endpush
