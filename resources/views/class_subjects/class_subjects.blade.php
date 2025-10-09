@extends('layouts.master')

@section('content')
    <div class="row">
        {{-- Display Flash Messages --}}
        @include('components.flash_message')
        
        @include('class_subjects.class_subject_form')

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Class Subject List</h4>
                    <!-- <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                            <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                        </div>
                    </div> -->
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="class_id" name="class_id" placeholder="Class">
                                    <option value="">Please select</option>
                                    @foreach ($com_classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <label for="class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="state_id" name="state_id" placeholder="States">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="state_id" class="form-label">States</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="subject_id" name="subject_id" placeholder="Subjects">
                                    <option value="">Please select</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                                <label for="subject_id" class="form-label">Subjects</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <input id="mySearch" type="text" placeholder="Search.." class="form-control">
                                <label for="mySearch" class="form-label">Search...</label>
                            </div>
                        </div>
                    </div>
                    <table id="class-subject-datatable" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Branch</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>State</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Branch</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>State</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>


                </div>
            </div>
        </div>
    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#class-subject-datatable').DataTable({
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
                searchPlaceholder: "Search..."
            },
            ajax: {
                    url: "{{ route('class-subjects.index') }}",
                    data: function(d) {
                        d.class_id = $('#class_id').val();
                        d.subject_id = $('#subject_id').val();
                        d.state_id = $('#state_id').val();
                        d.searchName = $('#mySearch').val().toLowerCase();
                    }
                },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'branch',
                    name: 'branch',
                    width: "5%"
                },
                {
                    data: 'class.class_name',
                    name: 'class.class_name'
                },
                {
                    data: 'subject.subject_name',
                    name: 'subject.subject_name'
                },
                {
                    data: 'state',
                    name: 'state'
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
            $('#class-subject-datatable').DataTable().ajax.reload(null, false).page('first');
        });

        $(document).on("keyup", '#mySearch', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 0 || value.length == 0) {
                $('#class-subject-datatable').DataTable().ajax.reload(null, false).page('first');
            }
        });

        // Store old values for restoration
        var oldClassId = '{{ old("class_id") }}';
        var oldBranchId = '{{ old("branch_id") }}';

        // Override the loadSelectHandler for class subjects form
        $(document).on('change', '#branch', function() {
            var target = $(this).data('target');
            var url = $(this).data('url');
            
            if (target === 'class_id') {
                var target_name = 'class';
                var url_param_split = url.split('/');
                var url_param = url_param_split[url_param_split.length - 1];
                url = url + '?id=' + $(this).val();
                
                $.ajax({
                    url: url,
                    type: "GET",
                    cache: false,
                    success: function(data) {
                        var options = `<option value="">Please select a ${target_name}</option>`;
                        
                        if (data) {
                            $.each(data, function(index, value) {
                                var selected = '';
                                // Use com_classes.id instead of BranchClass.id
                                var classId = value.com_classes ? value.com_classes.id : value.id;
                                var className = value.com_classes ? value.com_classes.class_name : value.class_name;
                                
                                if (oldClassId && oldClassId == classId) {
                                    selected = 'selected';
                                }
                                options += `<option value="${classId}" ${selected}>${className}</option>`;
                            });
                        }
                        
                        $('#class_id').html(options).attr('disabled', false);
                    },
                    error: function() {
                        console.log('Error loading classes');
                    }
                });
            }
        });

        // Restore values on page load
        $(document).ready(function() {
            if (oldBranchId && oldBranchId !== '') {
                $('#branch').val(oldBranchId);
                // Trigger change to load classes for the selected branch
                $('#branch').trigger('change');
            }
        });
    </script>
@endpush
