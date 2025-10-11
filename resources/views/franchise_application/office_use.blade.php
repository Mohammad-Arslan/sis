
<div class="accordion-item accordion-fill-primary mt-3">
	<h2 class="accordion-header" id="officeUseOnly">
		<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse5" aria-expanded="true" aria-controls="accor_borderedcollapse5" @if(!isset($franchise)) disabled @endif>
			Application Status
		</button>
	</h2>
	<div id="accor_borderedcollapse5" class="accordion-collapse collapse" aria-labelledby="officeUseOnly" data-bs-parent="#accordionBorderedInquirerPersonalInfo" style="">
		<div class="accordion-body">
            @if(isset($franchise))
			<div class="row align-items-center mb-3">
				<div class="col-auto">
					<p class="m-0"><strong>Status:</strong></p>
				</div>
				<div class="col-md-8 col-sm-12">
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="approved" value="A" {{ isset($franchise->status) && $franchise->status == 'A'  ? 'checked' : '' }}>
						<label class="form-check-label" for="approved">Approved</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="pending" value="P" {{ isset($franchise->status) && $franchise->status == 'P'  ? 'checked' : '' }}  >
						<label class="form-check-label" for="pending">Pending</label>
					</div>
                    <div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="cancelled" value="C" {{ isset($franchise->status) && $franchise->status == 'C'  ? 'checked' : '' }}>
						<label class="form-check-label" for="cancelled">Cancelled</label>
					</div>
					{{-- <div class="form-check mb-3 form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="rejected" value="R" {{ isset($franchise->status) && $franchise->status == 'R'  ? 'checked' : '' }}>
						<label class="form-check-label" for="rejected">Rejected</label>
					</div> --}}
				</div>
			</div>

			<div class="row align-items-center mb-3">
				<div class="col-auto">
					<p class="m-0"><strong>Agreement Type:</strong></p>
				</div>
				<div class="col-md-8 col-sm-12">
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="agreement_type" id="LOI" value="LOI" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'LOI'  ? 'checked' : '' }}  onclick="gototype('loi')">
						<label class="form-check-label" for="LOI">LOI</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="agreement_type" id="MOU" value="MOU" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'MOU'  ? 'checked' : '' }} onclick="gototype('mou')">
						<label class="form-check-label" for="MOU">MOU</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="agreement_type" id="FA" value="FA" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'FA'  ? 'checked' : '' }} onclick="gototype('fa')">
						<label class="form-check-label" for="FA">FA</label>
					</div>
					<div class="form-check mb-2 form-check-inline">
						<input class="form-check-input" type="radio" name="agreement_type" id="APP" value="APP" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'APP'  ? 'checked' : '' }} onclick="gototype('app')">
						<label class="form-check-label" for="APP">Applied</label>
					</div>
				</div>
			</div>

            <div class="all row">

                <div class="loi col-md-3 col-sm-12 mt-3"  @if($franchise->agreement_type == 'LOI') style="display: block;" @else style="display: none;" @endif>
                    <div class="form-label-group in-border">
                        <input type="number" class="form-control" name="loi_token_amount" id="loi_token_amount" value="{{$franchise->loi_token_amount}}" >
                        <label for="loi_token_amount" class="form-label">Token Amount *</label>
                        {{-- <div class="invalid-tooltip">
                            Token amount is required!
                        </div> --}}
                    </div>
                </div>

                <div class="lm col-md-3 col-sm-12 mt-3" @if($franchise->agreement_type == 'MOU' || $franchise->agreement_type == 'LOI') style="display: block;" @else style="display: none;" @endif>
                    <div class="input-group date form-label-group in-border">
                        <input type="text" class="form-control" data-provider="flatpickr" name="execution_date" id="execution_date" value="{{ isset($franchise->execution_date) ? $franchise->execution_date : old('execution_date') }}" required>
                        <div class="input-group-text bg-primary border-primary text-white">
                            <i class="ri-calendar-2-line"></i>
                        </div>
                        <label for="execution_date" class="form-label">Execution Date</label>
                        <div class="invalid-tooltip">
                            Execution Date is required!
                        </div>
                    </div>
                </div>
                <div class="lm col-md-3 col-sm-12 mt-3" @if($franchise->agreement_type == 'MOU' || $franchise->agreement_type == 'LOI') style="display: block;" @else style="display: none;" @endif>
                    <div class="input-group date form-label-group in-border">
                        <input type="text" class="form-control" data-provider="flatpickr" name="cut_off_date" id="cut_off_date" value="{{ isset($franchise->cut_off_date) ? $franchise->cut_off_date : old('cut_off_date') }}">
                        <div class="input-group-text bg-primary border-primary text-white">
                            <i class="ri-calendar-2-line"></i>
                        </div>
                        <label for="cut_off_date" class="form-label">Cut Off Date</label>
                        {{-- <div class="invalid-tooltip">
                            Cut Off Date is required!
                        </div> --}}
                    </div>
                </div>
                <div class="lm col-md-3 col-sm-12 mt-3" @if($franchise->agreement_type == 'MOU' || $franchise->agreement_type == 'LOI') style="display: block;" @else style="display: none;" @endif>
                    <div class="input-group date form-label-group in-border">
                        <input type="text" class="form-control" data-provider="flatpickr" name="extension_date" id="extension_date" value="{{ isset($franchise->extension_date) ? $franchise->extension_date : old('extension_date') }}">
                        <div class="input-group-text bg-primary border-primary text-white">
                            <i class="ri-calendar-2-line"></i>
                        </div>
                        <label for="extension_date" class="form-label">Extension Date </label>
                    </div>
                </div>
            </div>


			<div class="row">
				{{--<div class="col-md-12 col-sm-12  mt-3">
					<label class="form-check-label" for="purposeBuildCampus">
						If Approved:&nbsp;&nbsp;&nbsp;
					</label>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="agreement_type" id="loi" value="loi" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'loi'  ? 'checked' : '' }}>
						<label class="form-check-label" for="loi">LOI</label>
					</div>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="agreement_type" id="mou" value="mou" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'mou'  ? 'checked' : '' }}>
						<label class="form-check-label" for="mou">MOU</label>
					</div>
					<div class="form-check mb-2 form-check-inline ">
						<input class="form-check-input" type="radio" name="agreement_type" id="fa" value="fa" {{ isset($franchise->agreement_type) && $franchise->agreement_type == 'fa'  ? 'checked' : '' }}>
						<label class="form-check-label" for="fa">FA</label>
					</div>
				</div>--}}

				@if(isset($users))
				<div class="col-md-4 col-sm-12 mt-3">
					<div class="form-label-group in-border">
						<select class="form-select" id="recommendedBy" name="recommended_by" aria-label="Recommended user select" required>
							<option value="">Please select</option>
							@foreach ($recommended_by as $arr)
                                @if($franchise->recommended_by == $arr['user']['id'] && ($arr['left_date'] != '' || $arr['left_date'] == '' ))
                                    <option value="{{ $arr['user']['id'] }}" selected='selected'>{{ $arr['user']['name'] }}</option>
                                @elseif($franchise->recommended_by != $arr['user']['id'] && $arr['left_date'] == '' )
                                    <option value="{{ $arr['user']['id'] }}">{{ $arr['user']['name'] }}</option>
                                @endif
                            @endforeach
						</select>
						<label for="recommendedBy" class="form-label">Recommended by</label>
						<div class="invalid-tooltip">
							Recommended by is required!
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-12 mt-3">
					<div class="form-label-group in-border">
						<select class="form-select" id="approvedBy" name="approved_by" aria-label="Approved user select" required>
							<option value="">Please select</option>
							@foreach ($approved_by as $arr)
                                @if($franchise->approved_by == $arr['user']['id'] && ($arr['left_date'] != '' || $arr['left_date'] == '' ))
                                    <option value="{{ $arr['user']['id'] }}" selected='selected'>{{ $arr['user']['name'] }}</option>
                                @elseif($franchise->approved_by != $arr['user']['id'] && $arr['left_date'] == '' )
                                    <option value="{{ $arr['user']['id'] }}">{{ $arr['user']['name'] }}</option>
                                @endif

							@endforeach
						</select>
						<label for="approvedBy" class="form-label">Approved by</label>
						<div class="invalid-tooltip">
							Approved by is required!
						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 mt-3">
                    <div class="form-label-group in-border">
                        <textarea class="form-control" name="application_status_remarks" id="application_status_remarks" placeholder="Write Here...">{{ $franchise->application_status_remarks }}</textarea>
                        <label class="form-label">Remarks</label>
                    </div>
				</div>

				<!-- <div class="col-md-4 col-sm-12 mt-3">
					<div class="form-label-group in-border">
						<select class="form-select" id="forwardedBy" name="forwarded_by" aria-label="Forwarded user select" required>
							<option value="">Please select</option>
							@foreach ($users as $user)
							<option value="{{ $user->id }}" {{ $franchise->forwarded_by == $user->id ? 'selected' : '' }} >{{ $user->name }}</option>
							@endforeach
						</select>
						<label for="forwardedBy" class="form-label">Forwarded by</label>
						<div class="invalid-tooltip">
							Forwarded by is required!
						</div>
					</div>
				</div> -->
				@endif
			</div>
			<div class="row mt-3">
				<div class="col-12 text-end">
					<button class="btn btn-primary" type="submit" id="officeFormId" form="officeUseForm">Submit form</button>
					<a href="{{ url('franchises') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
				</div>
			</div>
            @endif
		</div>
	</div>
</div>

@push('header_scripts')


@endpush

@push('footer_scripts')
<script>
    function gototype(type){
        if(type == 'loi')
        {
            $('.all').show();
            $('.loi').show();
            $('.lm').show();
        }
        else if(type == 'mou')
        {
            $('.all').show();
            $('.lm').show();
            $('.loi').hide();
        }
        else if(type == 'fa' || type == 'app')
        {
            $('.loi').hide();
            $('.all').hide();
            $('.lm').hide();
        }

    }
    $(document).ready(function() {
        $(document).on("click","#officeFormId",function() {
            //var execution_date = $("input[name='execution_date']").val();
            //var cut_off_date = $("input[name='cut_off_date']").val();
            // if (execution_date != undefined && execution_date.length == 0) {
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Oops...',
            //         text: 'Execution date is required!',
            //         footer: '<a href="javascript:void(0);">Please fill the execution date</a>'
            //     })
            //     return false;
            // }
            // if (cut_off_date != undefined && cut_off_date.length == 0) {
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Oops...',
            //         text: 'Cut off date is required!',
            //         footer: '<a href="javascript:void(0);">Please fill the cut off date</a>'
            //     })
            //     return false;
            // }
        })
    });
</script>
@endpush
