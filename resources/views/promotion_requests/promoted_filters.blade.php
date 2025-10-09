@php
    $isNorIndividualRequest =
        request()
            ->route()
            ->getName() != 'promotion-requests.individual';
    isset($promotionRequest) && $promotionRequest->is_promotion == 1 ? ($isPromotion = true) : ($isPromotion = false);
@endphp
@push('header_scripts')
    <style>
        .is_promotion {
            display: none;
        }
    </style>
@endpush
<div class="col-md-4 col-sm-12">
    <div class="form-label-group in-border">
        <select class="form-select @if ($errors->has('type')) is-invalid @endif" id="is_promotion"
            name="is_promotion" required>
            <option value="">Please select a Type</option>
            <option value="1"
                {{ isset($promotionRequest) && $promotionRequest->is_promotion == 1 ? 'selected' : (old('is_promotion') == 1 ? 'selected' : '') }}>
                Promotion
            </option>
            <option value="0"
                {{ isset($promotionRequest) && $promotionRequest->is_promotion == 0 ? 'selected' : (old('is_promotion') === 0 ? 'selected' : '') }}>
                Pass-out
            </option>
        </select>
        <label for="is_promotion" class="form-label">Type <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('type'))
                {{ $errors->first('type') }}
            @else
                Type is required!
            @endif
        </div>
    </div>
</div>
{{-- @if ($isNorIndividualRequest) --}}
{{-- <div class="col-md-4 col-sm-12 is_promotion">
    <div class="form-label-group in-border">
        <select class="load-select form-select @if ($errors->has('promoted_branch_id')) is-invalid @endif"
            data-target="promoted_branch_class_id" data-url="{{ route('list-branch-classes') }}" id="promoted_branch_id"
            name="promoted_branch_id" aria-label="Branch select" required>
            <option value="">Please select</option>
            @if (isset($branch))
                <option value="{{ $branch['id'] }}" selected>
                    {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
            @else
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}"
                        {{ (isset($promotionRequest) && $promotionRequest->cur_branch_id == $branch->id) || (old('promoted_branch_id') == $branch->id) ? 'selected' : '' }}>
                        {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                @endforeach
            @endif
        </select>
        <label for="promoted_branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('promoted_branch_id'))
                {{ $errors->first('promoted_branch_id') }}
            @else
                Branch is required!
            @endif
        </div>
    </div>
</div> --}}
<div class="col-md-4 col-sm-12 is_promotion">
    <div class="form-label-group in-border">
        <select class="load-select form-select @if ($errors->has('promoted_branch_id')) is-invalid @endif"
            data-target="promoted_branch_class_id" data-url="{{ route('list-branch-classes') }}" id="promoted_branch_id"
            name="promoted_branch_id" aria-label="Branch select" required>
            <option value="">Please select</option>
            @if (isset($branch))
                <option value="{{ $branch['id'] }}" selected>
                    {{ $branch['br_name'] . ' (' . $branch['branch_code'] . ')' }}</option>
            @else
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}"
                        {{ (isset($promotionRequest) && $promotionRequest->cur_branch_id == $branch->id) || (old('promoted_branch_id') == $branch->id) ? 'selected' : '' }}>
                        {{ $branch->br_name . ' (' . $branch->branch_code . ')' }}</option>
                @endforeach
            @endif
        </select>
        <label for="promoted_branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('promoted_branch_id'))
                {{ $errors->first('promoted_branch_id') }}
            @else
                Branch is required!
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectElement = document.getElementById('promoted_branch_id');
        selectElement.addEventListener('mousedown', function(event) {
            event.preventDefault();
        });
        selectElement.addEventListener('keydown', function(event) {
            event.preventDefault();
        });
    });
</script>

<div class="col-md-4 col-sm-12 is_promotion">
    <div class="form-label-group in-border">
        <select class="form-select  @if ($errors->has('promoted_academic_year_id')) is-invalid @endif" id="promoted_academic_year_id"
            name="promoted_academic_year_id" required>
            <option value="">Please select Academic Year</option>
            @foreach ($academic_years as $academic_year)
                <option value="{{ $academic_year->id }}"
                    {{ (isset($promotionRequest) && $promotionRequest->cur_academic_year_id == $academic_year->id) || (old('promoted_academic_year_id') == $academic_year->id) ? 'selected' : '' }}>
                    {{ $academic_year->title }}</option>
            @endforeach
        </select>
        <label for="section" class="form-label">Academic Year <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('promoted_academic_year_id'))
                {{ $errors->first('promoted_academic_year_id') }}
            @else
                Academic Year is required!
            @endif
        </div>
    </div>
