@extends('layouts.master')
@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lesson-plans.index') }}">Lesson Plan List</a></li>
        <li class="breadcrumb-item active">Evaluation Lesson Plan</li>
    </x-breadcrumb>

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Evaluation Lesson Plan</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-sm btn-primary" href="{{route('lesson-plans.show',$lessonPlan->id)}}">View Lesson Plan</a>
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <form class="row needs-validation" action="{{route('lesson-plans.update',$lessonPlan->id)}}" method="POST" id="taught_date_form" novalidate>
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-2">
                        <img src="{{ asset('ucs-icon.svg') }}" width="70" class="img-fluid" alt="img">
                    </div>
                    <div class="col-md-8 text-center">
                        <h4>{{__('United Charter Schools')}}</h4>
                        <h6>({{$lessonPlan['academic_year']['title']}})</h6>
                        <h4>{{$lessonPlan['topic']}}</h4>
                        <h6>{{$lessonPlan['term']['name']}} / {{$lessonPlan['week']['name']}} / Day {{$lessonPlan['day']}}</h6>
                    </div>
                    <div class="col-md-2"></div>
                </div>

                <div class="row mt-4 text-center">
                    <div class="col-lg-4 col-6">
                        <b class="">School Type</b>
                        <h5 class="fs-13 mt-2">{{isset($lessonPlan['com_class']['class_group_class']['class_group']) ? $lessonPlan['com_class']['class_group_class']['class_group']['name'] : '-'}}</h5>
                    </div>
                    <div class="col-lg-4 col-6">
                        <b>Class</b>
                        <h5 class="fs-13 mt-2">{{$lessonPlan['com_class']['class_name']}}</h5>
                    </div>
                    <div class="col-lg-4 col-6">
                        <b>Subject</b>
                        <h5 class="fs-13 mt-2">{{$lessonPlan['subject']['subject_name']}}</h5>
                    </div>
                </div>
                <div class="row mt-4 text-center">
                    <div class="col-lg-4 col-6">
                        <b>Theme / (Unit/Chapter)</b>
                        <h5 class="fs-13 mt-2">{{$lessonPlan['theme']}} / {{$lessonPlan['chapter']}}</h5>
                    </div>
                    <div class="col-lg-4 col-6">
                        <b>Topic</b>
                        <h5 class="fs-13 mt-2">{{$lessonPlan['topic']}}</h5>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-3 col-sm-12 mb-2">
                        <div class="form-label-group in-border mb-1">
                            <div class="input-group">
                                <input type="text" class="form-control @if ($errors->has('taught_date_from')) is-invalid @endif"
                                       data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                       value="{{old('taught_date_from') ? old('taught_date_from') : (isset($lessonPlan) ? $lessonPlan['taught_date_from'] : '')}}" name="taught_date_from" id="taught_date_from" required>
                                <label for="taught_date_from" class="form-label">Taught Date From</label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('taught_date_from'))
                                        {{ $errors->first('taught_date_from') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 mb-2">
                        <div class="form-label-group in-border mb-1">
                            <div class="input-group">
                                <input type="text" class="form-control @if ($errors->has('taught_date_to')) is-invalid @endif"
                                       data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                                       value="{{old('taught_date_to') ? old('taught_date_to') : (isset($lessonPlan) ? $lessonPlan['taught_date_to'] : '') }}" name="taught_date_to" id="taught_date_to" required>
                                <label for="taught_date_to" class="form-label">Taught Date To</label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('taught_date_to'))
                                        {{ $errors->first('taught_date_to') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-2">
                        <div class="form-label-group in-border mb-1">
                            <div class="input-group">
                                <select class="form-control js-example-basic-multiple" name="sections[]" multiple="multiple" style="width:100%" required>
                                    @foreach($sections as $section)
                                        <option value="{{$section->id}}" {{in_array($section->id,$selected_section_ids) ? 'selected' : ''}}>{{$section->section_name}}</option>
                                    @endforeach
                                </select>
                                <label for="stateName" class="form-label">Sections</label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('sections'))
                                        {{ $errors->first('sections') }}
                                    @else
                                        Section is required
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6 col-sm-12 mb-2">
                        <label for="evaluation_of_student" class="form-label">Evaluation of Student:</label>
                        <ul>
                            <li>What did the students learn in this lesson?</li>
                            <li>What were they not able to do / understand?</li>
                        </ul>
                        <div class="form-label-group in-border mb-1">
                            <textarea class="form-control" name="evaluation_of_student" id="evaluation_of_student" cols="30" rows="4" required>{{old('evaluation_of_student') ? old('evaluation_of_student') : (!empty($lessonPlan['evaluation_of_student']) ? $lessonPlan['evaluation_of_student'] : '')}}</textarea>
                            <div class="invalid-tooltip">
                                @if ($errors->has('evaluation_of_student'))
                                    {{ $errors->first('evaluation_of_student') }}
                                @else
                                    Evaluation of Student required
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-2">
                        <label for="evaluation_of_teacher" class="form-label">Evaluation of Teacher:</label>
                        <ul>
                            <li>What went well? How do you know?</li>
                            <li>What went wrong? How will you fix it?</li>
                        </ul>
                        <div class="form-label-group in-border mb-1">
                            <div class="input-group">
                                <textarea class="form-control" name="evaluation_of_teacher" id="evaluation_of_teacher" cols="30" rows="4" required>{{old('evaluation_of_teacher') ? old('evaluation_of_teacher') : (!empty($lessonPlan['evaluation_of_teacher']) ? $lessonPlan['evaluation_of_teacher'] : '')}}</textarea>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('evaluation_of_teacher'))
                                        {{ $errors->first('evaluation_of_teacher') }}
                                    @else
                                        Evaluation of Teacher required
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end mt-3">
                    <button class="btn btn-primary" type="submit" form="taught_date_form">Save Changes</button>
                    <a href="{{ route('lesson-plans.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                </div>

            </form>

        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".js-example-basic-multiple").select2();
        });
    </script>
@endpush
