<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Edit Challan</h4>
            <div class="flex-shrink-0">
                @permission('upload-beams-challan')
                    <a href="{{ route('beams-challans.index') }}" class="btn btn-sm btn-soft-success">
                        <i class="ri-add-circle-line align-middle me-1"></i> Add New Beams Challan
                    </a>
                @endpermission
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate
                    action="{{ route('beams-challans.update', $beamsChallan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="load-select-filter form-select mb-3" id="academic_year_id" name="academic_year_id" data-target="branch_id" data-url="{{route('list-academic-branches')}}" required>
                                <option value="" disabled selected>Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        @if($beamsChallan->academic_year_id == $academic_year->id) {{ 'selected' }} @endif> {{ $academic_year->title }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic Year *</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('academic_year_id'))
                                    {{ $errors->first('academic_year_id') }}
                                @else
                                Academic year is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="load-select-filter form-select mb-3 @if($errors->has('branch_id')) is-invalid @endif" id="branch_id" data-target="class_id" data-url="{{route('list-academic-branch-classes')}}" name="branch_id" required>
                                <option value="" disabled selected>Branch Option</option>
                                @foreach ($branches as $branch)
                                    @if($beamsChallan->branch_id  == $branch->id)
                                        <option selected='selected' value="{{$branch->id}}"> {{ $branch->br_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <label for="branch_id" class="form-label">Select Branch *</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('branch_id'))
                                    {{ $errors->first('branch_id') }}
                                @else
                                Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="load-select-filter form-select mb-3 @if($errors->has('class_id')) is-invalid @endif" id="class_id" data-target="section_id" data-url="{{route('list-academic-branch-class-sections')}}" name="class_id" required>
                                <option value="" disabled selected>Class Option</option>
                                @foreach ($classes as $class)
                                    @if($beamsChallan->class_id  == $class->id)
                                        <option selected='selected' value="{{$class->id}}"> {{ $class->class_name }}</option>
                                    @endif
                                @endforeach

                            </select>
                            <label for="class_id" class="form-label">Select Class *</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('class_id'))
                                    {{ $errors->first('class_id') }}
                                @else
                                Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3 @if($errors->has('section_id')) is-invalid @endif" id="section_id" name="section_id" required>
                                <option value="" disabled selected>Section Option</option>
                                @foreach ($sections as $section)
                                    @if($beamsChallan->section_id  == $section->id)
                                        <option selected='selected' value="{{$section->id}}"> {{ $section->section_name }}</option>
                                    @endif
                                @endforeach

                            </select>
                            <label for="section_id" class="form-label">Select Section *</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('section_id'))
                                    {{ $errors->first('section_id') }}
                                @else
                                Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3 @if($errors->has('challan_month')) is-invalid @endif" id="challan_month" name="challan_month" required>
                                <option value="" disabled selected>Month Option</option>
                                <option @if($beamsChallan->challan_month == 'January') {{ 'selected' }} @endif value="January">January</option>
                                <option @if($beamsChallan->challan_month == 'February') {{ 'selected' }} @endif value="February">February</option>
                                <option @if($beamsChallan->challan_month == 'March') {{ 'selected' }} @endif value="March">March</option>
                                <option @if($beamsChallan->challan_month == 'April') {{ 'selected' }} @endif value="April">April</option>
                                <option @if($beamsChallan->challan_month == 'May') {{ 'selected' }} @endif value="May">May</option>
                                <option @if($beamsChallan->challan_month == 'June') {{ 'selected' }} @endif value="June">June</option>
                                <option @if($beamsChallan->challan_month == 'July') {{ 'selected' }} @endif value="July">July</option>
                                <option @if($beamsChallan->challan_month == 'August') {{ 'selected' }} @endif value="August">August</option>
                                <option @if($beamsChallan->challan_month == 'September') {{ 'selected' }} @endif value="September">September</option>
                                <option @if($beamsChallan->challan_month == 'October') {{ 'selected' }} @endif value="October">October</option>
                                <option @if($beamsChallan->challan_month == 'November') {{ 'selected' }} @endif value="November">November</option>
                                <option @if($beamsChallan->challan_month == 'December') {{ 'selected' }} @endif value="December">December</option>
                            </select>
                            <label for="challan_month" class="form-label">Select Month *</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('challan_month'))
                                    {{ $errors->first('challan_month') }}
                                @else
                                Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <input type="file" class="form-control" id="challan_pdf" name="challan_pdf" accept="application/pdf">
                            <label for="challan_pdf" class="form-label">Challan PDF *</label>
                            {{-- <div class="invalid-tooltip">
                                @if($errors->has('challan_pdf'))
                                {{ $errors->first('challan_pdf') }}
                                @else
                                Challan PDF is required!
                                @endif
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <a href="{{ route('beams-challans.index') }}"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
