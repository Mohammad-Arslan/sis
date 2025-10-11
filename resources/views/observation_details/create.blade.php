@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title">Create Observation Detail</h5>
                </div>
                <a href="{{ route('teacher_evaluation.index') }}" class="btn btn-danger">Back</a>
            </div>

        </div>
    </div>

    <div class="card">

        <div class="card-body">
            <form method="POST" action="{{ route('observation_details.store') }}">

                @csrf

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select name="teacher_observation_id" class="form-control" required>
                                <option value="{{ $teacherObservations->id }}">{{ $teacherObservations->branch->br_name
                                    }}</option>
                            </select>
                            <label class="form-label">Teacher Branch</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select name="teacher_observation_id" class="form-control" required>
                                <option value="{{ $teacherObservations->id }}">{{
                                    $teacherObservations->teacher->preferred_name }}</option>
                            </select>
                            <label class="form-label">Teacher Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select name="academic_years_id" class="form-control" required>
                                @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->title }}</option>
                                @endforeach
                            </select>
                            <label class="form-label">Academic Year</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="datetime-local" class="form-control" name="observation_date" id="observation_date" required>
                            <label class="form-label">Observation Date</label>
                        </div>
                    </div>



                </div>
                @if ($classes->count() > 0)
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select id="class_id" name="class_id" class="form-control"
                                onchange="updateSectionsAndSubjects()" required>
                                <option value="">Select Class</option>
                                @php
                                $uniqueClassNames = [];
                                @endphp
                                @foreach ($classes as $classTeacher)
                                @foreach($classTeacher->class_teachers as $teacherclass)
                                @php
                                $className = $teacherclass->branch_class_section->com_classes->class_name;
                                @endphp
                                @if (!in_array($className, $uniqueClassNames))
                                <option value="{{$teacherclass->branch_class_section->com_classes->id}}">
                                    {{$className}}
                                </option>
                                @php
                                $uniqueClassNames[] = $className;
                                @endphp
                                @endif
                                @endforeach
                                @endforeach
                            </select>
                            <label class="form-label">Class</label>
                        </div>
                    </div>
                </div>
                @else
                <p>No classes found for this employee.</p>
                @endif

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select name="section_id" class="form-control" id="section_id" required></select>
                            <label class="form-label">Section</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select name="subject_id" class="form-control" id="subject_id" required></select>
                            <label class="form-label">Subject</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select name="part_of_period_observed" class="form-control" required>
                                <option value="FULL PERIOD">FULL PERIOD</option>
                                <option value="FIRST 20 MIN">FIRST 20 MIN</option>
                                <option value="MID 20 MIN">MID 20 MIN</option>
                                <option value="LAST 20 MIN">LAST 20 MIN</option>
                            </select>
                            <label class="form-label">Part of Period Observed</label>
                        </div>
                    </div>
                </div>
                <div class="form-label-group in-border d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Start Evaluation</button>
                </div>

        </div>
    </div>
    </form>
</div>

@endsection
<script>
    function updateSectionsAndSubjects() {
        var selectedClass = document.getElementById('class_id').value;
        var sectionSelect = document.getElementById('section_id');
        var subjectSelect = document.getElementById('subject_id');

        sectionSelect.innerHTML = '';
        subjectSelect.innerHTML = '';

        var sectionNames = [];
        var subjectNames = [];

        @foreach($classTeacher->class_teachers as $teacherclass)
            if ("{{$teacherclass->branch_class_section->com_classes->id}}" === selectedClass) {
                var sectionId = "{{$teacherclass->branch_class_section->sections->id}}";
                var subjectId = "{{$teacherclass->subject->id}}";
                var sectionName = "{{$teacherclass->branch_class_section->sections->section_name}}";
                var subjectName = "{{$teacherclass->subject->subject_name}}";

                if (!sectionNames.includes(sectionName)) {
                    var sectionOption = document.createElement('option');
                    sectionOption.value = sectionId;
                    sectionOption.textContent = sectionName;
                    sectionSelect.appendChild(sectionOption);
                    sectionNames.push(sectionName); // Add the name to the array
                }

                if (!subjectNames.includes(subjectName)) {
                    var subjectOption = document.createElement('option');
                    subjectOption.value = subjectId;
                    subjectOption.textContent = subjectName;
                    subjectSelect.appendChild(subjectOption);
                    subjectNames.push(subjectName); // Add the name to the array
                }
            }
        @endforeach
    }
</script>
