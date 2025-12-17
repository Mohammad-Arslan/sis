<div id='guardianAlert' role="alert"></div>

@if(isset($guardian))
@include('students.guardian_info_edit_form')
@else
    {{-- @permission('create-student-parent-info') --}}
    @include('students.guardian_info_form')
    {{-- @endpermission --}}
@endif
<div class="border my-3 border-dashed"></div>

<div class="col-lg-12">

    <table id="guardian-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Emp ID</th>
                <th>Emp Branch</th>
                <th>Student Name</th>
                <th>CNIC</th>
                <th>Mobile Number</th>
                <th>Email</th>
                <th>Relation Name</th>
                <th>Created AT</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Emp ID</th>
                <th>Emp Branch</th>
                <th>Student Name</th>
                <th>CNIC</th>
                <th>Mobile Number</th>
                <th>Email</th>
                <th>Relation Name</th>
                <th>Created AT</th>
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

        $('#guardian-data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            bLengthChange: false,
            pageLength: 10,
            scrollX: true,
            language: {
                search: "",
                processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                searchPlaceholder: "Search..."
            },
            ajax: "{{ route('guardians.index', [ 'student' => isset($student) ? $student->id : 0 ]) }}"+'&guardian_id='+{{isset($guardian) ? $guardian->id : 0}},
            columns: [
                {
                    data: 'guardian_name',
                    name: 'guardian_name'
                },
                {
                    data: 'employee_no',
                    name: 'employee_no',
                    width: "5%"
                },
                {
                    data: 'emp_branch_name',
                    name: 'emp_branch_name',
                    width: "5%"
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'CNIC',
                    name: 'CNIC'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'relation_name',
                    name: 'relation name'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    width: "15%"
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
