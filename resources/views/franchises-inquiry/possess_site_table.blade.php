<li class="list-group-item">
	<div class="card">
		<div class="card-light card-header">
			<div class="d-flex align-items-center">
				<div class="flex-grow-1">
					<h6 class="card-title mb-0">if you already possess a site, please give details of he site</h6>
				</div>
			</div>
		</div>
		<div class="card-body collapse show">
			<div class="table-responsive">
				<table class="table table-nowrap mb-0" id="possessSiteTable" style="width:100%">
					<thead class="table-light">
						<tr>
							<th scope="col" class="pb-3">Ownership</th>
							<th scope="col" class="pb-3">Lease/Rental</th>
							<th scope="col" class="pb-3">From Date</th>
							<th scope="col" class="pb-3">To Date</th>
							<th scope="col" class="pb-3">Total Area</th>
							<th scope="col" class="pb-3">Tiled/Carpet Area</th>
							<th scope="col" class="pb-3">Location</th>

							<th scope="col">
								<div class="d-flex flex-wrap justify-content-end">
									<button type="button" class="btn btn-primary btn-label right rounded-pill possess_site_modal " data-target="#desiredFranchiseInfoModal" data-inquiry_id = "{{ isset($franchise->id) ? $franchise->id : ''}}">
										<i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New &nbsp;
									</button>
								</div>
							</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</li>

@push('header_scripts')


@endpush

@push('footer_scripts')

<script type="text/javascript">

	$(document).ready(function() {
		$('#possessSiteTable').DataTable({
			processing: true,
			searching: false,
			serverSide: true,
			responsive: true,
			bLengthChange: false,
			ordering: true,
			pageLength: 10,
			scrollX: true,
			language: {search: "",
                processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                searchPlaceholder: "Search..."},
			ajax: {
				url: "{{ route('load-posses-site-data') }}",
				data: function ( d ) {
					d.inquiry_id =  $('.possess_site_modal').data('inquiry_id');
				},
				dataType: "json",
				method:'GET'
			},
			columns: [
			{data: 'ownership', name: 'ownership'},
			{data: 'lease_rental', name: 'lease_rental'},
			{data: 'from_date', name: 'from_date'},
			{data: 'to_date', name: 'to_date'},
			{data: 'total_area', name: 'total_area'},
			{data: 'tile_carpet', name: 'tile_carpet'},
			{data: 'location', name: 'location'},
			{data: 'action', name: 'action', orderable: false, searchable: false, width: "5%", sClass: 'text-center'},
			],
		});


	});


	$(document).on('click', '.possess_site_modal', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		var inquiry_id = $(this).data('inquiry_id');
		$("#desiredFranchiseInfoModal .modal-body #inquiry_id").val(inquiry_id);
		$('.message').html('');
		$('.message').removeClass('link-success');
		$('.message').removeClass('link-danger');
		$(target).modal('show');
	});


	$(document).on('click', '.edit_possess_site', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		var id = $(this).data('id');
		var inquiry_id = $(this).data('inquiry_id');
		$.ajax({
			url: "{{ route('get-possess-site-data') }}?id="+id+"&inquiry_id="+inquiry_id,
			type: "GET",
			cache: false,
			success: function (data) {
				$("#editDesiredFranchiseInfoModal .modal-body #id").val(data.id);
				$("#editDesiredFranchiseInfoModal .modal-body #inquiry_id").val(data.inquiry_id);
				$('#editDesiredFranchiseInfoModal .modal-body input:radio[name="ownership"][id='+ data.ownership +']').prop('checked', true);
				$('#editDesiredFranchiseInfoModal .modal-body input:radio[name="lease_rental"][id='+ data.lease_rental +']').prop('checked', true);
				$("#editDesiredFranchiseInfoModal .modal-body #from_date").val(data.from_date);
				$("#editDesiredFranchiseInfoModal .modal-body #to_date").val(data.to_date)

				$("#editDesiredFranchiseInfoModal .modal-body #total_area").val(data.total_area);
				;
				$("#editDesiredFranchiseInfoModal .modal-body #tileCarpet").val(data.tile_carpet);
				;
				$('#editDesiredFranchiseInfoModal .modal-body input:radio[name="location"][id='+ data.location +']').prop('checked', true);
				$(target).modal('show');

			},
			error: function () {},
			beforeSend: function () {
				$('.message').html('');
				$('.message').removeClass('link-success');
				$('.message').removeClass('link-danger');
			},
			complete: function () {}
		});

	});

	$(document).on('click', '.remove_qualification', function(e) {
		e.preventDefault();
		var inquiry_id = $(this).data('inquiry_id');
		console.log(inquiry_id);

	});

	$(document).on('hidden.bs.modal','#desiredFranchiseInfoModal, #editDesiredFranchiseInfoModal', function () {
		$('#possessSiteTable').DataTable().ajax.reload(null, false);
	});

</script>

@endpush
