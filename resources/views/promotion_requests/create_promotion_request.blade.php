@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('promotion-requests.index') }}">Promotion Requests</a></li>
        @if (isset($promotionRequest))
            <li class="breadcrumb-item active">Edit Student Bulk Promotion</li>
        @else
            <li class="breadcrumb-item active">Student Bulk Promotion</li>
        @endif
    </x-breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            @include('components.flash_message')
            <form class="row g-3 needs-validation promotion-form" novalidate method="POST"
                action="{{ isset($promotionRequest) ? route('promotion-requests.bulk-update') : route('promotion-requests.bulk-store') }}">
                @csrf
                {{-- @if (isset($promotionRequest))
                    @method('POST')
                @endif --}}
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        @if (isset($promotionRequest))
                            <h4 class="card-title mb-0 flex-grow-1">Edit Student Bulk Promotion</h4>
                        @else
                            <h4 class="card-title mb-0 flex-grow-1">Student Bulk Promotion</h4>
                        @endif
                        <div class="flex-shrink-0">
                            <a href="{{ route('promotion-requests.index') }}" class="btn btn-success-new btn-label btn-sm">
                                <i class="ri-file-list-3-fill label-icon align-middle fs-16 me-2"></i> Promotion Request
                                List
                            </a>
                        </div>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <h5 class="text-muted d-flex align-items-center mb-3"><i
                                class="ri-folder-shield-2-fill me-1"></i>Selection Criteria</h5>
                        <div class="live-preview">
                            <div class="row g-3 upper_filter">
                                @include('promotion_requests.common_filters')
                                @if (!isset($promotionRequest))
                                    <div class="col-12 text-end">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary fetch_students">Fetch
                                            Students</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted d-flex align-items-center mb-3"><i
                                class="ri-folder-shield-2-fill me-1"></i>Promoted To</h5>
                        <div class="live-preview">
                            <div class="row g-3 lower_filter">
                                @include('promotion_requests.promoted_filters')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row g3">
                                @include('promotion_requests.students_datatable')
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="student_ids[]" id="student_ids" value="">
                <input type="hidden" name="promotion_type" id="" value="bulk">
                @if (isset($promotionRequest))
                    <input type="hidden" name="promotion_request_id" id="" value="{{ $promotionRequest->id }}">
                @endif
            </form>
        </div>
    </div>
@endsection

@push('header_scripts')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        var selectedStudents = [];
        $(document).ready(function() {
            /*selectAllItems('selectAllStudents', 'student_checkbox', selectedStudents, function () {
                $('#student_ids').val(selectedStudents);
            })*/

            $('#selectAllStudents').change(function() {
                if ($('#selectAllStudents').is(':checked')) {
                    $('.student_checkbox').prop('checked', true);
                } else {
                    $('.student_checkbox').prop('checked', false);
                }
                studentCheckboxEventHandler();
                // $('.student_checkbox').trigger('change');
            });

            $('.student_checkbox').change(studentCheckboxEventHandler);

            function studentCheckboxEventHandler() {
                /*console.log('student_checkbox is called');*/

                selectedStudents = $('.student_checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedStudents.length > 0) {
                    if ($('.student_checkbox').length == selectedStudents.length) {
                        $('#selectAllStudents').prop('checked', true);
                    } else if ($('.student_checkbox').length > selectedStudents.length) {
                        $('#selectAllStudents').prop('checked', false);
                    }
                } else {
                    $('#selectAllStudents').prop('checked', false);
                }
                /*console.log(selectedStudents);*/
                $('#student_ids').val(selectedStudents);
            }

            $('.promotion-form').on('submit', function(e) {
                if (selectedStudents.length === 0) {
                    $('#selectAllStudents').addClass('is-invalid');
                    $('#selectAllStudents').next('.invalid-tooltip').text(
                        'Please select at least one student.');
                    setTimeout(function() {
                        $('.promotion-form').removeClass('was-validated');
                    }, 100);
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                return true;
            });

            $('#students-list').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // bLengthChange: false,
                // pageLength: 50,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: '{{ route('promotion-requests.student-list') }}',
                    data: function(d) {
                        d.students = {!! isset($promotionRequest) ? $promotionRequest->student_promotion_requests->pluck('student_id') : 'null' !!};
                        d.academic_years = $('#academic_year_id').val() == "" ? [] : [$(
                            '#academic_year_id').val()];
                        d.branches = $('#branch_id').val() == "" ? [] : [$('#branch_id').val()];
                        d.classes = $('#class_id').val() == "" ? [] : [$('#class_id').val()];
                        d.sections = $('#section_id').val() == "" ? [] : [$('#section_id').val()];
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },

                    {
                        data: 'roll_no',
                        name: 'roll_no'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'active_class.branch_class_sections.com_classes.class_name',
                        name: 'active_class.branch_class_sections.com_classes.class_name'
                    },
                    {
                        data: 'active_class.branch_class_sections.sections.section_name',
                        name: 'active_class.branch_class_sections.sections.section_name'
                    },
                    {
                        data: 'invoice_status',
                        name: 'invoice_status'
                    },
                    {
                        data: 'promotion_status',
                        name: 'promotion_status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '5%'
                    },
                ]
            });

            $('#branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#class_id').data('url', route);
            });

            $('#promoted_branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#promoted_branch_class_id').data('url', route);
                /*console.log('#promoted_branch_id', $('#promoted_branch_class_id').data('url'));*/
            });

            $('.refresh-table').on('change', function() {
                $('.list_student_table tbody').html(`<tr class="text-center">
                        <td colspan="7">No Record Fetched Yet.</td>
                    </tr>`);
            });

            $(document).on('click', '.fetch_students', function() {
                const section_id = $('#section_id').val();
                let selected_class = $("#class_id option:selected").text().toLowerCase();

                if ($('#academic_year_id').val() == "") {
                    alert('Please select academic year');
                    return false;
                }
                if ($('#branch_id').val() == "") {
                    alert('Please select branch');
                    return false;
                }
                if ($('#class_id').val() == "") {
                    alert('Please select class');
                    return false;
                }
                if (section_id == "") {
                    alert('Please select section');
                    return false;
                }

                $('#students-list').DataTable().ajax.reload(null, false).page('first');

                /*const academic_year = $('#academic_year_id').val();

                $.ajax({
                    url: '{{-- {{ route('promotion-requests.get-filter') }} --}}',
                    type: 'GET',
                    data: {
                        'academic_years': $('#academic_year_id').val() == "" ? [] : [$('#academic_year_id').val()],
                        'branches': $('#branch_id').val() == "" ? [] : [$('#branch_id').val()],
                        'classes': $('#class_id').val() == "" ? [] : [$('#class_id').val()],
                        'sections': $('#section_id').val() == "" ? [] : [$('#section_id').val()],
                    },
                    cache: false,
                    success: function (result) {
                        $('.lower_filter').html(result.html)
                    },
                    error: function (error) {
                        console.log("Sorry! Server error!");
                        console.log(error);
                    }
                }).fail(function (jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });*/
            });

            $(document).ajaxComplete(function() {
                if ($('#selectAllStudents').is(':checked')) {
                    $('.student_checkbox').prop('checked', true);
                } else {
                    $('.student_checkbox').prop('checked', false);
                }
                $('.student_checkbox').change(studentCheckboxEventHandler);
                {!! isset($promotionRequest)
                    ? '$("#selectAllStudents").prop("checked",true); $(\'#selectAllStudents\').trigger(\'change\');'
                    : '' !!}
                studentCheckboxEventHandler();
            });
        });
    </script>
@endpush
