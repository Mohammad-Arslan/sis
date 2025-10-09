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
    {{-- https://test-mcbpk.mtf.gateway.mastercard.com/static/checkout/checkout.min.js
        https://mcbpk.gateway.mastercard.com/checkout/version/60/checkout.js
    --}}
    <script src="https://mcbpk.gateway.mastercard.com/checkout/version/60/checkout.js"
        data-error="errorCallback"
        data-cancel="cancelCallback"
        data-complete="completeCallback"
        data-beforeRedirect="getPageState"
        data-afterRedirect="restorePageState"
        data-timeout="timeoutCallback">
    </script>
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
        <section class="form-bg">
            @include('components.flash_message')
            <br>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">UCS Online Billing</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <form id="ucsBillingForm" class="row g-3 needs-validation" method="POST">
                                @csrf
                                <div class="card col-md-12 mb-3">
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped">
                                            <tr><th colspan="2"><img src="{{ get_file_from_s3('images/' .$studentInvoice->student->student_image)}}" class="avatar-xs rounded-circle" alt="Student Image">&nbsp;{{$studentInvoice->student->first_name.' '.$studentInvoice->student->middle_name.' '.$studentInvoice->student->last_name }}</th></tr>
                                            <tr><th>Class</th><td>{{$studentInvoice->student_fee_package->com_class->class_name}}</td></tr>
                                            <tr><th>Section</th><td>{{$studentInvoice->student_fee_package->section->section_name}}</td></tr>
                                            <tr><th>Email</th><td>{{$studentInvoice->student->email}}</td></tr>
                                            <tr><th>Status</th><td>{{ucfirst(str_replace("_" , " ",$studentInvoice->student->status))}}</td></tr>
                                        </table>
                                        <p><hr></p>
                                        @php
                                            $ipg_charges = round((1.32 / 100) * $billingInfo['total']);
                                            $fed_charges = round($ipg_charges  * 0.13);
                                        @endphp
                                        {{-- @if($billingInfo['total'] < 8000)
                                            @php
                                                $fed_charges = round($ipg_charges  * 0.13);
                                            @endphp
                                        @endif --}}
                                        <table class="table table-bordered table-striped">
                                            <tr><th>Invoice ID</th><td>{{$studentInvoice->invoice_no}}</td></tr>
                                            <tr><th>Fee cycle</th><td>{{$studentInvoice->fee_period->from_date->format("M-Y")}}</td></tr>
                                            <tr><th>Valid Date</th><td>{{$studentInvoice->validity_date->format("M-d-Y")}}</td></tr>
                                            <tr><th>Due Date</th><td>{{$studentInvoice->due_date->format("M-d-Y")}}</td></tr>
                                            <tr><th>IPG Charges</th><td>{{$ipg_charges}} PKR</td></tr>
                                            @if($fed_charges > 0)
                                            <tr><th>FED Charges</th><td>{{$fed_charges}} PKR</td></tr>
                                            @endif
                                            <tr><th>Amount</th><td>{{number_format($billingInfo['total']+$ipg_charges+$fed_charges)}} PKR</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-12 mb-3 text-right">
                                        <input type="hidden" name="state" id="state" value="">
                                        <input type="hidden" id="orderId" name="orderId" value="{{ $orderId }}">
                                        <input type="hidden" id="invoice_id" name="invoice_id" value="{{ $studentInvoice->id }}">
                                        <input type="hidden" id="branch_id" name="branch_id" value="{{ $studentInvoice->student->branch->id }}">
                                        <input type="hidden" id="student_id" name="student_id" value="{{ $studentInvoice->student->id }}">
                                        <input type="hidden" id="sessionId" name="sessionId" value="{{ $session_id }}">
                                        <input type="hidden" class="form-control" name="otp" id="otp" value="{{ $otp }}" >
                                        <input type="hidden" class="form-control" name="amount" id="amount" value="{{$billingInfo['total']+$ipg_charges+$fed_charges}}" >
                                        <input type="hidden" class="form-control" name="bank_received_amount" id="bank_received_amount" value="{{$billingInfo['total']}}" >
                                        <input type="hidden" class="form-control" name="discountable_charges" id="discountable_charges" value="{{$billingInfo['discountable_charges']}}" >
                                        <input type="hidden" class="form-control" name="non_refundable_charges" id="non_refundable_charges" value="{{$billingInfo['non_refundable_charges']}}" >
                                        <input type="hidden" class="form-control" name="sibling_discount_percentage" id="sibling_discount_percentage" value="{{$billingInfo['sibling_discount_percentage']}}" >
                                        <input type="hidden" class="form-control" name="concession_type" id="concession_type" value="{{$billingInfo['concession_type']}}" >
                                        <input type="hidden" class="form-control" name="concession_percentage" id="concession_percentage" value="{{$billingInfo['concession_percentage']}}" >
                                        <input type="hidden" class="form-control" name="concession_discount" id="concession_discount" value="{{$billingInfo['concession_discount']}}" >
                                        <input type="hidden" class="form-control" name="royalty_amount" id="royalty_amount" value="{{$billingInfo['royalty_amount']}}" >
                                        <input type="hidden" class="form-control" name="royalty_percentage" id="royalty_percentage" value="{{$billingInfo['royalty_percentage']}}" >
                                        <input type="hidden" class="form-control" name="total_after_royalty" id="total_after_royalty" value="{{$billingInfo['total_after_royalty']}}" >
                                        <input type="hidden" class="form-control" name="arrears" id="arrears" value="{{$billingInfo['arrears']}}" >
                                        <button class="btn btn-primary" type="button" onclick="Checkout.showPaymentPage();" >Pay Now</button>
                                    </div>
                                </div>

                        </form>
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

