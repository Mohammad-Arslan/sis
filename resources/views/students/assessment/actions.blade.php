{{-- @permission('delete-student-concession') --}}
<button class="btn btn-sm btn-primary show-progress-report-modal" data-target="#progressReportModal"
    data-url="{{ route('grade-book.get-report', [$row->student->id, $row->id]) }}">Get Report</button>
{{-- @endpermission --}}
