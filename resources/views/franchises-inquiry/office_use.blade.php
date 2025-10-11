
<div class="accordion-item accordion-fill-primary mt-3">
	<h2 class="accordion-header" id="officeUseOnly">
		<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse5" aria-expanded="true" aria-controls="accor_borderedcollapse5" @if(!isset($franchise)) disabled @endif>
			Application Status
		</button>
	</h2>
	<div id="accor_borderedcollapse5" class="accordion-collapse collapse" aria-labelledby="officeUseOnly" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
		<div class="accordion-body">
            @if(isset($franchise))
			<div class="row">
				<div class="col-md-12 col-sm-12  mt-3">
					<p><strong>Application Status</strong></p>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="status" id="pending" value="P" {{ isset($franchise->status) && $franchise->status == 'P'  ? 'checked' : '' }}  >
						<label class="form-check-label" for="pending">Not Approved</label>
					</div>
					<div class="form-check mb-2 form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="approved" value="A" {{ isset($franchise->status) && $franchise->status == 'A'  ? 'checked' : '' }}>
						<label class="form-check-label" for="approved">Approved</label>
					</div>
					<div class="form-check mb-2 form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="rejected" value="R" {{ isset($franchise->status) && $franchise->status == 'R'  ? 'checked' : '' }}>
						<label class="form-check-label" for="rejected">Rejected</label>
					</div>
				</div>

				{{--<div class="col-md-12 col-sm-12  mt-3">
					<label class="form-check-label" for="purposeBuildCampus">
						If Approved:&nbsp;&nbsp;&nbsp;
					</label>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="agreement_type" id="loi" value="loi" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'loi'  ? 'checked' : '' }}>
						<label class="form-check-label" for="loi">LOI</label>
					</div>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="agreement_type" id="mou" value="mou" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'mou'  ? 'checked' : '' }}>
						<label class="form-check-label" for="mou">MOU</label>
					</div>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="agreement_type" id="fa" value="fa" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'fa'  ? 'checked' : '' }}>
						<label class="form-check-label" for="fa">FA</label>
					</div>
				</div>--}}

				@if(isset($users))
				<div class="col-md-4 col-sm-12 mt-3">
					<div class="form-label-group in-border">
						<select class="form-select" id="recommendedBy" name="recommended_by" aria-label="Recommended user select" required>
							<option value="">Please select</option>
							@foreach ($users as $user)
							<option value="{{ $user->id }}" {{ $franchise->recommended_by == $user->id ? 'selected' : '' }} >{{ $user->name }}</option>
							@endforeach
						</select>
						<label for="recommendedBy" class="form-label">Recommended by</label>
						<div class="invalid-tooltip">
							Recommended by is required!
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-12 mt-3">
					<div class="form-label-group in-border">
						<select class="form-select" id="approvedBy" name="approved_by" aria-label="Approved user select" required>
							<option value="">Please select</option>
							@foreach ($users as $user)
							<option value="{{ $user->id }}" {{ $franchise->approved_by == $user->id ? 'selected' : '' }} >{{ $user->name }}</option>
							@endforeach
						</select>
						<label for="approvedBy" class="form-label">Approved by</label>
						<div class="invalid-tooltip">
							Approved by is required!
						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 mt-3">
                    <div class="form-label-group in-border">
                        <textarea class="form-control" name="application_status_remarks" id="application_status_remarks" placeholder="Write Here...">{{ $franchise->application_status_remarks }}</textarea>
                        <label class="form-label">Remarks</label>
                    </div>
				</div>

				<!-- <div class="col-md-4 col-sm-12 mt-3">
					<div class="form-label-group in-border">
						<select class="form-select" id="forwardedBy" name="forwarded_by" aria-label="Forwarded user select" required>
							<option value="">Please select</option>
							@foreach ($users as $user)
							<option value="{{ $user->id }}" {{ $franchise->forwarded_by == $user->id ? 'selected' : '' }} >{{ $user->name }}</option>
							@endforeach
						</select>
						<label for="forwardedBy" class="form-label">Forwarded by</label>
						<div class="invalid-tooltip">
							Forwarded by is required!
						</div>
					</div>
				</div> -->
				@endif
			</div>
			<div class="row mt-3">
				<div class="col-12 text-end">
					<button class="btn btn-primary" type="submit" form="officeUseForm">Submit form</button>
					<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
				</div>
			</div>
            @endif
		</div>
	</div>
</div>

@push('header_scripts')


@endpush

@push('footer_scripts')

@endpush
