<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCS Online Billing</title>
    <script src="{{ asset('js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/dist/default/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
    type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/dist/default/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('css/custom.min.css') }}" rel="stylesheet" type="text/css" />


    <style type="text/css">
        .submit-btn {
            background: #1d398d;
            padding: 5px 35px;
            color:  #fff !important;
            font-size: 20px;
            font-family: "open sans", sans-serif;
            border-radius: 100px;
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>
</head>

<body style="overflow-x: hidden">
    <div class="container">
        <div class="billing-header">
            <div class="logo-header">
                <a href="https://www.unitedcharterschools.net/" target="_blank">
                    <img src="{{ asset('ucs-logo.png') }}" width="300">
                </a>
            </div>
        </div>
        <div class="clear"></div>
        {{-- <section class="admission-text ">
            <h1>Apply for Franchise</h1>
            <p>United Charter Schools was launched with the intention of combining Beaconhouse's 46 years of excellence in education with the innovations of modern technology.
                <br>
                Fill out the form below and join us as a franchisee on our shared journey towards giving the children of tomorrow a solid foundation to build their future.
            </p>
        </section> --}}

        <section class="form-bg">
            @include('components.flash_message')
            <br>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">UCS Online Billing</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                                <div class="card border card-border-info col-md-12 mb-3">
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div class="form-label-group in-border">
                                                <label for="studentID" class="form-label">Student ID <span class="star-red">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="studentID" id="studentID" class="form-control" value="{{ old('studentID') }}" maxlength="15" id="studentID" placeholder="Enter Student ID and press the Submit button" required>
                                                    <button class="btn btn-outline-success shadow-none" type="button" id="button-Search" onclick="SearchGaurdians(document.getElementById('studentID').value);">Submit</button>
                                                </div>

                                                <div class="invalid-tooltip">
                                                    @if ($errors->has('studentID'))
                                                        {{ $errors->first('studentID') }}
                                                    @else
                                                    Student ID is required!
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card col-md-12 mb-3" id="otp_card" style="display: none;">
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div class="form-label-group in-border">
                                                <label for="mode" class="form-label">Please choose from the following modes mentioned below (Mobile or Email) to receive your One-Time Password and click on the Send OTP button</label>
                                                <div id="otp_mode">

                                                </div>
                                                <div class="invalid-tooltip">
                                                    @if ($errors->has('mode'))
                                                        {{ $errors->first('mode') }}
                                                    @else
                                                    mode is required!
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3 mb-3">
                                            <button class="btn btn-primary" type="button" id="send_otp" onclick="SendOTP()">Send OTP</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card col-md-12 mb-3" id="otp_submit" style="display: none;">
                                    <form id="ucsBillingForm" class="row g-3 needs-validation" action="/verify-billing-otp" method="GET">
                                        @csrf
                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <div class="form-label-group in-border">
                                                    <label for="otp" class="form-label">An OTP (One-Time Password) will be sent as an SMS or Email to the mode you have selected above. Please enter the 4 digit OTP and click on the Submit button to access your child's fee challan.</label>
                                                    <input type="text" class="form-control @if($errors->has('otp')) is-invalid @endif" id="otp" name="otp" required>
                                                    <div class="invalid-tooltip">
                                                        @if($errors->has('otp'))
                                                        {{ $errors->first('otp') }}
                                                        @else
                                                        OTP is required!
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3 mb-3">
                                                <button class="btn btn-primary" type="submit" id="verify_otp">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        </div>


                    </div>
                </div>
            </div>
        </section>
    </div>
<script src="{{ asset('theme/dist/default/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>


<!-- aos js -->
<script src="{{ asset('theme/dist/default/assets/libs/aos/aos.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/pages/form-validation.init.js') }}"></script>
<!-- animation init -->
<script src="{{ asset('theme/dist/default/assets/js/pages/animation-aos.init.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.js">
</script>
<script src="{{ asset('theme/dist/default/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
{{-- <script src="{{asset('theme/dist/default/assets/js/pages/sweetalerts.init.js')}}"></script> --}}


<!-- App js -->
<script src="{{ asset('theme/dist/default/assets/js/app.js') }}"></script>

<!-- form masks init -->
<script src="{{ asset('theme/dist/default/assets/libs/cleave.js/cleave.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/js/blockui.js') }}"></script>
<!-- cleave.js -->
<script src="{{ asset('theme/dist/default/assets/libs/cleave.js/cleave.min.js') }}"></script>

<script>
    function SearchGaurdians(search_val)
    {
        if(search_val =='')
        {
            //$.ajax({
                Swal.fire({html:'<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">Please enter the Student ID and press submit button</p></div></div>',showCancelButton:!0,showConfirmButton:!1,cancelButtonClass:"btn btn-primary w-xs mb-1",cancelButtonText:"Dismiss",buttonsStyling:!1,showCloseButton:!0});
                return false;
            //});
        }
        var data = null;
        data =
        {
            'field_val' : search_val,
            'field' : 'roll_no'
        }

        $.ajax({
            type:'POST',
            url:'/get-gaurdian-details',
            data: data,
            headers: {
               'X-CSRF-Token': '{{ csrf_token() }}',
           },
            success:function(response) {
                if(response!='')
                {
                    $('#otp_card').show();
                    $('#otp_mode').html('');
                    $('#otp_mode').html(response);
                }
                else{
                    Swal.fire({html:'<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">Student ID is invalid</p></div></div>',showCancelButton:!0,showConfirmButton:!1,cancelButtonClass:"btn btn-primary w-xs mb-1",cancelButtonText:"Dismiss",buttonsStyling:!1,showCloseButton:!0})
                }

            }
        });
    }

    function SendOTP()
    {
        var selectedValue = document.querySelector('input[name="mode"]:checked');
        var student_id = document.getElementById('student_id').value;
        //alert(selectedValue.value);
        data =
        {
            'mode' : selectedValue.value,
            'student_id' : student_id
        }

        $.ajax({
            type:'POST',
            url:'/send-billing-otp',
            data: data,
            headers: {
               'X-CSRF-Token': '{{ csrf_token() }}',
           },
            success:function(response) {
                if(response!='')
                {
                    $("#send_otp").attr("disabled", true);
                    $('#otp_submit').show();
                }
                else{
                    Swal.fire({html:'<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">Please try again later</p></div></div>',showCancelButton:!0,showConfirmButton:!1,cancelButtonClass:"btn btn-primary w-xs mb-1",cancelButtonText:"Dismiss",buttonsStyling:!1,showCloseButton:!0})
                }

            }
        });
    }

    /*function VerifyOTP()
    {
        var otp = $('#otp').val();
        $.ajax({
            type:'GET',
            url:'/verify-billing-otp?otp='+otp,
            //data: form,
            headers: {
               'X-CSRF-Token': '{{ csrf_token() }}',
           },
            success:function(response) {
                var result = JSON.parse(JSON.stringify(response));
                if(result.orderId !='')
                {
                    //$("#send_otp").attr("disabled", true);
                    //$('#otp_submit').show();
                }
                else{
                    Swal.fire({html:'<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">Please try again later</p></div></div>',showCancelButton:!0,showConfirmButton:!1,cancelButtonClass:"btn btn-primary w-xs mb-1",cancelButtonText:"Dismiss",buttonsStyling:!1,showCloseButton:!0})
                }

            }
        });

    }*/
</script>
</body>
</html>
