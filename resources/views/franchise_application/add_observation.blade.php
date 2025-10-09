<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Observation</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('frachiseApplicationRemark.store') }}" method="post">
                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="observation_for" name="observation_for" aria-label="select">
                                <option value="">Please select a type</option>
                                <option value="QA_Report" {{ old('observation_for') == 'QA_Report' ? 'selected' : '' }}>QA Report</option>
                                <option value="BD" {{ old('observation_for') == 'BD' ? 'selected' : '' }}>BD</option>
                                <option value="TOR" {{ old('observation_for') == 'TOR' ? 'selected' : '' }}>TOR</option>
                                <option value="Application" {{ old('observation_for') == 'Application' ? 'selected' : '' }}>Application</option>
                                <option value="Documents" {{ old('observation_for') == 'Documents' ? 'selected' : '' }}>Documents</option>
                                <option value="IASF" {{ old('observation_for') == 'IASF' ? 'selected' : '' }}>IASF</option>
                                <option value="General" {{ old('observation_for') == 'General' ? 'selected' : '' }}>General</option>
                            </select>
                            <label for="observation_for" class="form-label">Observation Type</label>
                            <div class="invalid-tooltip">
                                    @if($errors->has('observation_for'))
                                    {{ $errors->first('observation_for') }}
                                    @else
                                    Observation type is required!
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-label-group in-border">
                            <textarea class="form-control @if($errors->has('observation')) is-invalid @endif" id="observation" name="observation" rows="1" placeholder="Please enter your observation" required>{{ old('observation') }}</textarea>
                            <label for="observation" class="form-label">Observation</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('observation'))
                                {{ $errors->first('observation') }}
                                @else
                                Observation is required!
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="col-12 text-end">
                        <input type="hidden" class="form-control  " name="user_id" id="user_id" value="{{ auth()->user()->id }}" required>
                        <input type="hidden" class="form-control  " name="user_role" id="user_role" value="{{ get_login_user_role()}}" required>
                        <input type="hidden" class="form-control  " name="franchise_application_id" id="franchise_application_id" value="{{ request()->franchise_application_id }}" required>
                        <button class="btn btn-primary" type="submit">Save Observation</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
