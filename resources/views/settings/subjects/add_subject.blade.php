<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Subject</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div>

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('subjects.store') }}" method="post">
                    @csrf
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text"
                                class="form-control @if ($errors->has('subject_name')) is-invalid @endif"
                                name="subject_name" id="subjectName" placeholder="Please enter subject name"
                                value="{{ old('subject_name') }}" required>
                            <label for="name" class="form-label">Subject Name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('subject_name'))
                                    {{ $errors->first('subject_name') }}
                                @else
                                    Subject name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="abbreviation" id="subjectAbbr"
                                placeholder="Please enter abbreviation" value="{{ old('abbreviation') }}">
                            <label for="subjectAbbr" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Abbreviation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="sort_no" id="subjectAbbr"
                                placeholder="Please enter sort_no" value="{{ old('sort_no') }}">
                            <label for="subjectAbbr" class="form-label">Sort No</label>
                            <div class="invalid-tooltip">Sort No is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="languageName" name="language_id" required>
                                <option value="" disabled selected>Languages</option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language->id }}"
                                        @if (old('language_id') == $language->id) {{ 'selected' }} @endif>
                                        {{ $language->language_name }}</option>
                                @endforeach
                            </select>
                            <label for="languageName" class="form-label">Language</label>
                            <div class="invalid-tooltip">Select the Language!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="subjectGroupName" name="subject_group_id">
                                <option value="" disabled selected>Subject Groups</option>
                                @foreach ($subjectGroups as $subjectGroup)
                                    <option value="{{ $subjectGroup->id }}"
                                        @if (old('subject_group_id') == $subjectGroup->id) {{ 'selected' }} @endif>
                                        {{ $subjectGroup->subject_group_name }}</option>
                                @endforeach
                            </select>
                            <label for="subjectGroupName" class="form-label">Subject Groups</label>
                            <div class="invalid-tooltip">Select the Subject Groups!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="isAcademic" name="is_academic" required>
                                <option value="" disabled selected>Academic options</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <label for="isAcademic" class="form-label">Academic</label>
                            <div class="invalid-tooltip">Kindly select the academic options!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="subject_type" name="subject_type" required>
                                <option value="" disabled selected>Subject Type options</option>
                                <option value="Major">Major</option>
                                <option value="Minor">Minor</option>
                            </select>
                            <label for="subject_type" class="form-label">Subject Type</label>
                            <div class="invalid-tooltip">Kindly select the subject type!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
