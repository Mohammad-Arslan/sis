@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        @if (isset($subjectRemark))
            <li class="breadcrumb-item"><a href="{{ route('subject-remarks.index') }}">Subject Marks</a></li>
            <li class="breadcrumb-item active">Edit</li>
        @else
            <li class="breadcrumb-item active">Subject Marks</li>
        @endif
    </x-breadcrumb>
    @include('components.flash_message')
    <div class="row">
        <form class="needs-validation" method="POST"
            action="{{ isset($subjectRemark) ? route('subject-remarks.update', $subjectRemark['id']) : route('subject-remarks.store') }}"
            novalidate>
            @csrf
            @if (isset($subjectRemark))
                @method('PATCH')
            @endif
            @include('assessment.subject_remarks.create_edit_entry')
            @include('assessment.subject_remarks.studentList')
        </form>
        @include('assessment.subject_remarks.list')
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
        });
    </script>
@endpush
