<div class="accordion-item mt-3">
	<h2 class="accordion-header" id="accordionborderedOtherInformation">
		<button class="accordion-button {{ isset($franchise->other_informations) && empty($franchise->proposed_property_status)  ? '' : 'colapsed' }} " type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse3" aria-expanded="false" aria-controls="accor_borderedcollapse3" @if(!isset($franchise)) disabled @endif>
			Other information
		</button>
	</h2>
	<div id="accor_borderedcollapse3" class="accordion-collapse collapse {{ isset($franchise->other_informations) && empty($franchise->proposed_property_status)  ? 'show' : '' }}" aria-labelledby="accordionborderedOtherInformation" data-bs-parent="#accordionBordered" style="">
		<div class="accordion-body">
            @if(isset($franchise))
			<div class="table-responsive">
				<table class="table table-nowrap mb-0" id="otherInfoTable" style="width:100%">
					<thead class="table-light">
						<tr>
							<th scope="col" class="pb-3">Company Name</th>
							<th scope="col" class="pb-3">Designation</th>
							<th scope="col" class="pb-3">Experience (No. of years)</th>
							<th scope="col" class="pb-3">Personally Associated<br /><sub>(Education organisation)</sub></th>
							<th scope="col" class="pb-3">Remarks</th>
							<th scope="col" class="pb-3">Family Member Associated<br /><sub>(Education organisation)</sub></th>
							<th scope="col" class="pb-3">Remarks</th>
							<th scope="col">
								<div class="d-flex flex-wrap justify-content-end">
									<button type="button" class="btn btn-primary btn-label right rounded-pill other_info_modal " data-target="#otherInfoModal" data-inquiry_id = "{{ isset($franchise->id) ? $franchise->id : ''}}">
										<i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New &nbsp;
									</button>
								</div>
							</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
            @endif
		</div>
	</div>
</div>

@push('header_scripts')


@endpush

@push('footer_scripts')

<script type="text/javascript">

	$(document).ready(function() {
        $('#otherInfoTable').DataTable({
            processing: true,
            searching: false,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: true,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                searchPlaceholder: "Search..."},
            ajax: {
                url: "{{ route('load-other-information-data') }}",
                data: function ( d ) {
                    d.inquiry_id =  $('.other_info_modal').data('inquiry_id');
                },
                dataType: "json",
                method:'GET'
            },
            columns: [
                {data: 'company_name', name: 'company_name'},
                {data: 'designation', name: 'designation'},
                {data: 'experience', name: 'experience'},
                {data: 'personally_associated_with_org', name: 'personally_associated_with_org'},

                {data: 'personally_associated_info', name: 'personally_associated_info'},

                {data: 'family_member_associated_with_org', name: 'family_member_associated_with_org'},

                {data: 'family_associated_info', name: 'family_associated_info'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: "5%", sClass: 'text-center'},
            ],
        });


    });


	$(document).on('click', '.other_info_modal', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		var inquiry_id = $(this).data('inquiry_id');
		$("#otherInfoModal .modal-body #inquiry_id").val(inquiry_id);
		$('.message').html('');
		$('.message').removeClass('link-success');
		$('.message').removeClass('link-danger');
		$(target).modal('show');
	});


	$(document).on('click', '.edit_other_information', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		var id = $(this).data('id');
		var inquiry_id = $(this).data('inquiry_id');
		$.ajax({
            url: "{{ route('get-inquiry-other-information') }}?id="+id+"&inquiry_id="+inquiry_id,
            type: "GET",
            cache: false,
            success: function (data) {
            	$("#editOtherInfoModal .modal-body #id").val(data.id);
            	$("#editOtherInfoModal .modal-body #inquiry_id").val(data.inquiry_id);
            	$("#editOtherInfoModal .modal-body #companyName").val(data.company_name);
            	$("#editOtherInfoModal .modal-body #designation").val(data.designation);
            	$("#editOtherInfoModal .modal-body #experience").val(data.experience);
            	$("#editOtherInfoModal .modal-body #personallyAssociatedInfo").val(data.personally_associated_info);
            	$("input[name=personally_associated_with_org][value='"+data.personally_associated_with_org+"']").prop("checked",true);
            	$("#editOtherInfoModal .modal-body #familyAssociatedInfo").val(data.family_associated_info);
            	$("input[name=family_member_associated_with_org][value='"+data.family_member_associated_with_org+"']").prop("checked",true);
            	$(target).modal('show');

            },
            error: function () {},
            beforeSend: function () {},
            complete: function () {}
        });

	});

	$(document).on('click', '.remove_other_information', function(e) {
		e.preventDefault();
		var inquiry_id = $(this).data('inquiry_id');
		console.log(inquiry_id);

	});

	 $(document).on('hidden.bs.modal','#otherInfoModal, #editOtherInfoModal', function () {
        $('#otherInfoTable').DataTable().ajax.reload(null, false);
    });

</script>

@endpush


