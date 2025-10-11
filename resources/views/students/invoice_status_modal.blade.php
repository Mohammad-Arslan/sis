<div id="changeInvoiceStatusModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Update Invoice Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-9">
                        <div class="form-label-group in-border me-2">
                            <select class="form-select @if ($errors->has('invoice_frequency')) is-invalid @endif"
                                id="paymentStatus" name="payment_status" aria-label="Invoice type select" required>
                                <option value="paid">Paid</option>
                                <option value="unpaid" selected>Unpaid</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <label for="invoiceFrequency" class="form-label">Payment Status</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('invoice_frequency'))
                                    {{ $errors->first('invoice_frequency') }}
                                @else
                                    Payment Status is required!
                                @endif
                            </div>

                            <input type="hidden" id="studentInvoiceId" value="" />
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="button" onclick="updatePaymentStatus()"
                            class="btn btn-success waves-effect waves-light">Update</button>
                    </div>
                    <div id="datePickerField">
                        <div class="col-8">
                            <div class="form-label-group in-border">
                                <div class="input-group">
                                    <input type="text" class="form-control disable-max-date "
                                        data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                        value="{{ old('paid_date') }}" name="paid_date" id="paidDate" required>
                                    <label for="paidDate" class="form-label">Date <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('paid_date'))
                                            {{ $errors->first('paid_date') }}
                                        @else
                                            Date is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="fee_amount_field">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="number"
                                        class="form-control @if ($errors->has('input_paid_amount')) is-invalid @endif"
                                        id="input_paid_amount" min="0" name="input_paid_amount" value="0"
                                        placeholder="Please enter fee amount" value="{{ old('input_paid_amount') }}"
                                        required>
                                    <label for="input_paid_amount" class="form-label">Fee Amount<span
                                            class="text-danger">*</span></label>
                                    <div class="invalid-tooltip">
                                        @if ($errors->has('input_paid_amount'))
                                            {{ $errors->first('input_paid_amount') }}
                                        @else
                                            Amount is required!
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text"
                                        class="form-control @if ($errors->has('payment_remarks')) is-invalid @endif"
                                        id="payment_remarks" name="payment_remarks"
                                        placeholder="Please enter remarks" value="{{ old('payment_remarks') }}">
                                    <label for="payment_remarks" class="form-label">Remarks</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
    </div>

    @push('footer_scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                console.log('Payment status modal loaded.')
            })
        </script>
    @endpush

    <script>
        $(document).ready(function() {
            toggleFields(); // call this first so we start out with the correct visibility depending on the selected form values
            // this will call our toggleFields function every time the selection value of our other field changes
            $("#paymentStatus").change(function() {
                toggleFields();
            });

        });
        var payDate = $('#paidDate');
        payDate.flatpickr({
            onChange: function(paidDate) {
                var formatedDate = new Date(paidDate)
                var date =
                    `${('0' -'0' + (formatedDate.getMonth() + 1)).slice(-2)}-${formatedDate.getFullYear()}`;
                payDate.val(date);
            }
        })

        // this toggles the visibility of other server
        function toggleFields() {
            if ($("#paymentStatus").val() === "paid") {
                $("#datePickerField").show();
                $("#fee_amount_field").show();
            } else if ($("#paymentStatus").val() === "cancelled") {
                $("#datePickerField").show();
            } else {
                $("#datePickerField").hide();
                $("#fee_amount_field").hide();
            }
        }
    </script>
