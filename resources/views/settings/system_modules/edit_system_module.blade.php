<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit System Module</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('system-modules.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-system-module')
                <a href="{{ route('system-modules.index') }}" class="btn btn-sm btn-soft-success">
                    <i class="ri-add-circle-line align-middle me-1"></i> Add New System Module
                </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('system-modules.update', $systemModule->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Please enter name" value="{{ $systemModule->name }}" required>
                            <label for="name" class="form-label">Name</label>
                            <div class="invalid-tooltip">Name is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="parentID" name="parent_id">
                                <option value="" disabled selected>Parent ID</option>
                                @if (isset($parents))
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id }}"" @if ($systemModule->parent_id == $parent->id) selected @endif>{{ $parent->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="parentID" class="form-label">Parent ID</label>
                            <div class="invalid-tooltip">Parent ID is required!</div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="systemModulesDescription" placeholder="Enter fee charges description here...">{{ $systemModule->description }}</textarea>
                            <label for="systemModulesDescription" class="form-label">Description</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('system-modules.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
