@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Form Title</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">
								<table class="table table-bordered table-nowrap">
									<colgroup>
										<col span="1" style="width: 5%;">
										<col span="1" style="width: 20%;">
										<col span="1" style="width: 20%;">
										<col span="1" style="width: 20%;">
										<col span="1" style="width: 35%;">
									</colgroup>

									<thead>
											<tr>
													<th scope="col">Sr #</th>
													<th scope="col">Description</th>
													<th scope="col" colspan="2">Ranking</th>
													<th scope="col">Remarks</th>
											</tr>
									</thead>
									<tbody>
											{{-- <tr>
													<th scope="row">1</th>
													<td colspan="4" class="fw-bold">Proposed site details:</td>
											</tr> --}}
											{{-- <tr>
													<th scope="row">1.1</th>
													<td>Total plot size</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">1.2</th>
													<td>Site Location</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="site_location" id="siteLocation01">
															<label class="form-check-label" for="siteLocation01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="site_location" id="siteLocation02">
															<label class="form-check-label" for="siteLocation02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr> --}}
											<tr>
												<th scope="row">2</th>
												<td colspan="4" class="fw-bold">Building details:</td>
											</tr>
											<tr>
													<th scope="row">2.1</th>
													<td>Type of building</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_building" id="typeOfBuilding01">
															<label class="form-check-label" for="typeOfBuilding01">
																	Purpose built
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_building" id="typeOfBuilding02">
															<label class="form-check-label" for="typeOfBuilding02">
																	Refurbished
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">2.2</th>
													<td>Type of construction</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_construction" id="typeOfConstrunction01">
															<label class="form-check-label" for="typeOfConstrunction01">
																	Frame Structure
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_construction" id="typeOfConstrunction02">
															<label class="form-check-label" for="typeOfConstrunction02">
																	Load Bearing Wall
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">2.3</th>
													<td>Total covered area of building</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
															<span class="input-group-text"> Sft</span>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">2.4</th>
													<td>Total open area</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">2.5</th>
													<td>Total nos. of Classrooms</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">2.6</th>
												<td>Total Lab Rooms & Library</td>
												<td>
													<div class="input-group">
														<span class="input-group-text">Required = </span>
														<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
													</div>
												</td>
												<td>
													<div class="input-group">
														<span class="input-group-text">Actual = </span>
														<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
													</div>
												</td>
												<td>
													<textarea class="form-control" rows="1"></textarea>
												</td>
											</tr>
											<tr>
													<th scope="row">2.7</th>
													<td>Classroom's size</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Required = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<span class="input-group-text">Actual = </span>
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
															<span class="input-group-text"> Sft</span>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">2.8</th>
													<td>Provision of Extension for further rooms</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="provision_of_extension" id="provisionOfExtension01">
															<label class="form-check-label" for="provisionOfExtension01">
																	Available
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="provision_of_extension" id="provisionOfExtension02">
															<label class="form-check-label" for="provisionOfExtension02">
																	Not Available
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">2.9</th>
													<td>Admin Block</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="admin_block" id="adminBlock01">
															<label class="form-check-label" for="adminBlock01">
																	Available
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="admin_block" id="adminBlock02">
															<label class="form-check-label" for="adminBlock02">
																	Not Available
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">3</th>
												<td colspan="4" class="fw-bold">Area Population:</td>
											</tr>
											<tr>
													<th scope="row">3.1</th>
													<td>within .5-1-2 km radius</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">4</th>
												<td colspan="4" class="fw-bold">Surroundings:</td>
											</tr>
											<tr>
													<th scope="row">4.1</th>
													<td>School in vicinity</td>
													<td colspan="2">
														<div class="input-group">
															<span class="input-group-text">1.</span>
															<input type="text" class="form-control">
															<span class="input-group-text">2.</span>
															<input type="text" class="form-control">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.2</th>
													<td>Type of locality</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_locality" id="typeOfLocality01">
															<label class="form-check-label" for="typeOfLocality01">
																	Residentail
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="type_of_locality" id="typeOfLocality02">
															<label class="form-check-label" for="typeOfLocality02">
																	Commercial
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.3</th>
													<td>Is the School site centrally located within the locality</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_central_locality" id="isCentralLocality01">
															<label class="form-check-label" for="isCentralLocality01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_central_locality" id="isCentralLocality01">
															<label class="form-check-label" for="isCentralLocality01">
																	Yes
															</label>
														</div><div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_central_locality" id="isCentralLocality01">
															<label class="form-check-label" for="isCentralLocality01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.4</th>
													<td>Is a market / book shop near-by</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_market_nearby" id="isMarketNearby01">
															<label class="form-check-label" for="isMarketNearby01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_market_nearby" id="isMarketNearby02">
															<label class="form-check-label" for="isMarketNearby02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.5</th>
													<td>Road access & condition</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="road_access" id="roadAccess01">
															<label class="form-check-label" for="roadAccess01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="road_access" id="roadAccess02">
															<label class="form-check-label" for="roadAccess02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.6</th>
													<td>Road access type</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.7</th>
													<td>Facing road width/size</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.8</th>
													<td>Is public transport facility available near-by</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_public_transport" id="isPublicTransport01">
															<label class="form-check-label" for="isPublicTransport01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_public_transport" id="isPublicTransport02">
															<label class="form-check-label" for="isPublicTransport02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.9</th>
													<td>Noise pollution</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="noise_pollution" id="noisePollution01">
															<label class="form-check-label" for="noisePollution01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="noise_pollution" id="noisePollution02">
															<label class="form-check-label" for="noisePollution02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.10</th>
													<td>Is a Hospital / Clinic available within easy reach</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_hospital_available" id="isHospitalAvailable01">
															<label class="form-check-label" for="isHospitalAvailable01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_hospital_available" id="isHospitalAvailable02">
															<label class="form-check-label" for="isHospitalAvailable02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.11</th>
													<td>Is a fire brigade available near-by</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_rescue_available" id="isRescueAvailable01">
															<label class="form-check-label" for="isRescueAvailable01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_rescue_available" id="isRescueAvailable02">
															<label class="form-check-label" for="isRescueAvailable02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.12</th>
													<td>Is Police station available near-by</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_police_available" id="isPoliceAvailable01">
															<label class="form-check-label" for="isPoliceAvailable01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_police_available" id="isPoliceAvailable02">
															<label class="form-check-label" for="isPoliceAvailable02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.13</th>
													<td>Is the School site located near a river / canal</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_river_nearby" id="isRiverNearby01">
															<label class="form-check-label" for="isRiverNearby01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_river_nearby" id="isRiverNearby02">
															<label class="form-check-label" for="isRiverNearby02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">4.14</th>
													<td>Is the School site protected against flooding</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_flood_protected" id="isFloodProtected01">
															<label class="form-check-label" for="isFloodProtected01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="is_flood_protected" id="isFloodProtected02">
															<label class="form-check-label" for="isFloodProtected02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">5</th>
												<td colspan="4" class="fw-bold">Traffic Control:</td>
											</tr>
											<tr>
													<th scope="row">5.1</th>
													<td>Seperate visitor parking</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_parking" id="seperateParking01">
															<label class="form-check-label" for="seperateParking01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_parking" id="seperateParking02">
															<label class="form-check-label" for="seperateParking02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">5.2</th>
													<td>Traffic signs</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="traffic_signs" id="trafficSigns01">
															<label class="form-check-label" for="trafficSigns01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="traffic_signs" id="trafficSigns02">
															<label class="form-check-label" for="trafficSigns02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">5.3</th>
													<td>Street having dead end</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="dead_end_street" id="deadEndStreet01">
															<label class="form-check-label" for="deadEndStreet01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="dead_end_street" id="deadEndStreet02">
															<label class="form-check-label" for="deadEndStreet02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">6</th>
												<td colspan="4" class="fw-bold">Play Ground:</td>
											</tr>
											<tr>
													<th scope="row">6.1</th>
													<td>Available</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="available" id="available01">
															<label class="form-check-label" for="available01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="available" id="available02">
															<label class="form-check-label" for="available02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">6.2</th>
													<td>Available minimun play ground @ 35% of total plot area</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="minimum_playground" id="minimumPlayground01">
															<label class="form-check-label" for="minimumPlayground01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="minimum_playground" id="minimumPlayground02">
															<label class="form-check-label" for="minimumPlayground02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">7</th>
												<td colspan="4" class="fw-bold">Appearance of building:</td>
											</tr>
											<tr>
													<th scope="row">7.1</th>
													<td>Boundary wall plaster</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_plaster" id="wallPlaster01">
															<label class="form-check-label" for="wallPlaster01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_plaster" id="wallPlaster02">
															<label class="form-check-label" for="wallPlaster02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.2</th>
													<td>Over all building plaster</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_plaster" id="buildingPlaster01">
															<label class="form-check-label" for="buildingPlaster01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_plaster" id="buildingPlaster02">
															<label class="form-check-label" for="buildingPlaster02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.3</th>
													<td>Boundary wall paintwork</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_paintwork" id="wallPaintwork01">
															<label class="form-check-label" for="wallPaintwork01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_paintwork" id="wallPaintwork02">
															<label class="form-check-label" for="wallPaintwork02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.4</th>
													<td>Over all building paintwork <br/> Seepage</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_paintwork" id="buildingPaintwork01">
															<label class="form-check-label" for="buildingPaintwork01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="building_paintwork" id="buildingPaintwork02">
															<label class="form-check-label" for="buildingPaintwork02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.5</th>
													<td>Windows & Doors condition</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_condition" id="doorsCondition01">
															<label class="form-check-label" for="doorsCondition01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_condition" id="doorsCondition02">
															<label class="form-check-label" for="doorsCondition02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.6</th>
													<td>Windows & Doors paintwork</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_paintwork" id="doorsPaintwork01">
															<label class="form-check-label" for="doorsPaintwork01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="doors_paintwork" id="doorsPaintwork02">
															<label class="form-check-label" for="doorsPaintwork02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.7</th>
													<td>Roof condition</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="roof_condition" id="roofCondition01">
															<label class="form-check-label" for="roofCondition01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="roof_condition" id="roofCondition02">
															<label class="form-check-label" for="roofCondition02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.8</th>
													<td>Wall condition</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_condition" id="wallCondition01">
															<label class="form-check-label" for="wallCondition01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="wall_condition" id="wallCondition02">
															<label class="form-check-label" for="wallCondition02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.9</th>
													<td>Floor condition</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="floor_condition" id="floorCondition01">
															<label class="form-check-label" for="floorCondition01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="floor_condition" id="floorCondition02">
															<label class="form-check-label" for="floorCondition02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">7.10</th>
													<td>Tripping and Shipping Hazards</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="slipping_hazard" id="slippingHazard01">
															<label class="form-check-label" for="slippingHazard01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="slipping_hazard" id="slippingHazard02">
															<label class="form-check-label" for="slippingHazard02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">8</th>
												<td colspan="4" class="fw-bold">Electrical:</td>
											</tr>
											<tr>
													<th scope="row">8.1</th>
													<td>Electrical observation</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="electrical_observation" id="electricalObservation01">
															<label class="form-check-label" for="electricalObservation01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="electrical_observation" id="electricalObservation02">
															<label class="form-check-label" for="electricalObservation02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">9</th>
												<td colspan="4" class="fw-bold">Boundary wall</td>
											</tr>
											<tr>
													<th scope="row">9.1</th>
													<td>Boundary wall should be minimum 8' in height from road level +2' razor wire</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="boundary_wall_height" id="boundaryWallHeight01">
															<label class="form-check-label" for="boundaryWallHeight01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="boundary_wall_height" id="boundaryWallHeight02">
															<label class="form-check-label" for="boundaryWallHeight02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">10</th>
												<td colspan="4" class="fw-bold">Main Gate</td>
											</tr>
											<tr>
													<th scope="row">10.1</th>
													<td>Main gate along with wicked gate</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="main_gate" id="mainGate01">
															<label class="form-check-label" for="mainGate01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="main_gate" id="mainGate02">
															<label class="form-check-label" for="mainGate02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">10.2</th>
													<td>Guardroom</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guardroom" id="guardroom01">
															<label class="form-check-label" for="guardroom01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guardroom" id="guardroom02">
															<label class="form-check-label" for="guardroom02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">11</th>
												<td colspan="4" class="fw-bold">Toilet's blocks</td>
											</tr>
											<tr>
													<th scope="row">11.1</th>
													<td>Seperate toilets are provided for staff and visitors</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_toilets" id="seperateToilets01">
															<label class="form-check-label" for="seperateToilets01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="seperate_toilets" id="seperateToilets02">
															<label class="form-check-label" for="seperateToilets02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">11.2</th>
													<td>Toilet block a way from class rooms</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block" id="toiletBlock01">
															<label class="form-check-label" for="toiletBlock01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block" id="toiletBlock02">
															<label class="form-check-label" for="toiletBlock02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">11.3</th>
													<td>Washroom for Students</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="student_washroom" id="studentWashroom01">
															<label class="form-check-label" for="studentWashroom01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="student_washroom" id="studentWashroom02">
															<label class="form-check-label" for="studentWashroom02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">12</th>
												<td colspan="4" class="fw-bold">Lighting & Ventilation:</td>
											</tr>
											<tr>
													<th scope="row">12.1</th>
													<td>Class rooms lighting</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_lighting" id="classroomLighting01">
															<label class="form-check-label" for="classroomLighting01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_lighting" id="classroomLighting02">
															<label class="form-check-label" for="classroomLighting02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">12.2</th>
													<td>Overall building lighting</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_lighting" id="overallLighting01">
															<label class="form-check-label" for="overallLighting01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_lighting" id="overallLighting02">
															<label class="form-check-label" for="overallLighting02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">12.3</th>
													<td>Class rooms ventilation</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_ventilation" id="classroomVentilation01">
															<label class="form-check-label" for="classroomVentilation01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="classroom_ventilation" id="classroomVentilation02">
															<label class="form-check-label" for="classroomVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">12.4</th>
													<td>Science lab lighting</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_lighting" id="scienceLabLighting01">
															<label class="form-check-label" for="scienceLabLighting01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_lighting" id="scienceLabLighting02">
															<label class="form-check-label" for="scienceLabLighting02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">12.5</th>
													<td>Science lab ventilation</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_ventilation" id="scienceLabVentilation01">
															<label class="form-check-label" for="scienceLabVentilation01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="science_lab_ventilation" id="scienceLabVentilation02">
															<label class="form-check-label" for="scienceLabVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">12.6</th>
													<td>Toilet block ventilation</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block_ventialation" id="toiletBlockVentilation01">
															<label class="form-check-label" for="toiletBlockVentilation01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="toilet_block_ventialation" id="toiletBlockVentilation02">
															<label class="form-check-label" for="toiletBlockVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
													<th scope="row">12.7</th>
													<td>Overall building ventilation</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_ventilation" id="overallVentilation01">
															<label class="form-check-label" for="overallVentilation01">
																	Satisfactory
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="overall_ventilation" id="overallVentilation02">
															<label class="form-check-label" for="overallVentilation02">
																	Unsatisfactory
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">13</th>
												<td colspan="4" class="fw-bold">Guard Room:</td>
											</tr>
											<tr>
													<th scope="row">13.1</th>
													<td>Proper guard room available inside the gate</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guard_room" id="guardRoom01">
															<label class="form-check-label" for="guardRoom01">
																	Yes
															</label>
														</div>
													</td>
													<td>
														<div class="form-check mb-2">
															<input class="form-check-input" type="radio" name="guard_room" id="guardRoom02">
															<label class="form-check-label" for="guardRoom02">
																	No
															</label>
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
											<tr>
												<th scope="row">14</th>
												<td colspan="4" class="fw-bold">Campus to campus distance:</td>
											</tr>
											<tr>
													<th scope="row">14.1</th>
													<td>Distance b/w nearest UCS campus</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<div class="input-group">
															<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
														</div>
													</td>
													<td>
														<textarea class="form-control" rows="1"></textarea>
													</td>
											</tr>
									</tbody>
							</table>
            </div>
        </div>
    </div>
</div>


@endsection
