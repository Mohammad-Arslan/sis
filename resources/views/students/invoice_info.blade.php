<div id='feePackageAlert' role="alert"></div>

{{-- @if (request()->route('student') && $check_student_admission_status && $student_fee_package->fee_package->fee_package_type->name == 'Admission')
    <a href="{{ route('students.attach_monthly_package', $student->id) }}" class="btn btn-success"><i
            class="ri-printer-line align-bottom me-1"></i>
        Generate Monthly Package Invoice</a>
@endif --}}

@if (request()->route('student') != null && isset($student_fee_package))
    {{-- @permission('create-student-invoice') --}}
    @include('students.invoice_info_form')
    {{-- @endpermission --}}
@else
    <p class="text-center">Student doesn't have a fee package.</p>
@endif
<div class="border my-3 border-dashed"></div>
<div class="col-md-4 col-sm-12">
    <div class="form-label-group in-border">
        <select class="filter form-select" id="academic_year_id">
            <option value="">Please select</option>
            @foreach ($academic_years as $academic_year)
                <option @if ($academic_year->academic_year->active == 1) selected @endif
                    value="{{ $academic_year->academic_year->id }}">{{ $academic_year->academic_year->title }} </option>
            @endforeach
        </select>
        <label for="academic_year_id" class="form-label">Academic Year</label>
    </div>
</div>
<div class="col-lg-12">

    <table id="invoices-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
        style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice no.</th>
                <th>Month</th>
                <th>Invoice Type</th>
                <th>Academic Year</th>
                <th>Class</th>
                <th>Section</th>
                <th>Concession %</th>
                <th>Concession Type</th>
                <th>Due Date</th>
                <th>Validity Date</th>
                <th>Invoice Amount</th>
                <th>Due Date Fine</th>
                <th>Discount</th>
                <th>Arrears</th>
                <th>Arrears Carried To This Invoice</th>
                <th>Arrears Cleared Date</th>
                <th>Advance Payment <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Advance is the extra amount paid, which will be applied to future invoices."></i></th>
                <th>Payable</th>
                <th>Paid Amount</th>
                <th>Balance <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Balance is the overpaid amount for this invoice only."></i></th>
                <th>Payment Status</th>
                <th>Payment Date</th>
                <th>Remarks</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Invoice no.</th>
                <th>Month</th>
                <th>Invoice Type</th>
                <th>Academic Year</th>
                <th>Class</th>
                <th>Section</th>
                <th>Concession %</th>
                <th>Concession Type</th>
                <th>Due Date</th>
                <th>Validity Date</th>
                <th>Invoice Amount</th>
                <th>Due Date Fine</th>
                <th>Discount</th>
                <th>Arrears</th>
                <th>Arrears Carried To This Invoice</th>
                <th>Arrears Cleared Date</th>
                <th>Advance Payment <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Advance is the extra amount paid, which will be applied to future invoices."></i></th>
                <th>Payable</th>
                <th>Paid Amount</th>
                <th>Balance <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Balance is the overpaid amount for this invoice only."></i></th>
                <th>Payment Status</th>
                <th>Payment Date</th>
                <th>Remarks</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

</div>

@include('students.invoice_status_modal')

