<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<style>
    table{
        font-size: 10px;
    }

    .table td, .table th {
        padding: 0.45rem;
    }
</style>
<body>
        <div class="row col-md-12 text-center">
            <h6><strong>Inquiry Assessment & Survey</strong></h6>
        </div>

            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0" style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td class="col-md-6"><table class="table table-borderless mb-0">
                                            <tr>
                                                <th class="ps-0" scope="row" width="25%">NWA Name :</th>
                                                <td>{{$applicant_name}}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Contact :</th>
                                                <td>{{$applicant_contact}}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Address :</th>
                                                <td>{{$nwa_address}}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">CNIC :</th>
                                                <td>{{$cnic}}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Email :</th>
                                                <td>{{$nwa_email}}</td>
                                            </tr>
                                            </table>
                                        </td>
                                        <td class="col-md-6">
                                            <table class="table table-borderless mb-0">
                                                <tr>
                                                    <th class="ps-0" scope="row">Agreement Type :</th>
                                                    <td>{{$agreement_type}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Site Address :</th>
                                                    <td>{{$proposed_location}}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>
            <div class="row mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width: 100%">
                                <tbody>
                                    <tr>
                                        <th colspan="3" class="text-uppercase text-center">School Details</th>
                                    </tr>
                                    <tr>
                                        <th class="text-uppercase">Type</th>
                                        <th colspan="2" class="text-uppercase">Configuration</th>
                                    </tr>
                                    <tr>
                                        <td class="">{{$school_type}}</td>
                                        <td colspan="2" class="">{{$school_configuration}}</td>
                                    </tr>
                                    <tr>
                                        <th class="" width="20%">BD Status</th>
                                        <td colspan="2" width="80%">{{$bd_status}}</td>
                                    </tr>
                                    <tr>
                                        <th>BD Remarks</th>
                                        <td colspan="2" class="text-wrap">{{$bd_remarks}}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-uppercase text-center">School Operations</th>
                                    </tr>
                                    <tr>
                                        <th class="">MOU/FA Status</th>
                                        <td colspan="2" class="">{{$mou_fa_status}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">MOU/FA Date</th>
                                        <td colspan="2" class="">{{$agreement_date}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Operational Date as per MOU/FA</th>
                                        <td colspan="2" class="">{{$operational_date}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Actual Operational Date</th>
                                        <td colspan="2" class="">{{$actual_operational_date}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Renewal Date</th>
                                        <td colspan="2" class="">{{$renewal_date}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Bank Account Details</th>
                                        <td colspan="2" class="">{{$bank_acc_detail}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Proposed School Name</th>
                                        <td colspan="2" class="">{{$proposed_school_name}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Nature/Type of locality</th>
                                        <td colspan="2" class="">{{ucfirst($type_of_locality)}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Type of Consturction</th>
                                        <td colspan="2" class="">{{$type_of_construction}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">Plot Area</th>
                                        <td colspan="2" class="">{{$plot_size_actual}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">QA Status</th>
                                        <td colspan="2">{{ucfirst($qa_status)}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">QA Remarks</th>
                                        <td colspan="2" class="text-wrap">{{$qa_remarks}}</td>
                                    </tr>
                                    <tr>
                                        <th class="">DD Status</th>
                                        {{-- <td colspan="2">{{ucfirst($dd_status)}}</td> --}}
                                        <td colspan="2">{{($dd_status) == 'approved' ? 'Approved' : ( ($dd_status) == 'not_approved' ? 'Not Approved': 'Pending')}}</td>

                                    </tr>
                                    <tr>
                                        <th class="">DD Remarks</th>
                                        <td colspan="2" class="text-wrap">{{$dd_remarks}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>
            <div class="row mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered"  style="width: 100%">
                                <tr>
                                    <th colspan="6" class="text-uppercase text-center">Payment Details</th>
                                </tr>
                                <tr>
                                    <th>School Type</th>
                                    <th>Agreement Type</th>
                                    <th>Total Franchise Fee As Per MOU/FA (PKR)</th>
                                    <th>Total Received (PKR)</th>
                                    <th>Total Receivable (PKR)</th>
                                    <th>Remarks</th>
                                </tr>
                                <tr>
                                    <td>{{$school_type}}</td>
                                    <td>{{$agreement_type}}</td>
                                    <td>{{number_format($total_franchise_fee + $token_money)}}</td>
                                    <td>{{number_format($amount_received)}}</td>
                                    <td>{{number_format((($total_franchise_fee + $token_money) - $amount_received))}}</td>
                                    <td class="text-wrap">{{$tor_remarks}}</td>
                                </tr>
                            </table>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

