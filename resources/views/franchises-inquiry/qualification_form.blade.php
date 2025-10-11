<div class="tab-pane  active" id="qualification-overview" role="tabpanel">
	<div class="table-responsive">
		<table class="table table-nowrap mb-0" id="qualificationTable" style="width:100%">
			<thead class="table-light">
				<tr>
					<th scope="col" class="pb-3">Qualification</th>
					<th scope="col" class="pb-3">Year of Passing</th>
					<th scope="col" class="pb-3">Institute</th>
					<th scope="col">
						<div class="d-flex flex-wrap justify-content-end">
							<button type="button" class="btn btn-primary btn-label right rounded-pill qualification_info_modal " data-target="#qualificationModal" data-inquiry_id = "{{ isset($franchise->id) ? $franchise->id : ''}}">
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

@push('header_scripts')


@endpush

@push('footer_scripts')

<script type="text/javascript">

	$(document).ready(function() {

        $('#qualificationTable').DataTable({
            processing: true,
            searching: false,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            ordering: false,
            pageLength: 4,
            scrollX: true,
            language: {search: "",
                processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                searchPlaceholder: "Search..."},
            ajax: {
                url: "{{ route('load-qualification-data') }}",
                data: function ( d ) {
                    d.inquiry_id =  $('.qualification_info_modal').data('inquiry_id');
                },
                dataType: "json",
                method:'GET'
            },
            columns: [
                {data: 'qualification', name: 'qualification'},
                {data: 'passing_year', name: 'passing_year'},
                {data: 'institute', name: 'institute'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: "5%", sClass: 'text-center'},
            ],
        });


    });


	$(document).on('click', '.qualification_info_modal', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		var inquiry_id = $(this).data('inquiry_id');
		$("#qualificationModal .modal-body #inquiry_id").val(inquiry_id);
		$('.message').html('');
		$('.message').removeClass('link-success');
		$('.message').removeClass('link-danger');
		$(target).modal('show');
	});


	$(document).on('click', '.edit_qualification', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		var id = $(this).data('id');
		var inquiry_id = $(this).data('inquiry_id');
		$.ajax({
            url: "{{ route('get-inquiry-qualification') }}?id="+id+"&inquiry_id="+inquiry_id,
            type: "GET",
            cache: false,
            success: function (data) {
            	$("#editQualificationModal .modal-body #id").val(data.id);
            	$("#editQualificationModal .modal-body #inquiry_id").val(data.inquiry_id);
            	$("#editQualificationModal .modal-body #qualification").val(data.qualification);
            	$("#editQualificationModal .modal-body #passingYear").val(data.passing_year);
            	$("#editQualificationModal .modal-body #instituteName").val(data.institute);
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

	 $(document).on('hidden.bs.modal','#qualificationModal, #editQualificationModal', function () {
        $('#qualificationTable').DataTable().ajax.reload(null, false);
    });



</script>

@endpush
