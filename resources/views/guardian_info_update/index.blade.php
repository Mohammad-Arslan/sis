@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Guardian Info Update Requests</li>
    </x-breadcrumb>
    @include('components.flash_message')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Guardian Info Update Requests</h4>
                </div><!-- end card header -->

                <div class="card-body">

                    <table id="guardian-info-data-table"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guardian Name</th>
                                <th>Data Type</th>
                                <th>Old Data</th>
                                <th>New Data</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Guardian Name</th>
                                <th>Data Type</th>
                                <th>Old Data</th>
                                <th>New Data</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header_scripts')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });


            $('#guardian-info-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: "{{ route('guardian-info-update.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    /*{
                        data: 'guardian.guardian_name',
                        name: 'guardian_name'
                    },*/
                    {
                        "data": "guardian_id",

                        render: function(data, type, row) {

                            // return row.guardian.guardian_name + ' - ' + '[' + row.guardian.id + ']';
                            return row.guardian.guardian_name;
                        }
                    },
                    {
                        data: 'update_type',
                        // name: 'update_type'
                        render: function(data, type, row) {

                            if (data == 'email') {
                                return 'Email';
                            } else if (data == 'phone') {
                                return 'Phone';
                            } else if (data == 'cors_mobile') {
                                return 'Correspondence Mobile';
                            } else if (data == 'cors_address') {
                                return 'Correspondence Address';
                            }
                        }
                    },

                    /*{
                        data: 'guardian.email',
                        name: 'email'
                    },*/

                    {
                        "data": "update_type",

                        render: function(data, type, row) {

                            if (data == 'email') {
                                return row.guardian.email;
                            } else if (data == 'phone') {
                                return row.guardian.mobile;
                            } else if (data == 'cors_mobile') {
                                return row.student.student_address.per_phone;
                            } else if (data == 'cors_address') {
                                return row.student.student_address.per_address;
                            }
                        }
                    },


                    {
                        data: 'update_value',
                        name: 'update_value'
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
                    },
                ]
            });
        });

        //Send update info using AJAX request
        $(document).on('click', 'body .button-update-guardian-info', function() {

            //Get record id, using data attribute
            let guardian_info_update_id = $(this).data('guardian-info-update-id');
            //
            console.log(guardian_info_update_id);

            $.ajax({
                url: "/guardian-info-update/" + guardian_info_update_id,
                type: 'PATCH',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function(result) {
                    //Reload page
                    location.reload();

                },
                error: function() {
                    console.log("Sorry! Server error!");
                },
                timeout: 3000
            }).fail(function(jqXHR, textStatus) {
                if (textStatus === 'timeout') {
                    console.log("Sorry Please Wait... Slow connection!");
                }
            });
        });
    </script>
@endpush
