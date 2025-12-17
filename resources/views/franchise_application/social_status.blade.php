<div class="tab-pane" id="social-status-overview" role="tabpanel">
	<div class="col-xl-12 col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="live-preview">
					<ol class="list-group list-group-numbered">
						<li class="list-group-item">
							Do you have any criminal records?.
							<div class="form-check mb-2 form-check-inline ">
								<input class="form-check-input" type="radio" name="criminal_record" id="yes" value="Y" {{ isset($franchise->criminal_record) && $franchise->criminal_record == 'Y'  ? 'checked' : '' }}>
								<label class="form-check-label" for="yes">Yes</label>
							</div>
							<div class="form-check mb-2 form-check-inline">
								<input class="form-check-input" type="radio" name="criminal_record" id="no" value="N" {{ isset($franchise->criminal_record) && $franchise->criminal_record == 'N'  ? 'checked' : '' }}>
								<label class="form-check-label" for="no">No</label>
							</div>
						</li>
						<li class="list-group-item">
							Are any criminal proceedings pending against you in any courts in Pakistan?.
							<div class="form-check mb-2 form-check-inline ">
								<input class="form-check-input" type="radio" name="criminal_proceedings" id="yes" value="Y" {{ isset($franchise->criminal_proceedings) && $franchise->criminal_proceedings == 'Y'  ? 'checked' : '' }}>
								<label class="form-check-label" for="yes">Yes</label>
							</div>
							<div class="form-check mb-2 form-check-inline">
								<input class="form-check-input" type="radio" name="criminal_proceedings" id="no" value="N" {{ isset($franchise->criminal_proceedings) && $franchise->criminal_proceedings == 'N'  ? 'checked' : '' }}>
								<label class="form-check-label" for="no">No</label>
							</div>
						</li>
						<li class="list-group-item">
							Have you ever been charged for any unlawful acts?.
							<div class="form-check mb-2 form-check-inline ">
								<input class="form-check-input" type="radio" name="unlawful_acts" id="yes" value="Y" {{ isset($franchise->unlawful_acts) && $franchise->unlawful_acts == 'Y'  ? 'checked' : '' }}>
								<label class="form-check-label" for="yes">Yes</label>
							</div>
							<div class="form-check mb-2 form-check-inline">
								<input class="form-check-input" type="radio" name="unlawful_acts" id="no" value="N" {{ isset($franchise->unlawful_acts) && $franchise->unlawful_acts == 'N'  ? 'checked' : '' }}>
								<label class="form-check-label" for="no">No</label>
							</div>
						</li>
						<li class="list-group-item">If you have ticked 'Yes' for any of the above options, please state details below:
							<div class="form-label-group in-border mt-2">
								<textarea class="form-control" id="criminalRecordDetails" name="criminal_record_details" placeholder="Criminal Records Details">{{ isset($franchise) ? $franchise->criminal_record_details : old('criminal_record_details') }}</textarea>
								<label for="criminalRecordDetails" class="form-label">Criminal Records Details</label>
							</div>
						</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-12 text-end">
			<button class="btn btn-primary" type="submit">Submit form</button>
			<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
		</div>
	</div>
</div>
