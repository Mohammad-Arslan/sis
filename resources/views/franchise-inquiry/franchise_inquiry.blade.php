@if(auth()->check())
    @extends('layouts.master')
    @section('content')
        @include('components.flash_message')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Apply for Franchise</h4>
                        <a href="{{route('franchises-inquiry.index')}}" class="btn btn-primary btn-sm pull-right">Franchise Inquires</a>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <form class="needs-validation" novalidate action="{{ isset($franchise_inquiry) ? route('franchises-inquiry.update',$franchise_inquiry['id']) : route('franchises-inquiry.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if(isset($franchise_inquiry))
                                @method('Patch')
                            @endif

                            <div class="row mb-3">
                                <h5 class="text-muted d-flex align-items-center mt-2">Personal Information</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('full_name')) is-invalid @endif" id="full_name" placeholder="Full Name" name="full_name" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->full_name : old('full_name') }}" required>
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('full_name'))
                                                {{ $errors->first('full_name') }}
                                            @else
                                                Full name is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('CNIC')) is-invalid @endif" id="CNIC" name="CNIC" placeholder="11111-1111111-1" maxlength="15" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->CNIC : old('CNIC') }}" required>
                                        <label for="CNIC" class="form-label">CNIC</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('CNIC'))
                                                {{ $errors->first('CNIC') }}
                                            @else
                                                CNIC is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('email')) is-invalid @endif" id="email" name="email" placeholder="example@email.com" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->email : old('email') }}" required>
                                        <label for="email" class="form-label">Email</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('email'))
                                                {{ $errors->first('email') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <textarea class="form-control @if($errors->has('address')) is-invalid @endif" id="address" name="address" placeholder="Enter Address" required>{{ isset($franchise_inquiry) ? $franchise_inquiry->address : old('address') }}</textarea>
                                        <label for="address" class="form-label">Address</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('address'))
                                                {{ $errors->first('address') }}
                                            @else
                                                Address is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select @if($errors->has('city_id')) is-invalid @endif" id="city_id" name="city_id" required>
                                            <option value="">Please select</option>
                                            @foreach($cities as $city)
                                                <option value="{{$city['id']}}" {{isset($franchise_inquiry) && $franchise_inquiry['city_id'] == $city['id'] ? 'selected' : ''}}>{{$city['city_name']}}</option>
                                            @endforeach
                                        </select>
                                        <label for="cityId" class="form-label">City</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('city_id'))
                                                {{ $errors->first('city_id') }}
                                            @else
                                                City is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('contact_no_1')) is-invalid @endif" id="contact_no_1" name="contact_no_1" placeholder="Mobile No (03001234567)" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->contact_no_1 : old('contact_no_1') }}" required maxlength="13">
                                        <label for="contact_no_1" class="form-label">Contact No. 1</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('contact_no_1'))
                                                {{ $errors->first('contact_no_1') }}
                                            @else
                                                Contact no 1 is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('contact_no_2')) is-invalid @endif" id="contact_no_2" name="contact_no_2" placeholder="Mobile No (03001234567)" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->contact_no_2 : old('contact_no_2') }}" maxlength="13">
                                        <label for="contact_no_2" class="form-label">Contact No. 2</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('contact_no_2'))
                                                {{ $errors->first('contact_no_2') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="border mt-3 border-dashed"></div>
                                <h5 class="text-muted mt-3 d-flex align-items-center">Other Information</h5>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('current_occupation')) is-invalid @endif" id="current_occupation" name="current_occupation" placeholder="Current Business/Occupation" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->current_occupation : old('current_occupation') }}" maxlength="13" required>
                                        <label class="form-label">Current Business/Occupation</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('current_occupation'))
                                                {{ $errors->first('current_occupation') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-check-label" for="purposeBuildCampus">
                                        Have you led a franchise? If yes, please mention the name:&nbsp;&nbsp;&nbsp;
                                    </label>
                                    <div class="form-check mb-2 form-check-inline">
                                        <input class="form-check-input led_franchise" type="radio" name="led_franchise" id="led_franchise_yes" value="yes" {{ isset($franchise_inquiry->led_franchise) && $franchise_inquiry->led_franchise == 'yes' ? 'checked' : '' }} required>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check mb-2 form-check-inline">
                                        <input class="form-check-input led_franchise @if($errors->has('led_franchise')) is-invalid @endif" type="radio" name="led_franchise" id="led_franchise_no" value="no" {{ isset($franchise_inquiry->led_franchise) && $franchise_inquiry->led_franchise == 'no'  ? 'checked' : '' }} required>
                                        <label class="form-check-label">No</label>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if($errors->has('led_franchise'))
                                            {{ $errors->first('led_franchise') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('franchise_name')) is-invalid @endif" id="franchise_name" name="franchise_name" placeholder="Franchise Name" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->franchise_name : old('franchise_name') }}" maxlength="13">
                                        <label for="franchise_name" class="form-label">Franchise Name</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('franchise_name'))
                                                {{ $errors->first('franchise_name') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-check-label">
                                        Are you interested in :&nbsp;&nbsp;&nbsp;
                                    </label>
                                    <div class="form-check mb-2 form-check-inline ">
                                        <input class="form-check-input" type="radio" name="franchise_interest" id="male" value="new_franchise" {{ isset($franchise_inquiry->franchise_interest) && $franchise_inquiry->franchise_interest == 'new_franchise'  ? 'checked' : '' }} required>
                                        <label class="form-check-label">Opening a new franchise</label>
                                    </div>
                                    <div class="form-check mb-2 form-check-inline">
                                        <input class="form-check-input @if($errors->has('new_franchise')) is-invalid @endif" type="radio" name="franchise_interest" id="female" value="convert_existing" {{ isset($franchise_inquiry->franchise_interest) && $franchise_inquiry->franchise_interest == 'convert_existing'  ? 'checked' : '' }} required>
                                        <label class="form-check-label">Converting an existing school/college</label>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if($errors->has('new_franchise'))
                                            {{ $errors->first('new_franchise') }}
                                        @else
                                            Select the option
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('area_location')) is-invalid @endif" id="area_location" name="area_location" placeholder="Area/Location" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->area_location : old('area_location') }}" maxlength="13" required>
                                        <label class="form-label">Area/Location of interest for UCS franchise</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('area_location'))
                                                {{ $errors->first('area_location') }}
                                            @else
                                                Area/Location is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div class="form-label-group in-border">
                                        <select class="load-select form-select @if($errors->has('source_id')) is-invalid @endif" id="source_id" name="source_id" required>
                                            <option value="">Select</option>
                                            @foreach($sources as $source)
                                                <option value="{{$source['id']}}" {{isset($franchise_inquiry) && $franchise_inquiry['source_id'] == $source['id'] ? 'selected' : ''}}>{{$source['source_name']}}</option>
                                            @endforeach
                                        </select>
                                        <label for="cityId" class="form-label">Where did you hear about us</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('source_id'))
                                                {{ $errors->first('source_id') }}
                                            @else
                                                Source is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="border mt-3 border-dashed"></div>
                                <h5 class="text-muted mt-3 d-flex align-items-center">Post Inquiry Information</h5>
                            </div>

                            <div class="row">

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('call_center_agent')) is-invalid @endif" id="call_center_agent" name="call_center_agent" placeholder="Call Center Agent" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->call_center_agent : old('call_center_agent') }}">
                                        <label for="firstName" class="form-label">Call Center Agent</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('call_center_agent'))
                                                {{ $errors->first('call_center_agent') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <select class="form-control @if($errors->has('inquiry_status')) is-invalid @endif" id="inquiry_status" name="inquiry_status">
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "" ? 'selected' : '') : (old('inquiry_status') == "" ? 'selected' : '') }} value="">Please Select</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "email_sent" ? 'selected' : '') : (old('inquiry_status') == "email_sent" ? 'selected' : '') }} value="email_sent">Email Sent</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "bd_no_answer" ? 'selected' : '') : (old('inquiry_status') == "bd_no_answer" ? 'selected' : '') }} value="bd_no_answer">BD - No Answer / Powered Off</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "approved_by_qa" ? 'selected' : '') : (old('inquiry_status') == "approved_by_qa" ? 'selected' : '') }} value="approved_by_qa">Approved by QA</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "meeting_done_with_bd" ? 'selected' : '') : (old('inquiry_status') == "meeting_done_with_bd" ? 'selected' : '') }} value="meeting_done_with_bd">Meeting Done with BD</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "forwarded_to_qa" ? 'selected' : '') : (old('inquiry_status') == "forwarded_to_qa" ? 'selected' : '') }} value="forwarded_to_qa">Forwarded to QA</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "franchise_confirm" ? 'selected' : '') : (old('inquiry_status') == "franchise_confirm" ? 'selected' : '') }} value="franchise_confirm">Franchise Confirm</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "meeting_schedule_with_bd" ? 'selected' : '') : (old('inquiry_status') == "meeting_schedule_with_bd" ? 'selected' : '') }} value="meeting_schedule_with_bd">Meeting Schedule with BD</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "called_back_no_reply" ? 'selected' : '') : (old('inquiry_status') == "called_back_no_reply" ? 'selected' : '') }} value="called_back_no_reply">Called Back-No Reply</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "discussion_required_with_bd" ? 'selected' : '') : (old('inquiry_status') == "discussion_required_with_bd" ? 'selected' : '') }} value="discussion_required_with_bd">Discussion Required With Bd</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "whatsapp_required" ? 'selected' : '') : (old('inquiry_status') == "whatsapp_required" ? 'selected' : '') }} value="whatsapp_required">WhatsApp Required</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "duplicate_entry" ? 'selected' : '') : (old('inquiry_status') == "duplicate_entry" ? 'selected' : '') }} value="duplicate_entry">Duplicate Entry</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "called_back" ? 'selected' : '') : (old('inquiry_status') == "called_back" ? 'selected' : '') }} value="called_back">Called Back</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "not_interested" ? 'selected' : '') : (old('inquiry_status') == "not_interested" ? 'selected' : '') }} value="not_interested">Not Interested</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "not_eligible" ? 'selected' : '') : (old('inquiry_status') == "not_eligible" ? 'selected' : '') }} value="not_eligible">Not Eligible</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "bd_for_information_only" ? 'selected' : '') : (old('inquiry_status') == "bd_for_information_only" ? 'selected' : '') }} value="bd_for_information_only">BD - For Information Only</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "invalid" ? 'selected' : '') : (old('inquiry_status') == "invalid" ? 'selected' : '') }} value="invalid">Invalid #</option>
                                            <option {{ isset($franchise_inquiry) ? ($franchise_inquiry->inquiry_status == "email_required" ? 'selected' : '') : (old('inquiry_status') == "email_required" ? 'selected' : '') }} value="email_required">Email Required</option>
                                        </select>
                                        <label for="firstName" class="form-label">Inquiry Status</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('inquiry_status'))
                                                {{ $errors->first('inquiry_status') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <div class="input-group">
                                            <input type="text" class="form-control @if($errors->has('meeting_with_bd')) is-invalid @endif" data-provider="flatpickr" data-date-format="d-m-Y" data-enable-time value="{{ isset($franchise_inquiry) ? $franchise_inquiry->meeting_with_bd : old('meeting_with_bd') }}" name="meeting_with_bd" id="meeting_with_bd" required>
                                            <label for="meeting_with_bd" class="form-label">Meeting with BD (Date/Time)<span class="text-danger">*</span></label>
                                            <div class="input-group-text bg-primary border-primary text-white">
                                                <i class="ri-calendar-2-line"></i>
                                            </div>
                                            <div class="invalid-tooltip">
                                                @if($errors->has('meeting_with_bd'))
                                                    {{ $errors->first('meeting_with_bd') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <div class="input-group">
                                            <input type="text" class="form-control @if($errors->has('call_back')) is-invalid @endif" data-provider="flatpickr" data-date-format="d-m-Y" data-enable-time value="{{ isset($franchise_inquiry) ? $franchise_inquiry->call_back : old('call_back') }}" name="call_back" id="call_back" required>
                                            <label for="call_back" class="form-label">Call Back (Date/Time)<span class="text-danger">*</span></label>
                                            <div class="input-group-text bg-primary border-primary text-white">
                                                <i class="ri-calendar-2-line"></i>
                                            </div>
                                            <div class="invalid-tooltip">
                                                @if($errors->has('call_back'))
                                                    {{ $errors->first('call_back') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('experience')) is-invalid @endif" id="experience" name="experience" placeholder="Experience" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->experience : old('experience') }}">
                                        <label for="experience" class="form-label">Experience/Portfolio</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('experience'))
                                                {{ $errors->first('experience') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('launching_year')) is-invalid @endif" id="launching_year" name="launching_year" placeholder="Launching Year" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->launching_year : old('launching_year') }}">
                                        <label for="launching_year" class="form-label">Expected Launching Year/Session</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('launching_year'))
                                                {{ $errors->first('launching_year') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('land_area')) is-invalid @endif" id="land_area" name="land_area" placeholder="Land Area" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->land_area : old('land_area') }}">
                                        <label for="land_area" class="form-label">Land Area</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('land_area'))
                                                {{ $errors->first('land_area') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <input type="text" class="form-control @if($errors->has('covered_area')) is-invalid @endif" id="covered_area" name="covered_area" placeholder="Covered Area" value="{{ isset($franchise_inquiry) ? $franchise_inquiry->covered_area : old('covered_area') }}">
                                        <label for="covered_area" class="form-label">Covered Area</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('covered_area'))
                                                {{ $errors->first('covered_area') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <textarea type="text" class="form-control @if($errors->has('general_remarks')) is-invalid @endif" id="general_remarks" name="general_remarks" placeholder="Enter Remarks">{{ isset($franchise_inquiry) ? $franchise_inquiry->general_remarks : old('general_remarks') }}</textarea>
                                        <label for="general_remarks" class="form-label">General Remarks</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('general_remarks'))
                                                {{ $errors->first('general_remarks') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <textarea type="text" class="form-control @if($errors->has('inquiry_remarks')) is-invalid @endif" id="inquiry_remarks" name="inquiry_remarks" placeholder="Enter Inquiry Remarks">{{ isset($franchise_inquiry) ? $franchise_inquiry->inquiry_remarks : old('inquiry_remarks') }}</textarea>
                                        <label for="inquiry_remarks" class="form-label">Inquiry Remarks</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('inquiry_remarks'))
                                                {{ $errors->first('inquiry_remarks') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12  mt-3">
                                    <div class="form-label-group in-border">
                                        <textarea type="text" class="form-control @if($errors->has('meeting_remarks')) is-invalid @endif" id="meeting_remarks" name="meeting_remarks" placeholder="Enter Meeting Remarks" >{{ isset($franchise_inquiry) ? $franchise_inquiry->meeting_remarks : old('meeting_remarks') }}</textarea>
                                        <label for="meeting_remarks" class="form-label">Meeting Remarks</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('meeting_remarks'))
                                                {{ $errors->first('meeting_remarks') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div class="form-label-group in-border">
                                        <textarea class="form-control @if($errors->has('expected_franchise_address')) is-invalid @endif" id="expected_franchise_address" name="expected_franchise_address" placeholder="Enter Address">{{ isset($franchise_inquiry) ? $franchise_inquiry->expected_franchise_address : old('expected_franchise_address') }}</textarea>
                                        <label for="expected_franchise_address" class="form-label">Expected Franchise Address</label>
                                        <div class="invalid-tooltip">
                                            @if($errors->has('expected_franchise_address'))
                                                {{ $errors->first('expected_franchise_address') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-12 text-end mt-3">
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('footer_scripts')
        <script type="text/javascript">
            $(document).on('change', '.led_franchise', function (e){
                let led_franchise_value = $(this).val();
                if (led_franchise_value == 'yes')
                    $('#franchise_name').prop('required',true);
                else
                    $('#franchise_name').prop('required',false);
            });

        </script>
    @endpush
@else
    @include('franchise-inquiry.campaign_inquiry')
@endif
