<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">BD Visit</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" method="POST" action="{{ route('franchise-application-bd.update', $franchise_application_bd_visit->id) }}" novalidate>

                    @method('PUT')
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('nwa_name')) is-invalid @endif" id="nwa_name" name="nwa_name" value="{{$nwa_name}}" disabled>
                            <label for="nwa_name" class="form-label">NWA Name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('nwa_name'))
                                {{ $errors->first('nwa_name') }}
                                @else
                                Applicant name not found!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                          <input type="text" class="form-control @if($errors->has('nwa_contact')) is-invalid @endif" id="nwa_contact" name="nwa_contact" value="{{ $nwa_contact }}" disabled>
                          <label for="nwa_contact" class="form-label">Contact</label>
                          <div class="invalid-tooltip">
                              @if($errors->has('nwa_contact'))
                              {{ $errors->first('nwa_contact') }}
                              @else
                              NWA contact not found!
                              @endif
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                          <input type="text" class="form-control @if($errors->has('site_address')) is-invalid @endif" id="siteAddress" name="site_address" placeholder="Please enter first name" value="{{ $franchise_application_bd_visit->site_address }}" required>
                          <label for="siteAddress" class="form-label">Site Address</label>
                          <div class="invalid-tooltip">
                              @if($errors->has('site_address'))
                              {{ $errors->first('site_address') }}
                              @else
                              Proposed location address is required!
                              @endif
                          </div>
                      </div>
                    </div>
                    <div class="border border-dashed mb-3"></div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('school_type')) is-invalid @endif" id="schoolType" name="school_type" aria-label="school_type select" onchange="setSchoolConfiguration(this.value)" required>
                                    <option value="">Please select a Proposed school type</option>
                                    @foreach ($class_groups as $class_group)
                                        <option value="{{ $class_group->id }}" {{ $franchise_application_bd_visit->school_type == $class_group->id ? 'selected' : '' }}>{{ $class_group->name }}</option>
                                    @endforeach
                            </select>
                            <label for="schoolType" class="form-label">Proposed School Type</label>
                            <div class="invalid-tooltip">
                                    @if($errors->has('school_type'))
                                    {{ $errors->first('school_type') }}
                                    @else
                                    Proposed School Type is required!
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('school_configuration')) is-invalid @endif" id="school_configuration" name="school_configuration" aria-label="school_configuration select" disabled required>
                                <option value="">Please select</option>
                                @foreach ($class_groups as $class_group)
                                    <option value="{{ $class_group->id }}" {{ $franchise_application_bd_visit->school_type == $class_group->id ? 'selected' : '' }}>{{ $class_group->description }}</option>
                                @endforeach
                            </select>
                            <label for="school_configuration" class="form-label">School Configuration</label>
                            <div class="invalid-tooltip">
                                    @if($errors->has('school_configuration'))
                                    {{ $errors->first('school_configuration') }}
                                    @else
                                    School Configuration is required!
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('proposed_school_name')) is-invalid @endif" id="proposed_school_name" name="proposed_school_name" placeholder="Please enter proposed school name" value="{{ $franchise_application_bd_visit->proposed_school_name }}" required>
                            <label for="proposed_school_name" class="form-label">Proposed School Name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('proposed_school_name'))
                                {{ $errors->first('proposed_school_name') }}
                                @else
                                Propose school name is required!
                                @endif
                            </div>
                        </div>
                      </div>
                    {{-- <div class="border border-dashed mb-3"></div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('area_population_half_km_radius')) is-invalid @endif" id="area_population_half_km_radius" name="area_population_half_km_radius" aria-label="area_population_half_km_radius select" required>
                                    <option value="">Please select</option>
                                    <option value="UM" {{ $franchise_application_bd_visit->area_population_half_km_radius == 'UM' ? 'selected' : '' }}>UM</option>
                                    <option value="M" {{ $franchise_application_bd_visit->area_population_half_km_radius == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="LM" {{ $franchise_application_bd_visit->area_population_half_km_radius == 'LM' ? 'selected' : '' }}>LM</option>
                            </select>
                            <label for="area_population_half_km_radius" class="form-label">Area of population Within 0.5 KM radius</label>
                            <div class="invalid-tooltip">
                                    @if($errors->has('area_population_half_km_radius'))
                                    {{ $errors->first('area_population_half_km_radius') }}
                                    @else
                                    Area of population is required!
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('area_population_one_km_radius')) is-invalid @endif" id="area_population_one_km_radius" name="area_population_one_km_radius" aria-label="area_population_one_km_radius select" required>
                                    <option value="">Please select</option>
                                    <option value="UM" {{ $franchise_application_bd_visit->area_population_one_km_radius == 'UM' ? 'selected' : '' }}>UM</option>
                                    <option value="M" {{ $franchise_application_bd_visit->area_population_one_km_radius == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="LM" {{ $franchise_application_bd_visit->area_population_one_km_radius == 'LM' ? 'selected' : '' }}>LM</option>
                            </select>
                            <label for="area_population_one_km_radius" class="form-label">Area of population Within 01 KM radius</label>
                            <div class="invalid-tooltip">
                                    @if($errors->has('area_population_one_km_radius'))
                                    {{ $errors->first('area_population_one_km_radius') }}
                                    @else
                                    Area of population is required!
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('area_population_two_km_radius')) is-invalid @endif" id="area_population_two_km_radius" name="area_population_two_km_radius" aria-label="area_population_two_km_radius select" required>
                                    <option value="">Please select</option>
                                    <option value="UM" {{ $franchise_application_bd_visit->area_population_two_km_radius == 'UM' ? 'selected' : '' }}>UM</option>
                                    <option value="M" {{ $franchise_application_bd_visit->area_population_two_km_radius == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="LM" {{ $franchise_application_bd_visit->area_population_two_km_radius == 'LM' ? 'selected' : '' }}>LM</option>
                            </select>
                            <label for="area_population_two_km_radius" class="form-label">Area of population Within 02 KM radius</label>
                            <div class="invalid-tooltip">
                                    @if($errors->has('area_population_two_km_radius'))
                                    {{ $errors->first('area_population_two_km_radius') }}
                                    @else
                                    Area of population is required!
                                    @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="border border-dashed mb-3"></div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                        <div class="input-group">
                            <input type="text" class="form-control @if($errors->has('visit_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ $franchise_application_bd_visit->visit_date }}" name="visit_date" id="visitDate" required>
                            <label for="visitDate" class="form-label">Visit date</label>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <div class="invalid-tooltip">
                                @if($errors->has('visit_date'))
                                {{ $errors->first('visit_date') }}
                                @else
                                Visit date is required!
                                @endif
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                        <select class="form-select @if($errors->has('visit_by')) is-invalid @endif" id="visitBy" name="visit_by" aria-label="select" required>
                            <option value="">Please select a Visit By</option>
                            @foreach ($bd_reps as $bd_rep)
                                <option value="{{ $bd_rep->id }}" {{ $franchise_application_bd_visit->visit_by == $bd_rep->id ? 'selected' : '' }} >{{ $bd_rep->user->name }}</option>
                            @endforeach
                        </select>
                        <label for="visitBy" class="form-label">BD Representative</label>
                        <div class="invalid-tooltip">
                            @if($errors->has('visit_by'))
                            {{ $errors->first('visit_by') }}
                            @else
                            Sales Representative is required!
                            @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                        <select class="form-select @if($errors->has('bd_status')) is-invalid @endif" id="bdStatus" name="bd_status" aria-label="select" required>
                            <option value="pending" {{ $franchise_application_bd_visit->getRawOriginal('bd_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $franchise_application_bd_visit->getRawOriginal('bd_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="not_approved" {{ $franchise_application_bd_visit->getRawOriginal('bd_status') == 'not_approved' ? 'selected' : '' }}>Not Approved</option>
                        </select>
                        <label for="bdStatus" class="form-label">BD Status</label>
                        <div class="invalid-tooltip">
                            @if($errors->has('bd_status'))
                            {{ $errors->first('bd_status') }}
                            @else
                            QA Status is required!
                            @endif
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                          <input type="text" class="form-control @if($errors->has('site_purpose')) is-invalid @endif" id="sitePurpose" name="site_purpose" placeholder="Please enter first name" value="{{ $franchise_application_bd_visit->site_purpose }}" required>
                          <label for="sitePurpose" class="form-label">Site Purpose</label>
                          <div class="invalid-tooltip">
                              @if($errors->has('site_purpose'))
                              {{ $errors->first('site_purpose') }}
                              @else
                              Purpose of visit is required!
                              @endif
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                        <select class="form-select @if($errors->has('forward_to')) is-invalid @endif" id="forwardTo" name="forward_to" aria-label="select" required>
                            <option value="">Please select a Forward To</option>
                            @foreach ($forward_to as $emp)
                                <option value="{{ $emp->id }}" {{ $franchise_application_bd_visit->forward_to == $emp->id ? 'selected' : '' }} >{{ $emp->user->name }}</option>
                            @endforeach
                        </select>
                        <label for="forwardTo" class="form-label">Forward To</label>
                        <div class="invalid-tooltip">
                            @if($errors->has('forward_to'))
                            {{ $errors->first('forward_to') }}
                            @else
                            QA Representative Type is required!
                            @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <div class="input-group">
                                <input type="text" class="form-control @if($errors->has('forwarded_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ $franchise_application_bd_visit->forwarded_date }}" name="forwarded_date" id="visitDate" required>
                                <label for="visitDate" class="form-label">Forwarded date</label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                  <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                  @if($errors->has('forwarded_date'))
                                  {{ $errors->first('forwarded_date') }}
                                  @else
                                  Forwarded date is required!
                                  @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-label-group in-border">
                        <select class="form-select @if($errors->has('approved_by')) is-invalid @endif" id="approvedBy" name="approved_by" aria-label="select" required>
                            <option value="">Please select a Approved By</option>
                            @foreach ($bd_reps as $bd_rep)
                                <option value="{{ $bd_rep->id }}" {{ $franchise_application_bd_visit->approved_by == $bd_rep->id ? 'selected' : '' }} >{{ $bd_rep->user->name }}</option>
                            @endforeach
                        </select>
                        <label for="approvedBy" class="form-label">Approved By</label>
                        <div class="invalid-tooltip">
                            @if($errors->has('approved_by'))
                            {{ $errors->first('approved_by') }}
                            @else
                            Approved By is required!
                            @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <div class="input-group">
                                <input type="text" class="form-control @if($errors->has('approval_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ $franchise_application_bd_visit->approval_date }}" name="approval_date" id="visitDate" required>
                                <label for="visitDate" class="form-label">Approval date</label>
                                <div class="input-group-text bg-primary border-primary text-white">
                                  <i class="ri-calendar-2-line"></i>
                                </div>
                                <div class="invalid-tooltip">
                                  @if($errors->has('approval_date'))
                                  {{ $errors->first('approval_date') }}
                                  @else
                                  Approval date is required!
                                  @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-label-group in-border">
                          <textarea class="form-control @if($errors->has('remarks')) is-invalid @endif" id="bdRemarks" name="remarks" rows="2" placeholder="Please enter your qa remarks" required>{{ $franchise_application_bd_visit->remarks }}</textarea>
                          <label for="bdRemarks" class="form-label">BD Remarks</label>
                          <div class="invalid-tooltip">
                              @if($errors->has('remarks'))
                              {{ $errors->first('remarks') }}
                              @else
                              BD Remarks is required!
                              @endif
                          </div>
                      </div>
                    </div>

                    <input type="hidden" name="franchise_application_id" value="{{ $franchise_application['id'] }}"  />

                    @csrf
                    @permission('update-franchise-application-bd')
                        <div class="col-12 text-end">
                          <button class="btn btn-primary" type="submit">Save Changes</button>
                          <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                        </div>
                    @endpermission
                </form>
            </div>
        </div>
    </div>
</div>
