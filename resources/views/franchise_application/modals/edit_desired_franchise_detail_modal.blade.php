<div class="modal fade" id="editDesiredFranchiseInfoModal" tabindex="-1" aria-labelledby="editDesiredFranchiseInfoModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDesiredFranchiseInfoModalLabel">Possess a site details <p class="message text-end fs-11"></<p></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="row g-3 needs-validation" novalidate id="editDesiredFranchiseInfoModalForm">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="franchise_application_id" name="franchise_application_id" value="">
                    <div class="col-md-6 col-sm-12">
                        <p><strong>Ownership</strong></p>&nbsp;
                        <div class="form-check mb-2 form-check-inline pl-13">
                            <input class="form-check-input" type="radio" name="ownership" id="yes" value="yes" required>
                            <label class="form-check-label" for="yes">Yes</label>
                        </div>
                        <div class="form-check mb-2 form-check-inline">
                            <input class="form-check-input" type="radio" name="ownership" id="no" value="no" required>
                            <label class="form-check-label" for="no">No</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <p><strong>Lease/Rental</strong></p>&nbsp;
                        <div class="form-check mb-2 form-check-inline pl-13">
                            <input class="form-check-input" type="radio" name="lease_rental" id="yes" value="yes" required>
                            <label class="form-check-label" for="yes">Yes</label>
                        </div>
                        <div class="form-check mb-2 form-check-inline">
                            <input class="form-check-input" type="radio" name="lease_rental" id="no" value="no" required>
                            <label class="form-check-label" for="no">No</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label">Period of Rent/Lease</label>
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('from_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('from_date') }}" name="from_date" id="from_date" required>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="from_date" class="form-label">From:</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('from_date'))
                                {{ $errors->first('from_date') }}
                                @else
                                From Date is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <label class="form-label">&nbsp;</label>
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('to_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('to_date') }}" name="to_date" id="to_date" required>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="to_date" class="form-label">To:</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('to_date'))
                                {{ $errors->first('to_date') }}
                                @else
                                To Date is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="total_area" name="total_area" placeholder="Total Area" required>
                            <label for="total_area" class="form-label"> Total Area</label>
                            <div class="invalid-tooltip">Total Area is required!</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="tileCarpet" name="tile_carpet" placeholder="Covered Area" required>
                            <label for="tileCarpet" class="form-label"> Covered Area</label>
                            <div class="invalid-tooltip">Covered Area is required!</div>
                        </div>
                    </div>


                    <div class="col-md-12 col-sm-12">
                        <p><strong>Location</strong></p>&nbsp;
                        <div class="form-check mb-2 form-check-inline pl-13">
                            <input class="form-check-input" type="radio" name="location" id="commercial" value="commercial" required>
                            <label class="form-check-label" for="commercial">Commercial</label>
                        </div>
                        <div class="form-check mb-2 form-check-inline">
                            <input class="form-check-input" type="radio" name="location" id="residential" value="residential" required>
                            <label class="form-check-label" for="residential">Residential</label>
                        </div>

                        <div class="form-check mb-2 form-check-inline">
                            <input class="form-check-input" type="radio" name="location" id="amenity" value="amenity" required>
                            <label class="form-check-label" for="amenity">Amenity</label>
                        </div>
                    </div>


                    <div class="col-12 text-end">
                        <button class="btn btn-primary update_possess_site_info" type="submit">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('header_scripts')

<style type="text/css">
    .pl-13{ padding-left: 13px !important; }
</style>
@endpush

@push('footer_scripts')


<script type="text/javascript">
    $(document).on('submit', '#editDesiredFranchiseInfoModalForm', function(e) {
        e.preventDefault();
        if (!this.checkValidity()) {
            return;
        }
        $.ajax({
            url: "{{ route('save-application-possess-site') }}",
            type: "POST",
            data: $('#editDesiredFranchiseInfoModalForm').serializeArray(),
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            success: function (data) {
               document.getElementById("editDesiredFranchiseInfoModalForm").reset();
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
