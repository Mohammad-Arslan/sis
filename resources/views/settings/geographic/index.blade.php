@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Geographic & Location Settings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Geographic Settings</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-4">Manage countries, states, cities, towns, regions, and building types in one place</p>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" 
                           data-bs-toggle="tab" 
                           href="#countries-tab" 
                           role="tab"
                           aria-selected="true">
                            <i class="ri-global-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Countries</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           data-bs-toggle="tab" 
                           href="#states-tab" 
                           role="tab"
                           aria-selected="false">
                            <i class="ri-map-pin-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">States</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           data-bs-toggle="tab" 
                           href="#cities-tab" 
                           role="tab"
                           aria-selected="false">
                            <i class="ri-building-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Cities</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           data-bs-toggle="tab" 
                           href="#towns-tab" 
                           role="tab"
                           aria-selected="false">
                            <i class="ri-community-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Towns</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           data-bs-toggle="tab" 
                           href="#regions-tab" 
                           role="tab"
                           aria-selected="false">
                            <i class="ri-map-2-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Regions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           data-bs-toggle="tab" 
                           href="#building-types-tab" 
                           role="tab"
                           aria-selected="false">
                            <i class="ri-building-2-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Building Types</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Countries Tab -->
                    <div class="tab-pane fade show active" 
                         id="countries-tab" 
                         role="tabpanel">
                        @include('settings.geographic.tabs.countries')
                    </div>

                    <!-- States Tab -->
                    <div class="tab-pane fade" 
                         id="states-tab" 
                         role="tabpanel">
                        @include('settings.geographic.tabs.states')
                    </div>

                    <!-- Cities Tab -->
                    <div class="tab-pane fade" 
                         id="cities-tab" 
                         role="tabpanel">
                        @include('settings.geographic.tabs.cities')
                    </div>

                    <!-- Towns Tab -->
                    <div class="tab-pane fade" 
                         id="towns-tab" 
                         role="tabpanel">
                        @include('settings.geographic.tabs.towns')
                    </div>

                    <!-- Regions Tab -->
                    <div class="tab-pane fade" 
                         id="regions-tab" 
                         role="tabpanel">
                        @include('settings.geographic.tabs.regions')
                    </div>

                    <!-- Building Types Tab -->
                    <div class="tab-pane fade" 
                         id="building-types-tab" 
                         role="tabpanel">
                        @include('settings.geographic.tabs.building-types')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
@endsection

@push('footer_scripts')
{{-- Global functions are now in crud-operations.js --}}
@endpush

