@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Subject Settings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Subject Settings</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-4">Manage subjects and subject groups in one place</p>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" 
                           data-bs-toggle="tab" 
                           href="#subjects-tab" 
                           role="tab"
                           aria-selected="true">
                            <i class="ri-book-open-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Subjects</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                           data-bs-toggle="tab" 
                           href="#subject-groups-tab" 
                           role="tab"
                           aria-selected="false">
                            <i class="ri-folder-line me-1 align-middle"></i>
                            <span class="d-none d-sm-inline-block">Subject Groups</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Subjects Tab -->
                    <div class="tab-pane fade show active" 
                         id="subjects-tab" 
                         role="tabpanel">
                        @include('settings.subjects.tabs.subjects')
                    </div>

                    <!-- Subject Groups Tab -->
                    <div class="tab-pane fade" 
                         id="subject-groups-tab" 
                         role="tabpanel">
                        @include('settings.subjects.tabs.subject-groups')
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Adjust DataTables when tab is shown
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endpush
