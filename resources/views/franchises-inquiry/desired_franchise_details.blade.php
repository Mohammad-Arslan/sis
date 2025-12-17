<div class="accordion-item mt-3">
	<h2 class="accordion-header" id="accordionborderedDesiredFranchiseDetails">
		<button class="accordion-button {{ isset($franchise->proposed_property_status) && $franchise->proposed_property_status != ''  ? '' : 'colapsed' }} " type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse4" aria-expanded="false" aria-controls="accor_borderedcollapse4" @if(!isset($franchise)) disabled @endif>
			Desired Franchise Details
		</button>
	</h2>
	<div id="accor_borderedcollapse4" class="accordion-collapse collapse {{ isset($franchise->proposed_property_status) && $franchise->proposed_property_status != ''  ? 'show' : '' }}" aria-labelledby="accordionborderedDesiredFranchiseDetails" data-bs-parent="#accordionBordered" style="">
		<div class="accordion-body">
            @if(isset($franchise))
			<div class="col-xl-12 col-sm-12">
				<ul class="list-group">
					<li class="list-group-item">
						Status of Proposed Property: (Please tick one) ?.
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="proposed_property_status" id="rented" value="Rented" {{ isset($franchise->proposed_property_status) && $franchise->proposed_property_status == 'Rented'  ? 'checked' : '' }}  required>
							<label class="form-check-label" for="rented">Rented</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="proposed_property_status" id="owned" value="Owned" {{ isset($franchise->proposed_property_status) && $franchise->proposed_property_status == 'Owned'  ? 'checked' : '' }}>
							<label class="form-check-label" for="owned">Owned</label>
						</div>
					</li>
					<li class="list-group-item">
						<p>In what capacity are you submitting an application for a school franchise:</p>
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="school_franchise_capacity" id="individualCapacity" value="Individual Capacity" {{ isset($franchise->school_franchise_capacity) && $franchise->school_franchise_capacity == 'Individual Capacity'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="individualCapacity">Individual Capacity</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="school_franchise_capacity" id="partnership" value="Partnership" {{ isset($franchise->school_franchise_capacity) && $franchise->school_franchise_capacity == 'Partnership'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="partnership">Partnership</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="school_franchise_capacity" id="directorsCompany" value="Directors in case of Company" {{ isset($franchise->school_franchise_capacity) && $franchise->school_franchise_capacity == 'Directors in case of Company'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="directorsCompany">Directors in case of Company</label>
						</div>
					</li>
					<li class="list-group-item">
						<p>How do you propose to setup the school ? (Please tick one)</p>
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="propose_to_setup_school" id="proprietorship" value="Proprietorship" {{ isset($franchise->propose_to_setup_school) && $franchise->propose_to_setup_school == 'Proprietorship'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="proprietorship">Proprietorship</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="propose_to_setup_school" id="partnership" value="Partnership" {{ isset($franchise->propose_to_setup_school) && $franchise->propose_to_setup_school == 'Partnership'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="partnership">Partnership</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="propose_to_setup_school" id="private" value="private" {{ isset($franchise->propose_to_setup_school) && $franchise->propose_to_setup_school == 'private'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="private">Private Ltd.</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="propose_to_setup_school" id="public" value="public" {{ isset($franchise->propose_to_setup_school) && $franchise->propose_to_setup_school == 'public'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="public">Public Ltd.</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="propose_to_setup_school" id="society" value="society" {{ isset($franchise->propose_to_setup_school) && $franchise->propose_to_setup_school == 'society'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="society">Society</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="propose_to_setup_school" id="trust" value="trust" {{ isset($franchise->propose_to_setup_school) && $franchise->propose_to_setup_school == 'trust'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="trust">Trust</label>
						</div>
					</li>
					<li class="list-group-item">
						Is the Proprietorship/Partnership/Company already in existence?.
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="company_already_in_existence" id="yes" value="yes" {{ isset($franchise->company_already_in_existence) && $franchise->company_already_in_existence == 'yes'  ? 'checked' : '' }}>
							<label class="form-check-label" for="yes">Yes</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="company_already_in_existence" id="no" value="no" {{ isset($franchise->company_already_in_existence) && $franchise->company_already_in_existence == 'no'  ? 'checked' : '' }}>
							<label class="form-check-label" for="no">No</label>
						</div>
					</li>
					<li class="list-group-item">
						<p>If yes, what is the name of the Business/Firm/Company;</p>
						<div class="form-label-group in-border mt-2">
							<input class="form-control" type="text" id="businessFirmCompanyName" name="business_firm_company_name" placeholder="Other Specify" value="{{ isset($franchise->business_firm_company_name) && !empty($franchise->business_firm_company_name) ? $franchise->business_firm_company_name : old('business_firm_company_name') }}">
							<label for="businessFirmCompanyName" class="form-label">Business/Firm/Company</label>
						</div>
					</li>
					<li class="list-group-item">
						<p>City/Town where you propose to setup the new venture </p>
						<div class="form-label-group in-border mt-2">
							<input class="form-control" type="text" id="setupProposeCity" name="setup_propose_city" placeholder="Other Specify" value="{{ isset($franchise->setup_propose_city) && !empty($franchise->setup_propose_city) ? $franchise->setup_propose_city : old('setup_propose_city') }}">
							<label for="setupProposeCity" class="form-label">City/Town</label>
						</div>
					</li>
					<li class="list-group-item">
						<p>When do you propose to setup the new venture ?</p>
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="duration_new_venture_setup" id="immediately" value="immediately" {{ isset($franchise->duration_new_venture_setup) && $franchise->duration_new_venture_setup == 'immediately'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="immediately">Immediately</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="duration_new_venture_setup" id="Within3Months" value="Within next 3 months" {{ isset($franchise->duration_new_venture_setup) && $franchise->duration_new_venture_setup == 'Within next 3 months'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="Within3Months">Within next 3 months </label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="duration_new_venture_setup" id="Within6Months" value="Within next 6 months" {{ isset($franchise->duration_new_venture_setup) && $franchise->duration_new_venture_setup == 'Within next 6 months'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="Within6Months">Within next 6 months </label>
						</div>
					</li>
					<li class="list-group-item">
						Do you already possess a site ?
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="already_possess_site" id="yes" value="yes" {{ isset($franchise->already_possess_site) && $franchise->already_possess_site == 'yes'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="yes">Yes</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="already_possess_site" id="no" value="no" {{ isset($franchise->already_possess_site) && $franchise->already_possess_site == 'no'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="no">No</label>
						</div>
					</li>
					<li class="list-group-item">
						If not, do you have a site in mind?
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="site_in_mind" id="yes" value="yes" {{ isset($franchise->site_in_mind) && $franchise->site_in_mind == 'yes'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="yes">Yes</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="site_in_mind" id="no" value="no" {{ isset($franchise->site_in_mind) && $franchise->site_in_mind == 'no'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="no">No</label>
						</div>
					</li>

					@include('franchises-inquiry.possess_site_table')

					<li class="list-group-item">
						In case you do not have a site, do you plan to rent a site ?
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="plan_rent_site" id="yes" value="yes" {{ isset($franchise->plan_rent_site) && $franchise->plan_rent_site == 'yes'  ? 'checked' : '' }}>
							<label class="form-check-label" for="yes">Yes</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="plan_rent_site" id="no" value="no" {{ isset($franchise->plan_rent_site) && $franchise->plan_rent_site == 'no'  ? 'checked' : '' }}>
							<label class="form-check-label" for="no">No</label>
						</div>
						<p>If yes, within how many months? </p>
						<div class="form-label-group in-border mt-2">
							<input class="form-control" type="text" id="noOfMonthForRentSsite" name="no_of_month_for_rent_a_site" placeholder="Other Specify" value="{{ isset($franchise->no_of_month_for_rent_a_site) && !empty($franchise->no_of_month_for_rent_a_site) ? $franchise->no_of_month_for_rent_a_site : old('no_of_month_for_rent_a_site') }}">
							<label for="noOfMonthForRentSsite" class="form-label">No. of Months</label>
						</div>
					</li>

					<li class="list-group-item">
						<p>How much funding are you willing to invest initially ?</p>
						<div class="form-check mb-2 form-check-inline ">
							<input class="form-check-input" type="radio" name="initial_funding" id="moreThan10M" value="More than 10 Million" {{ isset($franchise->initial_funding) && $franchise->initial_funding == 'More than 10 Million'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="moreThan10M">More than 10 Million</label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="initial_funding" id="more5Million" value="5 to 10 Million" {{ isset($franchise->initial_funding) && $franchise->initial_funding == '5 to 10 Million'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="more5Million">5 to 10 Million </label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="initial_funding" id="btw5to2M" value="5 to 2.5 Million" {{ isset($franchise->initial_funding) && $franchise->initial_funding == '5 to 2.5 Million'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="btw5to2M">5 to 2.5 Million </label>
						</div>
						<div class="form-check mb-2 form-check-inline">
							<input class="form-check-input" type="radio" name="initial_funding" id="below2M" value="Below 2.5 Million" {{ isset($franchise->initial_funding) && $franchise->initial_funding == 'Below 2.5 Million'  ? 'checked' : '' }} required>
							<label class="form-check-label" for="below2M">Below 2.5 Million </label>
						</div>
					</li>

					<li class="list-group-item">
						<p>What efforts/initiatives would you put into making this business a success?</p>
						<div class="form-label-group in-border mt-2">
							<input class="form-control" type="text" id="businessSuccessInitiative" name="business_success_initiative" placeholder="Other Specify" value="{{ isset($franchise->business_success_initiative) && !empty($franchise->business_success_initiative) ? $franchise->business_success_initiative : old('business_success_initiative') }}">
							<label for="businessSuccessInitiative" class="form-label">Details</label>
						</div>
					</li>
					<li class="list-group-item">
						<p>State Reasons why UCS should consider you as a franchisee.</p>
						<div class="form-label-group in-border mt-2">
							<input class="form-control" type="text" id="reasonSuitableFranchises" name="suitable_franchisee_reason" placeholder="suitable_franchisee_reason" value="{{ isset($franchise->suitable_franchisee_reason) && !empty($franchise->suitable_franchisee_reason) ? $franchise->suitable_franchisee_reason : old('suitable_franchisee_reason') }}" required>
							<label for="reasonSuitableFranchises" class="form-label">Reasons</label>
						</div>
					</li>
					{{--<li class="list-group-item">
						<ul>
							<li>UCS exclusively reserves the rights to reject this application without reason.</li>
							<li>Approval of the application is subject to the post visit evaluation report of the UCS Team</li>
							<li>One franchise application is entertainable for each desired city/town</li>
							<li>
								<p>Important documents (To be annexed herewith):</p>
								<ol>
									<li>Bank Draft of Rs.5000 drawn in favour of the Educational Services Pvt.Ltd as non-refundable scrutiny/process fee against each application</li>
									<li>Original & Photocopy of the Franchise Application Form.</li>
									<li>Copy of Computerised CNIC</li>
									<li>Bank Statement of Last 6 Months.</li>
									<li>Copy of Board Resolution/Authority Letter in case of apply on behalf of company/firm</li>
									<li>Copies of Ownership/Rental/Lease documents (In case availability of the site(s))</li>
								</ol>
							</li>
						</ul>
					</li>--}}
				</ul>

			</div>
			<div class="row mt-3">
				<div class="col-12 text-end">
					<button class="btn btn-primary" type="submit" form="desiredFranchiseDetailsForm">Submit form</button>
					<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
				</div>
			</div>
            @endif
		</div>

	</div>
</div>
