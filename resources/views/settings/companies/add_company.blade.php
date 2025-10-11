@extends('layouts.master')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Create New Company</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">
                <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-primary mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->query('tab') == 'basic' ? 'active' : '' }}" href={{ (isset($company)) ? '/companies/'.$company->id.'/edit?tab=basic' : '/companies/create?tab=basic' }} aria-selected="false">
                            <i class="ri-home-5-line align-middle me-1"></i> Basic Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->query('tab') == 'bank' ? 'active' : '' }} {{ isset($company) ? '' : 'disabled' }}" href={{ (isset($company)) ? '/companies/'.$company->id.'/edit?tab=bank' : '#' }} aria-selected="false">
                            <i class="ri-contacts-book-line me-1 align-middle"></i> Bank Account Details
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane {{ request()->query('tab') == 'basic' ? 'active' : '' }}" id="nav-border-justified-basic" role="tabpanel">
                        @if (isset($company))
                        @include('settings.companies.edit_companies')
                        @else
                        @include('settings.companies.add_company_form')
                        @endif
                    </div>
                    <div class="tab-pane {{ request()->query('tab') == 'bank' ? 'active' : '' }}" id="nav-border-justified-bank" role="tabpanel">
                        @if (session()->has('bank_account'))
                        @include('components.edit_bank_account_form', ['bank_account' => session()->get('bank_account')])
                        @else
                        @include('components.add_bank_account_form')
                        @endif

                        <div class="border my-3 border-dashed"></div>
                        @include('components.bank-accounts',['route_parameters' => ['company_id' => isset($company)? $company->id : 0]])
                    </div>
                </div>
            </div>

            {{-- <div class="card-body">
                <div class="live-preview">
                    @include('settings.companies.add_company_form')
                </div>
            </div> --}}
        </div>
    </div>
@endsection
