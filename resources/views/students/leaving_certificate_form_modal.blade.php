<div id="LeavingCertificateFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Student Withdrawal Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <div class="modal-body transfer_case_modal">
                @include('students.leaving_certificate_form_pdf')
            </div>
            <div class="hstack justify-content-end p-2">
                <a href="" id="print_transfer_form" class="btn btn-info"><i
                        class="ri-printer-line align-bottom me-1"></i> Print</a>
            </div>
        </div>
    </div>
</div>