@push('footer_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script type="text/javascript">
        // function updatePaymentStatus() {
        //     $.ajax({
        //         url: `student-invoices/update-payment-status/${$('#studentInvoiceId').val()}?payment_status=${$('#paymentStatus').val()}`,
        //         data: {
        //             'paid_date': $('#paidDate').val(),
        //             'paid_amount': $('#input_paid_amount').val(),
        //             'remarks': $('#payment_remarks').val(),
        //         },
        //         success: function(result) {
        //             console.log(result)
        //             $('#changeInvoiceStatusModal').modal('toggle');
        //             $('#invoices-data-table').DataTable().ajax.reload(null, false)
        //             if ($('#paymentStatus').val() == 'paid')
        //                 location.reload();
        //         }
        //     });
        // }

        function updatePaymentStatus() {
            $.ajax({
                url: `student-invoices/update-payment-status/${$('#studentInvoiceId').val()}?payment_status=${$('#paymentStatus').val()}`,
                method: 'POST', // Laravel validation works on POST/PUT etc.
                data: {
                    'paid_date': $('#paidDate').val(),
                    'paid_amount': $('#input_paid_amount').val(),
                    'remarks': $('#payment_remarks').val(),
                    _token: '{{ csrf_token() }}' // Important for POST
                },
                success: function(result) {
                    console.log(result);
                    $('#changeInvoiceStatusModal').modal('toggle');
                    $('#invoices-data-table').DataTable().ajax.reload(null, false);
                    //if ($('#paymentStatus').val() == 'paid')
                    //location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        // Clear previous errors
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-tooltip').html('');

                        // Show new errors
                        if (errors.paid_date) {
                            $('#paidDate').addClass('is-invalid');
                            $('#paidDate').closest('.input-group').find('.invalid-tooltip').html(errors
                                .paid_date[0]);
                        }
                        if (errors.paid_amount) {
                            $('#input_paid_amount').addClass('is-invalid');
                            $('#input_paid_amount').closest('.form-label-group').find('.invalid-tooltip').html(
                                errors.paid_amount[0]);
                        }
                    }
                }
            });
        }

        function changePaymentStatus(invoiceId) {
            $('#studentInvoiceId').val(invoiceId);
            $('#changeInvoiceStatusModal').modal('show');
        }

        $(document).ready(function() {
            // Tooltip initializer
            const tooltipInit = () => {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(el) {
                    new bootstrap.Tooltip(el);
                });
            };

            // Bootstrap styling for search/filter
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            console.log('Initializing DataTable...');
            const table = $('#invoices-data-table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                bLengthChange: false,
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('student-invoices.index', ['student' => isset($student) ? $student->id : 0]) }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                    },
                    dataSrc: function(json) {
                        //console.log('Processing data, data length:', json.data ? json.data.length :
                        //    'no data property');
                        //console.log('Full response in dataSrc:', json);
                        return json.data || [];
                    },
                    error: function(xhr, error, thrown) {
                        //console.error('Ajax error:', error, thrown);
                        //console.log('Response:', xhr.responseText);
                    }
                },
                columns: [{
                        data: 'invoice.id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'invoice.invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'invoice.invoice_type.name',
                        name: 'invoice_type',
                        defaultContent: '-'
                    },
                    {
                        data: 'invoice.student_fee_package.academic_year.title',
                        name: 'academic_year',
                        defaultContent: '-'
                    },
                    {
                        data: 'invoice.student_fee_package.com_class.class_name',
                        name: 'class',
                        defaultContent: '-'
                    },
                    {
                        data: 'invoice.student_fee_package.section.section_name',
                        name: 'section',
                        defaultContent: '-'
                    },
                    {
                        data: 'invoice.student_fee_package.fee_concession.concession_percentage',
                        name: 'concession_percentage',
                        defaultContent: '-'
                    },
                    {
                        data: 'invoice.student_fee_package.fee_concession.fee_concession_type.name',
                        name: 'concession_type',
                        defaultContent: '-'
                    },
                    {
                        data: 'invoice.due_date',
                        name: 'due_date',
                    },
                    {
                        data: 'invoice.validity_date',
                        name: 'validity_date',
                    },
                    {
                        data: 'items_total',
                        name: 'invoice_amount',
                        width: "15%",
                        render: function(data) {
                            return parseFloat(data).toLocaleString('en-IN');
                        }
                    },
                    {
                        data: 'invoice.due_date_fine',
                        name: 'fine',
                        width: "15%",
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('en-IN') : '0';
                        }
                    },
                    {
                        data: 'invoice.student_fee_package.fee_concession.concession_percentage',
                        name: 'discount',
                        width: "15%",
                        render: function(data) {
                            return data ? data + '%' : '0%';
                        }
                    },
                    {
                        data: 'arrears',
                        name: 'arrears',
                        width: "15%",
                        render: function(data) {
                            return parseFloat(data).toLocaleString('en-IN');
                        }
                    },
                    {
                        data: 'arrears_breakdown',
                        name: 'arrears_carried_to',
                        width: "20%",
                        render: function(data, type, row) {
                            if (data && data.length) {
                                let html = '<ul style="margin:0; padding-left: 1em;">';
                                data.forEach(function(item) {
                                    html += '<li>';
                                    html += 'Amount: ' + parseFloat(item.amount).toLocaleString('en-IN');
                                    if (item.from_month_label) {
                                        html += ' (From: ' + item.from_month_label + ')';
                                    }
                                    html += '</li>';
                                });
                                html += '</ul>';
                                return html;
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'arrears_breakdown',
                        name: 'arrears_cleared_date',
                        width: "15%",
                        render: function(data) {
                            if (data && data.length) {
                                let html = '<ul style="margin:0; padding-left: 1em;">';
                                data.forEach(function(item) {
                                    html += '<li>';
                                    if (item.cleared_date) {
                                        html += moment(item.cleared_date).format('DD MMM YYYY');
                                    } else {
                                        html += '-';
                                    }
                                    html += '</li>';
                                });
                                html += '</ul>';
                                return html;
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'advance_breakdown',
                        name: 'advance_payment',
                        width: "15%",
                        render: function(data, type, row) {
                            if (row.advance_payment === 0) return '-';
                            if (data && data.length) {
                                let html = '<ul style="margin:0; padding-left: 1em;">';
                                data.forEach(function(item) {
                                    html += '<li>';
                                    html += 'Amount: ' + Math.abs(parseFloat(item.amount)).toLocaleString('en-IN');
                                    if (item.from_month_label) {
                                        html += ' (From: ' + item.from_month_label + ')';
                                    }
                                    if (item.carried_date) {
                                        let dateObj = new Date(item.carried_date);
                                        let month = dateObj.toLocaleString('default', { month: 'long' });
                                        let year = dateObj.getFullYear();
                                    }
                                    html += '</li>';
                                });
                                html += '</ul>';
                                return html;
                            }
                            return Math.abs(parseFloat(row.advance_payment)).toLocaleString('en-IN');
                        }
                    },
                    {
                        data: 'total_payable',
                        name: 'payable',
                        width: "15%",
                        render: function(data) {
                            return parseFloat(data).toLocaleString('en-IN');
                        }
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                        orderable: false,
                        searchable: false,
                        className: "text-nowrap",
                        render: function(data, type, row) {
                            // Show 0 if unpaid
                            if(row.payment_status === 'unpaid') {
                                return '0';
                            }
                            return parseFloat(data).toLocaleString('en-IN');
                        }
                    },
                    {
                        data: null,
                        name: 'balance',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Show balance only for overpaid invoices
                            if(row.payment_status === 'overpaid') {
                                var balance = parseFloat(row.paid_amount) - parseFloat(row.total_payable);
                                return balance > 0 ? balance.toLocaleString('en-IN') : '-';
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'invoice.bank_payment_status',
                        name: 'payment_status',
                        render: function(data) {
                            switch (data) {
                                case 'paid':
                                    return '<span class="badge bg-success">Paid</span>';
                                case 'unpaid':
                                    return '<span class="badge bg-danger">Unpaid</span>';
                                case 'cancelled':
                                    return '<span class="badge bg-secondary">Cancelled</span>';
                                case 'overpaid':
                                    return '<span class="badge bg-info">Overpaid</span>';
                                case 'partially_paid':
                                    return '<span class="badge bg-warning">Partially Paid</span>';
                                default:
                                    return '<span class="badge bg-secondary">' + (data ? data : 'Adjusted') + '</span>';
                            }
                        }
                    },
                    {
                        data: 'invoice.paid_date',
                        name: 'paid_date'
                    },
                    {
                        data: 'invoice.remarks',
                        name: 'remarks',
                        defaultContent: '-'
                    },
                    {
                        data: 'invoice.created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'invoice.id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        render: function(data) {
                            return `
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item show-modal" href="javascript:void(0);" data-url="{{ url('student-invoices') }}/${data}" data-target="#invoiceDetailModal"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                        <li><a class="dropdown-item" href="{{ url('student-invoices') }}/${data}/edit"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        <li><button class="dropdown-item" type="button" onclick="changePaymentStatus(${data})"><i class="ri-bank-card-line align-bottom me-2 text-muted"></i> Payment Status</button></li>
                                    </ul>
                                </div>
                            `;
                        }
                    }
                ],
                drawCallback: function() {
                    tooltipInit(); // Re-initialize tooltips every redraw
                }
            });

            // Reload on filter change
            $(document).on('change', '.filter', function() {
                table.ajax.reload(null, false);
            });
        });
    </script>
@endpush
