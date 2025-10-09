<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">&nbsp;</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('branches.index') }}" class="btn btn-primary btn-label btn-sm">
                        <i class="ri-arrow-left-fill label-icon align-middle fs-16 me-2"></i> Cancel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="filter form-select" id="type_id" name="type_id" placeholder="Branch Staff">
                                <option value="">Please Select Staff Type</option>
                                @foreach ($designation_type as $types)
                                    <option value="{{ $types->id }}">{{ $types->type_name }}</option>
                                @endforeach
                            </select>
                            <label for="type_id" class="form-label">Staff Type</label>
                        </div>
                    </div>

                    {{-- <div class="col-md-2 col-sm-12">
                       <div class="form-label-group in-border">
                           <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                           <label for="mySearch" class="form-label">Search...</label>
                       </div>
                    </div> --}}
                </div>
                <div class="row">
                    <table id="employee-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Company</th>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Staff</th>
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
                                <th>Staff</th>
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

            $('#employee-table').dataTable({
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
                    url: "{{ route('get-employees', ['id' => $branch->id]) }}",
                    data: function(d) {
                        d.type_id = $('#type_id').val();
                        //d.searchName = $('#mySearch').val().toLowerCase();
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
                        data: 'designation_type_name',
                        name: 'designation_type_name'
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
            $('#employee-table').DataTable().ajax.reload(null, false);
        });

        /*$(document).on("keyup",'#mySearch', function() {
    		var value = $(this).val().toLowerCase();
    		if(value.length > 0 || value.length == 0){
    			$('#employee-table').DataTable().ajax.reload(null, false);
    		}
    	});*/
    </script>
@endpush