</div>
{{-- @endif --}}
<div class="col-md-6 col-sm-12 is_promotion">
    <div class="form-label-group in-border">
        <select class="form-select @if ($errors->has('promoted_branch_class_id')) is-invalid @endif"
            data-target="subject_id,promoted_section_id"
            data-url="{{ isset($promotionRequest) ? route('get-class-section-subjects', $promotionRequest->cur_branch_id) : '' }}"
            id="promoted_branch_class_id" name="promoted_branch_class_id" required>
            <option value="">Please select a class</option>
            @if (isset($promotionRequest))
                @foreach ($branch_classes as $branch_class)
                    <option value="{{ $branch_class->id }}"
                        {{ ($branch_class->class_id == $promotionRequest->cur_class_id) || (old('promoted_branch_class_id') == $branch_class->id) ? 'selected' : '' }}>
                        {{ $branch_class->com_classes->class_name }}</option>
                @endforeach
            @else
                {{-- Classes will be loaded via AJAX when branch is selected --}}
            @endif
        </select>
        <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('promoted_branch_class_id'))
                {{ $errors->first('promoted_branch_class_id') }}
            @else
                Class is required!
            @endif
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-12 is_promotion">
    <div class="form-label-group in-border">
        <select class="form-select  @if ($errors->has('promoted_section_id')) is-invalid @endif" id="promoted_section_id"
            name="promoted_section_id" aria-label="Branch select" required>
            <option value="">Please select a section</option>
            @if (isset($promotionRequest))
                @foreach ($class_sections as $class_section)
                    <option value="{{ $class_section->section_id }}"
                        {{ ($class_section->section_id == $promotionRequest->cur_section_id) || (old('promoted_section_id') == $class_section->section_id) ? 'selected' : '' }}>
                        {{ $class_section->sections->section_name }}</option>
                @endforeach
            @else
                {{-- Sections will be loaded via AJAX when class is selected --}}
            @endif
        </select>
        <label class="form-label">Section <span class="text-danger">*</span></label>
        <div class="invalid-tooltip">
            @if ($errors->has('promoted_section_id'))
                {{ $errors->first('promoted_section_id') }}
            @else
                Section is required!
            @endif
        </div>
    </div>
</div>

