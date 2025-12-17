<div class="row">
    {{-- <div></div> --}}
    <div class="col-lg-12">
        @if(isset($academic_years))
        <div class="col-md-4">
            <div class="form-label-group in-border">
                <select class="filter form-select" id="academic_year_id">
                    <option value="">Please select</option>
                    @foreach ($academic_years as $academic_year)
                            <option @if($academic_year->academic_year->active == 1) selected @endif value="{{ $academic_year->academic_year->id }}">{{ $academic_year->academic_year->title }} </option>
                    @endforeach
                </select>
                <label for="academic_year_id" class="form-label">Academic Year</label>
            </div>
        </div>
        @endif
        <table id="student-ledger-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
            style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Academic Year</th>
                    <th>Month</th>
                    <th>Issue Date</th>
                    <th>Payment Date</th>
                    <th>Invoice No.</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Academic Year</th>
                    <th>Month</th>
                    <th>Issue Date</th>
                    <th>Payment Date</th>
                    <th>Invoice No.</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#student-ledger-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                order: [
                    [0, 'asc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                // ajax: "{{ route('student-ledger.index', ['student' => isset($student) ? $student->id : 0]) }}",
                ajax: {
                    url: "{{ route('student-ledger.index', ['student' => isset($student) ? $student->id : 0]) }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                    }
                },
                columns: [{
                        data: 'sort',
                        name: 'sort',
                        width: "5%"
                    },
                    {
                        data: 'invoice.student_fee_package.com_class.class_name',
                        name: 'invoice.student_fee_package.com_class.class_name'
                    },
                    {
                        data: 'invoice.student_fee_package.section.section_name',
                        name: 'invoice.student_fee_package.section.section_name'
                    },
                    {
                        data: 'invoice.student_fee_package.academic_year.title',
                        name: 'invoice.student_fee_package.academic_year.title'
                    },
                    {
                        data: 'month_string',
                        name: 'month_string'
                    },
                    {
                        data: 'issue_date',
                        name: 'issue_date'
                    },
                    {
                        data: 'payment_date',
                        name: 'payment_date'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'debit',
                        name: 'debit'
                    },
                    {
                        data: 'credit',
                        name: 'credit'
                    },
                    {
                        data: 'balance',
                        name: 'balance',
                    }
                ]
            });
            $(document).on('change', '.filter', function() {
                $('#student-ledger-table').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
