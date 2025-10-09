@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        @if (isset($assessmentEntry))
            <li class="breadcrumb-item"><a href="{{ route('assessment-entry.index') }}">Assessment Entry</a></li>
            <li class="breadcrumb-item active">Edit</li>
        @else
            <li class="breadcrumb-item active">Assessment Entry</li>
        @endif
    </x-breadcrumb>
    @include('components.flash_message')
    <div class="row">
        <form class="needs-validation" method="POST"
            action="{{ isset($assessmentEntry) ? route('assessment-entry.update', $assessmentEntry['id']) : route('assessment-entry.store') }}"
            novalidate>
            @csrf
            @if (isset($assessmentEntry))
                @method('PATCH')
            @endif
            @include('assessment.assessment_entry.create_edit_entry')
            @include('assessment.assessment_entry.studentList')
        </form>
        @include('assessment.assessment_entry.list')
    </div>
@endsection

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var subject_type = '';
            $('#subject_id').change(function() {
                subject_type = $(this).find(':selected').data('subject-type');
                // alert(subject_type);
                if (subject_type == 'Minor') {
                    // $('#term_id').attr("disabled", true);
                    $('#assessment_date').attr("disabled", true);
                    $('#assessment_level_one_id').attr("disabled", true);
                    $('#assessment_level_two_id').attr("disabled", true);
                    $('#assessment_level_three_id').attr("disabled", true);
                    $('#marks_type').attr("disabled", true);
                    $('#grade_marks').attr("disabled", true);

                } else {
                    // $('#term_id').attr("disabled", false);
                    $('#assessment_date').attr("disabled", false);
                    $('#assessment_level_one_id').attr("disabled", false);
                    $('#assessment_level_two_id').attr("disabled", false);
                    $('#assessment_level_three_id').attr("disabled", false);
                    $('#marks_type').attr("disabled", false);
                    $('#grade_marks').attr("disabled", false);
                }
            });

            $(document).on('change', '.student-obtained-marks', function() {
                let obtained_marks = parseFloat($(this).val());
                let previous_marks = $(this).data('previous_marks');
                let marks_type = $('#marks_type').val();
                let total_marks = parseInt($('#grade_marks').val());
                var regex = new RegExp(/^[+-]?\d+(\.\d+)?$/);
                // alert(typeof total_marks))
                // alert(typeof parseInt(obtained_marks))
                if (marks_type == '' && (subject_type == 'Major' || subject_type == '')) {
                    alert('please select marks type first')
                    return false;
                }

                if (total_marks == '' && (subject_type == 'Major' || subject_type == '')) {

                    alert('please select total marks first')
                    return false;
                }

                if (marks_type === 'marks' && total_marks < obtained_marks && (subject_type == 'Major' ||
                        subject_type == '')) {

                    alert('Obtained marks must be less or equal to total marks.');
                    $(this).val(previous_marks);
                }
                if (marks_type === 'marks' && !regex.test(obtained_marks) ) {
                    alert('Special characters and Alphabets are not allowed in obtained marks');
                    $(this).val(previous_marks);
                }
                 else {
                    $(this).data('previous_marks', obtained_marks)
                }
            });

            $(document).on('change', '#marks_type', function() {
                let marks_type = $(this).val();

                if (marks_type == 'grades')
                    $("#grade_marks").prop("type", "text");
                else
                    $("#grade_marks").prop("type", "number");
            });
        });
    </script>
@endpush
