
<div class="accordion-item accordion-fill-primary">
	<h2 class="accordion-header" id="personalInformation">
		<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse1" aria-expanded="true" aria-controls="accor_borderedcollapse1">
			Personal Particular of Applicant
		</button>
	</h2>
	<div id="accor_borderedcollapse1" class="accordion-collapse collapse @if(!isset($franchise)) show @endif" aria-labelledby="personalInformation" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
		<div class="accordion-body">
			<div class="row">
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
							State/Province is required!
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
						<input type="text" class="form-control" id="contact_no_1" name="contact_no_1" placeholder="Mobile No (03001234567)" value="{{ isset($franchise) ? $franchise->contact_no_1 : old('contact_no_1') }}" required maxlength="13">
						<label for="contact_no_1" class="form-label">Contact No. 1</label>
						<div class="invalid-tooltip">
							Provide atleast one contact no.
						</div>
					</div>
				</div>

				<div class="col-md-4 col-sm-12  mt-3">
					<div class="form-label-group in-border">
						<input type="text" class="form-control" id="contact_no_2" name="contact_no_2" placeholder="Mobile No (03001234567)" value="{{ isset($franchise) ? $franchise->contact_no_2 : old('contact_no_2') }}" maxlength="13">
						<label for="contact_no_2" class="form-label">Contact No. 2</label>
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
					<label class="form-check-label" for="purposeBuildCampus">
						Gender:&nbsp;&nbsp;&nbsp;
					</label>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="gender" id="male" value="Male" {{ isset($franchise->gender) && $franchise->gender == 'Male'  ? 'checked' : '' }}  >
						<label class="form-check-label" for="Male">Male</label>
					</div>
					<div class="form-check mb-2 form-check-inline">
						<input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ isset($franchise->gender) && $franchise->gender == 'female'  ? 'checked' : '' }}>
						<label class="form-check-label" for="female">Female</label>
					</div>
				</div>

				<div class="col-md-4 col-sm-12  mt-3">
					<label class="form-check-label" for="purposeBuildCampus">
						Married:&nbsp;&nbsp;&nbsp;
					</label>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="marital_status" id="yes" value="Y" {{ isset($franchise->marital_status) && $franchise->marital_status == 'Y'  ? 'checked' : '' }}>
						<label class="form-check-label" for="yes">Yes</label>
					</div>
					<div class="form-check mb-2 form-check-inline">
						<input class="form-check-input" type="radio" name="marital_status" id="no" value="N" {{ isset($franchise->marital_status) && $franchise->marital_status == 'N'  ? 'checked' : '' }}>
						<label class="form-check-label" for="no">No</label>
					</div>
				</div>

				<div class="col-md-4 col-sm-12  mt-3">
					<div class="form-label-group in-border">
						<input type="text" class="form-control" id="inquirerQualification" name="qualification" placeholder="Import Export" value="{{ isset($franchise) ? $franchise->qualification : old('qualification') }}">
						<label for="inquirerQualification" class="form-label">Qualification</label>
					</div>
				</div>



				<div class="col-md-8 col-sm-12  mt-3">
					<div class="form-label-group in-border">
						<textarea class="form-control @if($errors->has('personal_address')) is-invalid @endif" id="personal_address" name="personal_address" placeholder="Enter Personal Address" required>{{ isset($franchise) ? $franchise->personal_address : old('personal_address') }}</textarea>
						<label for="personal_address" class="form-label">Address</label>
						<div class="invalid-tooltip">
							Please provied your address!
						</div>
					</div>
				</div>

			</div>
			<div class="row mt-3">
				<div class="col-12 text-end">
					<button class="btn btn-primary" type="submit" form="personalParticularOfApplicantForm">Submit form</button>
					<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
				</div>
			</div>
		</div>
	</div>
</div>

@push('header_scripts')


@endpush

@push('footer_scripts')

@endpush
