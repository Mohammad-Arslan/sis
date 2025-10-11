<div class="modal fade" id="otherInfoModal" tabindex="-1" aria-labelledby="otherInfoModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otherInfoModalLabel">Add New Other Information <p class="message text-end fs-11"></<p></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="row g-3 needs-validation" novalidate id="otherInfoForm">
                     <input type="hidden" id="id" name="id" value="">
                     <input type="hidden" id="inquiry_id" name="inquiry_id" value="">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">

                            <input type="text" class="form-control" id="companyName" name="company_name" placeholder="company_name" required>
                            <label for="companyName" class="form-label">Company Name</label>
                            <div class="invalid-tooltip">Company Name is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="designation" name="designation" placeholder="Designation" required>
                            <label for="designation" class="form-label"> Designation</label>
                            <div class="invalid-tooltip">Designation is required!</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" id="experience" name="experience" placeholder="experience" required></textarea>
                            <label for="experience" class="form-label">Experience (No. of years)</label>
                            <div class="invalid-tooltip">Experience is required!</div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <p>Are you currently associated with any professional group/association/educational organisation/ educational institution (owned / franchise) ?</p>
                        <div class="form-check mb-2 form-check-inline ">
                            <input class="form-check-input" type="radio" name="personally_associated_with_org" id="yes" value="yes" required>
                            <label class="form-check-label" for="yes">Yes</label>
                        </div>
                        <div class="form-check mb-2 form-check-inline">
                            <input class="form-check-input" type="radio" name="personally_associated_with_org" id="no" value="no" required>
                            <label class="form-check-label" for="no">No</label>
                        </div>
                        <p>If yes, please mention deails including the names and location/address (es) of those institutions;</p>
                        <div class="form-label-group in-border mt-2">
                            <textarea class="form-control" id="personallyAssociatedInfo" name="personally_associated_info" placeholder="Other Specify"></textarea>
                            <label for="personallyAssociatedInfo" class="form-label">Details</label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <p>Are any ofyour close relatives, your blodd relatives and/or any of your dependents currently associated with any professional group/association/educational organisation/ educational institution (owned / franchise) ?</p>
                        <div class="form-check mb-2 form-check-inline ">
                            <input class="form-check-input" type="radio" name="family_member_associated_with_org" id="yes" value="yes" required>
                            <label class="form-check-label" for="yes">Yes</label>
                        </div>
                        <div class="form-check mb-2 form-check-inline">
                            <input class="form-check-input" type="radio" name="family_member_associated_with_org" id="no" value="no" required>
                            <label class="form-check-label" for="no">No</label>
                        </div>
                        <p>If yes, please mention deails including the names and location/address (es) of those institutions;</p>
                        <div class="form-label-group in-border mt-2">
                            <textarea class="form-control" id="familyAssociatedInfo" name="family_associated_info" placeholder="Other Specify"></textarea>
                            <label for="familyAssociatedInfo" class="form-label">Details</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary save-other-info" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('header_scripts')


@endpush

@push('footer_scripts')

<script type="text/javascript">

    $(document).on('submit', '#otherInfoForm', function(e) {
        e.preventDefault();
        if (!this.checkValidity()) {
            return;
        }

        $.ajax({
            url: "{{ route('save-inquiry-other-information') }}",
            type: "POST",
            data: $('#otherInfoForm').serializeArray(),
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            success: function (data) {
               document.getElementById("otherInfoForm").reset();
                $('.btn-close').trigger('click');
                Toastify({
                    text: data.message,
                    className: data.class,
                    duration: 3000
                }).showToast();
            },
            error: function () {},
            beforeSend: function () {
                $('.message').html('');
                $('.message').removeClass('link-success');
                $('.message').removeClass('link-danger');
            },
            complete: function () {}
        });
        e.preventDefault();

    });

</script>

@endpush
