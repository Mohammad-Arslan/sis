<div id='guardianAlert' role="alert"></div>

@if (isset($previous_schools) && isset($student))
    {{-- @permission('create-student-parent-info') --}}
    @include('students.previous_school_info.previous_school_info_form')
    {{-- @endpermission --}}
@endif
