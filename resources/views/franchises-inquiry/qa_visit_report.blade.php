@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">QA Visit Report</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">
							<form class="row g-3 needs-validation" novalidate>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
											<input type="text" class="form-control @if($errors->has('proposed_address')) is-invalid @endif" id="locationAddress" name="proposed_address" placeholder="Please enter first name" value="{{ old('proposed_address') }}" required>
											<label for="locationAddress" class="form-label">Proposed Location Address</label>
											<div class="invalid-tooltip">
													@if($errors->has('proposed_address'))
													{{ $errors->first('proposed_address') }}
													@else
													Proposed location address is required!
													@endif
											</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
										<div class="input-group">
												<input type="text" class="form-control @if($errors->has('visit_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ old('visit_date') }}" name="visit_date" id="visitDate" required>
												<label for="visitDate" class="form-label">Visit date</label>
												<div class="input-group-text bg-primary border-primary text-white">
														<i class="ri-calendar-2-line"></i>
												</div>
												<div class="invalid-tooltip">
														@if($errors->has('visit_date'))
														{{ $errors->first('visit_date') }}
														@else
														Visit date is required!
														@endif
												</div>
										</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
											<input type="text" class="form-control @if($errors->has('sales_rep_name')) is-invalid @endif" id="salesRepName" name="sales_rep_name" placeholder="Please enter first name" value="{{ old('sales_rep_name') }}" required>
											<label for="salesRepName" class="form-label">Sales Representative Name</label>
											<div class="invalid-tooltip">
													@if($errors->has('sales_rep_name'))
													{{ $errors->first('sales_rep_name') }}
													@else
													Sales Representative Name is required!
													@endif
											</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
											<input type="text" class="form-control @if($errors->has('qa_req_name')) is-invalid @endif" id="qaReqName" name="qa_req_name" placeholder="Please enter first name" value="{{ old('qa_req_name') }}" required>
											<label for="qaReqName" class="form-label">QA Representative</label>
											<div class="invalid-tooltip">
													@if($errors->has('qa_req_name'))
													{{ $errors->first('qa_req_name') }}
													@else
													QA Representative is required!
													@endif
											</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
											<input type="text" class="form-control @if($errors->has('client_name')) is-invalid @endif" id="clientName" name="client_name" placeholder="Please enter first name" value="{{ old('client_name') }}" required>
											<label for="clientName" class="form-label">Client Name</label>
											<div class="invalid-tooltip">
													@if($errors->has('client_name'))
													{{ $errors->first('client_name') }}
													@else
													Client Name is required!
													@endif
											</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
											<input type="text" class="form-control @if($errors->has('client_contact')) is-invalid @endif" id="clientContact" name="client_contact" placeholder="Please enter first name" value="{{ old('client_contact') }}" required>
											<label for="clientContact" class="form-label">Client Contact</label>
											<div class="invalid-tooltip">
													@if($errors->has('client_contact'))
													{{ $errors->first('client_contact') }}
													@else
													Client Contact is required!
													@endif
											</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
											<input type="text" class="form-control @if($errors->has('purpose_of_visit')) is-invalid @endif" id="purposeOfVisit" name="purpose_of_visit" placeholder="Please enter first name" value="{{ old('purpose_of_visit') }}" required>
											<label for="purposeOfVisit" class="form-label">Purpose of visit</label>
											<div class="invalid-tooltip">
													@if($errors->has('purpose_of_visit'))
													{{ $errors->first('purpose_of_visit') }}
													@else
													Purpose of visit is required!
													@endif
											</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12">
									<div class="form-label-group in-border">
											<select class="form-select @if($errors->has('school_type')) is-invalid @endif" id="schoolType" name="school_type" aria-label="school_type select" required>
													<option value="">Please select a Proposed school type</option>
													<option value="5th" {{ old('school_type') == '5th' ? 'selected' : '' }}>Pre Nursery to 5th</option>
													<option value="o_level" {{ old('school_type') == 'o_level' ? 'selected' : '' }}>Pre Nursery to O Levels</option>
											</select>
											<label for="schoolType" class="form-label">Proposed school type</label>
											<div class="invalid-tooltip">
													@if($errors->has('school_type'))
													{{ $errors->first('school_type') }}
													@else
													Proposed School Type is required!
													@endif
											</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-label-group in-border">
											<textarea class="form-control" id="qaRemarks" name="qa_remarks" rows="2" placeholder="Please enter your street address" required>{{ old('qa_remarks') }}</textarea>
											<label for="qaRemarks" class="form-label">QA Remarks</label>
											<div class="invalid-tooltip">
													@if($errors->has('qa_remarks'))
													{{ $errors->first('qa_remarks') }}
													@else
													QA Remarks is required!
													@endif
											</div>
									</div>
								</div>
								<div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
									{{-- 1 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#proposedSiteAccordian" aria-expanded="true" aria-controls="proposedSiteAccordian">
												1. Proposed site details:
											</button>
										</h2>
										<div id="proposedSiteAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">1.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Total plot size</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">1.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Site Location</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="site_location" id="siteLocation01">
															<label class="form-check-label" for="siteLocation01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="site_location" id="siteLocation02">
															<label class="form-check-label" for="siteLocation02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 2 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#buildingDetailAccordian" aria-expanded="true" aria-controls="buildingDetailAccordian">
												2. Building details:
											</button>
										</h2>
										<div id="buildingDetailAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">2.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Type of building</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_building" id="typeOfBuilding01">
															<label class="form-check-label" for="typeOfBuilding01">
																	Purpose built
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_building" id="typeOfBuilding02">
															<label class="form-check-label" for="typeOfBuilding02">
																	Refurbished
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">2.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Type of construction</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_construction" id="typeOfConstrunction01">
															<label class="form-check-label" for="typeOfConstrunction01">
																	Frame Structure
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_construction" id="typeOfConstrunction02">
															<label class="form-check-label" for="typeOfConstrunction02">
																	Load Bearing Wall
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">2.3</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Total covered area of building</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
															<span class="input-group-text"> Sft</span>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">2.4</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Total open area</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">2.5</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Total nos. of Classrooms</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">2.6</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Total Lab Rooms & Library</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">2.7</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Classroom's size</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
															<span class="input-group-text"> Sft</span>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">2.8</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Provision of Extension for further rooms</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="provision_of_extension" id="provisionOfExtension01">
															<label class="form-check-label" for="provisionOfExtension01">
																	Available
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="provision_of_extension" id="provisionOfExtension02">
															<label class="form-check-label" for="provisionOfExtension02">
																	Not Available
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">2.9</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Admin Block</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="admin_block" id="adminBlock01">
															<label class="form-check-label" for="adminBlock01">
																	Available
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="admin_block" id="adminBlock02">
															<label class="form-check-label" for="adminBlock02">
																	Not Available
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 3 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#areaPopulationAccordian" aria-expanded="true" aria-controls="areaPopulationAccordian">
												3. Area Population:
											</button>
										</h2>
										<div id="areaPopulationAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center py-2">
													<div class="col-auto">3.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">within .5-1-2 km radius</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 4 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#surroundingsAccordian" aria-expanded="true" aria-controls="surroundingsAccordian">
												4. Surroundings:
											</button>
										</h2>
										<div id="surroundingsAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">4.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">School in vicinity</div>
													<div class="col-lg-6 col-md-8">
														<div class="input-group">
															<span class="input-group-text">1.</span>
															<input type="text" class="form-control">
															<span class="input-group-text">2.</span>
															<input type="text" class="form-control">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Type of locality</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_locality" id="typeOfLocality01">
															<label class="form-check-label" for="typeOfLocality01">
																	Residentail
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_locality" id="typeOfLocality02">
															<label class="form-check-label" for="typeOfLocality02">
																	Commercial
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.3</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is the School site centrally located within the locality</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_central_locality" id="isCentralLocality01">
															<label class="form-check-label" for="isCentralLocality01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_central_locality" id="isCentralLocality01">
															<label class="form-check-label" for="isCentralLocality01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.4</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is a market / book shop near-by</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_market_nearby" id="isMarketNearby01">
															<label class="form-check-label" for="isMarketNearby01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_market_nearby" id="isMarketNearby02">
															<label class="form-check-label" for="isMarketNearby02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.5</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Road access & condition</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="road_access" id="roadAccess01">
															<label class="form-check-label" for="roadAccess01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="road_access" id="roadAccess02">
															<label class="form-check-label" for="roadAccess02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.6</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Road access type</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.7</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Facing road width/size</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Width = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Size = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.8</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is public transport facility available near-by</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_public_transport" id="isPublicTransport01">
															<label class="form-check-label" for="isPublicTransport01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_public_transport" id="isPublicTransport02">
															<label class="form-check-label" for="isPublicTransport02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.9</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Noise pollution</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="noise_pollution" id="noisePollution01">
															<label class="form-check-label" for="noisePollution01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="noise_pollution" id="noisePollution02">
															<label class="form-check-label" for="noisePollution02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.10</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is a Hospital / Clinic available within easy reach</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_hospital_available" id="isHospitalAvailable01">
															<label class="form-check-label" for="isHospitalAvailable01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_hospital_available" id="isHospitalAvailable02">
															<label class="form-check-label" for="isHospitalAvailable02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.11</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is a fire brigade available near-by</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_rescue_available" id="isRescueAvailable01">
															<label class="form-check-label" for="isRescueAvailable01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_rescue_available" id="isRescueAvailable02">
															<label class="form-check-label" for="isRescueAvailable02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.12</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is Police station available near-by</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_police_available" id="isPoliceAvailable01">
															<label class="form-check-label" for="isPoliceAvailable01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_police_available" id="isPoliceAvailable02">
															<label class="form-check-label" for="isPoliceAvailable02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">4.13</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is the School site located near a river / canal</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_river_nearby" id="isRiverNearby01">
															<label class="form-check-label" for="isRiverNearby01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_river_nearby" id="isRiverNearby02">
															<label class="form-check-label" for="isRiverNearby02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">4.14</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Is the School site protected against flooding</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_flood_protected" id="isFloodProtected01">
															<label class="form-check-label" for="isFloodProtected01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_flood_protected" id="isFloodProtected02">
															<label class="form-check-label" for="isFloodProtected02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 5 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#trafficControlAccordian" aria-expanded="true" aria-controls="trafficControlAccordian">
												5. Traffic Control:
											</button>
										</h2>
										<div id="trafficControlAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">5.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Seperate visitor parking</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_parking" id="seperateParking01">
															<label class="form-check-label" for="seperateParking01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_parking" id="seperateParking02">
															<label class="form-check-label" for="seperateParking02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">5.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Traffic signs</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="traffic_signs" id="trafficSigns01">
															<label class="form-check-label" for="trafficSigns01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="traffic_signs" id="trafficSigns02">
															<label class="form-check-label" for="trafficSigns02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">5.3</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Street having dead end</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="dead_end_street" id="deadEndStreet01">
															<label class="form-check-label" for="deadEndStreet01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="dead_end_street" id="deadEndStreet02">
															<label class="form-check-label" for="deadEndStreet02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 6 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#playgroundAccordian" aria-expanded="true" aria-controls="playgroundAccordian">
												6. Play Ground:
											</button>
										</h2>
										<div id="playgroundAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">6.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Available</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="available" id="available01">
															<label class="form-check-label" for="available01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="available" id="available02">
															<label class="form-check-label" for="available02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">6.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Available minimun play ground @ 35% of total plot area</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="minimum_playground" id="minimumPlayground01">
															<label class="form-check-label" for="minimumPlayground01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="minimum_playground" id="minimumPlayground02">
															<label class="form-check-label" for="minimumPlayground02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 7 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#buildingAppearanceAccordian" aria-expanded="true" aria-controls="buildingAppearanceAccordian">
												7. Appearance of building:
											</button>
										</h2>
										<div id="buildingAppearanceAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">7.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Boundary wall plaster</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_plaster" id="wallPlaster01">
															<label class="form-check-label" for="wallPlaster01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_plaster" id="wallPlaster02">
															<label class="form-check-label" for="wallPlaster02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Over all building plaster</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_plaster" id="buildingPlaster01">
															<label class="form-check-label" for="buildingPlaster01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_plaster" id="buildingPlaster02">
															<label class="form-check-label" for="buildingPlaster02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.3</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Boundary wall paintwork</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_paintwork" id="wallPaintwork01">
															<label class="form-check-label" for="wallPaintwork01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_paintwork" id="wallPaintwork02">
															<label class="form-check-label" for="wallPaintwork02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.4</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Over all building paintwork <br/> Seepage</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_paintwork" id="buildingPaintwork01">
															<label class="form-check-label" for="buildingPaintwork01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_paintwork" id="buildingPaintwork02">
															<label class="form-check-label" for="buildingPaintwork02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.5</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Windows & Doors condition</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_condition" id="doorsCondition01">
															<label class="form-check-label" for="doorsCondition01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_condition" id="doorsCondition02">
															<label class="form-check-label" for="doorsCondition02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.6</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Windows & Doors paintwork</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_paintwork" id="doorsPaintwork01">
															<label class="form-check-label" for="doorsPaintwork01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_paintwork" id="doorsPaintwork02">
															<label class="form-check-label" for="doorsPaintwork02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.7</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Roof condition</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="roof_condition" id="roofCondition01">
															<label class="form-check-label" for="roofCondition01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="roof_condition" id="roofCondition02">
															<label class="form-check-label" for="roofCondition02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.8</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Wall condition</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_condition" id="wallCondition01">
															<label class="form-check-label" for="wallCondition01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_condition" id="wallCondition02">
															<label class="form-check-label" for="wallCondition02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">7.9</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Floor condition</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="floor_condition" id="floorCondition01">
															<label class="form-check-label" for="floorCondition01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="floor_condition" id="floorCondition02">
															<label class="form-check-label" for="floorCondition02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">7.10</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Tripping and Shipping Hazards</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="slipping_hazard" id="slippingHazard01">
															<label class="form-check-label" for="slippingHazard01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="slipping_hazard" id="slippingHazard02">
															<label class="form-check-label" for="slippingHazard02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 8 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#electricalAccordian" aria-expanded="true" aria-controls="electricalAccordian">
												8. Electrical:
											</button>
										</h2>
										<div id="electricalAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center py-2">
													<div class="col-auto">8.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Electrical observation</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="electrical_observation" id="electricalObservation01">
															<label class="form-check-label" for="electricalObservation01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="electrical_observation" id="electricalObservation02">
															<label class="form-check-label" for="electricalObservation02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 9 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#boundaryWallAccordian" aria-expanded="true" aria-controls="boundaryWallAccordian">
												9. Boundary wall:
											</button>
										</h2>
										<div id="boundaryWallAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center py-2">
													<div class="col-auto">9.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Boundary wall should be minimum 8' in height from road level +2' razor wire</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="boundary_wall_height" id="boundaryWallHeight01">
															<label class="form-check-label" for="boundaryWallHeight01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="boundary_wall_height" id="boundaryWallHeight02">
															<label class="form-check-label" for="boundaryWallHeight02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 10 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainGateAccordian" aria-expanded="true" aria-controls="mainGateAccordian">
												10. Main Gate:
											</button>
										</h2>
										<div id="mainGateAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">10.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Main gate along with wicked gate</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="main_gate" id="mainGate01">
															<label class="form-check-label" for="mainGate01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="main_gate" id="mainGate02">
															<label class="form-check-label" for="mainGate02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">10.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Guardroom</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guardroom" id="guardroom01">
															<label class="form-check-label" for="guardroom01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guardroom" id="guardroom02">
															<label class="form-check-label" for="guardroom02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 11 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#toiletBlockAccordian" aria-expanded="true" aria-controls="toiletBlockAccordian">
												11. Toilet's blocks:
											</button>
										</h2>
										<div id="toiletBlockAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">11.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Seperate toilets are provided for staff and visitors</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_toilets" id="seperateToilets01">
															<label class="form-check-label" for="seperateToilets01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_toilets" id="seperateToilets02">
															<label class="form-check-label" for="seperateToilets02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">11.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Toilet block a way from class rooms</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block" id="toiletBlock01">
															<label class="form-check-label" for="toiletBlock01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block" id="toiletBlock02">
															<label class="form-check-label" for="toiletBlock02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">11.3</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Washroom for Students</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="student_washroom" id="studentWashroom01">
															<label class="form-check-label" for="studentWashroom01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="student_washroom" id="studentWashroom02">
															<label class="form-check-label" for="studentWashroom02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 12 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#lightningVentilationAccordian" aria-expanded="true" aria-controls="lightningVentilationAccordian">
												12. Lighting & Ventilation:
											</button>
										</h2>
										<div id="lightningVentilationAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center border-bottom pt-2 pb-3">
													<div class="col-auto">12.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Class rooms lighting</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_lighting" id="classroomLighting01">
															<label class="form-check-label" for="classroomLighting01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_lighting" id="classroomLighting02">
															<label class="form-check-label" for="classroomLighting02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">12.2</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Overall building lighting</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_lighting" id="overallLighting01">
															<label class="form-check-label" for="overallLighting01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_lighting" id="overallLighting02">
															<label class="form-check-label" for="overallLighting02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">12.3</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Class rooms ventilation</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_ventilation" id="classroomVentilation01">
															<label class="form-check-label" for="classroomVentilation01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_ventilation" id="classroomVentilation02">
															<label class="form-check-label" for="classroomVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">12.4</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Science lab lighting</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_lighting" id="scienceLabLighting01">
															<label class="form-check-label" for="scienceLabLighting01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_lighting" id="scienceLabLighting02">
															<label class="form-check-label" for="scienceLabLighting02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">12.5</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Science lab ventilation</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_ventilation" id="scienceLabVentilation01">
															<label class="form-check-label" for="scienceLabVentilation01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_ventilation" id="scienceLabVentilation02">
															<label class="form-check-label" for="scienceLabVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center border-bottom py-3">
													<div class="col-auto">12.6</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Toilet block ventilation</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block_ventialation" id="toiletBlockVentilation01">
															<label class="form-check-label" for="toiletBlockVentilation01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block_ventialation" id="toiletBlockVentilation02">
															<label class="form-check-label" for="toiletBlockVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
												<div class="row align-items-center pt-3 pb-2">
													<div class="col-auto">12.7</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Overall building ventilation</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_ventilation" id="overallVentilation01">
															<label class="form-check-label" for="overallVentilation01">
																	Satisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_ventilation" id="overallVentilation02">
															<label class="form-check-label" for="overallVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 13 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guardroomAccordian" aria-expanded="true" aria-controls="guardroomAccordian">
												13. Guard Room:
											</button>
										</h2>
										<div id="guardroomAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center py-2">
													<div class="col-auto">13.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Proper guard room available inside the gate</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guard_room" id="guardRoom01">
															<label class="form-check-label" for="guardRoom01">
																	Yes
															</label>
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guard_room" id="guardRoom02">
															<label class="form-check-label" for="guardRoom02">
																	No
															</label>
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									{{-- 14 --}}
									<div class="accordion-item accordion-fill-primary">
										<h2 class="accordion-header" id="personalInformation">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#campusToCampusAccordian" aria-expanded="true" aria-controls="campusToCampusAccordian">
												14. Campus to campus distance:
											</button>
										</h2>
										<div id="campusToCampusAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
											<div class="accordion-body">
												<div class="row align-items-center py-2">
													<div class="col-auto">14.1</div>
													<div class="col-lg-1 col-md-3 py-md-0 py-2">Distance b/w nearest UCS campus</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-3 col-md-4 py-lg-0 py-2">
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</div>
													<div class="col-lg-4 col-md-12 py-md-0 py-2">
														<textarea class="form-control" rows="1"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<p><b>Purpose of Visit:</b> New Site for United Charter School:
									<br />Total land area is 01 Kanal & 04 Marla, which is complying with UCS standards (Minimum land required is 01-02 kanal). As per Client, he will complete the all
									required work before the start of new session-2022.
									<br /><b>1.</b> 08 rooms for Classes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>2.</b> Labs 00 rooms &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>3.</b> Library Ol room &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>4.</b> Admin Block 03 rooms
									<br />(Rooms required to start a Pre + Primary School). Details are as;
									<br />Proposed site is on link road.
									<br /><br /><b>Instructions:</b>
									<br />As built & proposed drawing is required. Boundary wall height should be 8' (From road level) minimum along with 1.5' or 2' razor wire. Scaning room is required
									adjacent to the main gate and a security picket should be constructed on top of scaning/guard room. Separate visitors parking area should be marked outside
									the building. All signage’s should be as per UCS standard.
									<br /><br /><b>Following points discussed during the visit:</b>
									<br />1. Building facede and admin area should be as design per UCS standards.
									<br />2. Client will modified the exising ground floor in admin area and classrooms.
									<br />3. Doors & Windows will needs to some repaire work.
									<br />4. Separate toilets for visitors, Staff, Girls & Boys should be provided and marked on drawing.
									<br />5. Emergency evacuated plan should be displayed/placed in corridors & admin area.
									<br />6. General Laboratory Safety rules should be placed in all labs.
									<br />7. Fire extinguishers and other safety equipment's should located & available near the laboratory entrance.
									<br />8. Client will submit the building fitness certificate by structure engineer or relevant authority after the completion of all renovation works.
									<br />9. Whole building paintwork is required.
									<br />QA need following documents before the MOU;
									<br /><b>Property ownership / lease agreement &nbsp;&nbsp; : &nbsp;&nbsp; Plot layout plan & as built plan</b>
									<br />Coordinates of proposed site are: 24.863197,67.075945
									<br /><b>Site is satisfactory for Concordia College.</b>
									<br />Subsequent visit will be required after completion of all the works.
								</p>
								<div class="col-12 text-end">
									<button class="btn btn-primary" type="submit">Save Changes</button>
									<button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
								</div>
							</form>
            </div>
        </div>
    </div>
</div>


@endsection
