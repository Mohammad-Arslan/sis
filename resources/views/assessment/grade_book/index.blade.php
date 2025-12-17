@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Grade Book</li>
    </x-breadcrumb>
    <div class="row">
        @include('assessment.grade_book.report_fetching_entries')
        @include('assessment.grade_book.list')
        @include('assessment.grade_book.student_list')
    </div>
    <div id="progress-report-modal-div">
    </div>
@endsection

@push('header_scripts')
    @include('assessment.grade_book.bulk_progress_reports.bulk_progress_report_lower_primary_style')
    @include('assessment.grade_book.bulk_progress_reports.bulk_progress_report_early_year_style')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('click', '.fetch_students', function() {
                let section_id = $('#section_id').val();
                let student_behaiour_skill_id = $(this).data('student_behaiour_skill_id');

                $.ajax({
                    url: '{{ route('gradebook.list-students') }}',
                    type: 'GET',
                    data: {
                        //sections: [section_id],
                        //calling_from: 'gradebook',
                        student_behaiour_skill_id: student_behaiour_skill_id,
                    },
                    cache: false,
                    success: function(result) {
                        $('#studentList-datatable tbody').html(result.html)
                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 8000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });

            $(document).on('change', '.refresh-table', function() {
                $('#gradebook-datatable tbody').html(`<tr class="text-center">
                    <td colspan="4">No Record Fetched Yet.</td>
                </tr>`);
                $('#studentList-datatable tbody').html(`<tr class="text-center">
                    <td colspan="4">No Record Fetched Yet.</td>
                </tr>`);
            });
        });
    </script>
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.get_bulk_report', function() {
                let section_id = $('#section_id').val();
                let student_behaiour_skill_id = $(this).data('student_behaiour_skill_id');

                const target = $(this).data('target');
                const url = $(this).data('url');

                $.ajax({
                    url: '{{ route('gradebook.generate-bulk-progress-reports') }}',
                    type: 'GET',
                    data: {
                        //sections: [section_id],
                        //calling_from: 'gradebook',
                        student_behaiour_skill_id: student_behaiour_skill_id,
                    },
                    cache: false,
                    success: function(result) {
                        $('#progress-report-modal-div').html(result.modal);

                        $(target).modal('show');

                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    // timeout: 36000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });

            $(document).on('change', '.refresh-table', function() {
                $('#gradebook-datatable tbody').html(`<tr class="text-center">
                    <td colspan="4">No Record Fetched Yet.</td>
                </tr>`);
                $('#studentList-datatable tbody').html(`<tr class="text-center">
                    <td colspan="4">No Record Fetched Yet.</td>
                </tr>`);
            });
        });
    </script>
@endpush
