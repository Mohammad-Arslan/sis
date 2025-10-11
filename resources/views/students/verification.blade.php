<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Billing</title>
    <!-- Layout config Js -->
    <script src="{{ asset('js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
.auth-wrapper .auth-page-content {
  padding-bottom: 60px;
  position: relative;
  z-index: 2;
  width: 100%;
}
.auth-wrapper .footer {
  left: 0;
  background-color: transparent;
  color: #212529;
}
.auth-bg {
  background-image: url("{{ asset('verification-header.jpg') }}");
  background-position: center;
  background-size: cover;
}
.auth-bg .bgr-overlay {
  background: -webkit-gradient(linear, left top, right top, from(#41319c), to(#4b38b3));
  background: linear-gradient(to right, #41319c, #4b38b3);
  opacity: 0.9;
}
.auth-bg .shape {
  position: absolute;
  bottom: 0;
  right: 0;
  left: 0;
  z-index: 1;
  pointer-events: none;
}
.auth-bg .shape > svg {
  width: 100%;
  height: auto;
  fill: var(--vz-body-bg);
}
.bg-postitions {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  width: 100%;
  height: 380px;
}
@media (max-width: 575.98px) {
  .bg-postitions {
    height: 280px;
  }
}
    </style>

</head>
<body><!--<div class="card-header align-items-center d-flex">
                            <img src="asset('ucs-logo.png') }}" width="300">
                        </div> end card header -->
                        <div class="auth-wrapper pt-5">
                            <!-- auth page bg -->
                            <div class="bg-postitions auth-bg"  id="auth-particles">
                                <div class="bgr-overlay"></div>

                                {{-- <div class="shape">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                                        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                                    </svg>
                                </div> --}}
                            </div>

                            <!-- auth page content -->
                            <div class="auth-page-content">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="text-center mt-sm-5 pt-4 mb-4">
                                                <div class="mb-sm-4 pb-sm-4 pb-5">
                                                    <br><br><br><br><br><br>
                                                </div>
                                                <div class="mb-5">
                                                    <h1 class="display-2 coming-soon-text">Student Info</h1>
                                                </div>
                                                <div>
                                                    <!-- <div class="row justify-content-center mt-5">
                                                        <div class="col-lg-8">
                                                            <div id="countdown" class="countdownlist"></div>
                                                        </div>
                                                    </div> -->

                                                    <div class="mt-5">
                                                        <table class="table table-striped table-borderless">
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="d-flex gap-2 align-items-center">
                                                                        <div class="flex-shrink-0">
                                                                            <img src="{{ get_file_from_s3('images/' . $studentInfo->student_image, $studentInfo->student_image) }}" alt=""
                                                                                class="avatar-xl rounded-circle" />
                                                                        </div>
                                                                        <div class="text-bold">
                                                                            &nbsp;<b>{{ $studentInfo->first_name }} {{ $studentInfo->middle_name }} {{ $studentInfo->last_name }}</b>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Gender</th>
                                                                <td>{{ ucfirst($studentInfo->gender) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Roll No.</th>
                                                                <td>
                                                                    {{ $studentInfo->roll_no }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Gaurdian Name</th>
                                                                <td>
                                                                    {{ $studentInfo->guardian->guardian_name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Branch</th>
                                                                <td>
                                                                    {{ $studentInfo->branch->br_name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Class / Section</th>
                                                                <td>
                                                                    {{ $studentInfo->active_class->branch_class_sections->com_classes->class_name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Enrolled</th>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($studentInfo->admission_wef)->format('d M Y') }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Left</th>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($studentInfo->student_withdrawals->last_day_at)->format('d M Y') }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Certificate No.</th>
                                                                <td>{{ $studentInfo->certificate_number }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row -->

                                </div>
                                <!-- end container -->
                            </div>
                            <!-- end auth page content -->

                            <!-- footer -->
                            <footer class="footer">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="text-center">
                                                <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Online School Leaving Certificate Verification By UCS (A Project of Beaconhouse)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </footer>
                            <!-- end Footer -->
                        </div>

    {{-- @endsection --}}
    <!-- JAVASCRIPT -->
    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <!-- particles js -->
    <script src="{{ asset('libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ asset('js/pages/particles.app.js') }}"></script>
    <!-- Countdown js -->
    <script src="{{ asset('js/pages/coming-soon.init.js') }}"></script>


    </body>
</html>
