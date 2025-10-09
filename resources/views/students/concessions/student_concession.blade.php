<div id='guardianAlert' role="alert"></div>

@if(isset($student_concession))
 @include('students.concessions.edit_concession')
@else

    {{-- @permission('create-student-concession') --}}
    @include('students.concessions.add_concession')
    {{-- @endpermission --}}
@endif
<div class="border my-3 border-dashed"></div>

<div class="col-lg-12">

    <table id="student-concession-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Academic Year</th>
                <th>Fee Charges</th>
                <th>Fee Concession</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Academic Year</th>
                <th>Fee Charges</th>
                <th>Fee Concession</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

</div>

@push('footer_scripts')

<script type="text/javascript">
    $(document).ready(function() {
        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        $('#student-concession-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                searchPlaceholder: "Search..."
            },
            ajax: "{{ route('student-concession.index', [ 'student' => isset($student) ? $student->id : 0 ]) }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    width: "5%"
                },
                {
                    data: 'academic_year',
                    name: 'academic_year'
                },
                {
                    data: 'fee_charge',
                    name: 'fee_charge'
                },
                {
                    data: 'fee_concession',
                    name: 'fee_concession'
                },
                {
                    data: 'from_date',
                    name: 'from_date'
                },
                {
                    data: 'to_date',
                    name: 'to_date'
                },
                {
                        data: 'is_valid',
                        width: '10%',
                        render: function(data, type, row) {

                            if (row.is_valid == '1') {
                                return '<span class="badge bg-success">Valid</span>';
                            } else {
                                return '<span class="badge bg-warning">Invalid</span>';
                            }
                        }
                    },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: "5%"
                }
            ]
        });
    });
</script>
@endpush
