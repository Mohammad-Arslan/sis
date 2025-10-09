<div id="withdrawalFormModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Student Withdrawal Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <div class="modal-body registration_slip_modal p-0 border m-3">
                @include('students.withdrawal.withdrawal_print_form_pdf')
            </div>
        </div>
    </div>
</div>
