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
  background-image: url("{{ asset('billing-bg.jpg') }}");
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
                                                    <img src="{{asset('mobile.png')}}" alt="" height="160" class="move-animation">
                                                </div>
                                                <div class="mb-5">
                                                    <h1 class="display-2 coming-soon-text">PAYING YOUR</h1>
                                                </div>
                                                <div>
                                                    <!-- <div class="row justify-content-center mt-5">
                                                        <div class="col-lg-8">
                                                            <div id="countdown" class="countdownlist"></div>
                                                        </div>
                                                    </div> -->

                                                    <div class="mt-5">
                                                        <h4>fee challan with ease</h4>
                                                        <p class="text-muted">United Charter Schools has set up a payment gateway to make it convenient for you to pay your child’s fee challan. This service makes it easy for you to pay online any time of the day, any day of week by choosing verity of options. You can select the payment option of your linking and proceed to pay the school fee in a safe and secure way. 😊</p>
                                                    </div>

                                                    <div class="input-group countdown-input-group mx-auto my-4">
                                                        <div class="col-md-12">
                                                            {{-- <p class="card-text">
                                                                <a href="javascript:void(0);" class="btn btn-success waves-effect waves-light btn-block">Tutorial for payment through bank account</a>
                                                            </p> --}}
                                                            <p class="card-text">
                                                                <a href="{{route('ipg-billing-billing')}}" class="btn btn-success waves-effect waves-light btn-block">Pay through Debit/Credit Card</a>
                                                            </p>
                                                        </div>
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
                                                <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Online Secure Billing By UCS (A Project of Beaconhouse)</p>
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
