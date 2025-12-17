<h2></h2>

<form class="g-3 needs-validation" action="{{ route('franchises.store') }}" method="POST"  novalidate>
	@csrf
	<div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedInquirerPersonalInfo">
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="guide_line">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedGuideLinecollapse0" aria-expanded="true" aria-controls="accor_borderedGuideLinecollapse0">
					GuideLines
				</button>
			</h2>
			<div id="accor_borderedGuideLinecollapse0" class="accordion-collapse collapse show" aria-labelledby="personal_information" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row">
						<div class="col-md-4 col-sm-12  mt-3">
							<ol>
								<li>Please enter all relevant details in capital letters.Do not leave any section vacant/unfilled.</li>
								<li>In case of questions with multiple options, please tick the appropriate answer.</li>
								<li>In case you wish to provide any additional information, please attach a seperate sheet.</li>
								<li>Attach your valid CNIC, current updated CV and bussiness card along with this application form.</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="guide_line">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedGuideLinecollapse0" aria-expanded="true" aria-controls="accor_borderedGuideLinecollapse0">
					GuideLines
				</button>
			</h2>
			<h2 class="accordion-header" id="personal_information">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedInguirerPersonalInfocollapse1" aria-expanded="true" aria-controls="accor_borderedInguirerPersonalInfocollapse1">
					Personal Information
				</button>
			</h2>
			<div id="accor_borderedInguirerPersonalInfocollapse1" class="accordion-collapse collapse show" aria-labelledby="personal_information" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
				<div class="accordion-body">
					<div class="row">
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="firstName" name="appl_name" placeholder="Enter First Name" value="{{old('appl_name')}}" required>
								<label for="firstName" class="form-label">First Name</label>
							</div>
							<div class="invalid-tooltip">Applicant first name is required!</div>
							<div class="valid-feedback">Looks good!</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="lastName" name="appl_last_name" placeholder="Enter Last Name" value="{{old('appl_name')}}" required>
								<label for="lastName" class="form-label">Last Name</label>
							</div>
							<div class="invalid-tooltip">Applicant last name is required!</div>
							<div class="valid-feedback">Looks good!</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="CNIC" name="CNIC" placeholder="11111-1111111-1" value="{{old('CNIC')}}" maxlength="15">
								<label for="CNIC" class="form-label">CNIC</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="email" name="email" placeholder="example@email.com" value="{{old('email')}}" required>
								<label for="email" class="form-label">Email</label>
							</div>
							<div class="invalid-tooltip">Email is required!</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="load-select form-select" id="state" name="state_id" data-target="city_id" data-url="{{ route('list-cities') }}" aria-label="State select">
									<option value="">Please select</option>
									@if ($states)
									@foreach ($states as $state)
									<option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
									@endforeach
									@endif
								</select>
								<label for="state" class="form-label">State/Province</label>
							</div>
							<div class="invalid-tooltip">
								@if($errors->has('state_id'))
								{{ $errors->first('state_id') }}
								@else
								State/Province is required!
								@endif
							</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="load-select form-select" id="cityId" name="city_id" data-target="town_id" data-url="{{ route('list-towns') }}" aria-label="City select">
									<option value="">Please select</option>
								</select>
								<label for="cityId" class="form-label">City</label>
							</div>
							<div class="invalid-tooltip">
								@if($errors->has('city_id'))
								{{ $errors->first('city_id') }}
								@else
								City is required!
								@endif
							</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="primary_mobile_no" name="primary_mobile_no" placeholder="Mobile No (03001234567)" value="{{old('primary_mobile_no')}}" required maxlength="13">
								<label for="primary_mobile_no" class="form-label">Contact No. 1</label>
							</div>
							<div class="invalid-tooltip">Contact No 1 is required!</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="secondary_mobile_no" name="secondary_mobile_no" placeholder="Mobile No (03001234567)" value="{{old('secondary_mobile_no')}}" maxlength="13">
								<label for="secondary_mobile_no" class="form-label">Contact No. 2</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<select class="form-select" aria-label="form-select-sm example" id="source_id" name="source_id" required>
									<option value="">Please select</option>
									@foreach ($sources as $source)
									<option value="{{ $source->id }}" {{ old('source_id') == $source->id ? 'selected' : '' }}>{{ $source->source_name }}</option>
									@endforeach
								</select>
								<label for="source_id" class="form-label">Where did you hear about us? </label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="other_profession" name="other_profession" placeholder="Import Export" value="{{old('other_profession')}}">
								<label for="other_profession" class="form-label">Current Business/Occupation</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="organizationName" name="organization_name" placeholder="Organization Name:" value="{{old('organization_name')}}">
								<label for="organizationName" class="form-label">Organization Name:</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="inquirerDesignation" name="inquirer_designation" placeholder="Designation" value="{{old('inquirer_designation')}}">
								<label for="inquirerDesignation" class="form-label">Designation</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="inquirerQualification" name="inquirer_qualification" placeholder="Import Export" value="{{old('inquirer_qualification')}}">
								<label for="inquirerQualification" class="form-label">Qualification</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-8 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="personal_address" name="personal_address" placeholder="Enter Personal Address" value="{{old('personal_address')}}" required>
								<label for="personal_address" class="form-label">Address</label>
							</div>
							<div class="invalid-tooltip">Address is required!</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="accordion-item mt-3">
			<h2 class="accordion-header" id="accordionborderedLeadEducationalOrganization">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedLedEducationalOrganizationcollapse2" aria-expanded="false" aria-controls="accor_borderedLedEducationalOrganizationcollapse2">
					Have you led an educational organization ?
				</button>
			</h2>
			<div id="accor_borderedLedEducationalOrganizationcollapse2" class="accordion-collapse collapse" aria-labelledby="accordionborderedLeadEducationalOrganization" data-bs-parent="#accordionBordered" style="">
				<div class="accordion-body">
					<div class="row">
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="eduOrganizationName" name="edu_organization_name" placeholder="Organization Name:" value="{{old('edu_organization_name')}}">
								<label for="eduOrganizationName" class="form-label">Organization Name:</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="inquirerEduDesignation" name="inquirer_edu_designation" placeholder="Designation" value="{{old('inquirer_edu_designation')}}">
								<label for="inquirerEduDesignation" class="form-label">Designation</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="inquirerExperience" name="inquirer_experience" placeholder="Import Export" value="{{old('inquirer_experience')}}">
								<label for="inquirerExperience" class="form-label">Experience (Years):</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-12 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<textarea class="form-control" id="eduOrganizationDesc" name="edu_organization_desc" placeholder="Description" value="{{old('edu_organization_desc')}}" required></textarea>
								<label for="eduOrganizationDesc" class="form-label">Description:</label>
							</div>
							<div class="invalid-tooltip">Description is required!</div>
							<div class="valid-feedback">Looks good!</div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-12 text-end">
							<button class="btn btn-primary" type="submit">Submit form</button>
							<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion-item mt-3">
			<h2 class="accordion-header" id="accordionborderedLeadFranchise">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedLedFranchisecollapse3" aria-expanded="false" aria-controls="accor_borderedLedFranchisecollapse3">
					Have you led a Franchise ?
				</button>
			</h2>
			<div id="accor_borderedLedFranchisecollapse3" class="accordion-collapse collapse" aria-labelledby="accordionborderedLeadFranchise" data-bs-parent="#accordionBordered" style="">
				<div class="accordion-body">
					<div class="row">
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="eduOrganizationName" name="edu_organization_name" placeholder="Organization Name:" value="{{old('edu_organization_name')}}">
								<label for="eduOrganizationName" class="form-label">Organization Name:</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="inquirerEduDesignation" name="inquirer_edu_designation" placeholder="Designation" value="{{old('inquirer_edu_designation')}}">
								<label for="inquirerEduDesignation" class="form-label">Designation</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<input type="text" class="form-control" id="inquirerExperience" name="inquirer_experience" placeholder="Import Export" value="{{old('inquirer_experience')}}">
								<label for="inquirerExperience" class="form-label">Experience (Years):</label>
							</div>
							<div class="valid-feedback">Looks good!</div>
						</div>

						<div class="col-md-12 col-sm-12  mt-3">
							<div class="form-label-group in-border">
								<textarea class="form-control" id="eduOrganizationDesc" name="edu_organization_desc" placeholder="Description" value="{{old('edu_organization_desc')}}" required></textarea>
								<label for="eduOrganizationDesc" class="form-label">Description:</label>
							</div>
							<div class="invalid-tooltip">Description is required!</div>
							<div class="valid-feedback">Looks good!</div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-12 text-end">
							<button class="btn btn-primary" type="submit">Submit form</button>
							<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
