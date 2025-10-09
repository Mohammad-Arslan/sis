@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Add New Curriculum</li>
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Curriculum Form</h4>
                </div><!-- end card header -->

                <div class="card-body">

                    {{--<h5 class="text-muted d-flex align-items-center mb-3">
                        <i class="ri-folder-shield-2-fill me-1"></i>Student Information</h5>--}}
                    <form class="row g-3 needs-validation" novalidate method="POST"
                          action="{{ route('curriculum.store') }}">

                        <!-- Withdrawal info section start -->
                        {{--<div class="border mt-3 border-dashed"></div>--}}

                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border applicant_relation_field_container">
                                <select class="filter form-select @if ($errors->has('curriculum_type_id')) is-invalid @endif"
                                        id="curriculum_type_id" name="curriculum_type_id">
                                    <option value="">Please Select</option>
                                    @foreach ($curriculum_types as $type)
                                        <option value="{{ $type->id }}"
                                        {{ old('curriculum_type_id') == $type->id ? 'selected' : '' }}

                                        >{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="curriculum_type_id" class="form-label">Curriculum Type</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('curriculum_type_id'))
                                        {{ $errors->first('curriculum_type_id') }}
                                    @else
                                        Curriculum Type is required!
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border applicant_relation_field_container">
                                <select class="filter form-select @if ($errors->has('class_id')) is-invalid @endif"
                                        id="class_id" name="class_id"
                                        placeholder="Class">
                                    <option value="">Please Select</option>
                                    @if(!isSuperAdmin() && !isHeadOfficeEmp())
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->com_classes->id }}">{{ $class->com_classes->class_name }} </option>
                                        @endforeach
                                    @else
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="class_id" class="form-label">Class</label>
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
                            <div class="form-label-group in-border applicant_relation_field_container">
                                <select class="filter form-select @if ($errors->has('subject_id')) is-invalid @endif"
                                        id="subject_id" name="subject_id" placeholder="Subject">
                                    <option value="">Please Select</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                                <label for="subject_id" class="form-label">Subjects</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('subject_id'))
                                        {{ $errors->first('subject_id') }}
                                    @else
                                        Subject is required!
                                    @endif
                                </div>
                            </div>
                        </div>


                        {{--<div class="col-md-4 col-sm-12">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_strands"
                                       name="is_strands" {{ old('is_strands') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_strands">
                                    Is Strands
                                </label>
                            </div>
                        </div>--}}

                       {{-- <div class="col-md-4 col-md-12">
                            <div class="form-label-group in-border">
                                <input type="text"
                                       class="form-control @if ($errors->has('title')) is-invalid @endif"
                                       id="title" name="title" placeholder="Please enter curriculum title" value="{{ old('title') }}">
                                <label for="title" class="form-label">Title</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('title'))
                                        {{ $errors->first('title') }}
                                    @else
                                        Title is required!
                                    @endif
                                </div>
                            </div>
                        </div>--}}

                        {{--<div class="col-md-12">
                            <div class="form-label-group in-border">
                                <textarea class="form-control" id="remarks" name="description" rows="15"
                                              placeholder="Description">{{ old('description') }}</textarea>
                                <label for="description" class="form-label">Description </label>
                                <div class="invalid-tooltip">

                                </div>
                            </div>

                            <div class="ckeditor-classic"></div>

                        </div>--}}
                        <!-- Withdrawal info section end -->

                        <!-- Dues info section start -->

                        <div class="border mt-3 border-dashed"></div>

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
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">

        $( document ).ready(function() {
            if($('#studentRollNo').val() != ''){
                onStudentChange();
                onRelationChange();
            }
        });

        $(document).on('change', '#applicationDate', onApplicationDateChange);

        $("#applicationDate").flatpickr({
            maxDate: "today",
            // maxDate: new Date().fp_incr(7) // 7 days from now
        });

        $("#lastDayAt").flatpickr({
            maxDate: "today"
        });

        function onApplicationDateChange() {
            var aplicationDateObj = dmyStringToData(this.value)
            var date = addDaysToDate(aplicationDateObj, 30);
            var fpDate = flatpickr('#lastDayAt', {maxDate: "today"})
            fpDate.setDate(date);
        }

        $(document).on('change', '#libraryClearance', onLibraryClearanceChange);

        function onLibraryClearanceChange() {
            if (this.checked) $('#clearanceAmount').attr('disabled', false)
            else $('#clearanceAmount').attr('disabled', true)
        }

        $(document).on('change', '#approved_date', onApproveDataChange);

        function onApproveDataChange() {
            var aplicationDateObj = dmyStringToData(this.value)
            var date = addDaysToDate(aplicationDateObj, 30);
            var fpDate = flatpickr('#approved_date', {
                minDate: "today"
            });
            fpDate.setDate(date);
        }




    </script>
    <script src="{{ asset('js/pages/form-editor.init.js') }}"></script>
    <!-- ckeditor -->
    <script src="{{ asset('libs/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
@endpush
