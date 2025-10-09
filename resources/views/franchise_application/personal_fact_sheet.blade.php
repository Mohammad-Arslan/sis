<div class="accordion-item mt-3">
    <h2 class="accordion-header" id="accordionborderedPersonalFactSheet">
        <button class="accordion-button {{isset($franchise) ? '' : 'collapsed'}}" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse2" aria-expanded="false" aria-controls="accor_borderedcollapse2" @if(!isset($franchise)) disabled @endif>
            Personal Fact Sheet
        </button>
    </h2>
    <div id="accor_borderedcollapse2" class="accordion-collapse collapse {{isset($franchise) ? 'show' : ''}}" aria-labelledby="accordionborderedPersonalFactSheet" data-bs-parent="#accordionBordered" style="">
        <div class="accordion-body">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills arrow-navtabs nav-primary bg-light mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#qualification-overview" role="tab" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                <span class="d-none d-sm-block">Qualification</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#occupation-overview" role="tab" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                <span class="d-none d-sm-block">Occupation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#social-status-overview" role="tab" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                <span class="d-none d-sm-block">Social Status</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        @include('franchise_application.qualification_form')
                        @include('franchise_application.occupation')
                        @include('franchise_application.social_status')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
