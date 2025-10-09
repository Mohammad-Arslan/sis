@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Timetable Details</h5>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('timetables.update', $timetable->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Branch selection -->
                    <div class="form-group">
                        <label for="branch_id">Branch:</label>
                        <select name="branch_id" class="form-control" id="branch_id">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @if ($branch->id == $selectedBranch->id) selected @endif>
                                    {{ $branch->br_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="employee_id">Teacher:</label>
                        <select name="employee_id" id="employee_id" class="form-control">
                            <option value="{{ $timetable->employee_id }}" selected>{{ $timetable->employee->preferred_name }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->preferred_name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subject selection -->
                    <div class="form-group">
                        <label for="class_nature">Nature of Class:</label>
                        <select name="class_nature" id="class_nature" class="form-control" required>
                            <option value="" selected disabled>Select Class Nature</option>
                            <option value="Substitution" {{ $timetable->class_nature == 'Substitution' ? 'selected': '' }}>Substitution</option>
                            <option value="Homeroom" {{ $timetable->class_nature == 'Homeroom' ? 'selected': '' }}>Homeroom</option>
                            <option value="Subject Specialist" {{ $timetable->class_nature == 'Subject Specialist' ? 'selected': '' }}>Subject Specialist</option>                            
                        </select>
                        @error('class_nature')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Class selection -->
                    <div class="form-group">
                        <label for="class_id">Class:</label>
                        <select name="class_id" id="class_id" class="form-control">
                            <option value="{{ $timetable->class_id }}" selected>{{ $timetable->class->class_name }}</option>
                            <!-- Options will be dynamically populated based on selected teacher -->
                        </select>
                        @error('class_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Section selection -->
                    <div class="form-group">
                        <label for="section_id">Section:</label>
                        <select name="section_id" id="section_id" class="form-control">
                            <option value="{{ $timetable->section_id }}" selected>{{ $timetable->section->section_name }}</option>
                            <!-- Options will be dynamically populated based on selected class -->
                        </select>
                        @error('section_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subject selection -->
                    <div class="form-group">
                        <label for="subject_id">Subject:</label>
                        <select name="subject_id" id="subject_id" class="form-control">
                            <option value="{{ $timetable->subject_id }}" selected>{{ $timetable->subject->subject_name }}</option>
                            <!-- Options will be dynamically populated based on selected class -->
                        </select>
                        @error('subject_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Day selection -->
                    {{-- <div class="form-group">
                        <label for="start_datetime">Date and Time:</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control" required value="{{ date('Y-m-d\TH:i', strtotime($timetable->start_datetime)) }}">
                    </div> --}}

                    <div class="form-group">
                        <label for="start_datetime">Date:</label>
                        <input type="date" name="date" id="date" class="form-control" required value="{{ date('Y-m-d', strtotime($timetable->start_datetime)) }}">
                        @error('date')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_datetime">Start Time:</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required value="{{ date('H:i', strtotime($timetable->start_datetime)) }}">
                        @error('start_time')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_datetime">End Time:</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required value="{{ date('H:i', strtotime($timetable->end_datetime)) }}">
                        @error('end_time')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="mt-2 btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('footer_scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var branchSelect = document.getElementById("branch_id");

            branchSelect.style.pointerEvents = "none"; // Disable pointer events on the select

            // Optionally, you can add a style to indicate that it's not selectable
            branchSelect.style.background = "lightgray"; // Change the background color

            // Prevent keypresses from opening the dropdown
            branchSelect.addEventListener("keydown", function(e) {
                e.preventDefault();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#employee_id').change(function() {
                var employeeId = $(this).val();

                // AJAX request to fetch classes based on selected teacher
                $.ajax({
                    url: '{{ route('getClassesByTeacher') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: employeeId
                    },
                    success: function(response) {
                        $('#class_id').empty().append('<option value="">Select Class</option>');
                        $.each(response.classes, function(index, classData) {
                            // console.log(classData);
                            $('#class_id').append('<option value="' + classData.id +
                                '">' + classData.class_name + '</option>');
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#class_id').change(function() {
                var employeeId = $(this).val();

                // AJAX request to fetch classes based on selected teacher
                $.ajax({
                    url: '{{ route('getSectionbyClasses') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: employeeId
                    },
                    success: function(response) {
                        // alert('fefefe')
                        $('#section_id').empty().append(
                            '<option value="">Select Section</option>');
                        $.each(response.sections, function(index, sectionData) {
                            console.log(sectionData);
                            $('#section_id').append('<option value="' + sectionData.id +
                                '">' + sectionData.section_name + '</option>');
                        });
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#class_id').change(function() {
                var employeeId = $(this).val();

                // AJAX request to fetch classes based on selected teacher
                $.ajax({
                    url: '{{ route('getSubjectbyClasses') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: employeeId
                    },
                    success: function(response) {
                        // alert('fefefe')
                        $('#subject_id').empty().append(
                            '<option value="">Select Subject</option>');
                        $.each(response.subjects, function(index, subjectsData) {
                            $('#subject_id').append('<option value="' + subjectsData
                                .id + '">' + subjectsData.subject_name + '</option>'
                            );
                        });
                    }
                });
            });
        });
    </script>

@endpush

