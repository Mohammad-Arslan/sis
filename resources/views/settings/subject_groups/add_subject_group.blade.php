<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Subject Group</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div>

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('subject-groups.store') }}"
                    method="post">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('subject_group_name')) is-invalid @endif"
                                name="subject_group_name" id="subjectGroupName"
                                placeholder="Please enter subject group name" value="{{ old('subject_group_name') }}"
                                required>
                            <label for="name" class="form-label">Subject Group name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('subject_group_name'))
                                    {{ $errors->first('subject_group_name') }}
                                @else
                                    Subject group name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="subjectGroupDescription"
                                placeholder="Enter subject group description here...">{{ old('description') }}</textarea>
                            <label for="subjectGroupDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
