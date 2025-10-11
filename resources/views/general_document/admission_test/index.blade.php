@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Admission Test</li>
    </x-breadcrumb>
    @include('components.flash_message')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Admission Tests List</h4>
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
                                <select class="filter form-select" id="state_id" {{$states->count() == 1 ? 'disabled' : ''}}>
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}" {{$states->count() == 1 ? 'selected' : ''}}>{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="state_id" class="form-label">Province</label>
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
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="subject_id">
                                    <option value="">Please select</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                                <label for="subject_id" class="form-label">Subject</label>
                            </div>
                        </div>
                    </div>
                    <table id="admissionTestTable" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                        <thead>
                        <tr>
                            <th>Document Name</th>
                        <th>Province</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Document Name</th>
                        <th>Province</th>
                        <th>Class</th>
                        <th>Subject</th>
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

            $('#admissionTestTable').DataTable({
                processing: true,
                serverSide: true,
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
                    url: "{{ route('general-document.admission-test.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.academic_year_id = $('#academic_year_id').val();
                        d.com_class_id = $('#com_class_id').val();
                        d.state_id = $('#state_id').val();
                        d.subject_id = $('#subject_id').val();
                    }
                },
                columns: [
                    {
                        data: 'document_name',
                        name: 'document_name'
                    },
                    {
                        data: 'state_name',
                        name: 'state_name'
                    },
                    {
                        data: 'class_name',
                        name: 'class_name'
                    },
                    {
                        data: 'subject_name',
                        name: 'subject_name'
                    },
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
                $('#admissionTestTable').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
