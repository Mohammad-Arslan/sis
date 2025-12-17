<div class="vertical-navs-step">
    <div class="row gy-5">
        <div class="col-lg-4 col-sm-12">            
            <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">

                <button class="nav-link done" id="v-pills-personal-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-personal-info" type="button" role="tab" aria-controls="v-pills-personal-info" aria-selected="false" data-position="0">
                    <span class="step-title me-2">
                        <i class="ri-close-circle-fill step-icon me-2"></i>
                        Step 1
                    </span>
                    Personal Information
                </button>

                <button class="nav-link @if(isset($franchise)) done @endif" id="v-pills-educational-organization-tab" data-bs-toggle="pill" data-bs-target="#v-pills-educational-organization" type="button" role="tab" aria-controls="v-pills-educational-organization" aria-selected="false" data-position="1">
                    <span class="step-title me-2">
                        <i class="ri-close-circle-fill step-icon me-2"></i>
                        Step 2
                    </span>
                    Led an educational organization before?
                </button>

                <button class="nav-link" id="v-pills-led-franchise-tab" data-bs-toggle="pill" data-bs-target="#v-pills-led-franchise" type="button" role="tab" aria-controls="v-pills-led-franchise" aria-selected="false" data-position="2">
                    <span class="step-title me-2">
                        <i class="ri-close-circle-fill step-icon me-2"></i>
                        Step 3
                    </span>
                    Have you led a Franchise ?
                </button>

                <button class="nav-link" id="v-pills-school-building-tab" data-bs-toggle="pill" data-bs-target="#v-pills-school-building" type="button" role="tab" aria-controls="v-pills-school-building" aria-selected="false" data-position="2">
                    <span class="step-title me-2">
                        <i class="ri-close-circle-fill step-icon me-2"></i>
                        Step 4
                    </span>
                    School Building Option
                </button>
                
                <button class="nav-link" id="v-pills-converting-building-tab" data-bs-toggle="pill" data-bs-target="#v-pills-converting-building" type="button" role="tab" aria-controls="v-pills-converting-building" aria-selected="false" data-position="2">
                    <span class="step-title me-2">
                        <i class="ri-close-circle-fill step-icon me-2"></i>
                        Step 5
                    </span>
                    Converting Existing Building
                </button>

                <button class="nav-link" id="v-pills-location-and-property-details-tab" data-bs-toggle="pill" data-bs-target="#v-pills-location-and-property-details" type="button" role="tab" aria-controls="v-pills-location-and-property-details" aria-selected="false" data-position="2">
                    <span class="step-title me-2">
                        <i class="ri-close-circle-fill step-icon me-2"></i>
                        Step 6
                    </span>
                    Location and Property Details
                </button>

                <button class="nav-link" id="v-pills-finish-tab" data-bs-toggle="pill" data-bs-target="#v-pills-finish" type="button" role="tab" aria-controls="v-pills-finish" aria-selected="true" data-position="3">
                    <span class="step-title me-2">
                        <i class="ri-close-circle-fill step-icon me-2"></i>
                        Step 7
                    </span>
                    Finish
                </button>
            </div>

        </div>
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="px-lg-4">
                <div class="tab-content">
                    @include('franchises.registration-forms.personal_info_form')
                    @include('franchises.registration-forms.educational_organization_form')
                    @include('franchises.registration-forms.franchise_Info_form')
                    @include('franchises.registration-forms.school_building_options_form')
                    @include('franchises.registration-forms.converting_existing_building_form')
                    @include('franchises.registration-forms.location_and_property_details_form')
                    @include('franchises.registration-forms.franchise_form_completed_screen')                    
                </div>
            </div>
        </div>
    </div>
</div>
