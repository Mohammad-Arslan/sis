@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills arrow-navtabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->query('tab') == 'royalty' ? 'active' : '' }}"
                                href={{ route('reports') . '?tab=royalty' }} aria-selected="false">
                                <i class="ri-home-5-line align-middle me-1"></i> Royalty Computation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->query('tab') == 'reimbursement' ? 'active' : '' }}"
                                href={{ route('reports') . '?tab=reimbursement' }} aria-selected="false">
                                <i class="ri-contacts-book-line me-1 align-middle"></i> Royalty Reimbursement Note
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{ request()->query('tab') == 'royalty' ? 'active' : '' }}"
                            id="nav-border-justified-branch" role="tabpanel">
                            @include('reports.franchise_royalty_report')

                        </div>
                        <div class="tab-pane {{ request()->query('tab') == 'reimbursement' ? 'active' : '' }}"
                            id="nav-border-justified-contact" role="tabpanel">
                            @include('reports.royalty_reimbursement')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection