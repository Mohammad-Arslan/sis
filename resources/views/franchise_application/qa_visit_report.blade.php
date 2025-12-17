<form class="row g-3 needs-validation" method="POST" action="{{ route('franchise-application-qa.store') }}" novalidate>
	<div class="col-md-4 col-sm-12">
		<div class="form-label-group in-border">
				<input type="text" class="form-control @if($errors->has('proposed_location')) is-invalid @endif" id="locationAddress" name="proposed_location" placeholder="Please enter first name" value="{{ old('proposed_location') ? old('proposed_location') : (isset($franchise_application['franchise_application_bd_approved']) ? $franchise_application['franchise_application_bd_approved']['site_address'] : '') }}" required>
				<label for="locationAddress" class="form-label">Proposed Location Address</label>
				<div class="invalid-tooltip">
						@if($errors->has('proposed_location'))
						{{ $errors->first('proposed_location') }}
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
				<input type="text" class="form-control @if($errors->has('client_name')) is-invalid @endif" id="clientName" name="client_name" placeholder="Please enter first name" value="{{ $franchise_application->appl_name.' '.$franchise_application->appl_last_name }}" readonly required>
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
			<input type="text" class="form-control @if($errors->has('forwarded_date')) is-invalid @endif" id="forwarded_date" name="forwarded_date" placeholder="Please enter first name" value="{{ $forwarded_date }}" disabled required>
			<label for="forwarded_date" class="form-label">Forwarded Date</label>
			<div class="invalid-tooltip">
				@if($errors->has('forwarded_date'))
				{{ $errors->first('forwarded_date') }}
				@else
				Forwarded date is required!
				@endif
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12">
		<div class="form-label-group in-border">
			<select class="form-select @if($errors->has('sales_rep_id')) is-invalid @endif" id="schoolType" name="sales_rep_id" aria-label="select" required>
					<option value="">Please select a Sales Representative</option>
					@foreach ($sales_reps as $sales_rep)
						<option value="{{ $sales_rep->id }}" {{ old('sales_rep_id') == $sales_rep->id ? 'selected' : '' }}>{{ $sales_rep->user->name }}</option>
					@endforeach
			</select>
			<label for="schoolType" class="form-label">Sales Representative</label>
			<div class="invalid-tooltip">
					@if($errors->has('sales_rep_id'))
					{{ $errors->first('sales_rep_id') }}
					@else
					Sales Representative is required!
					@endif
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12">
		<div class="form-label-group in-border">
			<select class="form-select @if($errors->has('qa_rep_id')) is-invalid @endif" id="schoolType" name="qa_rep_id" aria-label="select" required>
					<option value="">Please select a QA Representative</option>
					@foreach ($qa_reps as $qa_rep)
						<option value="{{ $qa_rep->id }}" {{ old('qa_rep_id') == $qa_rep->id ? 'selected' : '' }}>{{ $qa_rep->user->name }}</option>
					@endforeach
			</select>
			<label for="schoolType" class="form-label">QA Representative</label>
			<div class="invalid-tooltip">
					@if($errors->has('qa_rep_id'))
					{{ $errors->first('qa_rep_id') }}
					@else
					QA Representative Type is required!
					@endif
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12">
		<div class="form-label-group in-border">
				<input type="text" class="form-control @if($errors->has('visit_purpose')) is-invalid @endif" id="purposeOfVisit" name="visit_purpose" placeholder="Please enter first name" value="{{ old('visit_purpose') }}" required>
				<label for="purposeOfVisit" class="form-label">Purpose of visit</label>
				<div class="invalid-tooltip">
						@if($errors->has('visit_purpose'))
						{{ $errors->first('visit_purpose') }}
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
						@foreach ($class_groups as $class_group)
							<option value="{{ $class_group->id }}" {{ old('school_type') == $class_group->id ? 'selected' : '' }}>{{ $class_group->name }}</option>
						@endforeach
				</select>
				<label for="schoolType" class="form-label">Proposed School Type</label>
				<div class="invalid-tooltip">
						@if($errors->has('school_type'))
						{{ $errors->first('school_type') }}
						@else
						Proposed School Type is required!
						@endif
				</div>
		</div>
	</div>
	{{-- <div class="col-md-4 col-sm-12">
		<div class="form-label-group in-border">
			<select class="form-select @if($errors->has('qa_status')) is-invalid @endif" id="qaStatus" name="qa_status" aria-label="select" required>
					<option value="Pending" {{ old('qa_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
					<option value="Approved" {{ old('qa_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
					<option value="Not Approved" {{ old('qa_status') == 'Not Approved' ? 'selected' : '' }}>Not Approved</option>
			</select>
			<label for="qaStatus" class="form-label">QA Status</label>
			<div class="invalid-tooltip">
					@if($errors->has('qa_status'))
					{{ $errors->first('qa_status') }}
					@else
					QA Status is required!
					@endif
			</div>
		</div>
	</div> --}}
	<div class="col-md-12">
		<div class="form-label-group in-border">
				<textarea class="form-control @if($errors->has('qa_remarks')) is-invalid @endif" id="qaRemarks" name="qa_remarks" rows="2" placeholder="Please enter your qa remarks" required>{{ old('qa_remarks') }}</textarea>
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
	<div class="border mt-3 border-dashed"></div>
	<h5 class="text-muted d-flex align-items-center"><i class="ri-todo-fill me-1"></i>QA Details</h5>
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
						<div class="col-lg-2 col-md-3 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Required = </span>
								<input type="text" class="form-control @if($errors->has('plot_size_required')) is-invalid @endif" name="plot_size_required" value="{{ old('plot_size_required') }}" id="plotSizeRequired" required>
								<div class="invalid-tooltip">
										@if($errors->has('plot_size_required'))
										{{ $errors->first('plot_size_required') }}
										@else
										Plot size required address is required!
										@endif
								</div>
							</div>
						</div>
                        <div class="col-lg-2 col-md-3 py-lg-0 py-2 mt-3">
							<div class="input-group form-label-group in-border">
								<select class="form-select @if($errors->has('required_uom')) is-invalid @endif" id="required_uom" name="required_uom" aria-label="Required UOM select" required>
									<option value="">Please Select</option>
                                    <option value="Marla" {{ old('required_uom') == 'Marla' ? 'selected' : '' }}>Marla</option>
                                    <option value="Kanal" {{ old('required_uom') == 'Kanal' ? 'selected' : '' }}>Kanal</option>
                                    <option value="Square-Yard" {{ old('required_uom') == 'Square-Yard' ? 'selected' : '' }}>Square Yard</option>
								</select>
								<label for="required_uom" class="form-label">UOM</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('required_uom'))
                                    {{ $errors->first('required_uom') }}
                                    @else
                                    Required size UOM is required!
                                    @endif
                                </div>
							</div>
						</div>
						<div class="col-lg-2 col-md-3 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Actual = </span>
								<input type="text" class="form-control @if($errors->has('plot_size_actual')) is-invalid @endif" name="plot_size_actual" value="{{ old('plot_size_actual') }}" id="plotSizeActual" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
										@if($errors->has('plot_size_actual'))
										{{ $errors->first('plot_size_actual') }}
										@else
										Plot size actual address is required!
										@endif
								</div>
							</div>
						</div>
                        <div class="col-lg-2 col-md-3 py-lg-0 py-2 mt-3">
							<div class="input-group form-label-group in-border">
								<select class="form-select @if($errors->has('actual_uom')) is-invalid @endif" id="actual_uom" name="actual_uom" aria-label="Actual UOM select" required>
									<option value="">Please Select</option>
                                    <option value="Marla" {{ old('actual_uom') == 'Marla' ? 'selected' : '' }}>Marla</option>
                                    <option value="Kanal" {{ old('actual_uom') == 'Kanal' ? 'selected' : '' }}>Kanal</option>
                                    <option value="Square-Yard" {{ old('actual_uom') == 'Square-Yard' ? 'selected' : '' }}>Square Yard</option>
								</select>
								<label for="actual_uom" class="form-label">UOM</label>
                                <div class="invalid-tooltip">
                                    @if($errors->has('actual_uom'))
                                    {{ $errors->first('actual_uom') }}
                                    @else
                                    Actual size UOM is required!
                                    @endif
                                </div>
							</div>
						</div>
						<div class="col-lg-2 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('plot_size_remarks')) is-invalid @endif" name="plot_size_remarks" id="plotSizeRemarks" rows="1">{{ old('plot_size_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">1.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Site Location</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="site_location" id="siteLocation01" value="1" {{ old('site_location') ? (old('site_location') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="siteLocation01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="site_location" id="siteLocation02" value="0" {{ old('site_location') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="siteLocation02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('site_location_remarks')) is-invalid @endif" name="site_location_remarks" id="siteLocationRemarks" rows="1">{{ old('site_location_remarks') }}</textarea>
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
								<input class="form-check-input" type="radio" name="type_of_building" id="typeOfBuilding01" value="purpose_build" {{ old('type_of_building') ? (old('type_of_building') == 'purpose_build' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="typeOfBuilding01">
										Purpose built
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="type_of_building" id="typeOfBuilding02" value="refurbished" {{ old('type_of_building') == 'refurbished' ? 'checked' : '' }}>
								<label class="form-check-label" for="typeOfBuilding02">
										Refurbished
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('type_of_building_remarks')) is-invalid @endif" name="type_of_building_remarks" id="typeOfBuildingRemarks" rows="1">{{ old('type_of_building_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">2.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Type of construction</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="type_of_construction" id="typeOfConstrunction01" value="frame_structure" {{ old('type_of_construction') ? (old('type_of_construction') == 'frame_structure' ? 'checked' : '') : 'checked' }} checked>
								<label class="form-check-label" for="typeOfConstrunction01">
										Frame Structure
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="type_of_construction" id="typeOfConstrunction02" value="load_bearing_wall" {{ old('type_of_construction') == 'load_bearing_wall' ? 'checked' : '' }}>
								<label class="form-check-label" for="typeOfConstrunction02">
										Load Bearing Wall
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('type_of_construction_remarks')) is-invalid @endif" name="type_of_construction_remarks" id="typeOfConstructionRemarks" rows="1">{{ old('type_of_construction_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">2.3</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Total covered area of building</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Required = </span>
								<input type="text" class="form-control @if($errors->has('building_covered_area_required')) is-invalid @endif" name="building_covered_area_required" value="{{ old('building_covered_area_required') }}" id="buildingCoveredAreaRequired" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('building_covered_area_required'))
									{{ $errors->first('building_covered_area_required') }}
									@else
									Required building coverd area is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Actual = </span>
								<input type="text" class="form-control @if($errors->has('building_covered_area_actual')) is-invalid @endif" name="building_covered_area_actual" value="{{ old('building_covered_area_actual') }}" id="buildingCoveredAreaActual" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('building_covered_area_actual'))
									{{ $errors->first('building_covered_area_actual') }}
									@else
									Actual building coverd area is required!
									@endif
								</div>
								<span class="input-group-text"> Sft</span>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('building_covered_area_remarks')) is-invalid @endif" name="building_covered_area_remarks" id="buildingCoveredAreaRemarks" rows="1">{{ old('building_covered_area_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">2.4</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Total open area</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<input type="text" class="form-control @if($errors->has('total_open_area_01')) is-invalid @endif" name="total_open_area_01" value="{{ old('total_open_area_01') }}" id="totalOpenArea01" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('total_open_area_01'))
									{{ $errors->first('total_open_area_01') }}
									@else
									Total open area is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<input type="text" class="form-control @if($errors->has('total_open_area_02')) is-invalid @endif" name="total_open_area_02" value="{{ old('total_open_area_02') }}" id="totalOpenArea02" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('total_open_area_01'))
									{{ $errors->first('total_open_area_01') }}
									@else
									Total open area is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('total_open_area_remarks')) is-invalid @endif" name="total_open_area_remarks" id="totalOpenAreaRemarks" rows="1">{{ old('total_open_area_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">2.5</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Total nos. of Classrooms</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Required = </span>
								<input type="text" class="form-control @if($errors->has('no_of_classrooms_required')) is-invalid @endif" name="no_of_classrooms_required" value="{{ old('no_of_classrooms_required') }}" id="noOfClassroomsRequired" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('total_open_area_01'))
									{{ $errors->first('total_open_area_01') }}
									@else
									Required no. of classroom is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Actual = </span>
								<input type="text" class="form-control @if($errors->has('no_of_classrooms_actual')) is-invalid @endif" name="no_of_classrooms_actual" value="{{ old('no_of_classrooms_actual') }}" id="noOfClassroomsActual" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('total_open_area_01'))
									{{ $errors->first('total_open_area_01') }}
									@else
									Actual no. of classroom is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('no_of_classrooms_remarks')) is-invalid @endif" name="no_of_classrooms_remarks" id="noOfClassroomsRemarks" rows="1">{{ old('no_of_classrooms_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">2.6</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Total Lab Rooms & Library</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Required = </span>
								<input type="text" class="form-control @if($errors->has('total_lab_room_library_required')) is-invalid @endif" name="total_lab_room_library_required" value="{{ old('total_lab_room_library_required') }}" id="totalLabRoomLibraryRequired" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('total_lab_room_library_required'))
									{{ $errors->first('total_lab_room_library_required') }}
									@else
									Required total lab room library is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Actual = </span>
								<input type="text" class="form-control @if($errors->has('total_lab_room_library_actual')) is-invalid @endif" name="total_lab_room_library_actual" value="{{ old('total_lab_room_library_actual') }}" id="totalLabRoomLibraryActual" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('total_lab_room_library_actual'))
									{{ $errors->first('total_lab_room_library_actuals') }}
									@else
									Actual total lab room library is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('total_lab_room_library_remarks')) is-invalid @endif" name="total_lab_room_library_remarks" id="totalLabRoomLibraryRemarks" rows="1">{{ old('total_lab_room_library_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">2.7</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Classroom's size</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Required = </span>
								<input type="text" class="form-control @if($errors->has('classroom_size_required')) is-invalid @endif" name="classroom_size_required" value="{{ old('classroom_size_required') }}" id="classroomSizeRequired" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('classroom_size_required'))
									{{ $errors->first('classroom_size_requireds') }}
									@else
									Required classroom size is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Actual = </span>
								<input type="text" class="form-control @if($errors->has('classroom_size_actual')) is-invalid @endif" name="classroom_size_actual" value="{{ old('classroom_size_actual') }}" id="classroomSizeActual" aria-label="Amount (to the nearest dollar)" required>
								<span class="input-group-text"> Sft</span>
								<div class="invalid-tooltip">
									@if($errors->has('classroom_size_actual'))
									{{ $errors->first('classroom_size_actuals') }}
									@else
									Actual classroom size is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('classroom_size_remarks')) is-invalid @endif" name="classroom_size_remarks" id="classroomSizeRemarks" rows="1">{{ old('classroom_size_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">2.8</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Provision of Extension for further rooms</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="provision_of_extension" id="provisionOfExtension01" value="1" {{ old('provision_of_extension') ? (old('provision_of_extension') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="provisionOfExtension01">
										Available
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="provision_of_extension" id="provisionOfExtension02" value="0" {{ old('provision_of_extension') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="provisionOfExtension02">
										Not Available
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('provision_of_extension_remarks')) is-invalid @endif" name="provision_of_extension_remarks" id="provisionOfExtensionRemarks" rows="1">{{ old('provision_of_extension_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">2.9</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Admin Block</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="admin_block" id="adminBlock01" value="1" {{ old('admin_block') ? (old('admin_block') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="adminBlock01">
										Available
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="admin_block" id="adminBlock02" value="0"  {{ old('admin_block') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="adminBlock02">
										Not Available
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('admin_block_remarks')) is-invalid @endif" name="admin_block_remarks" id="adminBlockRemarks" rows="1">{{  old('admin_block_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 3 --}}
		{{-- <div class="accordion-item accordion-fill-primary">
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
								<input type="text" class="form-control @if($errors->has('area_population_radius_required')) is-invalid @endif" name="area_population_radius_required" value="{{ old('area_population_radius_required') }}" id="areaPopulationRadiusRequired" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('area_population_radius_required'))
									{{ $errors->first('area_population_radius_required') }}
									@else
									Required area population radius is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Actual = </span>
								<input type="text" class="form-control @if($errors->has('area_population_radius_actual')) is-invalid @endif" name="area_population_radius_actual" value="{{ old('area_population_radius_actual') }}" id="areaPopulationRadiusActual" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('area_population_radius_actual'))
									{{ $errors->first('area_population_radius_actual') }}
									@else
									Actual area population radius is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('area_population_radius_remarks')) is-invalid @endif" name="area_population_radius_remarks" id="areaPopulationRadiusRemarks" rows="1">{{ old('area_population_radius_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div> --}}

		{{-- 4 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#surroundingsAccordian" aria-expanded="true" aria-controls="surroundingsAccordian">
					3. Surroundings:
				</button>
			</h2>
			<div id="surroundingsAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center border-bottom pt-2 pb-3">
						<div class="col-auto">3.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">School in vicinity</div>
						<div class="col-lg-6 col-md-8">
							<div class="input-group">
								<span class="input-group-text">1.</span>
								<input type="text" class="form-control @if($errors->has('school_in_vicinity_01')) is-invalid @endif" name="school_in_vicinity_01" value="{{ old('school_in_vicinity_01') }}" id="schoolInVicinity01" required>
								<div class="invalid-tooltip">
									@if($errors->has('school_in_vicinity_01'))
									{{ $errors->first('school_in_vicinity_01') }}
									@else
									School vicinity is required!
									@endif
								</div>
								<span class="input-group-text">2.</span>
								<input type="text" class="form-control @if($errors->has('school_in_vicinity_02')) is-invalid @endif" name="school_in_vicinity_02" value="{{ old('school_in_vicinity_02') }}" id="schoolInVicinity02" required>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('school_in_vicinity_remarks')) is-invalid @endif" name="school_in_vicinity_remarks" id="schoolInVicinityRemarks" rows="1">{{ old('school_in_vicinity_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Type of locality</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="type_of_locality" id="typeOfLocality01" value="resedential" {{ old('type_of_locality') ? (old('type_of_locality') == 'resedential' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="typeOfLocality01">
										Residentail
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="type_of_locality" id="typeOfLocality02" value="commercial" {{ old('type_of_locality') == 'commercial' ? 'checked' : '' }}>
								<label class="form-check-label" for="typeOfLocality02">
										Commercial
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('type_of_locality_remarks')) is-invalid @endif" name="type_of_locality_remarks" id="typeOfLocalityRemarks" rows="1">{{ old('type_of_locality_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.3</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is the School site centrally located within the locality</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_centrally_located" id="isCentralLocality01" value="1" {{ old('is_centrally_located') ? (old('is_centrally_located') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isCentralLocality01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_centrally_located" id="isCentralLocality02" value="0" {{ old('is_centrally_located') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isCentralLocality02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_centrally_located_remarks')) is-invalid @endif" name="is_centrally_located_remarks" id="isCentrallyLocatedRemarks" rows="1">{{  old('is_centrally_located_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.4</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is a market / book shop near-by</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_market_nearby" id="isMarketNearby01" value="1" {{ old('is_market_nearby') ? (old('is_market_nearby') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isMarketNearby01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_market_nearby" id="isMarketNearby02" value="0"  {{ old('is_market_nearby') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isMarketNearby02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_market_nearby_remarks')) is-invalid @endif" name="is_market_nearby_remarks" id="isMarketNearby" rows="1">{{ old('is_market_nearby_reamrks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.5</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Road access & condition</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="road_access" id="roadAccess01" value="1" {{ old('road_access') ? (old('road_access') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="roadAccess01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="road_access" id="roadAccess02" value="0" {{ old('road_access') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="roadAccess02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('road_access_remarks')) is-invalid @endif" name="road_access_remarks" id="roadAccessRemarks" rows="1">{{  old('road_access_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.6</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Road access type</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<input type="text" class="form-control @if($errors->has('road_access_type_01')) is-invalid @endif" name="road_access_type_01" value="{{ old('road_access_type_01') }}" id="roadAccessType01" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('road_access_type_01'))
									{{ $errors->first('road_access_type_01') }}
									@else
									Road access type is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<input type="text" class="form-control @if($errors->has('road_access_type_02')) is-invalid @endif" name="road_access_type_02" value="{{ old('road_access_type_02') }}" id="roadAccessType02" aria-label="Amount (to the nearest dollar)" required>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('road_access_type_remarks')) is-invalid @endif" name="road_access_type_remarks" id="roadAccessTypeRemarks" rows="1">{{ old('road_access_type_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.7</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Facing road width/size</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Width = </span>
								<input type="text" class="form-control @if($errors->has('facing_road_width')) is-invalid @endif" name="facing_road_width" value="{{ old('facing_road_width') }}" id="facingRoadWidth" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('facing_road_width'))
									{{ $errors->first('facing_road_width') }}
									@else
									Facing road width is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Size = </span>
								<input type="text" class="form-control @if($errors->has('facing_road_size')) is-invalid @endif" name="facing_road_size" value="{{ old('facing_road_size') }}" id="facingRoadSize" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('facing_road_width'))
									{{ $errors->first('facing_road_width') }}
									@else
									Facing road size is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('facing_road_remarks')) is-invalid @endif" name="facing_road_remarks" id="facingRoadRemarks" rows="1">{{ old('facing_road_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.8</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is public transport facility available near-by</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_public_transport_nearby" id="isPublicTransport01" value="1" {{ old('is_public_transport_nearby') ? (old('is_public_transport_nearby') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isPublicTransport01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_public_transport_nearby" id="isPublicTransport02" value="0" {{ old('is_public_transport_nearby') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isPublicTransport02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_public_transport_nearby_remarks')) is-invalid @endif" name="is_public_transport_nearby_remarks" id="isPublicTransportNearbyRemarks" rows="1">{{  old('is_public_transport_nearby_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.9</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Noise pollution</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="noise_pollution" id="noisePollution01" value="1" {{ old('noise_pollution') ? (old('noise_pollution') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="noisePollution01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="noise_pollution" id="noisePollution02" value="0" {{ old('noise_pollution') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="noisePollution02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('noise_pollution_remarks')) is-invalid @endif" name="noise_pollution_remarks" id="noisePollutionRemarks" rows="1">{{  old('noise_pollution_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.10</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is a Hospital / Clinic available within easy reach</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_hospital_nearby" id="isHospitalAvailable01" value="1" {{ old('is_hospital_nearby') ? (old('is_hospital_nearby') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isHospitalAvailable01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_hospital_nearby" id="isHospitalAvailable02" value="0" {{ old('is_hospital_nearby') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isHospitalAvailable02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_hospital_nearby_remarks')) is-invalid @endif" name="is_hospital_nearby_remarks" id="isHospitalNearbyRemarks" rows="1">{{  old('is_hospital_nearby_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.11</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is a fire brigade available near-by</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_rescue_nearby" id="isRescueAvailable01" value="1" {{ old('is_rescue_nearby') ? (old('is_rescue_nearby') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isRescueAvailable01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_rescue_nearby" id="isRescueAvailable02" value="0" {{ old('is_rescue_nearby') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isRescueAvailable02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_rescue_nearby_remarks')) is-invalid @endif" name="is_rescue_nearby_remarks" id="isRescueNearbyRemarks" rows="1">{{  old('is_rescue_nearby_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.12</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is Police station available near-by</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_police_nearby" id="isPoliceAvailable01" value="1" {{ old('is_police_nearby') ? (old('is_police_nearby') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isPoliceAvailable01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_police_nearby" id="isPoliceAvailable02" value="0" {{ old('is_police_nearby') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isPoliceAvailable02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_police_nearby_remarks')) is-invalid @endif" name="is_police_nearby_remarks" id="isPoliceNearbyRemarks" rows="1">{{  old('is_police_nearby_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">3.13</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is the School site located near a river / canal</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_river_nearby" id="isRiverNearby01" value="1" {{ old('is_river_nearby') ? (old('is_river_nearby') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isRiverNearby01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_river_nearby" id="isRiverNearby02" value="0" {{ old('is_river_nearby') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isRiverNearby02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_river_nearby_remarks')) is-invalid @endif" name="is_river_nearby_remarks" id="isRiverNearbyRemarks" rows="1">{{  old('is_river_nearby_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">3.14</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Is the School site protected against flooding</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_flood_protected" id="isFloodProtected01" value="1" {{ old('is_flood_protected') ? (old('is_flood_protected') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="isFloodProtected01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="is_flood_protected" id="isFloodProtected02" value="0" {{ old('is_flood_protected') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="isFloodProtected02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('is_flood_protected_remarks')) is-invalid @endif" name="is_flood_protected_remarks" id="isFloodProtectedRemarks" rows="1">{{  old('is_flood_protected_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 5 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#trafficControlAccordian" aria-expanded="true" aria-controls="trafficControlAccordian">
					4. Traffic Control:
				</button>
			</h2>
			<div id="trafficControlAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center border-bottom pt-2 pb-3">
						<div class="col-auto">4.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Seperate visitor parking</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="seperate_visitor_parking" id="seperateParking01" value="1" {{ old('seperate_visitor_parking') ? (old('seperate_visitor_parking') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="seperateParking01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="seperate_visitor_parking" id="seperateParking02" value="0" {{ old('seperate_visitor_parking') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="seperateParking02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('seperate_visitor_parking_remarks')) is-invalid @endif" name="seperate_visitor_parking_remarks" id="seperateVisitorParkingRemarks" rows="1">{{  old('seperate_visitor_parking_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">4.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Traffic signs</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="traffic_signs" id="trafficSigns01" value="1" {{ old('traffic_signs') ? (old('traffic_signs') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="trafficSigns01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="traffic_signs" id="trafficSigns02" value="0" {{ old('traffic_signs') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="trafficSigns02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('traffic_signs_remarks')) is-invalid @endif" name="traffic_signs_remarks" id="trafficSignsRemarks" rows="1">{{  old('traffic_signs_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">4.3</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Street having dead end</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="dead_end_street" id="deadEndStreet01" value="1" {{ old('dead_end_street') ? (old('dead_end_street') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="deadEndStreet01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="dead_end_street" id="deadEndStreet02" value="0" {{ old('dead_end_street') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="deadEndStreet02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('dead_end_street_remarks')) is-invalid @endif" name="dead_end_street_remarks" id="deadEndStreetRemarks" rows="1">{{ old('dead_end_street_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 6 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#playgroundAccordian" aria-expanded="true" aria-controls="playgroundAccordian">
					5. Play Ground:
				</button>
			</h2>
			<div id="playgroundAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center border-bottom pt-2 pb-3">
						<div class="col-auto">5.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Available</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="playground_available" id="available01" value="1" {{ old('playground_available') ? (old('playground_available') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="available01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="playground_available" id="available02" value="0" {{ old('playground_available') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="available02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('playground_available_remarks')) is-invalid @endif" name="playground_available_remarks" id="playgroundAvailableRemarks" rows="1">{{  old('playground_available_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">5.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Available minimun play ground @ 35% of total plot area</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="minimum_playground_35" id="minimumPlayground01" value="1" {{ old('minimum_playground_35') ? (old('minimum_playground_35') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="minimumPlayground01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="minimum_playground_35" id="minimumPlayground02" value="0" {{ old('minimum_playground_35') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="minimumPlayground02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('minimum_playground_35_remarks')) is-invalid @endif" name="minimum_playground_35_remarks" id="minimunPlayground35Remarks" rows="1">{{ old('minimum_playground_35_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 7 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#buildingAppearanceAccordian" aria-expanded="true" aria-controls="buildingAppearanceAccordian">
					6. Appearance of building:
				</button>
			</h2>
			<div id="buildingAppearanceAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center border-bottom pt-2 pb-3">
						<div class="col-auto">6.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Boundary wall plaster</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="boundary_wall_plaster" id="boundaryWallPlaster01" value="1" {{ old('boundary_wall_plaster') ? (old('boundary_wall_plaster') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="boundaryWallPlaster01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="boundary_wall_plaster" id="boundaryWallPlaster02" value="0" {{ old('boundary_wall_plaster') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="boundaryWallPlaster02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('boundary_wall_plaster_remarks')) is-invalid @endif" name="boundary_wall_plaster_remarks" id="boundaryWallPlasterRemarks" rows="1">{{ old('boundary_wall_plaster_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Over all building plaster</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="building_plaster" id="buildingPlaster01" value="1" {{ old('building_plaster') ? (old('building_plaster') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="buildingPlaster01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="building_plaster" id="buildingPlaster02" value="0" {{ old('building_plaster') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="buildingPlaster02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('building_plaster_remarks')) is-invalid @endif" name="building_plaster_remarks" id="buildingPlasterRemarks" rows="1">{{  old('building_plaster_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.3</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Boundary wall paintwork</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="boundary_wall_paintwork" id="wallPaintwork01" value="1" {{ old('boundary_wall_paintwork') ? (old('boundary_wall_paintwork') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="wallPaintwork01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="boundary_wall_paintwork" id="wallPaintwork02" value="0" {{ old('boundary_wall_paintwork') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="wallPaintwork02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('boundary_wall_paintwork_remarks')) is-invalid @endif" name="boundary_wall_paintwork_remarks" id="boundaryWallPaintworkRemarks" rows="1">{{ old('boundary_wall_paintwork_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.4</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Over all building paintwork <br/> Seepage</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="building_paintwork" id="buildingPaintwork01" value="1" {{ old('building_paintwork') ? (old('building_paintwork') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="buildingPaintwork01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="building_paintwork" id="buildingPaintwork02" value="0" {{ old('building_paintwork') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="buildingPaintwork02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('building_paintwork_remarks')) is-invalid @endif" name="building_paintwork_remarks" id="buildingPaintworkRemarks" rows="1">{{  old('building_paintwork_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.5</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Windows & Doors condition</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="window_door_condition" id="doorsCondition01" value="1" {{ old('window_door_condition') ? (old('window_door_condition') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="doorsCondition01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="window_door_condition" id="doorsCondition02" value="0" {{ old('window_door_condition') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="doorsCondition02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('window_door_condition_remarks')) is-invalid @endif" name="window_door_condition_remarks" id="windowDoorConditionRemarks" rows="1">{{  old('window_door_condition_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.6</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Windows & Doors paintwork</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="window_door_paintwork" id="doorsPaintwork01" value="1" {{ old('window_door_paintwork') ? (old('window_door_paintwork') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="doorsPaintwork01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="window_door_paintwork" id="doorsPaintwork02" value="0" {{ old('window_door_paintwork') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="doorsPaintwork02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('window_door_paintwork_remarks')) is-invalid @endif" name="window_door_paintwork_remarks" id="windowDoorPaintworkRemarks" rows="1">{{  old('window_door_paintwork_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.7</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Roof condition</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="roof_condition" id="roofCondition01" value="1" {{ old('roof_condition') ? (old('roof_condition') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="roofCondition01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="roof_condition" id="roofCondition02" value="0" {{ old('roof_condition') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="roofCondition02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('roof_condition_remarks')) is-invalid @endif" name="roof_condition_remarks" id="roofConditionRemarks" rows="1">{{  old('roof_condition_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.8</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Wall condition</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="wall_condition" id="wallCondition01" value="1" {{ old('wall_condition') ? (old('wall_condition') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="wallCondition01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="wall_condition" id="wallCondition02" value="0" {{ old('wall_condition') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="wallCondition02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('wall_condition_remarks')) is-invalid @endif" name="wall_condition_remarks" id="wallConditionRemarks" rows="1">{{  old('wall_condition_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">6.9</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Floor condition</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="floor_condition" id="floorCondition01" value="1" {{ old('floor_condition') ? (old('floor_condition') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="floorCondition01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="floor_condition" id="floorCondition02" value="0" {{ old('floor_condition') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="floorCondition02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('floor_condition_remarks')) is-invalid @endif" name="floor_condition_remarks" id="floorConditionRemarks" rows="1">{{  old('floor_condition_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">6.10</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Tripping and Shipping Hazards</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="slipping_hazard" id="slippingHazard01" value="1" {{ old('slipping_hazard') ? (old('slipping_hazard') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="slippingHazard01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="slipping_hazard" id="slippingHazard02" value="0" {{ old('slipping_hazard') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="slippingHazard02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('slipping_hazard_remarks')) is-invalid @endif" name="slipping_hazard_remarks" id="slippingHazardRemarks" rows="1">{{  old('slipping_hazard_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 8 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#electricalAccordian" aria-expanded="true" aria-controls="electricalAccordian">
					7. Electrical:
				</button>
			</h2>
			<div id="electricalAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center py-2">
						<div class="col-auto">7.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Electrical observation</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="electrical_observation" id="electricalObservation01" value="1" {{ old('electrical_observation') ? (old('electrical_observation') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="electricalObservation01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="electrical_observation" id="electricalObservation02" value="0" {{ old('electrical_observation') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="electricalObservation02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('electrical_observation_remarks')) is-invalid @endif" name="electrical_observation_remarks" id="ElectricalObservationRemarks" rows="1">{{  old('electrical_observation_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 9 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#boundaryWallAccordian" aria-expanded="true" aria-controls="boundaryWallAccordian">
					8. Boundary wall:
				</button>
			</h2>
			<div id="boundaryWallAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center py-2">
						<div class="col-auto">8.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Boundary wall should be minimum 8' in height from road level +2' razor wire</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="boundary_wall_height" id="boundaryWallHeight01" value="1" {{ old('boundary_wall_height') ? (old('boundary_wall_height') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="boundaryWallHeight01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="boundary_wall_height" id="boundaryWallHeight02" value="0" {{ old('boundary_wall_height') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="boundaryWallHeight02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('boundary_wall_height_remarks')) is-invalid @endif" name="boundary_wall_height_remarks" id="boundaryWallHeightRemarks" rows="1">{{  old('boundary_wall_height_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 10 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainGateAccordian" aria-expanded="true" aria-controls="mainGateAccordian">
					9. Main Gate:
				</button>
			</h2>
			<div id="mainGateAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center border-bottom pt-2 pb-3">
						<div class="col-auto">9.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Main gate along with wicked gate</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="main_along_wicked_gate" id="mainGate01" value="1" {{ old('main_along_wicked_gate') ? (old('main_along_wicked_gate') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="mainGate01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="main_along_wicked_gate" id="mainGate02" value="0" {{ old('main_along_wicked_gate') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="mainGate02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('main_along_wicked_gate_remarks')) is-invalid @endif" name="main_along_wicked_gate_remarks" id="mainGateWickedGateRemarks" rows="1">{{  old('main_along_wicked_gate_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">9.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Guardroom</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="guardroom" id="guardroom01" value="1" {{ old('guardroom') ? (old('guardroom') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="guardroom01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="guardroom" id="guardroom02" value="0" {{ old('guardroom') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="guardroom02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('guardroom_remarks')) is-invalid @endif" name="guardroom_remarks" id="guardroomRemarks" rows="1">{{  old('guardroom_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 11 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#toiletBlockAccordian" aria-expanded="true" aria-controls="toiletBlockAccordian">
					10. Toilet's blocks:
				</button>
			</h2>
			<div id="toiletBlockAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center border-bottom pt-2 pb-3">
						<div class="col-auto">10.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Seperate toilets are provided for staff and visitors</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="seperate_toilet_visitor" id="seperateToilets01" value="1" {{ old('seperate_toilet_visitor') ? (old('seperate_toilet_visitor') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="seperateToilets01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="seperate_toilet_visitor" id="seperateToilets02" value="0" {{ old('seperate_toilet_visitor') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="seperateToilets02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('seperate_toilet_visitor_remarks')) is-invalid @endif" name="seperate_toilet_visitor_remarks" id="seperateToiletVisitorRemarks" rows="1">{{  old('seperate_toilet_visitor_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">10.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Toilet block a way from class rooms</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="toilet_away_from_class" id="toiletBlock01" value="1" {{ old('toilet_away_from_class') ? (old('toilet_away_from_class') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="toiletBlock01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="toilet_away_from_class" id="toiletBlock02" value="0" {{ old('toilet_away_from_class') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="toiletBlock02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('toilet_away_from_class_remarks')) is-invalid @endif" name="toilet_away_from_class_remarks" id="toiletAwayFromClassRemarks" rows="1">{{  old('toilet_away_from_class_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">10.3</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Washroom for Students</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="student_washroom" id="studentWashroom01" value="1" {{ old('student_washroom') ? (old('student_washroom') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="studentWashroom01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="student_washroom" id="studentWashroom02" value="0" {{ old('student_washroom') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="studentWashroom02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('student_washroom_remarks')) is-invalid @endif" name="student_washroom_remarks" id="studentWashroomRemarks" rows="1">{{  old('student_washroom_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 12 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#lightningVentilationAccordian" aria-expanded="true" aria-controls="lightningVentilationAccordian">
					11. Lighting & Ventilation:
				</button>
			</h2>
			<div id="lightningVentilationAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center border-bottom pt-2 pb-3">
						<div class="col-auto">11.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Class rooms lighting</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="classroom_lighting" id="classroomLighting01" value="1" {{ old('classroom_lighting') ? (old('classroom_lighting') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="classroomLighting01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="classroom_lighting" id="classroomLighting02" value="0" {{ old('classroom_lighting') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="classroomLighting02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('classroom_lighting_remarks')) is-invalid @endif" name="classroom_lighting_remarks" id="classLightingRemarks" rows="1">{{  old('classroom_lighting_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">11.2</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Overall building lighting</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="overall_lighting" id="overallLighting01" value="1" {{ old('overall_lighting') ? (old('overall_lighting') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="overallLighting01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="overall_lighting" id="overallLighting02" value="0" {{ old('overall_lighting') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="overallLighting02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('overall_lighting_remarks')) is-invalid @endif" name="overall_lighting_remarks" id="overallLightingRemarks" rows="1">{{  old('overall_lighting_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">11.3</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Class rooms ventilation</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="classroom_ventilation" id="classroomVentilation01" value="1" {{ old('classroom_ventilation') ? (old('classroom_ventilation') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="classroomVentilation01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="classroom_ventilation" id="classroomVentilation02" value="0" {{ old('classroom_ventilation') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="classroomVentilation02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('classroom_ventilation_remarks')) is-invalid @endif" name="classroom_ventilation_remarks" id="classroomVentilationRemarks" rows="1">{{  old('classroom_ventilation_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">11.4</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Science lab lighting</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="science_lab_lighting" id="scienceLabLighting01" value="1" {{ old('science_lab_lighting') ? (old('science_lab_lighting') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="scienceLabLighting01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="science_lab_lighting" id="scienceLabLighting02" value="0" {{ old('science_lab_lighting') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="scienceLabLighting02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('science_lab_lighting_remarks')) is-invalid @endif" name="science_lab_lighting_remarks" id="scienceLabLightingRemarks" rows="1">{{  old('science_lab_lighting_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">11.5</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Science lab ventilation</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="science_lab_ventilation" id="scienceLabVentilation01" value="1" {{ old('science_lab_ventilation') ? (old('science_lab_ventilation') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="scienceLabVentilation01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="science_lab_ventilation" id="scienceLabVentilation02" value="0" {{ old('science_lab_ventilation') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="scienceLabVentilation02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('science_lab_ventilation_remarks')) is-invalid @endif" name="science_lab_ventilation_remarks" id="scienceLabLightingRemarks" rows="1">{{  old('science_lab_ventilation_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center border-bottom py-3">
						<div class="col-auto">11.6</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Toilet block ventilation</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="toilet_ventilation" id="toiletBlockVentilation01" value="1" {{ old('toilet_ventilation') ? (old('toilet_ventilation') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="toiletBlockVentilation01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="toilet_ventilation" id="toiletBlockVentilation02" value="0" {{ old('toilet_ventilation') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="toiletBlockVentilation02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('toilet_ventilation_remarks')) is-invalid @endif" name="toilet_ventilation_remarks" id="toiletVentilationRemarks" rows="1">{{  old('toilet_ventilation_remarks') }}</textarea>
						</div>
					</div>
					<div class="row align-items-center pt-3 pb-2">
						<div class="col-auto">11.7</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Overall building ventilation</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="overall_ventilation" id="overallVentilation01" value="1" {{ old('overall_ventilation') ? (old('overall_ventilation') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="overallVentilation01">
										Satisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="overall_ventilation" id="overallVentilation02" value="0" {{ old('overall_ventilation') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="overallVentilation02">
										Unsatisfactory
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('overall_ventilation_remarks')) is-invalid @endif" name="overall_ventilation_remarks" id="overallVentilationRemarks" rows="1">{{  old('overall_ventilation_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 13 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guardroomAccordian" aria-expanded="true" aria-controls="guardroomAccordian">
					12. Guard Room:
				</button>
			</h2>
			<div id="guardroomAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center py-2">
						<div class="col-auto">12.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Proper guard room available inside the gate</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="guardroom_inside_gate" id="guardRoom01" value="1" {{ old('guardroom_inside_gate') ? (old('guardroom_inside_gate') == '1' ? 'checked' : '') : 'checked' }}>
								<label class="form-check-label" for="guardRoom01">
										Yes
								</label>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="guardroom_inside_gate" id="guardRoom02" value="0" {{ old('guardroom_inside_gate') == '0' ? 'checked' : '' }}>
								<label class="form-check-label" for="guardRoom02">
										No
								</label>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('guardroom_inside_gate_remarks')) is-invalid @endif" name="guardroom_inside_gate_remarks" id="guardroomInsideGateRemarks" rows="1">{{  old('guardroom_inside_gate_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 14 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#campusToCampusAccordian" aria-expanded="true" aria-controls="campusToCampusAccordian">
					13. Campus to campus distance:
				</button>
			</h2>
			<div id="campusToCampusAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center py-2">
						<div class="col-auto">13.1</div>
						<div class="col-lg-1 col-md-3 py-md-0 py-2">Distance b/w nearest UCS campus</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Required = </span>
								<input type="text" class="form-control @if($errors->has('distance_to_ucs_campus_required')) is-invalid @endif" name="distance_to_ucs_campus_required" value="{{ old('distance_to_ucs_campus_required') }}" id="distanceToUcsCampusRequired" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('distance_to_ucs_campus_required'))
									{{ $errors->first('distance_to_ucs_campus_required') }}
									@else
									Required distance to UCS campus is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 py-lg-0 py-2">
							<div class="input-group">
								<span class="input-group-text">Actual = </span>
								<input type="text" class="form-control @if($errors->has('distance_to_ucs_campus_actual')) is-invalid @endif" name="distance_to_ucs_campus_actual" value="{{ old('distance_to_ucs_campus_actual') }}" id="distanceToUcsCampusActual" aria-label="Amount (to the nearest dollar)" required>
								<div class="invalid-tooltip">
									@if($errors->has('distance_to_ucs_campus_actual'))
									{{ $errors->first('distance_to_ucs_campus_actual') }}
									@else
									Actual distance to UCS is required is required!
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 py-md-0 py-2">
							<textarea class="form-control @if($errors->has('distance_to_ucs_campus_remarks')) is-invalid @endif" name="distance_to_ucs_campus_remarks" id="distanceToUcsCampusRemarks" rows="1">{{ old('distance_to_ucs_campus_remarks') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- 15 --}}
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personalInformation">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#regionalHeadAccordian" aria-expanded="true" aria-controls="regionalHeadAccordian">
					14. Forwarded To:
				</button>
			</h2>
			<div id="regionalHeadAccordian" class="accordion-collapse collapse" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row align-items-center py-2">
						<div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
								<select class="form-select @if($errors->has('regional_head_id')) is-invalid @endif" id="regionalHeadId" name="regional_head_id" aria-label="select" required>
										<option value="">Please select a Regional Head</option>
										@foreach ($regional_heads as $regional_head)
											<option value="{{ $regional_head->id }}" {{ old('regional_head_id') == $regional_head->id ? 'selected' : '' }}>{{ $regional_head->user->name }}</option>
										@endforeach
								</select>
								<label for="regionalHeadId" class="form-label">Forwarded To</label>
								<div class="invalid-tooltip">
										@if($errors->has('regional_head_id'))
										{{ $errors->first('regional_head_id') }}
										@else
										Forwarded To is required!
										@endif
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
								<div class="input-group">
									<input type="text" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ old('regional_head_sec_date') }}" name="regional_head_sec_date" id="regionalHeadSecDate">
									<label for="visitDate" class="form-label">Forwarded date</label>
									<div class="input-group-text bg-primary border-primary text-white">
											<i class="ri-calendar-2-line"></i>
									</div>
									<div class="invalid-tooltip">
											@if($errors->has('regional_head_sec_date'))
											{{ $errors->first('regional_head_sec_date') }}
											@else
											Date is required is required!
											@endif
									</div>
								</div>
							</div>
						</div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="form-select @if($errors->has('qa_status')) is-invalid @endif" id="qaStatus" name="qa_status" aria-label="select" required>
                                        <option value="Pending" {{ old('qa_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Forwarded" {{ old('qa_status') == 'Forwarded' ? 'selected' : '' }}>Forwarded with Observation</option>
                                        <option value="Approved" {{ old('qa_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        {{-- <option value="Not Approved" {{ old('qa_status') == 'Not Approved' ? 'selected' : '' }}>Not Approved</option> --}}
                                </select>
                                <label for="qaStatus" class="form-label">QA Status</label>
                                <div class="invalid-tooltip">
                                        @if($errors->has('qa_status'))
                                        {{ $errors->first('qa_status') }}
                                        @else
                                        QA Status is required!
                                        @endif
                                </div>
                            </div>
                        </div>
						{{-- <div class="col-md-4 col-sm-12">
							<div class="form-label-group in-border">
								<select class="form-select" id="headStatus" name="head_status" aria-label="select">
									<option value="InProgress" {{ old('head_status') == 'InProgress' ? 'selected' : '' }}>InProgress</option>
									<option value="Forwarded" {{ old('head_status') == 'Forwarded' ? 'selected' : '' }}>Forwarded with Observation</option>
								</select>
								<label for="headStatus" class="form-label">Status</label>
								<div class="invalid-tooltip">
										@if($errors->has('head_status'))
										{{ $errors->first('head_status') }}
										@else
										Regional is required!
										@endif
								</div>
							</div>
						</div> --}}
						<div class="col-md-12">
								<div class="form-label-group in-border">
                                    <textarea class="form-control" id="qaRemarks" name="qa_remarks" rows="2" placeholder="Please enter your qa remarks" >{{ old('qa_remarks') }}</textarea>
                                    <label for="qaRemarks" class="form-label">QA Remarks</label>
										{{-- <textarea class="form-control @if($errors->has('regional_head_sec_remarks')) is-invalid @endif" name="regional_head_sec_remarks" id="regionalHeadSecRemarks" placeholder="Write Here..." required>{{ old('regional_head_sec_remarks') }}</textarea>
										<label class="form-label">Head Remarks</label>
										<div class="invalid-tooltip">
											@if($errors->has('regional_head_sec_remarks'))
											{{ $errors->first('regional_head_sec_remarks') }}
											@else
											Regional is required!
											@endif
									    </div> --}}
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- <p><b>Purpose of Visit:</b> New Site for United Charter School:
		<br />Total land area is 01 Kanal & 04 Marla, which is complying with UCS standards (Minimum land is 01-02 kanal). As per Client, he will complete the all
		work before the start of new session-2022.
		<br /><b>1.</b> 08 rooms for Classes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>2.</b> Labs 00 rooms &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>3.</b> Library Ol room &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>4.</b> Admin Block 03 rooms
		<br />(Rooms to start a Pre + Primary School). Details are as;
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
		<br />Subsequent visit will be after completion of all the works.
	</p> --}}

	<input type="hidden" name="franchise_application_id" id="franchiseApplicationId" value="{{ $franchise_application->id }}" />
	{{-- <div class="border mt-3 border-dashed"></div>

	<h5 class="fs-15 mb-3">Regional Head Section</h5> --}}

	@csrf
	<div class="col-12 text-end">
		<button class="btn btn-primary" type="submit">Save Changes</button>
		<button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
	</div>
</form>
