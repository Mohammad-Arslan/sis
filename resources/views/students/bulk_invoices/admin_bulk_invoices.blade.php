@extends('layouts.master')


@section('content')
    @include('components.flash_message')
    <div id="alertDiv"></div>
    <div class="row">
        @include('students.bulk_invoices.admin_students_datatable')
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $('.fetch_data').on('click', function() {
            let branch_id = $('#branch_id').val();
            let academic_year_id = $('#academic_year_id').val();

            $.ajax({
                url: '{{ route('get-bulk-invoice-data') }}',
                type: 'GET',
                data: {
                    branch_id: branch_id,
                    academic_year_id: academic_year_id
                },
                cache: false,
                success: function(result) {
                    console.log(result)
                    $('.fetched_data_div').html(result)
                },
                error: function(error) {
                    console.log("Sorry! Server error!");
                    console.log(error);
                },
                timeout: 8000
            }).fail(function(jqXHR, textStatus) {
                if (textStatus === 'timeout') {
                    console.log("Sorry Please Wait... Slow connection!");
                }
            });
        });

        var selectedStudents = [];
        // $(document).ready(function() {
        var datatable_url = '/list-invoice-students'
        var table_id = '#branches-students-list'
        $(document).on('click', '#fetch_student_invoices', function(e) {
            e.preventDefault();
            $('#branches-students-list').DataTable().destroy();
            $('.form-check-input').prop('checked', false)
            let branches = $('#branch_id').val();
            let classes = $('#class_id').val();
            let sections = $('#section_id').val();
            let feePeriod = $('#feePeriodFilter');
            let feePeriodInput = $('#feePeriod');

            // alert(branches);
            feePeriod.on('change', function() {
                populateDatatable()
            })

            feePeriodInput.on('change', function() {
                populateDatatable()
            })

            $('#branches-students-list').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 100,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: datatable_url,
                    data: function(d) {
                        d.branches = branches
                        d.classes = classes
                        d.sections = sections
                        d.filters = {
                            feePeriod: feePeriod.val(),
                            feePeriodInput: feePeriodInput.val()
                        }
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: "5%"
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'active_class.branch_class_sections.com_classes.class_name',
                        name: 'active_class.branch_class_sections.com_classes.class_name'
                    },
                    {
                        data: 'active_class.branch_class_sections.sections.section_name',
                        name: 'active_class.branch_class_sections.sections.section_name'
                    },
                    {
                        data: 'invoice_status',
                        name: 'invoice_status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '5%'
                    },
                ]
            });
        })

        function populateDatatable() {
            $(table_id).DataTable().ajax.reload(null, false)
        }
        $(document).ready(function() {
            selectAllItems('selectAllStudents', 'student_checkbox', selectedStudents, function() {
                console.log(selectedStudents)
            });

            $("#loadPaidDate").flatpickr();
        });

        $('#bulk-invoices-status-form').submit(function(event) {
            event.preventDefault();
            const fee_period = $('#feePeriod').val();
            const paid_date = $('#loadPaidDate').val();

            $.ajax({
                url: '/change-bulk-invoices-status',
                method: 'POST',
                data: {
                    students: selectedStudents,
                    fee_period,
                    paid_date,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    document.getElementById('alertDiv').innerHTML =
                        "<div class='alert alert-success alert-dismissible alert-label-icon label-arrow fade show' role='alert'><i class='ri-notification-off-line label-icon'></i><strong>Success</strong>- Invoices paid successsfully<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"
                }
            })

            console.log(selectedStudents, fee_package, fee_period);
        })
    </script>
@endpush
