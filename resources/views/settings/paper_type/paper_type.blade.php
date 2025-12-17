<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{isset($paper_type) ? 'Update' : 'Add'}} Paper Type</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" method="POST" action="{{ isset($paper_type) ? route('paper-types.update',$paper_type['id']) : route('paper-types.store') }}" novalidate>
                    @if(isset($paper_type))
                        @method('PATCH')
                    @endif
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Tax Name"
                                   value="{{ old('name') ? old('name') : (isset($paper_type) ? $paper_type->name : '')}}" required>
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('name'))
                                    {{ $errors->first('name') }}
                                @else
                                    Name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('subject_id')) is-invalid @endif" id="subject_id" name="subject_id" aria-label="Section select">
                                <option value="">Please select a subject</option>
                                @if(isset($subjects))
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ isset($paper_type) && $paper_type['subject_id'] == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Subject <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('subject_id'))
                                    {{ $errors->first('subject_id') }}
                                @else
                                    Subject is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('status')) is-invalid @endif" name="status" required>
                                <option value="">Please select</option>
                                <option value="active" {{old('status') == 'active' ? 'selected' : (isset($paper_type) && $paper_type->status == 'active' ? 'selected' : '') }}>Active</option>
                                <option value="inactive" {{old('status') == 'inactive' ? 'selected' : (isset($paper_type) && $paper_type->status == 'inactive' ? 'selected' : '') }}>In Active</option>
                            </select>
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('status'))
                                    {{ $errors->first('status') }}
                                @else
                                    Status is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="description" placeholder="Enter description here...">{{ old('description') ? old('description') : (isset($paper_type) ? $paper_type->description : '')}}</textarea>
                            <label for="Description" class="form-label">Description</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('description'))
                                    {{ $errors->first('description') }}
                                @else
                                    Description is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    @csrf
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
