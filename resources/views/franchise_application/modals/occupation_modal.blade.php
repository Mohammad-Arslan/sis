<div class="modal fade" id="occupationModal" tabindex="-1" aria-labelledby="occupationModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="occupationModalLabel">Add New Occupation <p class="message text-end fs-11"></<p></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="row g-3 needs-validation" novalidate id="addoccupationForm">
                     <input type="hidden" id="id" name="id" value="">
                     <input type="hidden" id="franchise_application_id" name="franchise_application_id" value="">
                     <div class="col-md-6 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('from_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ old('from_date') }}" name="from_date" id="fromDate" required>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="fromDate" class="form-label">From Date</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('from_date'))
                                {{ $errors->first('from_date') }}
                                @else
                                Setup Date is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="input-group form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('to_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ old('to_date') }}" name="to_date" id="toDate" required>
                            <div class="input-group-text bg-primary border-primary text-white">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <label for="toDate" class="form-label">To Date</label>
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
                            <input type="text" class="form-control" id="organisation" name="organisation" placeholder="organisation" required>
                            <label for="organisation" class="form-label">Organisation</label>
                            <div class="invalid-tooltip">Organisation is required!</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" id="designation" name="designation" placeholder="designation" required>
                            <label for="designation" class="form-label"> Designation</label>
                            <div class="invalid-tooltip">Designation is required!</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" id="responsibilities" name="responsibilities" placeholder="Responsibilities" required></textarea>
                            <label for="responsibilities" class="form-label">Responsibilities</label>
                            <div class="invalid-tooltip">Responsibilities is required!</div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary save_service_record" type="submit">Save Changes</button>
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
    $(document).on('submit', '#addoccupationForm', function(e) {
        e.preventDefault();
        if (!this.checkValidity()) {
            return;
        }
        $.ajax({
            url: "{{ route('save-application-service') }}",
            type: "POST",
            data: $('#addoccupationForm').serializeArray(),
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            success: function (data) {
                document.getElementById("addoccupationForm").reset();
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
