@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Attachments</li>
    </x-breadcrumb>
    @include('components.flash_message')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Datesheet List</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        @if(!auth()->user()->hasRole('super_admin'))
                                            @if(get_set_NWABranchId())
                                                <option value="{{ $academic_year->id }}" {{ get_current_acad_year_by_branch_id(get_set_NWABranchId()) && get_current_acad_year_by_branch_id(get_set_NWABranchId())->academic_year_id == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                            @else
                                                <option value="{{ $academic_year->id }}" {{ get_current_acad_year_by_branch_id(auth()->user()['employee']['branch_id']) && get_current_acad_year_by_branch_id(auth()->user()['employee']['branch_id'])->academic_year_id == $academic_year->id ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="com_class_id">
                                    <option value="">Please select</option>
                                    @foreach ($classes as $com_class)
                                        <option value="{{ $com_class->id }}">{{ $com_class->class_name }}</option>
                                    @endforeach
                                </select>
                                <label for="com_class_id" class="form-label">Class</label>
                            </div>
                        </div>
                    </div>
                    <table id="datesheetTable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Academic Year</th>
                            <th>Class</th>
                            {{--<th>Branch</th>
                            <th>Type</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Status</th>--}}
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Document Name</th>
                            <th>Academic Year</th>
                            <th>Class</th>
                            {{--<th>Branch</th>
                            <th>Type</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Status</th>--}}
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#datesheetTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                order: false,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('general-document.subject-teacher-timetable.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                        d.com_class_id = $('#com_class_id').val();
                    }
                },
                columns: [
                    {
                        data: 'document_name',
                        name: 'document_name'
                    },
                    {
                        data: 'academic_year',
                        name: 'academic_year'
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    /*{
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'uploaded_by',
                        name: 'uploaded_by'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: "5%",
                        sClass: 'text-center'
                    },*/
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: 'text-center'
                    },
                ],
            });

            $(document).on('change', '.filter', function() {
                $('#datesheetTable').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
