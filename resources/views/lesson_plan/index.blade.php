@extends('layouts.master')
@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        @if (!empty($lesson_plan))
            <li class="breadcrumb-item">{{ ucwords($lesson_plan['com_class']['class_name']) }}</li>
            <li class="breadcrumb-item">{{ ucwords($lesson_plan['subject']['subject_name']) }}</li>
        @endif
        <li class="breadcrumb-item">Lesson Plan List</li>
    </x-breadcrumb>

    @include('components.flash_message')

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Lesson Plan List</h4>
                @permission('add-lesson-plan')
                    <div class="flex-shrink-0">
                        <a class="btn btn-sm btn-success-new" href="{{ route('lesson-plans.create') }}">Add New Lesson Plan</a>
                    </div>
                @endpermission
            </div><!-- end card header -->

            <div class="card-body">
                <div class="row">
                    @if (isHeadOfficeEmp() || auth()->user()->hasRole('super_admin'))
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                    @endforeach

                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id" disabled>
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                    @endforeach

                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-2 col-sm-12">
                        @if (!auth()->user()->hasRole('super_admin'))
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id"
                                    {{ auth()->user()->hasRole('teacher' || 'school_head')? 'disabled': '' }}>
                                    @foreach ($branches as $branch)
                                        @if (auth()->user()->hasRole('network_associate'))
                                            <option value="{{ $branch->id }}"
                                                {{ get_set_NWABranchId() == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->br_name }}</option>
                                        @else
                                            <option value="{{ $branch->id }}"
                                                {{ auth()->user()['employee']['branch_id'] == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->br_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        @else
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id">
                                    <option value="">Please select</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="com_class_id">
                                <option value="">Please select</option>
                                @foreach ($classes as $com_class)
                                    <option value="{{ $com_class->id }}">{{ $com_class->class_name }}</option>
                                @endforeach
                            </select>
                            <label for="com_class_id" class="form-label">Class</label>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="subject_id">
                                <option value="">Please select</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            <label for="subject_id" class="form-label">Subject</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control filter" data-provider="flatpickr"
                                data-date-format="Y-m-d" data-altFormat="d-m-Y" id="created_date">
                            <label for="created_date" class="form-label">Created Date</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select filter" id="term_id">
                                <option value="">Please select a Term</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                            <label for="term_id" class="form-label">Term</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            @php($state_count = $states->count())
                            <select class="form-select filter" id="state_id" {{ $state_count == 1 ? 'disabled' : '' }}>
                                <option value="">Please select a Province</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ $state_count == 1 ? 'selected' : '' }}>
                                        {{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            <label for="term_id" class="form-label">Province</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select filter" id="week_id">
                                <option value="">Please select a Week</option>
                                @foreach ($weeks as $week)
                                    <option value="{{ $week->id }}">{{ $week->name }}</option>
                                @endforeach
                            </select>
                            <label for="term_id" class="form-label">Week</label>
                        </div>
                    </div>
                </div>
                <table id="lesson-plan-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Folder Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Folder Name</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @include('lesson_plan.lesson_modal')
    @include('lesson_plan.attachment_modal')

    <form action="{{ route('lesson-plans.update-status') }}" method="POST" id="updateStatusForm">
        @csrf
        <input type="hidden" class="lesson_plan_id" name="lesson_plan_id">
        <input type="hidden" class="status" name="status">
        <input type="hidden" class="approval_for" name="approval_for">
    </form>
@endsection
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#lesson-plan-data-table').DataTable({
                processing: true,
                searching: false,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('lesson-plans.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.com_class_id = $('#com_class_id').val();
                        d.subject_id = $('#subject_id').val();
                        d.created_date = $('#created_date').val();
                        d.term_id = $('#term_id').val();
                        d.state_id = $('#state_id').val();
                        d.week_id = $('#week_id').val();
                    }
                },
                columns: [{
                        data: 'folder_name',
                        name: 'folder_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    },
                ],
            });

            $(document).on('click', '.show_lesson_modal', function(e) {
                let modal_heading = $(this).text();
                let lesson_plan_id = $(this).data('lessonplan-id');
                let url = $(this).data('getweekdays-route');
                $('#LessonPlanModalLabel').text(modal_heading);

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        if (data.code == 200) {
                            $('#LessonPlanModal').modal('show');
                            $('#dailyLessonPlan_Datatable tbody').empty().append(data.data
                                .daily_lesson_plan_rows);
                        }
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {}
                });
            });

            $(document).on('click', '.change_lessonplan_status', function(e) {

                let lesson_plan_id = $(this).data('lessonplan-id');
                let status = $(this).data('status');
                let approval_for = $(this).data('approval-for');

                let message = 'Are you Sure You want to Update Status ?'
                if (status == 'approved')
                    message = 'Are you Sure You want to Approve lesson plan ?'
                else if (status == 'publish')
                    message = 'Are you Sure You want to Publish lesson plan ?'
                else if (status == 'unpublish')
                    message = 'Are you Sure You want to Unpublish lesson plan ?'
                else if (status == 'pending_for_approval')
                    message = 'Are you Sure You want to Send for approval ?'

                Swal.fire({
                    html: '<div class="pt-2 fs-15 mx-5">' +
                        '<h4>Are you sure?</h4>' +
                        '<p class="text-muted mx-4 mb-0">' + message + '</p>' +
                        '</div>',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, Sure',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {

                    if (result.isConfirmed) {
                        //$('.lesson_plan_id').val(lesson_plan_id);
                        //$('.status').val(status);
                        //$('.approval_for').val(approval_for);
                        //$('#updateStatusForm').submit();

                        $.ajax({
                            url: "{{ route('lesson-plans.update-status') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            data: {
                                lesson_plan_id: lesson_plan_id,
                                status: status,
                                approval_for: approval_for,
                            },
                            cache: false,
                            success: function(data) {
                                if (data.code == 200) {

                                    if (approval_for == 'day') {
                                        $('.pending_for_approval_row' + lesson_plan_id)
                                            .hide();
                                        $('.approved_row' + lesson_plan_id).hide();
                                        $('.publish_row' + lesson_plan_id).hide();
                                        $('.draft_row' + lesson_plan_id).hide();

                                        if (status == 'approved') {
                                            $('.approved_row' + lesson_plan_id).show();
                                            $('.approved_lessonplan_btn' +
                                                lesson_plan_id).remove();
                                        } else if (status == 'publish') {
                                            $('.publish_row' + lesson_plan_id).show();
                                            $('.publish_lessonplan_btn' +
                                                lesson_plan_id).remove();
                                        } else if (status == 'pending_for_approval') {
                                            $('.pending_for_approval_row' +
                                                lesson_plan_id).show();
                                            $('.sendForApproval_lessonplan_btn' +
                                                lesson_plan_id).remove();
                                        }
                                    }

                                    $('#lesson-plan-data-table').DataTable().ajax
                                        .reload(null, false);
                                }
                            },
                            error: function() {

                            },
                            beforeSend: function() {

                            },
                            complete: function() {}
                        });
                    }
                });
            });

            $(document).on('click', '.show-attachment-modal', function(e) {
                let url = $(this).data('attachments-route');
                let modal_heading = 'Lesson Plan - (' + $(this).closest('tr').children('td:first').text() +
                    ') - ' + 'Attachment';
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        if (data.code == 200) {
                            $('#AttachmentModalLabel').text(modal_heading);
                            $('#AttachmentModal').modal('show');
                            $('#lessonPlanAttachment_Datatable tbody').empty().append(data.data
                                .attachments_rows);
                        }
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {}
                });
            });

            $(document).on('click', '.duplicate-lesson-plan', function(e) {

                let url = $(this).data('duplicate-lp-route');
                let message = 'Are you Sure You want to Duplicate Lesson Plan ?'

                Swal.fire({
                    html: '<div class="pt-2 fs-15 mx-5">' +
                        '<h4>Are you sure?</h4>' +
                        '<p class="text-muted mx-4 mb-0">' + message + '</p>' +
                        '</div>',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, Sure',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {
                    if (result.isConfirmed)
                        window.location = url;
                });
            });

            $(document).on('change', '.filter', function() {
                $('#lesson-plan-data-table').DataTable().ajax.reload(null, false);
            });
        });

        function GetDuplicateSelection() {
            var fields = $("input[name='lesson_plan_row[]']").serializeArray();
            var selections = '';
            if (fields.length == 0) {
                Swal.fire({
                    html: '<div class="pt-2 fs-15 mx-5">' +
                        '<h4>Error</h4>' +
                        '<p class="text-muted mx-4 mb-0">Nothing selected</p>' +
                        '</div>',
                    icon: 'error'
                });
            } else {
                for (i = 0; i < fields.length; i++) {
                    console.log('lesson_plan_id=' + fields[i].value);
                    selections = selections + fields[i].value + ',';
                }
                let lesson_plans = selections.substring(0, selections.length - 1);
                let message = 'Are you Sure You want to Duplicate selected (' + fields.length + ') Lesson Plan ?'

                Swal.fire({
                    html: '<div class="pt-2 fs-15 mx-5">' +
                        '<h4>Are you sure?</h4>' +
                        '<p class="text-muted mx-4 mb-0">' + message + '</p>' +
                        '</div>',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, Sure',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var data = null;
                        var url = 'lesson-plans';
                        data = {
                            'lesson_plans': lesson_plans
                        }

                        $.ajax({
                            type: 'POST',
                            url: '/duplicate-selected-lesson-plan',
                            data: data,
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response != '') {
                                    window.location = url;
                                } else {
                                    Swal.fire({
                                        html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">Unable to duplicate selected lesson plans</p></div></div>',
                                        showCancelButton: !0,
                                        showConfirmButton: !1,
                                        cancelButtonClass: "btn btn-primary w-xs mb-1",
                                        cancelButtonText: "Dismiss",
                                        buttonsStyling: !1,
                                        showCloseButton: !0
                                    })
                                }
                            }
                        });
                    }

                });
            }

        }
    </script>
@endpush
