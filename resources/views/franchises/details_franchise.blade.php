<div id="franchiseInquireModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Franchise Inquiry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBordered">
                    <div class="accordion-item accordion-fill-primary">
                        <h2 class="accordion-header" id="personal_information">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse1" aria-expanded="true" aria-controls="accor_borderedExamplecollapse1">
                                Personal Information
                            </button>
                        </h2>
                        <div id="accor_borderedExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="personal_information" data-bs-parent="#accordionBordered" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <span class="counter-value"><i class="las la-user-secret"></i>&nbsp;First Name</span>
                                            </h6>
                                            <p class="text-muted mb-0 pl-3">{{$franchise->appl_name}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <span class="counter-value"><i class="las la-user-secret"></i>&nbsp;Last Name</span>
                                            </h6>
                                            <p class="text-muted mb-0 pl-3">{{$franchise->appl_last_name}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <span class="counter-value"><i class="las la-address-card"></i>&nbsp;CNIC</span>
                                            </h6>
                                            <p class="text-muted mb-0 pl-3">{{$franchise->CNIC}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Email</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->email}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-phone-volume"></i>&nbsp;Contact No</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->primary_mobile_no}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-briefcase"></i>&nbsp;Current Bussiness/Occupation</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->other_profession}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Organization Name</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->organization_name}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-user-plus"></i>&nbsp;Designation</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_designation}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bxs-graduation"></i>&nbsp;Qualification</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_qualification}}</p>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-home"></i>&nbsp;Address</span>
                                        </h6>
                                        <p class="text-muted mb-0">{{$franchise->personal_address}}
                                        </p>
                                    </div>                        
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion-item accordion-fill-primary">
                        <h2 class="accordion-header" id="educationalOrganization">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse2" aria-expanded="true" aria-controls="accor_borderedExamplecollapse2">
                                Led an educational organization before
                            </button>
                        </h2>
                        <div id="accor_borderedExamplecollapse2" class="accordion-collapse collapse" aria-labelledby="educationalOrganization" data-bs-parent="#accordionBordered" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    @if(!empty($franchise->edu_organization_name))
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Organization Name</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->edu_organization_name}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-user-plus"></i>&nbsp;Designation</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_edu_designation}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-phone-volume"></i>&nbsp;Experience</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_experience}}. Years</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Description</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->edu_organization_desc}}</p>
                                    </div>
                                    @else 
                                    <div class="col-md-12 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><center><i>&nbsp;There is no detail available.</i></center></span>
                                        </h6>
                                    </div>
                                    @endif                       
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item accordion-fill-primary">
                        <h2 class="accordion-header" id="ledFranchise">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse3" aria-expanded="true" aria-controls="accor_borderedExamplecollapse3">
                                Led Franchise
                            </button>
                        </h2>
                        <div id="accor_borderedExamplecollapse3" class="accordion-collapse collapse" aria-labelledby="ledFranchise" data-bs-parent="#accordionBordered" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    @if(!empty($franchise->edu_organization_name))
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Organization Name</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->edu_organization_name}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-user-plus"></i>&nbsp;Designation</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_edu_designation}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-phone-volume"></i>&nbsp;Experience</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_experience}}. Years</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Description</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->edu_organization_desc}}</p>
                                    </div>
                                    @else 
                                    <div class="col-md-12 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><center><i>&nbsp;There is no detail available.</i></center></span>
                                        </h6>
                                    </div>
                                    @endif                       
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item accordion-fill-primary">
                        <h2 class="accordion-header" id="schoolBuildingOptions">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse4" aria-expanded="true" aria-controls="accor_borderedExamplecollapse4">
                                School Building
                            </button>
                        </h2>
                        <div id="accor_borderedExamplecollapse4" class="accordion-collapse collapse" aria-labelledby="schoolBuildingOptions" data-bs-parent="#accordionBordered" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    @if(!empty($franchise->edu_organization_name))
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Organization Name</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->edu_organization_name}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-user-plus"></i>&nbsp;Designation</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_edu_designation}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-phone-volume"></i>&nbsp;Experience</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->inquirer_experience}}. Years</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Description</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->edu_organization_desc}}</p>
                                    </div>
                                    @else 
                                    <div class="col-md-12 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><center><i>&nbsp;There is no detail available.</i></center></span>
                                        </h6>
                                    </div>
                                    @endif                       
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item accordion-fill-primary">
                        <h2 class="accordion-header" id="convertingExistingBuilding">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse5" aria-expanded="true" aria-controls="accor_borderedExamplecollapse5">
                                Converting Existing Building
                            </button>
                        </h2>
                        <div id="accor_borderedExamplecollapse5" class="accordion-collapse collapse" aria-labelledby="convertingExistingBuilding" data-bs-parent="#accordionBordered" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Building Status</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->building_status}}</p>
                                    </div>

                                     <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Building Ownership</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->building_ownership}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Area/Halqa</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->building_area}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Address</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->building_address}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Existing Area (Sq. Yards/Kanals)</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->building_existing_area_size}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;Total Covered Area</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->building_covered_area}}</p>
                                    </div>
                                                     
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion-item accordion-fill-primary">
                        <h2 class="accordion-header" id="locationAndPropertyDetails">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse6" aria-expanded="true" aria-controls="accor_borderedExamplecollapse6">
                                Location and property details
                            </button>
                        </h2>
                        <div id="accor_borderedExamplecollapse6" class="accordion-collapse collapse" aria-labelledby="locationAndPropertyDetails" data-bs-parent="#accordionBordered" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Time required for construction /renovation</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->time_required}}</p>
                                    </div>

                                     <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Proposed investment amount:PKR</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->proposed_investment}}</p>
                                    </div>

                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="bx bx-buildings"></i>&nbsp;Status opf Property</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->property_status}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12  mt-3">
                                        <h6 class="mb-1">
                                            <span class="counter-value"><i class="las la-envelope"></i>&nbsp;plan for financing the franchise</span>
                                        </h6>
                                        <p class="text-muted mb-0 pl-3">{{$franchise->financing_plan}}</p>
                                    </div>                                                
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>