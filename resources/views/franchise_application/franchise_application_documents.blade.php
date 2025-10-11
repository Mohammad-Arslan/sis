<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Attached Documents</h4>
    </div><!-- end card header -->

    <div class="card-body">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-2">
                    <tbody>
                        <tr>
                            <th class="ps-1" scope="row" width="20%">NWA Name :</th>
                            <td>{{$franchise_application->appl_name.' '.$franchise_application->appl_last_name}}</td>
                            <th class="ps-1" scope="row">Contact :</th>
                            <td>{{$franchise_application->contact_no_1}}</td>
                        </tr>
                        <tr>
                            <th class="ps-1" scope="row">Address :</th>
                            <td>{{$franchise_application->personal_address}}</td>
                            <th class="ps-1" scope="row">CNIC :</th>
                            <td>{{$franchise_application->CNIC}}</td>
                        </tr>
                        <tr>
                            <th class="ps-1" scope="row">Email :</th>
                            <td>{{$franchise_application->email}}</td>
                            <th class="ps-1" scope="row">Agreement Type :</th>
                            <td>{{$franchise_application->agreement_type}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="filter form-select" id="attachment_type_id" name="attachment_type_id" aria-label="Attachment select">
                        <option value="">Please select</option>
                        @foreach ($attachment_types as $attachment_type)
                            <option value="{{ $attachment_type->id }}">{{ $attachment_type->name }}</option>
                        @endforeach
                    </select>
                    <label for="attachment_type_id" class="form-label">Attachment Type</label>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-label-group in-border">
                    <select class="filter form-select" id="department_id" name="department_id" aria-label="Attachment select">
                        <option value="">Please select</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                    <label for="department_id" class="form-label">Departments</label>
                </div>
            </div>
        </div>
        <table id="documents-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
               style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Document Type</th>
                <th>Department</th>
                <th>Uploaded By</th>
                <th>Date</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
                {{-- @php
                     $docs = show_documents($franchise_application_id);
                @endphp
                @foreach ($docs as $doc)
                <tr>
                    <td>{{$doc['file_name']}}</td>
                    <td>{{$doc['attachment_type']['name']}}</td>
                    <td>{{isset($doc['user']) ? $doc['user']['name']: '-'}}</td>
                    <td>{{isset($doc['uploaded_date']) ? \Carbon\Carbon::parse($doc['uploaded_date'])->format('d-m-Y') : '-'}}</td>
                    <td>{{$doc['details']}}</td>
                </tr>
                @endforeach --}}
            </tbody>
            <tfoot>
            <tr>
                <th>Name</th>
                <th>Document Type</th>
                <th>Department</th>
                <th>Uploaded By</th>
                <th>Date</th>
                <th>Remarks</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
@push('header_scripts')
    <style type="text/css">

    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#documents-data-table').DataTable({
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
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "/franchise-application-la/documents/{{$franchise_application_id}}",
                    data: function(d) {
                        d.attachment_type_id = $('#attachment_type_id').val();
                        d.department_id = $('#department_id').val();

                    },
                    dataType: "json",
                    method: 'GET'
                },
                columns: [
                    {
                        data: 'file_name',
                        name: 'file_name'
                    },
                    {
                        data: 'document_type',
                        name: 'document_type'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'uploaded_by',
                        name: 'uploaded_by'
                    },
                    {
                        data: 'uploaded_date',
                        name: 'uploaded_date'
                    },
                    {
                        data: 'details',
                        name: 'details'
                    },
                ],
            });

            $(document).on('change', '.filter', function() {
            $('#documents-data-table').DataTable().ajax.reload(null, false).page('first');
        });

        });
    </script>
@endpush
