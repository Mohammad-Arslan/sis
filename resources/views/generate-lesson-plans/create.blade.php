@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">Create Lesson Plan</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('generate_lesson.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="branch_id">Branch</label>
                                <select name="branch_id" id="branch_id" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="state_id">State</label>
                                <select name="state_id" id="state_id" class="form-control">
                                    <option value="">Select State</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="academic_year_id">Academic Year</label>
                                <select name="academic_year_id" id="academic_year_id" class="form-control">
                                    <option value="">Select Academic Year</option>
                                    @foreach ($academicYears as $academicYear)
                                        <option value="{{ $academicYear->id }}">{{ $academicYear->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="com_class_id">Class</label>
                                <select name="com_class_id" id="com_class_id" class="form-control">
                                    <option value="">Select Class</option>
                                    @foreach ($comClasses as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="section_id">Section</label>
                                <select name="section_id" id="section_id" class="form-control">
                                    <option value="">Select Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject_id">Subject</label>
                                <select name="subject_id" id="subject_id" class="form-control">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="term_id">Term</label>
                                <select name="term_id" id="term_id" class="form-control">
                                    <option value="">Select Term</option>
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="week_id">Week</label>
                                <select name="week_id" id="week_id" class="form-control">
                                    <option value="">Select Week</option>
                                    @foreach ($weeks as $week)
                                        <option value="{{ $week->id }}">{{ $week->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="day">Day</label>
                                <input type="text" name="day" id="day" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="topic">Topic</label>
                                <input type="text" name="topic" id="topic" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="lesson_plan_details">Lesson Plan Details</label>
                                <textarea name="lesson_plan_details" id="lesson_plan_details" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="bocc_link">BOCC Link</label>
                                <input type="text" name="bocc_link" id="bocc_link" class="form-control">
                            </div>




                            <!-- Add fields for other attributes in your LessonPlanGenerate model -->

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Create Lesson Plan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
