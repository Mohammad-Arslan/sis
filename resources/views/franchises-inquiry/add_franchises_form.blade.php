@if(isset($franchise))
    {{--Seperat form tags for sseperate valiadtions of each form--}}
    <form method="POST" action="{{ route('franchises.update', $franchise->id) }}" class="row g-3 needs-validation" id="personalParticularOfApplicantForm" novalidate>
        @method('PATCH')
        @csrf
        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
            @include('franchises-inquiry.personal_particular_of_applicant')
            @include('franchises-inquiry.personal_fact_sheet')
            @include('franchises-inquiry.other_information')
        </div>
    </form>

    <form method="POST" action="{{ route('franchises.update', $franchise->id) }}" class="row g-3 needs-validation" id="desiredFranchiseDetailsForm" novalidate>
        @method('PATCH')
        @csrf
        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
            @include('franchises-inquiry.desired_franchise_details')
        </div>
    </form>

    @if(Auth::guard()->check())
        <form method="POST" action="{{ route('franchises.update', $franchise->id) }}" class="row g-3 needs-validation" id="officeUseForm" novalidate>
            @method('PATCH')
            @csrf
            <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
                @include('franchises-inquiry.office_use')
            </div>
        </form>
    @endif
@else
    <form method="POST" action="{{ route('franchises.store') }}" class="row g-3 needs-validation" id="personalParticularOfApplicantForm" novalidate>
        @csrf
        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="accordionBorderedGuideLines">
            @include('franchises-inquiry.personal_particular_of_applicant')
            @include('franchises-inquiry.personal_fact_sheet')
            @include('franchises-inquiry.other_information')
            @include('franchises-inquiry.desired_franchise_details')
            @if(Auth::guard()->check())
                @include('franchises-inquiry.office_use')
            @endif
        </div>
    </form>
@endif
