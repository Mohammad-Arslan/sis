<form class="g-3 needs-validation" action="{{ route('franchises.update', $franchise->id) }}" method="POST"  novalidate>
	@csrf
	@method('PATCH')
	<div class="row">
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="other_profession" class="form-label">Current Business/Occupation</label>
			<input type="text" class="form-control" id="other_profession" name="other_profession" placeholder="Import Export" value="{{$franchise->other_profession}}">
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="campus_location" class="form-label">Area/Location of interest for UCS franchise</label>
			<input type="text" class="form-control" id="campus_location" name="campus_location" placeholder="Clifton , Karachi" value="{{$franchise->campus_location}}">
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="source_id" class="form-label">Where did you hear about us? </label>

			<select class="form-select" aria-label="form-select-sm example" id="source_id" name="source_id" required>
				<option value="">Please select</option>
				@foreach ($sources as $source)
				<option value="{{ $source->id }}" {{ $franchise->source_id == $source->id ? 'selected' : '' }}>{{ $source->source_name }}</option>
				@endforeach
			</select>
			<div class="valid-feedback">Looks good!</div>
		</div>
		<div class="col-md-4 col-sm-12  mt-3">
			<label for="" class="form-label">Have you led a franchise? If yes, please mention the name.</label>
			<div class="input-group">
				<div class="input-group-text">
					Yes&nbsp;<input class="form-check-input mt-0" type="radio" value="yes" aria-label="Radio button for following text input" name="already_franchise" {{ $franchise->already_franchise == 'yes' ? 'checked' : '' }}> &nbsp;  &nbsp;No &nbsp;<input class="form-check-input mt-0" type="radio" value="no" aria-label="Radio button for following text input" name="already_franchise" {{ $franchise->already_franchise == 'no' ? 'checked' : '' }}>
				</div>
				<input type="text" class="form-control remarks" name="remarks" aria-label="Text input with radio button" readonly value="{{$franchise->remarks}}">
			</div>
			<div class="valid-feedback">Looks good!</div>
		</div>

		<div class="col-md-4 col-sm-12  mt-3">
			<label for="cnic" class="form-label">Are you interested in</label>
			<div class="form-check mt-1 mt-lg-1">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="franchise_type" id="new" value="new" {{ $franchise->franchise_type == 'new' ? 'checked' : '' }}>
                    <label class="form-check-label" for="yes">Opening a new franchise</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="franchise_type" id="old" value="old" {{ $franchise->franchise_type == 'old' ? 'checked' : '' }}>
                    <label class="form-check-label" for="no">Converting an existing school/college</label>
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
</form>