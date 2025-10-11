@if(isset($franchise))
    {{--Seperat form tags for sseperate valiadtions of each form--}}
    <form method="POST" action="{{ route('franchise-applications.update', $franchise->id) }}" class="row g-3 needs-validation" id="personalParticularOfApplicantForm" novalidate>
        @method('PATCH')
        @csrf
        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
            @include('franchise_application.personal_particular_of_applicant')
            @include('franchise_application.personal_fact_sheet')
            @include('franchise_application.other_information')
        </div>
    </form>

    <form method="POST" action="{{ route('franchise-applications.update', $franchise->id) }}" class="row g-3 needs-validation" id="desiredFranchiseDetailsForm" novalidate>
        @method('PATCH')
        @csrf
        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
            @include('franchise_application.desired_franchise_details')
        </div>
    </form>

    <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
        @include('franchise_application.upload_documents')
    </div>

    @if(Auth::guard()->check())
        <form method="POST" action="{{ route('franchise-applications.update', $franchise->id) }}" class="row g-3 needs-validation" id="officeUseForm" novalidate>
            @method('PATCH')
            @csrf
            <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
                @include('franchise_application.office_use')
            </div>
        </form>
    @endif
@else
    <form method="POST" action="{{ route('franchise-applications.store') }}" class="row g-3 needs-validation" id="personalParticularOfApplicantForm" novalidate>
        @csrf
        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
            @include('franchise_application.personal_particular_of_applicant')
            @include('franchise_application.personal_fact_sheet')
            @include('franchise_application.other_information')
            @include('franchise_application.desired_franchise_details')
            @include('franchise_application.upload_documents')
            @if(Auth::guard()->check())
                @include('franchise_application.office_use')
            @endif
        </div>
    </form>
@endif
