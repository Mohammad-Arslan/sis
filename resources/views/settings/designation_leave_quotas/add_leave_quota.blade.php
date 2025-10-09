<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create Designation Quota</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('designation-leave-quota.store') }}" method="post">
                    @csrf

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <select class="form-control" id="designation_id" name="designation_id" required>
                                <option value="">Please select a designation</option>
                                @forelse(designations() as $designation)
                                    <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <label for="designation_id" class="form-label">Designation</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('designation_id'))
                                    {{ $errors->first('designation_id') }}
                                @else
                                    Designation is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <select class="form-control" id="leave_type_id" name="leave_type_id" required>
                                <option value="">Please select a leave type</option>
                                @forelse(leaveTypes() as $leaveType)
                                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            <label for="leave_type_id" class="form-label">Leave Type</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('leave_type_id'))
                                    {{ $errors->first('leave_type_id') }}
                                @else
                                    Leave type is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <input type="number" min="1" class="form-control @if($errors->has('no_of_allowed_leaves')) is-invalid @endif" id="no_of_allowed_leaves" name="no_of_allowed_leaves" placeholder="Please enter number of allowed leaves" value="{{ old('no_of_allowed_leaves') }}" required>
                            <label for="no_of_allowed_leaves" class="form-label">Number&nbsp;of&nbsp;Allowed Leaves</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('no_of_allowed_leaves'))
                                    {{ $errors->first('no_of_allowed_leaves') }}
                                @else
                                    Number of allowed leaves are required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
