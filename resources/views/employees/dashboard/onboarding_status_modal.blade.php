<div id="statusDetailModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Onboarding Applications Status Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                        <thead>
                            <tr class="table-active">
                                <th scope="col" class="text-start">Status</th>
                                <th scope="col"class="text-start">Visit / Review Date</th>
                                <th scope="col"class="text-start" >Approval Date</th>
                                <th scope="col" class="text-start">Approved By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query as $data)
                                <tr>
                                    <th scope="row" class="text-start">BD - {{ isset($data->franchise_application_bd->bd_status) ? $data->franchise_application_bd->bd_status : 'Nill' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_bd->visit_date) ? \Carbon\Carbon::parse($data->franchise_application_bd->visit_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_bd->approval_date) ? \Carbon\Carbon::parse($data->franchise_application_bd->approval_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($bd_name) && $bd_name != null ? $bd_name[0]->preferred_name : '-' }}</th>
                                <tr>
                                    <th scope="row" class="text-start">QA - {{ isset($data->franchise_application_qa->qa_status) ? $data->franchise_application_qa->qa_status : 'Nill' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_qa->visit_date) ? \Carbon\Carbon::parse($data->franchise_application_qa->visit_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_qa->visit_date) ? \Carbon\Carbon::parse($data->franchise_application_qa->visit_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($qa_name) && $qa_name != null ? $qa_name[0]->preferred_name : '-' }}</th>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">TOR - {{ isset($data->franchise_application_tor->status) ? $data->franchise_application_tor->status : 'Nill' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_tor->review_date) ? \Carbon\Carbon::parse($data->franchise_application_tor->review_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_tor->approval_date) ? \Carbon\Carbon::parse($data->franchise_application_tor->approval_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($tor_name) && $tor_name != null ? $tor_name[0]->name : '-' }}</th>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">Legal - {{ isset($data->franchise_application_legal->status) ? $data->franchise_application_legal->status : 'Nill' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_legal->review_date) ? \Carbon\Carbon::parse($data->franchise_application_legal->review_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_legal->forwarded_date) ? \Carbon\Carbon::parse($data->franchise_application_legal->forwarded_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start"> {{ isset($legal_name) && $legal_name != null ? $legal_name[0]->name : '-' }}</th>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-start">DD - {{ isset($data->franchise_application_dd->status) ? $data->franchise_application_dd->status : 'Nill' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_dd->review_date) ? \Carbon\Carbon::parse($data->franchise_application_dd->review_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($data->franchise_application_dd->review_date) ? \Carbon\Carbon::parse($data->franchise_application_dd->review_date)->format('d-m-Y') : '-' }}</th>
                                    <th scope="row" class="text-start">{{ isset($dd_name) && $dd_name != null ? $dd_name[0]->name : '-' }}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
</div>
