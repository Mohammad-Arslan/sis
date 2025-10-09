<form class="g-3 needs-validation" action="{{ route('franchises.update',$franchise->id) }}" method="POST"  novalidate>
	@csrf
	@method('PATCH')
	<div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBordered">
		<div class="accordion-item accordion-fill-primary">
			<h2 class="accordion-header" id="personal_information">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse2" aria-expanded="true" aria-controls="accor_borderedExamplecollapse2">
					Personal Information
				</button>
			</h2>
			<div id="accor_borderedExamplecollapse2" class="accordion-collapse collapse show" aria-labelledby="personal_information" data-bs-parent="#accordionBordered" style="">
				<div class="accordion-body">
					<div class="row">
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="applicantName" class="form-label">Full Name</label>
							<input type="text" class="form-control" id="applicantName" name="appl_name" placeholder="Enter Full Name"  value="{{ $franchise->appl_name }}" required>
							<div class="invalid-tooltip">
								@if($errors->has('appl_name'))
								{{ $errors->first('appl_name') }}
								@else
								Applicant name is required!
								@endif
							</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="CNIC" class="form-label">CNIC</label>
							<input type="text" class="form-control" id="CNIC" name="CNIC" placeholder="11111-1111111-1" value="{{$franchise->CNIC}}" maxlength="15">
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="personal_address" class="form-label">Address</label>
							<input type="text" class="form-control" id="personal_address" name="personal_address" placeholder="Enter Personal Address" value="{{$franchise->personal_address}}" required>
							<div class="invalid-tooltip">
								@if($errors->has('personal_address'))
								{{ $errors->first('personal_address') }}
								@else
								Address is required.
								@endif
							</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="city_id" class="form-label">City</label>
							<select class="form-select" aria-label="form-select-sm example" id="city_id" name="city_id" required>
								<option value="">Please select</option>
								@foreach ($cities as $city)
								<option value="{{ $city->id }}" {{ $franchise->city_id == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="primary_mobile_no" class="form-label">Contact No. 1</label>
							<input type="text" class="form-control" id="primary_mobile_no" name="primary_mobile_no" placeholder="Mobile No (03001234567)" value="{{$franchise->primary_mobile_no}}" required maxlength="13">
							<div class="invalid-tooltip">
								@if($errors->has('primary_mobile_no'))
								{{ $errors->first('primary_mobile_no') }}
								@else
								Contact No 1 is required
								@endif
							</div>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="secondary_mobile_no" class="form-label">Contact No. 2</label>
							<input type="text" class="form-control" id="secondary_mobile_no" name="secondary_mobile_no" placeholder="Mobile No (03001234567)" value="{{$franchise->secondary_mobile_no}}" maxlength="13">
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="email" class="form-label">Email</label>
							<input type="text" class="form-control" id="email" name="email" placeholder="example@email.com" value="{{$franchise->email}}" required>
							<div class="invalid-tooltip">
								@if($errors->has('email'))
								{{ $errors->first('email') }}
								@else
								Email is required
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="accordion-item mt-3">
			<h2 class="accordion-header" id="accordionborderedExample3">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedExamplecollapse3" aria-expanded="false" aria-controls="accor_borderedExamplecollapse3">
					Other Information
				</button>
			</h2>
			<div id="accor_borderedExamplecollapse3" class="accordion-collapse collapse" aria-labelledby="accordionborderedExample3" data-bs-parent="#accordionBordered" style="">
				<div class="accordion-body">
					<div class="row">
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="other_profession" class="form-label">Current Business/Occupation</label>
							<input type="text" class="form-control" id="other_profession" name="other_profession" placeholder="Import Export" value="{{$franchise->other_profession}}">
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="campus_location" class="form-label">Area/Location of interest for UCS franchise</label>
							<input type="text" class="form-control" id="campus_location" name="campus_location" placeholder="Clifton , Karachi" value="{{$franchise->campus_location}}">
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="source_id" class="form-label">Where did you hear about us? </label>

							<select class="form-select" aria-label="form-select-sm example" id="source_id" name="source_id" required>
								<option value="">Please select</option>
								@foreach ($sources as $source)
								<option value="{{ $source->id }}" {{ $franchise->source_id == $source->id ? 'selected' : '' }}>{{ $source->source_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4 col-sm-12  mt-3">
							<label for="" class="form-label">Have you led a franchise? If yes, please mention the name.</label>
							<div class="input-group">
								<div class="input-group-text">
									Yes&nbsp;<input class="form-check-input mt-0" type="radio" value="Y" aria-label="Radio button for following text input" name="already_franchise" {{ $franchise->already_franchise == 'Y' ? 'checked' : '' }}> &nbsp;  &nbsp;No &nbsp;<input class="form-check-input mt-0" type="radio" value="N" aria-label="Radio button for following text input" name="already_franchise" {{ $franchise->already_franchise == 'N' ? 'checked' : '' }}>
								</div>
								<input type="text" class="form-control remarks" name="remarks" aria-label="Text input with radio button" readonly value="{{$franchise->remarks}}">
							</div>
						</div>

						<div class="col-md-4 col-sm-12  mt-3">
							<label class="form-label">Are you interested in</label>
							<div class="form-check mt-1 mt-lg-1">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="franchise_type" id="new" value="new" {{ $franchise->franchise_type == 'new' ? 'checked' : '' }}>
									<label class="form-check-label" for="new">Opening a new franchise</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="franchise_type" id="old" value="old" {{ $franchise->franchise_type == 'old' ? 'checked' : '' }}>
									<label class="form-check-label" for="old">Converting an existing school/college</label>
								</div>
							</div>
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
