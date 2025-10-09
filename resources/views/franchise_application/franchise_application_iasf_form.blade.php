@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('franchise-applications.index')}}">Franchise Application List</a></li>
        <li class="breadcrumb-item active">Inquiry Assessment & Survey</li>
    </x-breadcrumb>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Inquiry Assessment & Survey Form</h4>
                    <div class="flex-shrink-0">

                        {{-- @permission('add-franchise-application') --}}
                            <a href="{{ route('franchise-application-iasf.create_iasf_pdf', $id) }}" class="btn btn-success btn-label btn-sm">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Download PDF
                            </a>
                        {{-- @endpermission --}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="col-md-6"><table class="table table-borderless mb-0">
                                                    <tr>
                                                        <th class="ps-0" scope="row" width="20%">NWA Name :</th>
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
                    <div class="row">
                        <table id="iasfReport" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">

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
                            {{--<tr>
                                <th class="">Area Population (Approx.) </th>
                                <th colspan="2" class="">Classes (Approx. %) </th>
                            </tr>
                             <tr>
                                <th class="">Withon 0.5 KM Radius</th>
                                <td colspan="2" class="">{{$area_population_half_km_radius}}</td>
                            </tr>
                            <tr>
                                <th class="">Withon 01 KM Radius</th>
                                <td colspan="2" class="">{{$area_population_one_km_radius}}</td>
                            </tr>
                            <tr>
                                <th class="">Withon 02 KM Radius</th>
                                <td colspan="2" class="">{{$area_population_two_km_radius}}</td>
                            </tr> --}}
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
                        </table>
                        <table class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
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


                </div>
            </div>
        </div>
    </div>





@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">

    </script>
@endpush
