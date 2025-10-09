<div class="table-responsive service-section @if((isset($franchise->current_occupation) && $franchise->current_occupation == 'service') || (isset($franchise->current_occupation) && $franchise->current_occupation == 'both')) show @elseif(!isset($franchise)) show @else hide-section @endif">
    <table class="table table-bordered table-nowrap mb-0">
        <tbody>
        <tr>
            <th class="text-nowrap" scope="row">Name of current employer</th>
            <td><div class="form-label-group in-border">
                    <input type="text" class="form-control" id="currentEmployer" name="current_employer" placeholder="current_employer" value="{{ isset($franchise) ? $franchise->current_employer : old('current_employer') }}">
                    <label for="currentEmployer" class="form-label"> Current Employer</label>
                </div></td>
        </tr>
        <tr>
            <th class="text-nowrap" scope="row">Designation</th>
            <td><div class="form-label-group in-border">
                    <input type="text" class="form-control" id="lastDesignation" name="last_designation" placeholder="last_designation" value="{{ isset($franchise) ? $franchise->last_designation : old('last_designation') }}">
                    <label for="lastDesignation" class="form-label"> Designation</label>
                </div></td>
        </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive mt-3 service-section @if((isset($franchise->current_occupation) && $franchise->current_occupation == 'service') || (isset($franchise->current_occupation) && $franchise->current_occupation == 'both')) show @elseif(!isset($franchise)) show @else hide-section @endif">
    <table class="table table-nowrap mb-0" id="serviceOccupationTable" style="width:100%">
        <thead class="table-light">
        <tr>

            <th scope="col" class="pb-3">Organisation</th>
            <th scope="col" class="pb-3">Designation</th>
            <th scope="col" class="pb-3">Responsibilities</th>
            <th scope="col" class="pb-3">From Date</th>
            <th scope="col" class="pb-3">To Date</th>
            <th scope="col">
                <div class="d-flex flex-wrap justify-content-end">
                    <button type="button" class="btn btn-primary btn-label right rounded-pill service_info_modal" data-target="#occupationModal" data-inquiry_id = "{{ isset($franchise->id) ? $franchise->id : ''}}">
                        <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New &nbsp;
                    </button>
                </div>
            </th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@push('header_scripts')


@endpush

@push('footer_scripts')

    <script type="text/javascript">

        $(document).ready(function() {
            $('#serviceOccupationTable').DataTable({
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
                    url: "{{ route('load-inquiry-service-data') }}",
                    data: function ( d ) {
                        d.inquiry_id =  $('.service_info_modal').data('inquiry_id');
                    },
                    dataType: "json",
                    method:'GET'
                },
                columns: [
                    {data: 'organisation', name: 'organisation'},
                    {data: 'designation', name: 'designation'},
                    {data: 'responsibilities', name: 'responsibilities'},
                    {data: 'from_date', name: 'from_date'},
                    {data: 'to_date', name: 'to_date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, width: "5%", sClass: 'text-center'},
                ],
            });
        });


        $(document).on('click', '.service_info_modal', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            var inquiry_id = $(this).data('inquiry_id');
            $("#occupationModal .modal-body #inquiry_id").val(inquiry_id);
            $('.message').html('');
            $('.message').removeClass('link-success');
            $('.message').removeClass('link-danger');
            $(target).modal('show');
        });


        $(document).on('click', '.edit_inquiry_service', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            var id = $(this).data('id');
            var inquiry_id = $(this).data('inquiry_id');
            $.ajax({
                url: "{{ route('get-inquiry-service') }}?id="+id+"&inquiry_id="+inquiry_id,
                type: "GET",
                cache: false,
                success: function (data) {
                    console.log(data);
                    // var fromDate = $("#fromDate").flatpickr({});
                    // var toDate = $("#toDate").flatpickr({});

                    $("#editOccupationModal .modal-body #id").val(data.id);
                    $("#editOccupationModal .modal-body #inquiry_id").val(data.inquiry_id);
                    $("#editOccupationModal .modal-body #organisation").val(data.organisation);
                    $("#editOccupationModal .modal-body #designation").val(data.designation);
                    $("#editOccupationModal .modal-body #responsibilities").val(data.responsibilities);
                    $("#editOccupationModal .modal-body #fromDate").val(data.from_date);
                    $("#editOccupationModal .modal-body #toDate").val(data.to_date);
                    // fromDate.set('defaultDate', data.from_date);
                    // toDate.set('defaultDate', data.to_date);



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

        $(document).on('click', '.remove_inquiry_service', function(e) {
            e.preventDefault();
            var inquiry_id = $(this).data('inquiry_id');
            console.log(inquiry_id);

        });

        $(document).on('hidden.bs.modal','#occupationModal, #editOccupationModal', function () {
            $('#serviceOccupationTable').DataTable().ajax.reload(null, false);
        });

    </script>

@endpush
