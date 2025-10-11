
	<div class="col-xl-12 business-section mt-3 @if((isset($franchise->current_occupation) && $franchise->current_occupation == 'business') || (isset($franchise->current_occupation) && $franchise->current_occupation == 'both')) show @else hide-section @endif" id="card-none3">
		<div class="card">
			<div class="card-light card-header">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1">
						<h6 class="card-title mb-0">Does your professional background invlove any of the following ? (Please tick the appropriate box)</h6>
					</div>
				</div>
			</div>
			<div class="card-body collapse show" id="collapseExample3">
				<div class="form-check mb-2 form-check-inline ">
					<input class="form-check-input" type="radio" name="professional_background" id="marketing_or_sales" value="Marketing/Sales" {{ isset($franchise->professional_background) && $franchise->professional_background == 'Marketing/Sales'  ? 'checked' : '' }} >
					<label class="form-check-label" for="marketing_or_sales">Marketing/Sales</label>
				</div>
				<div class="form-check mb-2 form-check-inline">
					<input class="form-check-input" type="radio" name="professional_background" id="softwareHardwareIT" value="Software/Hardware/IT" {{ isset($franchise->professional_background) && $franchise->professional_background == 'Software/Hardware/IT'  ? 'checked' : '' }} >
					<label class="form-check-label" for="softwareHardwareIT">Software/Hardware/IT</label>
				</div>
				<div class="form-check mb-2 form-check-inline">
					<input class="form-check-input" type="radio" name="professional_background" id="educationalTraining" value="Educational/Training" {{ isset($franchise->professional_background) && $franchise->professional_background == 'Educational/Training'  ? 'checked' : '' }} >
					<label class="form-check-label" for="educationalTraining">Educational/Training</label>
				</div>
				<div class="form-check mb-2 form-check-inline ">
					<input class="form-check-input" type="radio" name="professional_background" id="profitCenterManagement" value="Profit Center Management" {{ isset($franchise->professional_background) && $franchise->professional_background == 'Profit Center Management'  ? 'checked' : '' }} >
					<label class="form-check-label" for="profitCenterManagement">Profit Center Management</label>
				</div>
				<div class="form-check mb-2 form-check-inline">
					<input class="form-check-input" type="radio" name="professional_background" id="smallBusinessMgmt" value="Small Business Mgmt" {{ isset($franchise->professional_background) && $franchise->professional_background == 'Small Business Mgmt'  ? 'checked' : '' }} >
					<label class="form-check-label" for="smallBusinessMgmt">Small Business Mgmt</label>
				</div>
				<div class="form-check mb-2">
					<input class="form-check-input" type="radio" name="professional_background" id="other" value="other" {{ isset($franchise->professional_background) && $franchise->professional_background == 'other'  ? 'checked' : '' }} >
					<label class="form-check-label" for="other">Other (specify)</label>
				</div>
				<div class="col-md-6 col-sm-12">
					<div class="form-label-group in-border">
						<textarea class="form-control" id="otherProfessionalBackground" name="other_professional_background" placeholder="Other Specify">{{ isset($franchise) ? $franchise->other_professional_background : old('other_professional_background') }}</textarea>
						<label for="otherProfessionalBackground" class="form-label">Other Specify</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive business-section @if((isset($franchise->current_occupation) && $franchise->current_occupation == 'business') || (isset($franchise->current_occupation) && $franchise->current_occupation == 'both')) show @else hide-section @endif">
		<table class="table table-bordered table-nowrap mb-0">
			<tbody>
				<tr>
					<th class="text-nowrap" scope="row">Business Name(s)</th>
					<td><div class="form-label-group in-border">
						<input type="text" class="form-control" id="businessName" name="business_name" placeholder="business_name" value="{{ isset($franchise) ? $franchise->business_name : old('business_name') }}">
						<label for="businessName" class="form-label"> Business Name</label>
					</div></td>
				</tr>
				<tr>
					<th class="text-nowrap" scope="row">Proprietary/Partnership/Private Ltd.</th>
					<td><div class="form-label-group in-border">
						<input type="text" class="form-control" id="proprietary" name="proprietary" placeholder="proprietary" value="{{ isset($franchise) ? $franchise->proprietary : old('proprietary') }}">
						<label for="proprietary" class="form-label"> Proprietary/Partnership/Private Ltd</label>
					</div></td>
				</tr>

				<tr>
					<th class="text-nowrap" scope="row">Nature of Business</th>
					<td><div class="form-label-group in-border">
						<input type="text" class="form-control" id="natureOfBusiness" name="nature_of_business" placeholder="nature_of_business" value="{{ isset($franchise) ? $franchise->nature_of_business : old('nature_of_business') }}">
						<label for="natureOfBusiness" class="form-label"> Business Nature</label>
					</div></td>
				</tr>
				<tr>
					<th class="text-nowrap" scope="row">Products/Services offered.</th>
					<td><div class="form-label-group in-border">
						<input type="text" class="form-control" id="offeredServices" name="offered_services" placeholder="offered services" value="{{ isset($franchise) ? $franchise->offered_services : old('offered_services') }}">
						<label for="offeredServices" class="form-label"> Products/Services offered .</label>
					</div></td>
				</tr>

				<tr>
					<th class="text-nowrap" scope="row">Years in Business</th>
					<td><div class="form-label-group in-border">
						<input type="text" class="form-control" id="businessYears" name="business_years" placeholder="business years" value="{{ isset($franchise) ? $franchise->business_years : old('business_years') }}">
						<label for="businessYears" class="form-label"> Years in Business </label>
					</div></td>
				</tr>

				<tr>
					<th class="text-nowrap" scope="row">Number of People Employed</th>
					<td><div class="form-label-group in-border">
						<input type="number" class="form-control" id="numberOfPeopleEmployed" name="number_of_people_employed" placeholder="nature_of_business" value="{{ isset($franchise) ? $franchise->number_of_people_employed : old('number_of_people_employed') }}">
						<label for="numberOfPeopleEmployed" class="form-label"> Number of People Employed</label>
					</div></td>
				</tr>
				<tr>
					<th class="text-nowrap" scope="row">Turnover (Rs.)of Last 3 Years</th>
					<td><div class="form-label-group in-border">
						<input type="text" class="form-control" id="turnOver" name="turn_over" placeholder="offered services" value="{{ isset($franchise) ? $franchise->turn_over : old('turn_over') }}">
						<label for="turnOver" class="form-label">Turnover (Rs.)of Last 3 Years</label>
					</div></td>
				</tr>
			</tbody>
		</table>
	</div>
