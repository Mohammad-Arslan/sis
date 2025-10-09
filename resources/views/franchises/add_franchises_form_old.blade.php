<form class="g-3 needs-validation" action="{{ route('franchises.store') }}" method="POST"  novalidate>
	@csrf
	<div class="row">
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="applicantName" class="form-label">Full Name</label>
			<input type="text" class="form-control" id="applicantName" name="appl_name" placeholder="Enter Full Name" value="" required>
			<div class="invalid-tooltip">Applicant name is required!</div>
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="cnic" class="form-label">CNIC</label>
			<input type="text" class="form-control" id="cnic" name="cnic" placeholder="Enter 13 digit without dashes" value="" maxlength="13">
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="personal_address" class="form-label">Address</label>
			<input type="text" class="form-control" id="personal_address" name="personal_address" placeholder="Enter Personal Address" value="" required>
			<div class="invalid-tooltip">Address is required!</div>
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="city_id" class="form-label">City</label>
			<select class="form-select" aria-label="form-select-sm example" id="city_id" name="city_id" required>
				<option value="">Please select</option>
				@foreach ($cities as $city)
				<option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
				@endforeach
			</select>
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="primary_mobile_no" class="form-label">Contact No. 1</label>
			<input type="text" class="form-control" id="primary_mobile_no" name="primary_mobile_no" placeholder="Mobile No (03001234567)" value="" required maxlength="13">
			<div class="invalid-tooltip">Contact No 1 is required!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="secondary_mobile_no" class="form-label">Contact No. 2</label>
			<input type="text" class="form-control" id="secondary_mobile_no" name="secondary_mobile_no" placeholder="Mobile No (03001234567)" value="" maxlength="13">
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="email" class="form-label">Email</label>
			<input type="text" class="form-control" id="email" name="email" placeholder="example@email.com" value="" required>
			<div class="invalid-tooltip">Email is required!</div>
			<div class="valid-feedback">Looks good!</div>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-12 text-end">
			<button class="btn btn-primary" type="submit">Submit form</button>

			<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
		</div>
	</div>
</form>
