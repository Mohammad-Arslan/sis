@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Tax Settings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tax Settings</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-4">Manage tax types and taxes in one place</p>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" 
                           data-bs-toggle="tab" 
                           href="#tax-types-tab" 
                           role="tab"
                           aria-selected="true">
                            <i class="ri-file-list-3-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Tax Types</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           data-bs-toggle="tab" 
                           href="#taxes-tab" 
                           role="tab"
                           aria-selected="false">
                            <i class="ri-money-dollar-circle-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Taxes</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Tax Types Tab -->
                    <div class="tab-pane fade show active" 
                         id="tax-types-tab" 
                         role="tabpanel">
                        @include('settings.tax.tabs.tax-types')
                    </div>

                    <!-- Taxes Tab -->
                    <div class="tab-pane fade" 
                         id="taxes-tab" 
                         role="tabpanel">
                        @include('settings.tax.tabs.taxes')
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

