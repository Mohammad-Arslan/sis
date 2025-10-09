<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Subject Group</h4>
            <div class="flex-shrink-0">
                @permission('add-subject-group')
                    <a href="{{ route('subject-groups.index') }}" class="btn btn-success btn-label btn-sm">
                        <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New Subject Group
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('subject-groups.update', $subjectGroup->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('subject_group_name')) is-invalid @endif"
                                name="subject_group_name" id="subjectGroupName"
                                placeholder="Please enter subject group name"
                                value="{{ $subjectGroup->subject_group_name }}" required>
                            <label for="name" class="form-label">Subject Group name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('subject_group_name'))
                                    {{ $errors->first('subject_group_name') }}
                                @else
                                    Subject Group name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="subjectGroupDescription"
                                placeholder="Enter subject group description here...">{{ $subjectGroup->description }}</textarea>
                            <label for="subjectGroupDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('subject-groups.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
