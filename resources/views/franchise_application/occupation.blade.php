<div class="tab-pane " id="occupation-overview" role="tabpanel">

	<div class="col-md-4 col-sm-12  mt-3">
		<div class="form-check mb-2 form-check-inline ">
			<input class="form-check-input" type="radio" name="current_occupation" id="service" value="service" {{ isset($franchise->current_occupation) && $franchise->current_occupation == 'service'  ? 'checked' : '' }} @if(!isset($franchise)) checked @endif >
			<label class="form-check-label" for="service">Services</label>
		</div>
		<div class="form-check mb-2 form-check-inline">
			<input class="form-check-input" type="radio" name="current_occupation" id="business" value="Business" {{ isset($franchise->current_occupation) && $franchise->current_occupation == 'Business'  ? 'checked' : '' }}>
			<label class="form-check-label" for="business">Business</label>
		</div>
		<div class="form-check mb-2 form-check-inline">
			<input class="form-check-input" type="radio" name="current_occupation" id="both" value="both" {{ isset($franchise->current_occupation) && $franchise->current_occupation == 'both'  ? 'checked' : '' }}>
			<label class="form-check-label" for="both">Both</label>
		</div>
	</div>
	@include('franchise_application.service_section')
	@include('franchise_application.business_service')
	<div class="row mt-3">
		<div class="col-12 text-end">
			<button class="btn btn-primary" type="submit">Submit form</button>
			<a href="{{ url('franchise-applications') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
		</div>
	</div>
</div>

