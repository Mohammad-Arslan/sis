	<div class="tab-pane fade @if(!isset($franchise)) active show @endif" id="v-pills-personal-info" role="tabpanel" aria-labelledby="v-pills-personal-info-tab">

		@if(isset($franchise))
			<form method="POST" action="{{ route('franchises.update', $franchise->id) }}" class="row g-3 needs-validation" id="personalFormInfo_bk" novalidate>
			@method('PATCH')
		@else
			<form method="POST" action="{{ route('franchises.store') }}" class="row g-3 needs-validation" id="personalFormInfo_bk" novalidate>
		@endif
				@csrf
				<div>
					<h5>Personal Information</h5>
					<p class="text-muted">Fill all the required information below.</p>
				</div>

				<div class="row g-3">

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="firstName" name="appl_name" placeholder="Enter First Name" value="{{ isset($franchise) ? $franchise->appl_name : old('appl_name') }}" required>
							<label for="firstName" class="form-label">First Name</label>
							<div class="invalid-tooltip">
								Applicant first name is required!
							</div>
						</div>
					</div>

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="lastName" name="appl_last_name" placeholder="Enter Last Name" value="{{ isset($franchise) ? $franchise->appl_last_name : old('appl_last_name') }}" required>
							<label for="lastName" class="form-label">Last Name</label>
							<div class="invalid-tooltip">
								Applicant last name is required!
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="CNIC" name="CNIC" placeholder="11111-1111111-1" maxlength="15" value="{{ isset($franchise) ? $franchise->CNIC : old('CNIC') }}">
							<label for="CNIC" class="form-label">CNIC</label>
						</div>
					</div>

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="email" name="email" placeholder="example@email.com" value="{{ isset($franchise) ? $franchise->email : old('email') }}" required>
							<label for="email" class="form-label">Email</label>
							<div class="invalid-tooltip">
								Applicant email is required!
							</div>
						</div>
					</div>

					@if(isset($franchise))
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="load-select form-select" id="state" name="state_id" data-target="city_id" data-url="{{ route('list-cities') }}" aria-label="State select" required>
									<option value="">Please select</option>
									@if ($states)
										@foreach ($states as $state)
											<option value="{{ $state->id }}" {{ $franchise->state_id == $state->id ? 'selected' : '' }} >{{ $state->state_name }}</option>
										@endforeach
									@endif
								</select>
								<label for="state" class="form-label">State/Province</label>
								<div class="invalid-tooltip">
									State is required!
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="load-select form-select" id="cityId" name="city_id" aria-label="City select" required>
									<option value="">Please select</option>
									@if ($states)
										@foreach ($cities as $city)
											<option value="{{ $city->id }}" {{ $franchise->city_id == $city->id ? 'selected' : '' }} >{{ $city->city_name }}</option>
										@endforeach
									@endif
								</select>
								<label for="cityId" class="form-label">City</label>
								<div class="invalid-tooltip">
									City is required!
								</div>
							</div>
						</div>
					@else
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="load-select form-select" id="state" name="state_id" data-target="city_id" data-url="{{ route('list-cities') }}" aria-label="State select" required>
									<option value="">Please select</option>
									@if ($states)
										@foreach ($states as $state)
											<option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }} >{{ $state->state_name }}</option>
										@endforeach
									@endif
								</select>
								<label for="state" class="form-label">State/Province</label>
								<div class="invalid-tooltip">
									State is required!
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="load-select form-select" id="cityId" name="city_id" data-target="town_id" data-url="{{ route('list-towns') }}" aria-label="City select" required>
									<option value="">Please select</option>
								</select>
								<label for="cityId" class="form-label">City</label>
								<div class="invalid-tooltip">
									City is required!
								</div>
							</div>
						</div>
					@endif

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="primary_mobile_no" name="primary_mobile_no" placeholder="Mobile No (03001234567)" value="{{ isset($franchise) ? $franchise->primary_mobile_no : old('primary_mobile_no') }}" required maxlength="13">
							<label for="primary_mobile_no" class="form-label">Contact No. 1</label>
							<div class="invalid-tooltip">
								Provide atleast one contact no.
							</div>
						</div>
					</div>

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="secondary_mobile_no" name="secondary_mobile_no" placeholder="Mobile No (03001234567)" value="{{ isset($franchise) ? $franchise->secondary_mobile_no : old('secondary_mobile_no') }}" maxlength="13">
							<label for="secondary_mobile_no" class="form-label">Contact No. 2</label>
						</div>
					</div>

					@if(isset($franchise))
					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<select class="form-select" aria-label="form-select-sm example" id="source_id" name="source_id" required>
								<option value="">Please select</option>
								@foreach ($sources as $source)
									<option value="{{ $source->id }}" {{ $franchise->source_id == $source->id ? 'selected' : '' }}>{{ $source->source_name }}</option>
								@endforeach
							</select>
							<label for="source_id" class="form-label">Source </label>
							<div class="invalid-tooltip">
								Where did you hear about us?
							</div>
						</div>
					</div>
					@else
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="form-select" aria-label="form-select-sm example" id="source_id" name="source_id" required>
									<option value="">Please select</option>
									@foreach ($sources as $source)
										<option value="{{ $source->id }}" {{ old('source_id') == $source->id ? 'selected' : '' }}>{{ $source->source_name }}</option>
									@endforeach
								</select>
								<label for="source_id" class="form-label">Source </label>
								<div class="invalid-tooltip">
									Where did you hear about us?
								</div>
							</div>
						</div>
					@endif

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="other_profession" name="other_profession" placeholder="Import Export" value="{{ isset($franchise) ? $franchise->other_profession : old('other_profession') }}">
							<label for="other_profession" class="form-label">Current Business/Occupation</label>
						</div>
					</div>

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="organizationName" name="organization_name" placeholder="Organization Name:" value="{{ isset($franchise) ? $franchise->organization_name : old('organization_name') }}">
							<label for="organizationName" class="form-label">Organization Name:</label>
						</div>
					</div>

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="inquirerDesignation" name="inquirer_designation" placeholder="Designation" value="{{ isset($franchise) ? $franchise->inquirer_designation : old('inquirer_designation') }}">
							<label for="inquirerDesignation" class="form-label">Designation</label>
						</div>
					</div>

					<div class="col-md-4 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control" id="inquirerQualification" name="inquirer_qualification" placeholder="Import Export" value="{{ isset($franchise) ? $franchise->inquirer_qualification : old('inquirer_qualification') }}">
							<label for="inquirerQualification" class="form-label">Qualification</label>
						</div>
					</div>

					<div class="col-md-8 col-sm-12  mt-3">
						<div class="form-label-group in-border">
							<input type="text" class="form-control @if($errors->has('personal_address')) is-invalid @endif" id="personal_address" name="personal_address" placeholder="Enter Personal Address" value="{{ isset($franchise) ? $franchise->personal_address : old('personal_address') }}" required>
							<label for="personal_address" class="form-label">Address</label>
							<div class="invalid-tooltip">
								Please provied your address!
							</div>
						</div>
					</div>

				</div>

				<div class="d-flex align-items-start gap-3 mt-4">
					<button type="submit" class="btn btn-primary btn-label right ms-auto nexttab save-personal-info" data-nexttab="v-pills-educational-organization-tab">
						<i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to Educational Organization
					</button>
				</div>

		</form>
	</div>


@push('header_scripts')


@endpush

@push('footer_scripts')

@endpush
