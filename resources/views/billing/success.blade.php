<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500&display=swap" rel="stylesheet">


    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login_style.css') }}" rel="stylesheet">

</head>

<body class=" ">
    <div class="row">
        <div class="card-parent">
            <div class="main-card col-md-12">
                <div class="main-card-inner row">
                    <div class="left col-md-6">
                        <div class="login-screen-left">

                            <div class="login-screen-inner">
                                <p class="section-heading">UNITED CHARTER SCHOOLS <br>BILL PAYMENT</p>
                                <div class="left-side-section decription">
                                    <h4 class="text-white">Your UCS Bill Payment has been successfully done 😊 !</h4>
                                </div>
                                <hr>
                                <div class="left-side-section decription">
                                    <p class="col-md-6 col-md-12">
                                        <table class="table table-bordered table-striped align-middle table-nowrap mb-0 text-white" style="width:100%">
                                            <tr>
                                                <th>Order ID</th>
                                                <td>{{ $orderId }}</td>
                                            </tr>
                                            <tr>
                                                <th>Amount</th>
                                                <td>{{ $amount }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <td>{{ date('d-M,Y')}}</td>
                                            </tr>
                                        </table>
                                </p>
                                </div>
                                <hr>

                                <!-- <div class="left-side-section lower row">
                                    <p class="support d-flex"><i class="fa fa-envelope email-icon" aria-hidden="true">
                                        </i>pmcsupport@beaconhouse.net</p>
                                    <p class="support d-flex"><i class="fa fa-phone" aria-hidden="true"></i>
                                        +42 111 277 111</p>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="login-screen-right col-md-6">

                        <div class="login-screen-inner right-contnet">
                            <div class="first-section">
                                <div class="logo">
                                    <img src="{{ asset('ucs-logo.png') }}" alt="logo" width="300">
                                </div>
                                <div class="avatar-lg mx-auto mt-2">
                                    <div class="avatar-title bg-light text-success display-3 rounded-circle">
                                        <i class="ri-checkbox-circle-fill"></i>
                                    </div>
                                </div>
                                <div class="right-contnet">
                                    <h1>
                                        Success
                                    </h1>
                                    <p>
                                        <img src="{{ asset('successful-transaction.png') }}"
                                    </p>
                                </div>
                            </div>
                            <div class="third-section">
                                <div class="btn_forget_password d-flex justify-content-between">
                                    <a class="btn btn-primary block full-width m-b mt-3" href="{{ route('ipg-billing.index')}}">Billing Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<script>

</script>
</html>
