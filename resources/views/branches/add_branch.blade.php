@extends('layouts.master')
@section('content')
    @include('components.flash_message')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                @if (isset($branch_associate))
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Branch Detail</h4>
                        <div class="flex-shrink-0">

                        </div>
                    </div>

                    <div class="card-body mt-3 mx-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="fw-medium" scope="row">Branch Code:</td>
                                                <td>{{ $branch->branch_code }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium" scope="row">Branch Name:</td>
                                                <td>{{ $branch->br_name }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border mt-3 border-dashed"></div>
                @endif


                <div class="card-body">
                    <ul class="nav nav-pills arrow-navtabs mb-3" role="tablist">
                        @role('super_admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab') == 'home' ? 'active' : '' }}"
                                    href={{ isset($branch) ? '/branches/' . $branch->id . '/edit?tab=home' : '/branches/create?tab=home' }}
                                    aria-selected="false">
                                    <span class="d-none d-sm-block">Basic Info</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab') == 'contact' ? 'active' : '' }} {{ isset($branch) ? '' : 'disabled' }}"
                                    href={{ isset($branch) ? '/branches/' . $branch->id . '/edit?tab=contact' : '#' }}
                                    aria-selected="false">
                                    <span class="d-none d-sm-block">Contact Info</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab') == 'bank' ? 'active' : '' }} {{ isset($branch) ? '' : 'disabled' }}"
                                    href={{ isset($branch) ? '/branches/' . $branch->id . '/edit?tab=bank' : '#' }}
                                    aria-selected="false">
                                    <span class="d-none d-sm-block">Bank Info</span>
                                </a>
                            </li>
                        @endrole
                        <li class="nav-item">
                            <a class="nav-link {{ request()->query('tab') == 'staff' ? 'active' : '' }} {{ isset($branch) ? '' : 'disabled' }}"
                                href={{ isset($branch) ? '/branches/' . $branch->id . '/edit?tab=staff' : '#' }}
                                aria-selected="false">
                                <span class="d-none d-sm-block">Administrator Staff</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->query('tab') == 'roles-info' ? 'active' : '' }} {{ isset($branch) ? '' : 'disabled' }}"
                            href={{ (isset($branch)) ? '/branches/'.$branch->id.'/edit?tab=roles-info' : '#' }} aria-selected="false">
                            <span class="d-none d-sm-block">Roles Info</span>
                        </a>
                    </li> --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->query('tab') == 'branch-classes' ? 'active' : '' }} {{ isset($branch) ? '' : 'disabled' }}"
                                href={{ isset($branch) ? '/branches/' . $branch->id . '/edit?tab=branch-classes' : '#' }}
                                aria-selected="false">
                                <span class="d-none d-sm-block">Branch Classes</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->query('tab') == 'update-email' ? 'active' : '' }} {{ isset($branch) ? '' : 'disabled' }}"
                            href={{ (isset($branch)) ? '/branches/'.$branch->id.'/edit?tab=update-email' : '#' }} aria-selected="false">
                            <span class="d-none d-sm-block">Update Email</span>
                        </a>
                    </li> --}}
                        @role('super_admin')
                            {{-- <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab') == 'royalty' ? 'active' : '' }} {{ isset($branch) ? '' : 'disabled' }}"
                                    href={{ isset($branch) ? '/branches/' . $branch->id . '/edit?tab=royalty' : '#' }}
                                    aria-selected="false">
                                    <span class="d-none d-sm-block">Royalty</span>
                                </a>
                            </li> --}}
                        @endrole
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{ request()->query('tab') == 'home' ? 'active' : '' }}"
                            id="nav-border-justified-home" role="tabpanel">
                            @if (isset($branch_associate))
                                @include('branches.edit_branch_form')
                            @else
                                @include('branches.add_branch_form')
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
                            id="nav-border-justified-bank" role="tabpanel">
                            @if (isset($branch_associate))
                                {{-- @include('components.add_bank_account_form') --}}

                                @if (session()->has('bank_account'))
                                    @include('components.edit_bank_account_form', [
                                        'bank_account' => session()->get('bank_account'),
                                    ])
                                @else
                                    @include('components.add_bank_account_form')
                                @endif

                                <div class="border my-3 border-dashed"></div>
                                @include('components.bank-accounts', [
                                    'route_parameters' => ['branch_id' => isset($branch) ? $branch->id : 0],
                                ])
                            @endif
                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'staff' ? 'active' : '' }}"
                            id="nav-border-justified-administrative-staff" role="tabpanel">
                            @if (isset($branch_associate))
                                @include('employees.edit_branch_administrative_staff')
                            @endif
                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'roles-info' ? 'active' : '' }}"
                            id="nav-border-justified-roles-info" role="tabpanel">
                            <h5 class="mb-sm-0 text-center">Coming Soon</h5>
                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'branch-classes' ? 'active' : '' }}"
                            id="nav-border-justified-branch-classes" role="tabpanel">
                            @if (isset($branch_associate))
                                @include('branches.branch_classes')
                            @endif
                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'update-email' ? 'active' : '' }}"
                            id="nav-border-justified-update-email" role="tabpanel">
                            <h5 class="mb-sm-0 text-center">Coming Soon</h5>
                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'royalty' ? 'active' : '' }}"
                            id="nav-border-justified-royalty" role="tabpanel">
                            @if (isset($branch_associate))
                                @include('branches.royalty.royalty', [
                                    'branch_royalty' => session()->get('branch_royalty'),
                                ])
                                <div class="border my-3 border-dashed"></div>
                                @include('branches.royalty.royalties', [
                                    'route_parameters' => ['branch_id' => isset($branch) ? $branch->id : 0],
                                ])
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
@endpush
