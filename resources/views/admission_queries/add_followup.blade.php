<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create New Follow Up</h4>
            <!-- <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                </div>
            </div> -->
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('admissionFollowUp.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="followup_type_id" name="followup_type_id">
                                <option value="">Please select</option>
                                @foreach ($followup_types as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['follow_up_type'] }}</option>
                                @endforeach
                            </select>
                            <label class="form-label">Lead Status <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('followup_type_id'))
                                {{ $errors->first('followup_type_id') }}
                                @else
                                follow up type  is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" data-deafult-date="" value="{{ old('next_follow_up_date') }}" name="next_follow_up_date" id="next_follow_up_date">
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="next_follow_up_date" class="form-label">Next Follow Up</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-label-group in-border">
                            <textarea name="remarks" class="form-control @if($errors->has('remarks')) is-invalid @endif" placeholder="Remarks" rows="5" id="remarks"></textarea>
                            <label class="form-label">Remarks <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('remarks'))
                                {{ $errors->first('remarks') }}
                                @else
                                Remarks is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <input type="hidden" class="form-control" name="admission_query_id" id="admission_query_id" value="{{request('admission_query_id')}}">
                        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{auth()->user()->id}}">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
