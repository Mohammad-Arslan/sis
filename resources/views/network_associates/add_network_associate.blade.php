@extends('layouts.master')

@section('content')
    @include('components.flash_message')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <!-- <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Form Title</h4>
                            <div class="flex-shrink-0">

                            </div>
                        </div> -->
                <div class="card-body">
                    <ul class="nav nav-pills arrow-navtabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->query('tab') == 'nwa' ? 'active' : '' }}"
                                href={{ isset($network_associate) ? '/network-associates/' . $network_associate->id . '/edit?tab=nwa' : '/network-associates/create?tab=nwa' }}
                                aria-selected="false">
                                <i class="ri-home-5-line align-middle me-1"></i> Network Associate Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->query('tab') == 'contact' ? 'active' : '' }} {{ isset($network_associate) ? '' : 'disabled' }}"
                                href={{ isset($network_associate) ? '/network-associates/' . $network_associate->id . '/edit?tab=contact' : '#' }}
                                aria-selected="false">
                                <i class="ri-contacts-book-line me-1 align-middle"></i> Contact Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->query('tab') == 'bank' ? 'active' : '' }} {{ isset($network_associate) ? '' : 'disabled' }}"
                                href={{ isset($network_associate) ? '/network-associates/' . $network_associate->id . '/edit?tab=bank' : '#' }}
                                aria-selected="false">
                                <i class="ri-contacts-book-line me-1 align-middle"></i> Bank Account Details
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{ request()->query('tab') == 'nwa' ? 'active' : '' }}"
                            id="nav-border-justified-branch" role="tabpanel">

                            @if (isset($network_associate))
                                @include('network_associates.edit_network_associate_form')
                            @else
                                @include('network_associates.add_network_associate_form')
                            @endif


                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'contact' ? 'active' : '' }}"
                            id="nav-border-justified-contact" role="tabpanel">

                            @if (isset($contact_information))
                                @include('components.edit_contact_information')
                            @else
                                @include('components.contact_information')
                            @endif

                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'bank' ? 'active' : '' }}"
                            id="nav-border-justified-contact" role="tabpanel">

                            @if (session()->has('bank_account'))
                                @include('components.edit_bank_account_form', [
                                    'bank_account' => session()->get('bank_account'),
                                ])
                            @else
                                @include('components.add_bank_account_form')
                            @endif

                            {{-- @include('components.add_bank_account_form') --}}

                            @if (isset($network_associate))
                                <div class="border my-3 border-dashed"></div>
                                @include('components.bank-accounts', [
                                    'route_parameters' => ['network_associate_id' => isset($network_associate) ? $network_associate->id : 0],
                                ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (isset($network_associate))
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">NWA Branch List</h4>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" id="assignBranch"
                                class="btn btn-success btn-label btn-sm show-modal" data-target="#assignBranchToNWA"
                                data-url="{{ route('network-associates.show', $network_associate->id) }}">
                                <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Assign Branch to NWA
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <table data-nwa_id="{{ $network_associate->id }}" id="nwa-branches-table"
                            class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Branch ID</th>
                                    <th>Branch Name</th>
                                    <th>Company Name</th>
                                    <th>NWA Name</th>
                                    <th>Region</th>
                                    <th>Setup Date</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Branch ID</th>
                                    <th>Branch Name</th>
                                    <th>Company Name</th>
                                    <th>NWA Name</th>
                                    <th>Region</th>
                                    <th>Setup Date</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
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

            $('#example').DataTable({
                retrieve: true,
                processing: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,

            });

            $('#nwa-branches-table').DataTable({
                processing: true,
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
                    url: "{{ route('branches.index') }}",
                    "data": function(d) {
                        d.nwa_id = $('#nwa-branches-table').data('nwa_id');
                    },
                    dataType: "json"
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%",
                        orderable: true
                    },
                    {
                        data: 'br_name',
                        name: 'br_name'
                    },
                    {
                        data: 'company.company_name',
                        name: 'company.company_name'
                    },
                    {
                        data: 'nwa.user.name',
                        name: 'nwa.user.name',
                        width: "10%"
                    },
                    {
                        data: 'region.region_name',
                        name: 'region.region_name',
                        width: "10%"
                    },
                    {
                        data: 'setup_date',
                        name: 'setup_date',
                        width: "8%"
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: "5%",
                        sClass: 'text-center'
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
                        sClass: 'text-center'
                    },
                ],
                order: [
                    [5, "desc"]
                ]
            });


        });

        $(document).on('click', '.assign-branch-nwa', function(e) {

            var nwa_id = $('#nwa-branches-table').data('nwa_id');
            var branch_id = $(this).val();
            var self = $(this);
            var url = "{{ route('network-associates-branches.store') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    nwa_id,
                    branch_id
                },
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function(data) {
                    self.prop('disabled', true);
                    self.parent().addClass('text-muted');
                    self.css('cursor', 'not-allowed');
                },
                error: function() {

                },
                beforeSend: function() {

                },
                complete: function() {}
            });
        });

        $(document).on('hidden.bs.modal', '#assignBranchToNWA', function() {
            $('#nwa-branches-table').DataTable().ajax.reload(null, false);
        });
    </script>
@endpush
