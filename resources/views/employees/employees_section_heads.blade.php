<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table id="employee-section-head"
                        class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name{{ $branch->id }}</th>
                                <th>Gender</th>
                                <th>Company</th>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>City</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Company</th>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>City</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#employee-section-head').dataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('get-employees', ['id' => $branch->id, 'desig' => 'Section Head']) }}",
                    data: function(d) {
                        d.gender = $('#gender').val();
                        d.company_id = $('#company_id').val();
                        d.branch_id = $('#branch_id').val();
                        d.department_id = $('#department_id').val();
                        d.designation_id = $('#designation_id').val();
                    }
                },
                columns: [{
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'user.gender',
                        name: 'user.gender'
                    },
                    {
                        data: 'company.company_name',
                        name: 'company.company_name'
                    },
                    {
                        data: 'branch.br_name',
                        name: 'branch.br_name'
                    },
                    {
                        data: 'department.department_name',
                        name: 'department.department_name'
                    },
                    {
                        data: 'designation.designation_name',
                        name: 'designation.designation_name'
                    },
                    {
                        data: 'cities.city_name',
                        name: 'cities.city_name'
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
                        width: "5%",
                        sClass: 'text-center'
                    }
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#employee-section-head').DataTable().ajax.reload(null, false);
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#employee-section-head').DataTable().ajax.reload(null, false);
            }
        });
    </script>
@endpush
