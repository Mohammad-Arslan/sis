<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Class</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div>

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('classes.store') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('class_name')) is-invalid @endif" name="class_name" id="className" placeholder="Please enter class name" value="{{old('class_name')}}" required>
                            <label for="className" class="form-label">Class name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('class_name'))
                                {{ $errors->first('class_name') }}
                                @else
                                Class name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="abbreviation" id="classNameAbbr" placeholder="Please enter abbreviation" value="{{old('abbreviation')}}" required>
                            <label for="classNameAbbr" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Class abbreviation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <select class="form-control" name="attendance_type_id" id="attendance_type_id" required>
                                <option value="">Select</option>
                                @foreach($attendance_types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                            <label for="designation_id" class="form-label">Attendance Type</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('attendance_type_id'))
                                    {{ $errors->first('attendance_type_id') }}
                                @else
                                    Attendance Type is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-group in-border">
                            <input type="number" class="form-control @if($errors->has('sort')) is-invalid @endif" name="sort" id="classSort" placeholder="Please enter sort order" value="{{old('sort')}}" min="1" required>
                            <label for="classSort" class="form-label">Sort Order</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('sort'))
                                {{ $errors->first('sort') }}
                                @else
                                Sort order is required!
                                @endif
                            </div>
                        </div>
                        <div class="form-text text-muted">
                            <i class="ri-information-line"></i> 
                            <small>Set sort order based on your class hierarchy. Students can only be promoted to the next higher sort order (e.g., 5 → 6).</small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="classDescription" placeholder="Enter class description here...">{{old('description')}}</textarea>
                            <label for="classDescription" class="form-label">Description</label>
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