<script type="text/javascript">
    function errorCallback(error) {
        alert("errorCallback function") ;
        console.log(JSON.stringify(error));
        document.getElementById("state").value = "errorCallback" ;
    }

    function cancelCallback() {
        //__hc-action-cancel
        $(document).ready(function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to cancel your challan payment!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
            if (result.isConfirmed) {
                var otp = $('#otp').val();
                $.ajax({
                    type:'GET',
                    url:'/verify-billing-otp?otp='+otp,
                    //data: form,
                    headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                    success:function(response) {
                        if(response!='')
                        {
                            console.log('Payment cancelled');
                            document.getElementById("state").value = "cancelCallback" ;
                        }
                        else{
                            Swal.fire({html:'<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">Please try again later</p></div></div>',showCancelButton:!0,showConfirmButton:!1,cancelButtonClass:"btn btn-primary w-xs mb-1",cancelButtonText:"Dismiss",buttonsStyling:!1,showCloseButton:!0})
                        }

                    }
                });
            }
        })
        });
    }

    function timeoutCallback() {
        alert("timeoutCallback function") ;
        console.log('Payment timedout');
        document.getElementById("state").value = "timeoutCallback" ;
    }

    function completeCallback(resultIndicator, sessionVersion) {
        //alert("I am here in complete") ;

       // var orderId = document.getElementById("orderId").value;
        //var amount = document.getElementById("amount").value;
        var invoice_id = document.getElementById("invoice_id").value;
        //var url = "{{ route('payment-success',['invoice_id' => '"+document.getElementById("invoice_id").value+"']) }}";
        var url = '{{ route("payment-success", ["invoice_id" => '+invoiceid+']) }}';
        url = url.replace("%2Binvoiceid%2B", invoice_id);
        var formData = null;
        formData =
        {
            'order_id' : document.getElementById("orderId").value,
            'student_id' : document.getElementById("student_id").value,
            'invoice_id' : document.getElementById("invoice_id").value,
            'branch_id' : document.getElementById("branch_id").value,
            'discountable_charges' : document.getElementById("discountable_charges").value,
            'non_refundable_charges' : document.getElementById("non_refundable_charges").value,
            'sibling_discount_percentage' : document.getElementById("sibling_discount_percentage").value,
            'concession_type' : document.getElementById("concession_type").value,
            'concession_percentage' : document.getElementById("concession_percentage").value,
            'concession_discount' : document.getElementById("concession_discount").value,
            'royalty_percentage' : document.getElementById("royalty_percentage").value,
            'royalty_amount' : document.getElementById("royalty_amount").value,
            'total_after_royalty' : document.getElementById("total_after_royalty").value,
            'arrears' : document.getElementById("arrears").value,
            'billing_amount' : document.getElementById("amount").value,
            'bank_received_amount' : document.getElementById("bank_received_amount").value
        }
        $.ajax({
            type:'POST',
            url:'/add-billing-details',
            data: formData,
            headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
            success:function(response) {
                if(response!='')
                    {
                        document.getElementById("state").value = "completeCallback" ;
                        document.location.href=url;
                    }
                else{
                        Swal.fire({html:'<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">Please try again later</p></div></div>',showCancelButton:!0,showConfirmButton:!1,cancelButtonClass:"btn btn-primary w-xs mb-1",cancelButtonText:"Dismiss",buttonsStyling:!1,showCloseButton:!0})
                    }

                }
            });


    }

    function getPageState() {
        return {
            /* Fill other details */
            orderId: document.getElementById("orderId").value,
            amount: document.getElementById("amount").value,
            otp: document.getElementById("otp").value,
        };
    }

    function restorePageState(data) {
        //alert("restorePageState Callback function called after " + document.getElementById("state").value + " function")  ;
        //alert("orderId: " + data.orderId + "\n amount: " + data.amount) ;
        Swal.fire(
        'orderId:'+ data.orderId,
        'Amount:' + data.amount,
        'success'
        )
        // data.key01
        // data.key02
    }

//Test829910158101
    Checkout.configure({
        merchant: '824410244809',
        session: {
            id: function() {
                return document.getElementById("sessionId").value;
            }
        },

        order: {
            amount: function() {
                return document.getElementById("amount").value;
            },
            currency: 'PKR',
            description: 'UCS Challan Payment',
            id: function() {
                return document.getElementById("orderId").value;
            },
        },

        interaction: {
            operation: 'PURCHASE', // set this field to 'PURCHASE' for <<checkout>> to perform a Pay Operation.
            merchant: {
                name: 'UCS', //UCS
                address: {
                    line1: '10-11 Gurumangat Road',
                    line2: 'Gulberg III Lahore, Pakistan'
                },
                logo: 'https://oms.ucs.edu.pk/ucs-logo-payment.png'
            }
        }
    });
</script>
</body>
</html>
