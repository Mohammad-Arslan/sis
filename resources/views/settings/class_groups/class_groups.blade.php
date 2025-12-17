@extends('layouts.master')

@section('content')
    <div class="row">

        @if (isset($classGroup))
            @include('settings.class_groups.edit_class_group')
        @else
            @permission('add-class-group')
                @include('settings.class_groups.add_class_group')
            @endpermission
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"> School Type List</h4>
                    <div class="flex-shrink-0">
                        <!-- Buttons with Label -->
                        <a class="btn btn-sm btn-primary btn-label waves-effect waves-light" href=""><i
                                class="ri-upload-2-line label-icon align-middle fs-16 me-2"></i> Import</a>
                        <a class="btn btn-sm btn-success btn-label waves-effect waves-light" href=""><i
                                class="ri-download-2-line label-icon align-middle fs-16 me-2"></i> Export</a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <table id="class-groups-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>School Type</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>School Type</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="classesModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomInModalLabel">Classes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody"></div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });


            $('#class-groups-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('class-groups.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: "text-center"
                    }
                ]
            });

            $(document).on('click', '.popup', function(e) {
                // var class_group_id = this.id;
                var url = '{{ route('groups-classes') }}';
                $.ajax({

                    url: url,
                    type: "GET",
                    data: {
                        'id': this.id
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        $('#classesModal').modal('show');
                        $('#modalBody').html(data).show();
                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {}
                });

            });

            $(document).on('click', '.form-check-input', function(e) {

                var class_id = $(this).val();
                var class_group_id = $(this).data('class_group_id');

                console.log(class_id, class_group_id);
                var url = "{{ route('add-groups-classes') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        'class_id': class_id,
                        'class_group_id': class_group_id
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {

                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {}
                });

            });

        });
    </script>
@endpush
