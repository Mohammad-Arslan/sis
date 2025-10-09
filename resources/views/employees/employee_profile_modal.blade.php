<div id="profileEmpModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Employee Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-5">
                    <div class="col-xxl-3">
                        <div class="card mt-n5">
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        @if ($employee[0]->emp_image != '')
                                            <img src="{{ get_file_from_s3('images/' . $employee[0]->emp_image) }}"
                                                class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                                alt="user-profile-image">
                                        @else
                                            <img src="{{ asset('uploads/employees/user-dummy-img.jpg') }}"
                                                class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                                alt="user-profile-image">
                                        @endif


                                    </div>
                                    <h5 class="fs-16 mb-1">{{ $employee[0]->employee_id }}</h5>
                                    <h5 class="fs-16 mb-1">
                                        {{ $employee[0]->user->first_name . ' ' . $employee[0]->user->last_name }}</h5>
                                    <p class="text-muted mb-0">{{ $employee[0]->department->department_name }} /
                                        {{ $employee[0]->designation->designation_name }}</p>
                                </div>
                            </div>
                        </div><!--end card-->

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-0">Official Info</h5>
                                    </div>
                                </div>
                                <div class="mb-3 d-flex">
                                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                                        <span class="avatar-title rounded-circle fs-16 bg-dark text-light">
                                            <i class="ri-mail-fill"></i>
                                        </span>
                                    </div>
                                    <strong class="text-muted mt-1">{{ $employee[0]->user->email }}</strong>
                                </div>
                                <div class="mb-3 d-flex">
                                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                                        <span class="avatar-title rounded-circle fs-16 bg-primary">
                                            <i class="ri-phone-fill"></i>
                                        </span>
                                    </div>
                                    <strong class="text-muted mt-1">{{ $employee[0]->mobile_number }}</strong>
                                </div>
                                {{-- <div class="mb-3 d-flex">
                                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                                        <span class="avatar-title rounded-circle fs-16 bg-success">
                                            <i class="ri-building-4-fill"></i>
                                        </span>
                                    </div>
                                    <strong class="text-muted mt-1">{{ $employee[0]->company->company_name }}</strong>
                                </div> --}}
                                <div class="d-flex">
                                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                                        <span class="avatar-title rounded-circle fs-16 bg-danger">
                                            <i class="ri-home-4-fill"></i>
                                        </span>
                                    </div>
                                    <strong class="text-muted mt-1">{{ $employee[0]->branch->br_name }}</strong>
                                </div>
                            </div>
                        </div><!--end card-->
                    </div><!--end col-->
                    <div class="col-xxl-9">
                        <div class="card mt-xxl-n5">
                            <div class="card-header">
                                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                    @permission('profile-basic-info')
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#basic" role="tab">
                                                <i class="fas fa-home"></i>
                                                Basic Info
                                            </a>
                                        </li>
                                    @endpermission
                                    @permission('profile-service-info')
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#service" role="tab">
                                                <i class="far fa-user"></i>
                                                Service Info
                                            </a>
                                        </li>
                                    @endpermission
                                    @permission('profile-company-info')
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#company" role="tab">
                                                <i class="far fa-envelope"></i>
                                                Company Info
                                            </a>
                                        </li>
                                    @endpermission
                                    @permission('profile-attendance')
                                        <li class="nav-item">
                                            <a class="nav-link attendance-tab" data-bs-toggle="tab" href="#attendance"
                                                role="tab">
                                                <i class="far fa-envelope"></i>
                                                Attendance
                                            </a>
                                        </li>
                                    @endpermission
                                </ul>
                            </div>
                            <div class="card-body p-4">
                                <div class="tab-content">
                                    @permission('profile-basic-info')
                                        <div class="tab-pane active" id="basic" role="tabpanel">
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-copper-coin-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Prefix :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->prefix }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-user-star-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">First Name :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->user->first_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-user-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Last Name :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->user->last_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-user-voice-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Preferred Name :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->preferred_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-account-pin-circle-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Father Name :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->father_name }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-user-heart-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Spouse/Partner Name :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->spouse_name }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Date of Birth :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->user->date_of_birth }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-global-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Nationality :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->nationality->nationality_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-genderless-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Gender :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->user->gender }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-copper-coin-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Religion :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->religion->religion_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-bank-card-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">CNIC :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->user->CNIC }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-check-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">CNIC Expiry :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->cnic_expiry }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-group-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Maritial Status :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->marital_status }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-event-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Date of Marriage :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->date_of_marriage }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-parent-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">No. of Children :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->no_of_children }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-file-user-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Children in Super Nova :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->children_in_ucs }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="border mt-3 border-dashed"></div>
                                                <h5 class="text-muted d-flex align-items-center mt-1"><i
                                                        class="ri-building-fill me-1"></i>Birth Place</h5>

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-earth-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Country :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->countries->country_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-map-pin-3-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">State :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->states->state_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-map-pin-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">City :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->cities->city_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end tab-pane-->
                                    @endpermission
                                    @permission('profile-service-info')
                                        <div class="tab-pane" id="service" role="tabpanel">
                                            <div class="row g-2">
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-chat-check-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Hiring Date :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->hiring_date }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-check-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Confirm Date :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->confirm_date }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-bookmark-3-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Job Status :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->job_status }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Regular Date :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->regular_date }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Left Date :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->left_date }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-3 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">From : &nbsp; To :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->from_date }}
                                                            </h6>&nbsp;<h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->to_date }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->


                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-flag-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Probation End Date :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->probation_end_date }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-window-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Probation Extended :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->probation_extended }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-todo-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Death Case :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                <input id="death_case" class="form-check-input mt-0"
                                                                    disabled type="checkbox"
                                                                    @if ($employee[0]->death_case == 'Y') checked @endif
                                                                    value="{{ $employee[0]->death_case }}"
                                                                    name="death_case">
                                                                {{ $employee[0]->death_date }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="border mt-3 border-dashed"></div>

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-coupon-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">EOBI No. :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->eobi_number }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-numbers-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">N.I.Number :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->ni_number }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-mail-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Email :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->user->email }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-smartphone-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Mobile :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->mobile_number }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-bank-card-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Passport# :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->passport_number }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Expiry Date :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->expiry_date }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-coupon-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">C.R.B :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->crb }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-calendar-event-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Issue Date :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->issue_date }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-coupon-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Social Security No. :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->ss_no }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->




                                            </div><!--end row-->
                                        </div><!--end tab-pane-->
                                    @endpermission
                                    @permission('profile-company-info')
                                        <div class="tab-pane" id="company" role="tabpanel">
                                            <div class="row g-2">
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-user-2-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Previous ID :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->previous_id }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-building-4-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Company :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->company->company_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-home-4-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Branch :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->branch->br_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-home-8-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Department :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->department->department_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-map-pin-user-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Designation :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ $employee[0]->designation->designation_name }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="border mt-3 border-dashed"></div>
                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-bank-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Basic Salary :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ number_format($employee[0]->basic_salary) }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-bank-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Gross Salary :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ number_format($employee[0]->gross_salary) }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-6 col-md-4">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-bank-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Allownces :</p>
                                                            <h6 class="text-truncate mb-0">
                                                                {{ number_format($employee[0]->allownces) }}</h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-12 col-md-12">
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                            <div
                                                                class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                <i class="ri-map-pin-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <p class="mb-1">Address :</p>
                                                            <h6 class="text-truncate mb-0">{{ $employee[0]->address }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                {{--
                                            <div class="border mt-3 border-dashed"></div>

                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-arrow-right-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Dept. Head :</p>
                                                        <h6 class="text-truncate mb-0"><input class="form-check-input" type="checkbox" id="dept_head" disabled name="dept_head" value="1" @if ($employee[0]->dept_head == 1) checked @endif></h6>
                                                    </div>
                                                </div>
                                            </div><!--end col-->

                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-arrow-right-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">School Head :</p>
                                                        <h6 class="text-truncate mb-0"><input class="form-check-input" type="checkbox" id="school_head" name="school_head" disabled value="1" @if ($employee[0]->school_head == 1) checked @endif></h6>
                                                    </div>
                                                </div>
                                            </div><!--end col-->

                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-arrow-right-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">SGO Head :</p>
                                                        <h6 class="text-truncate mb-0"><input class="form-check-input" type="checkbox" id="sgo_head" disabled name="sgo_head" value="1" @if ($employee[0]->sgo_head == 1) checked @endif></h6>
                                                    </div>
                                                </div>
                                            </div><!--end col-->

                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-arrow-right-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Un-Entiltled :</p>
                                                        <h6 class="text-truncate mb-0"><input class="form-check-input" type="checkbox" id="un_entiltled" disabled name="un_entiltled" value="1" @if ($employee[0]->un_entiltled == 1) checked @endif></h6>
                                                    </div>
                                                </div>
                                            </div><!--end col-->

                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-arrow-right-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">S.M :</p>
                                                        <h6 class="text-truncate mb-0"><input class="form-check-input" type="checkbox" disabled id="sm" name="sm" value="1" @if ($employee[0]->sm == 1) checked @endif></h6>
                                                    </div>
                                                </div>
                                            </div><!--end col-->

                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-arrow-right-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Accountant / Secretary :</p>
                                                        <h6 class="text-truncate mb-0"><input class="form-check-input" type="checkbox" id="acc_secr" disabled name="acc_secr" value="1" @if ($employee[0]->acc_secr == 1) checked @endif></h6>
                                                    </div>
                                                </div>
                                            </div><!--end col-->

                                            <div class="col-6 col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class="ri-arrow-right-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Multiple Appraisers :</p>
                                                        <h6 class="text-truncate mb-0"><input class="form-check-input" type="checkbox" id="multi_appraiser" disabled name="multi_appraiser" value="1" @if ($employee[0]->multi_appraiser == 1) checked @endif></h6>
                                                    </div>
                                                </div>
                                            </div><!--end col-->
                                            --}}

                                            </div><!--end row-->
                                        </div><!--end tab-pane-->
                                    @endpermission
                                    @permission('profile-attendance')
                                        <div class="tab-pane" id="attendance" role="tabpanel">
                                            <div class="row g-2">
                                                <div class="col-12 col-md-12">
                                                    <div class="card card-h-100">
                                                        <div class="card-body">
                                                            <div id="calendar"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endpermission

                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.attendance-tab', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            weekends: true,
            timeZone: 'local',
            themeSystem: 'bootstrap',
            headerToolbar: {
                right: 'prev,next today',
            },
            events: <?php echo json_encode($employee[0]->employeeAttendanceEvents()); ?>
        });

        calendar.render();
    })
</script>