@push('footer_scripts')
    <script>
        $(document).ready(function() {
            // Set edit mode flag to prevent interference
            const isEditMode = '{{ isset($promotionRequest) }}';
            
            // Only run JavaScript if not in edit mode
            if (!isEditMode) {
                // Load classes and sections if there are validation errors
                if ($('#promoted_branch_id').val() && $('#promoted_branch_id').val() !== '' && '{{ old("promoted_branch_class_id") }}') {
                    loadClassesAndSections();
                }
                
                $('#branch_id').on('change', function() {
                    $('#promoted_branch_id').val($(this).val());
                    $('#promoted_branch_id').trigger('change');
                });
            }

            // Always allow promotion type change handler (needed to show/hide fields)
            $('#is_promotion').on('change', function() {
                if ($(this).val() == 1) {
                    $('.is_promotion').show();
                    $('#promoted_branch_class_id').attr('required', true);
                    $('#promoted_section_id').attr('required', true);

                    // Only run validation if not in edit mode
                    if (!isEditMode) {
                        !isPromotion() ? $('#promoted_academic_year_id').addClass('is-invalid').next().next()
                            .text('Please select higher academic year') : $('#promoted_academic_year_id')
                            .removeClass('is-invalid').next().next().text('Academic year is required!');
                    }

                    return false;
                } else if ($(this).val() == 0) {
                    $('.is_promotion').hide();
                    $('#promoted_academic_year_id').parent().parent().show();
                    $('#promoted_branch_id').parent().parent().show();
                    $('#promoted_branch_class_id').attr('required', false);
                    $('#promoted_section_id').attr('required', false);

                    // Only run validation if not in edit mode
                    if (!isEditMode) {
                        isPassOut() ? $('#promoted_academic_year_id').removeClass('is-invalid').next().next()
                            .text('Academic year is required!') : $('#promoted_academic_year_id').addClass(
                                'is-invalid').next().next().text('Please select same academic year');
                    }
                    return false;
                }
            });
            // Always trigger change for promotion type to show/hide fields (needed for edit mode)
            {!! isset($promotionRequest) ? '$(\'#is_promotion\').trigger(\'change\');' : '' !!}
            
            // Only run validation handlers if not in edit mode
            if (!isEditMode) {
                $('#academic_year_id').on('change', validateYear);
                $('#promoted_academic_year_id').on('change', validateYear);
            }

            function validateYear() {
                let promoted_year = $('#promoted_academic_year_id option:selected').text().trim();
                let is_promotion = $('#is_promotion').val();

                if (promoted_year && is_promotion === '1') {
                    !isPromotion() ? $('#promoted_academic_year_id').addClass('is-invalid').next().next().text(
                        'Please select higher academic year') : $('#promoted_academic_year_id').removeClass(
                        'is-invalid').next().next().text('Academic year is required!');

                } else if (is_promotion === '0') {
                    isPassOut() ? $('#promoted_academic_year_id').removeClass('is-invalid').next().next().text(
                            'Academic year is required!') : $('#promoted_academic_year_id').addClass('is-invalid')
                        .next().next().text('Please select same academic year');
                    return false;
                }
            }

            function isPassOut() {
                let is_individual = `{{ request()->route()->getName() }}` === 'promotion-requests.individual';
                let prev_year = is_individual ? $('#academic_year_id').val() : $(
                    '#academic_year_id option:selected').text().trim();
                let curr_year = $('#promoted_academic_year_id option:selected').text().trim();

                return prev_year === curr_year;
            }

            function isPromotion() {
                let is_individual = `{{ request()->route()->getName() }}` === 'promotion-requests.individual';
                is_individual = !!(
                    {{ isset($promotionRequest) && $promotionRequest->type == 'individual'
                        ? 'true'
                        : (request()->route()->getName() === 'promotion-requests.individual'
                            ? 1
                            : 0) }}
                    );
                let prev_year = is_individual ? $('#academic_year_id').val().split('-')[1] : $(
                    '#academic_year_id option:selected').text().trim().split('-')[1];
                let curr_year = $('#promoted_academic_year_id option:selected').text().trim().split('-')[1];
                return prev_year < curr_year;
            }

            // Only run form and class change handlers if not in edit mode
            if (!isEditMode) {
                $('.promotion-form').on('submit', function(e) {
                    if (!isPromotion() && $('#is_promotion').val() === '1') {
                        if(!validateClass()){
                            e.preventDefault();
                            e.stopPropagation();

                            setTimeout(function() {
                                $('.promotion-form').removeClass('was-validated');
                            }, 100);
                        }
                    } else if (!isPassOut() && $('#is_promotion').val() === '0') {
                        e.preventDefault();
                        e.stopPropagation();
                        setTimeout(function() {
                            $('.promotion-form').removeClass('was-validated');
                        }, 100);
                    } else {
                        return true;
                    }
                });
                
                $('#promoted_branch_class_id').on('change', function() {
                    validateClass();
                    if ($(this).val()) {
                        // Only load sections if this is not the initial page load
                        if (!$(this).data('initial-load')) {
                            loadSections($(this).val());
                        }
                    } else {
                        $('#promoted_section_id').empty().append('<option value="">Please select a section</option>');
                    }
                });
            }

            function validateClass() {
                const next_class_id = $('#promoted_branch_class_id option:selected').val();
                const prev_class_name = $('#class').val();
                const prev_class_id = $('#promoted_branch_class_id option').filter(function() {
                    return $(this).text().trim() === prev_class_name.trim();
                }).val();
                // if (prev_class_id !== undefined && prev_class_id < next_class_id) {
                //     // The prev_class_id variable now contains the ID of the previous class
                //     $('#promoted_branch_class_id').removeClass('is-invalid').next().next().text(
                //         'Class is required');
                //     return true;
                // } else {
                //     $('#promoted_branch_class_id').addClass('is-invalid').next().next().text(
                //         'Please select higher class');
                //     return false;
                // }
            }
            
            function loadClassesAndSections() {
                const branchId = $('#promoted_branch_id').val();
                if (branchId) {
                    // Get current selected class ID (either from old input or current promotion request)
                    const currentSelectedClassId = '{{ old("promoted_branch_class_id") }}' || '{{ isset($promotionRequest) ? $promotionRequest->cur_branch_class_section_id : "" }}';
                    const currentClassId = '{{ isset($promotionRequest) ? $promotionRequest->cur_class_id : "" }}';
                    
                    // Set initial-load flag to prevent change event from triggering loadSections
                    $('#promoted_branch_class_id').data('initial-load', true);
                    
                    // Load classes
                    $.ajax({
                        url: '{{ route("list-branch-classes") }}',
                        method: 'GET',
                        data: { branch_id: branchId },
                        success: function(data) {
                            $('#promoted_branch_class_id').empty().append('<option value="">Please select a class</option>');
                            $.each(data, function(index, item) {
                                // Check if this is the selected class by comparing either the branch_class_id or the class_id
                                const selected = (item.id == currentSelectedClassId) || (item.class_id == currentClassId) ? 'selected' : '';
                                $('#promoted_branch_class_id').append(`<option value="${item.id}" ${selected}>${item.com_classes.class_name}</option>`);
                            });
                            
                            // Load sections if class is selected
                            if (currentSelectedClassId) {
                                loadSections(currentSelectedClassId);
                            }
                            
                            // Clear the initial-load flag after setup is complete
                            setTimeout(function() {
                                $('#promoted_branch_class_id').removeData('initial-load');
                            }, 100);
                        }
                    });
                }
            }
            
            function loadSections(classId) {
                // Get current selected section ID (either from old input or current promotion request)
                const currentSelectedSectionId = '{{ old("promoted_section_id") }}' || '{{ isset($promotionRequest) ? $promotionRequest->cur_section_id : "" }}';
                
                $.ajax({
                    url: '{{ route("get-class-section-subjects", ":id") }}'.replace(':id', $('#promoted_branch_id').val()),
                    method: 'GET',
                    data: { class_id: classId },
                    success: function(data) {
                        $('#promoted_section_id').empty().append('<option value="">Please select a section</option>');
                        $.each(data.sections, function(index, item) {
                            const selected = item.id == currentSelectedSectionId ? 'selected' : '';
                            $('#promoted_section_id').append(`<option value="${item.id}" ${selected}>${item.section_name}</option>`);
                        });
                    }
                });
            }
        });
    </script>
@endpush
