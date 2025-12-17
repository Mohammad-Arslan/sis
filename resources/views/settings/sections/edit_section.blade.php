<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Section</h4>
            <div class="flex-shrink-0">
                <!-- <a href="{{ route('countries.index') }}" class="btn btn-success btn-label btn-sm">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Add New Country
                </a> -->
                @permission('add-section')
                    <a href="{{ route('sections.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Section
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('sections.update', $section->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="section_name" id="sectionName"
                                placeholder="Please enter section name" value="{{ $section->section_name }}" required>
                            <label for="sectionName"
                                class="form-label @if ($errors->has('section_name')) is-invalid @endif">Section
                                name</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('section_name'))
                                    {{ $errors->first('section_name') }}
                                @else
                                    Section name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="abbreviation" id="sectionNameAbbr"
                                placeholder="Please enter abbreviation" value="{{ $section->abbreviation }}" required>
                            <label for="sectionNameAbbr" class="form-label">Abbreviation</label>
                            <div class="invalid-tooltip">Abbreviation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="description" id="sectionDescription"
                                placeholder="Enter section description here...">{{ $section->description }}</textarea>
                            <label for="sectionDescription" class="form-label">Description</label>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('sections.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
