@extends('layouts.master')
@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Student List</a></li>
        <li class="breadcrumb-item active">{{ isset($student) ? 'Edit' : 'Create' }} Student</li>
    </x-breadcrumb>
    @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if (isset($student))
                    <div class="card-header align-items-center d-flex">
                        <h4 class="mb-0 card-title flex-grow-1">Student Detail</h4>
                        <div class="flex-shrink-0">

                        </div>
                    </div>

                    <div class="mx-3 mt-3 card-body">
                        <div class="row">
                            @if (auth()->user()->hasRole('super_admin'))
                                <div class="col-md-6 d-flex" scope="row">
                                    <p><b>System ID:</b></p>
                                    <p><span>&nbsp&nbsp {{ $student->system_id }}</span></p>
                                </div>
                            @endif
                            <div class="col-md-6 d-flex" scope="row">
                                <p><b>Student Name:</b></p>
                                <p><span>&nbsp&nbsp
                                        {{ $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 d-flex" scope="row">
                                <p><b>Status:</b></p>
                                <p><span>&nbsp&nbsp
                                        @if ($student->status == 'on_roll')
                                            <span class="badge bg-success">On Roll</span>
                                        @elseif($student->status == 'registered')
                                            <span class="badge bg-primary">Registered</span>
                                        @elseif($student->status == 'left')
                                            <span class="badge bg-warning">Left</span>
                                        @elseif($student->status == 'pass-out')
                                            <span class="badge bg-info">Pass Out</span>
                                        @else
                                            <span class="badge bg-danger">Processing</span>
                                        @endif
                                    </span></p>
                            </div>
                            <div class="col-md-6 d-flex" scope="row">
                                <p><b>Reg No/Student ID:</b></p>
                                <p><span>&nbsp&nbsp
                                        {{ !empty($student->roll_no) ? $student->roll_no : $student->registration_no }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 d-flex" scope="row">
                                <p><b>Branch:</b></p>
                                <p><span>&nbsp&nbsp {{ $student->branch->br_name }}</span></p>
                            </div>

                            <div class="col-md-6 d-flex" scope="row">
                                <p><b>Class:</b></p>
                                {{-- @dd($student); --}}
                                <p><span>&nbsp&nbsp
                                        {{ $student->active_class->branch_class_sections->com_classes->class_name ?? ' ' }}</span>
                                </p>

                            </div>
                        </div>
                    </div>
                    <div class="mt-3 border border-dashed"></div>
                @endif

                <div class="card-body">
                    <ul class="mb-3 nav nav-pills arrow-navtabs" role="tablist">
                        <li class="nav-item bb">
                            <a class="nav-link {{ request()->query('tab') == 'personal' ? 'active' : '' }}"
                                href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=personal' : '/students/create?tab=personal' }}
                                {{-- data-bs-toggle="tab" href="#nav-border-justified-personal" role="tab" --}} aria-selected="false">
                                <i class="align-middle ri-user-line me-1"></i> Personal Info
                            </a>
                        </li>
                        @permission(['view-student-attachment', 'view-student-avatar'])
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'student_image' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=student_image' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-correspondence" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-image-line me-1"></i> Upload Image/Document
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-correspondence-info')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'correspondence' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=correspondence' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-correspondence" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-home-5-line me-1"></i> Correspondence Info
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-parent-info')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'guardian' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=guardian' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-guardian" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-plant-line me-1"></i> Parent Info
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-academic-info')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'academic' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=academic' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-academic" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-building-4-line me-1"></i> Academic Info
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-subject-info')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'subject' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=subject' : '#' }}
                                    aria-selected="false">
                                    <i class="align-middle ri-building-4-line me-1"></i> Student Subjects
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-concession')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'concession' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=concession' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-academic" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-building-4-line me-1"></i> Concession
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-fee-package')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'package' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=package' : '#' }}
                                    aria-selected="false">
                                    <i class="align-middle ri-file-list-2-line me-1"></i> Fee Package Info
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-invoice')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'invoice' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=invoice' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-registration" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-file-list-2-line me-1"></i> Invoice
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-ledger')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'ledger' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=ledger' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-registration" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-file-list-2-line me-1"></i> Student Ledger
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-family')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'family' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=family' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-registration" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-file-list-2-line me-1"></i> Family Info
                                </a>
                            </li>
                        @endpermission

                        @permission('view-student-family')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'assessment' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=assessment' : '#' }}
                                    {{-- data-bs-toggle="tab" href="#nav-border-justified-registration" role="tab" --}} aria-selected="false">
                                    <i class="align-middle ri-file-list-2-line me-1"></i> Student Assessment
                                </a>
                            </li>
                        @endpermission
                        <li class="nav-item bb">
                            <a class="nav-link {{ request()->query('tab') == 'gradebook-history' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=gradebook-history' : '#' }}
                                aria-selected="false">
                                <i class="align-middle ri-file-list-2-line me-1"></i> Grade Book History
                            </a>
                        </li>

                        <li class="nav-item bb">
                            <a class="nav-link {{ request()->query('tab') == 'prev_school_info' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=prev_school_info' : '#' }}
                                {{-- data-bs-toggle="tab" href="#nav-border-justified-registration" role="tab" --}} aria-selected="false">
                                <i class="align-middle ri-file-list-2-line me-1"></i> Previous School Info
                            </a>
                        </li>
                        @permission('view-student-ptm')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'ptm' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=ptm' : '#' }}
                                    aria-selected="false">
                                    <i class="align-middle ri-file-list-2-line me-1"></i> PTM
                                </a>
                            </li>
                        @endpermission
                        @permission('view-student-extra-curriculum')
                            <li class="nav-item bb">
                                <a class="nav-link {{ request()->query('tab') == 'extra_curriculum' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                    href={{ isset($student) ? '/students/' . $student->id . '/edit?tab=extra_curriculum' : '#' }}
                                    aria-selected="false">
                                    <i class="align-middle ri-file-list-2-line me-1"></i> Extra Curricular Activities
                                </a>
                            </li>
                        @endpermission
                    </ul>
                    <div class="tab-content">
                        <div id='studentAlert' role="alert"></div>
                        <div class="tab-pane {{ request()->query('tab') == 'personal' ? 'active' : '' }}"
                            id="nav-border-justified-personal" role="tabpanel">
                            @if (request()->query('tab') == 'personal')
                                @if (isset($student))
                                    @include('students.edit_personal_info_form')
                                @else
                                    @include('students.personal_info_form')
                                @endif
                            @endif
                        </div>
                        @permission(['view-student-attachment', 'view-student-avatar'])
                            <div class="tab-pane {{ request()->query('tab') == 'student_image' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-correspondence" role="tabpanel">
                                @if (isset($student) && request()->query('tab') == 'student_image')
                                    @permission('view-student-avatar')
                                        @include('students.student_image')
                                    @endpermission
                                    @if (isset($student))
                                        @permission('create-student-attachment')
                                            @include('general_document.create_edit_partial', [
                                                'student' => $student,
                                            ])
                                        @endpermission
                                        @permission('view-student-attachment')
                                            @include('general_document.listing_partial', [
                                                'student' => $student,
                                            ])
                                        @endpermission
                                    @endif
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-correspondence-info')
                            <div class="tab-pane {{ request()->query('tab') == 'correspondence' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-correspondence" role="tabpanel">
                                @if (request()->query('tab') == 'correspondence')
                                    @if (isset($student_address))
                                        @include('students.edit_correspondence_address_form')
                                    @else
                                        @include('students.correspondence_address_form')
                                    @endif
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-parent-info')
                            <div class="tab-pane {{ request()->query('tab') == 'guardian' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-guardian" role="tabpanel">
                                @if (request()->query('tab') == 'guardian')
                                    @include('students.guardian_info')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-subject-info')
                            <div class="tab-pane {{ request()->query('tab') == 'subject' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-subject" role="tabpanel">
                                @if (request()->query('tab') == 'subject')
                                    @include('students.subjects')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-academic-info')
                            <div class="tab-pane {{ request()->query('tab') == 'academic' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-academic" role="tabpanel">
                                @if (request()->query('tab') == 'academic')
                                    @include('students.academic_info')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-concession')
                            <div class="tab-pane {{ request()->query('tab') == 'concession' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-concession" role="tabpanel">
                                @if (request()->query('tab') == 'concession')
                                    @include('students.concessions.student_concession')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-fee-package')
                            <div class="tab-pane {{ request()->query('tab') == 'package' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-package" role="tabpanel">
                                @if (request()->query('tab') == 'package')
                                    @include('students.fee_package_info')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-invoice')
                            <div class="tab-pane {{ request()->query('tab') == 'invoice' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-invoice" role="tabpanel">
                                @if (request()->query('tab') == 'invoice')
                                    @include('students.invoice_info')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-ledger')
                            <div class="tab-pane {{ request()->query('tab') == 'ledger' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-ledger" role="tabpanel">
                                @if (request()->query('tab') == 'ledger')
                                    @include('students.ledger.student_ledger')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-family')
                            <div class="tab-pane {{ request()->query('tab') == 'family' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-family" role="tabpanel">
                                @if (request()->query('tab') == 'family')
                                    @include('students.family.family_info')
                                @endif
                            </div>
                        @endpermission

                        @permission('view-student-assessment')
                            <div class="tab-pane {{ request()->query('tab') == 'assessment' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-assessment" role="tabpanel">
                                @if (request()->query('tab') == 'assessment')
                                    @include('students.assessment.assessment_info')
                                @endif
                            </div>
                        @endpermission
                        <div class="tab-pane {{ request()->query('tab') == 'gradebook-history' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                            id="nav-border-justified-gradebook-history" role="tabpanel">
                            @if (request()->query('tab') == 'gradebook-history')
                                @include('gradebook_history.index')
                            @endif
                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'prev_school_info' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                            id="nav-border-justified-prev-school-info" role="tabpanel">
                            @if (request()->query('tab') == 'prev_school_info')
                                @include('students.previous_school_info.previous_school_info')
                            @endif
                        </div>
                        @permission('view-student-ptm')
                            <div class="tab-pane {{ request()->query('tab') == 'ptm' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-ptm" role="tabpanel">
                                @if (request()->query('tab') == 'ptm')
                                    @include('students.ptm.ptm_info')
                                @endif
                            </div>
                        @endpermission
                        @permission('view-student-extra-curriculum')
                            <div class="tab-pane {{ request()->query('tab') == 'extra_curriculum' ? 'active' : '' }} {{ isset($student) ? '' : 'disabled' }}"
                                id="nav-border-justified-extra-curriculum" role="tabpanel">
                                @if (request()->query('tab') == 'extra_curriculum')
                                    @include('students.extra_curriculum.extra_curriculum_info')
                                @endif
                            </div>
                        @endpermission
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('header_scripts')
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    @push('footer_scripts')
        <script type="text/javascript">
            $('#country,#countryID').on('change', function() {
                var options = `<option value="">Please select a city</option>`;
                $('select[name="city_id"]').html(options);
            });
        </script>
    @endpush

    <style>
        .bb {
            border-bottom: 1px solid #D6D6DA;
        }
    </style>
