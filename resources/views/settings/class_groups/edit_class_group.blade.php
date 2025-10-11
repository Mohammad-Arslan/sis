<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit School Type</h4>
            <div class="flex-shrink-0">
                @permission('add-class-group')
                    <a href="{{ route('class-groups.index') }}" class="btn btn-success btn-label btn-sm">
                        <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New School Type
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('class-groups.update', $classGroup->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                                name="name" id="name" placeholder="Please enter school type name"
                                value="{{ $classGroup->name }}" required>
                            <label for="name" class="form-label"> School Type name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('name'))
                                    {{ $errors->first('name') }}
                                @else
                                    School Type name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="classGroupDescription"
                                placeholder="Enter class group description here...">{{ $classGroup->description }}</textarea>
                            <label for="classGroupDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('class-groups.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
