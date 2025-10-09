@extends('layouts.master')

@section('content')
@include('components.flash_message')
    <div class="row">
        @if (isset($homeWorkDiary))
            @include('homeworkdiary.edit_homework')
        @else
            {{-- @permission('add-homework-diary') --}}
                @include('homeworkdiary.add_homework')
            {{-- @endpermission --}}
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="mb-0 card-title flex-grow-1">Homework Diary List</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_academic_year_id" name="s_academic_year_id" aria-label="Branch select">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ $academic_year->id }}" {{ $academic_year->active == '1' ? 'selected' : '' }}>{{ $academic_year->title }}</option>
                                    @endforeach
                                </select>
                                <label for="s_academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        @if(isHeadOfficeEmp() || isSuperAdmin())
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_state_id" name="s_state_id" aria-label="Province select">
                                    <option value="">Please select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                <label for="s_state_id" class="form-label">Province</label>
                            </div>
                        </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="filter form-select" id="s_branch_id" name="s_branch_id" aria-label="Branch select">
                                        <option value="">Please select</option>
                                        @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old("branch_id") == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="s_branch_id" class="form-label">Branch</label>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_class_id" name="s_class_id" aria-label="Classes select">
                                    <option value="">Please select</option>
                                    @foreach ($comclasses as $comclass)
                                        <option value="{{ $comclass['id'] }}">{{ $comclass['class_name'] }}</option>
                                    @endforeach
                                </select>
                                <label for="s_class_id" class="form-label">Class</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="s_section_id" name="s_section_id" aria-label="Section select">
                                    <option value="">Please select</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                                <label for="s_section_id" class="form-label">Section</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="input-group form-label-group in-border">
                                <input type="text" class="filter form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="" name="s_homework_date" id="s_homework_date">
                                <div class="text-white input-group-text bg-primary border-primary">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                                <label for="homework_date" class="form-label">Date</label>
                            </div>
                        </div>
                    </div>
                    <table id="homework-list-data-table" class="table mb-0 align-middle table-bordered table-striped table-nowrap"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>AY</th>
                                <th>Province</th>
                                <th>Branch</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>Created By</th>
                                <th>Created On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>AY</th>
                                <th>Province</th>
                                <th>Branch</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>Created By</th>
                                <th>Created On</th>
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


            $('#homework-list-data-table').DataTable({
                searching: false,
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
                ajax:{
                    url:"{{ route('homeWorkDiary.index') }}",
                    data: function(d) {

                        d.branch_id = $('#s_branch_id').val();
                        d.class_id = $('#s_class_id').val();
                        d.section_id = $('#s_section_id').val();
                        d.academic_year_id = $('#s_academic_year_id').val();
                        d.homework_date = $('#s_homework_date').val();
                        }
                    },

                columns: [
                    {
                        data: 'ay',
                        name: 'ay'
                    },
                    {
                        data: 'province',
                        name: 'province'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'class',
                        name: 'class'
                    },
                    {
                        data: 'section',
                        name: 'section'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'createdby',
                        name: 'createdby'
                    },
                    {
                        data: 'created_on',
                        name: 'created_on'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: "text-center"
                    },
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#homework-list-data-table').DataTable().ajax.reload(null, false).page('first');
        });
        // @role('super_admin')
        // $(document).on("keyup", '#mySearch', function() {
        //     var value = $(this).val().toLowerCase();
        //     if (value.length > 0 || value.length == 0) {
        //         $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        //     }
        // });
        // @endrole

        // $(document).ready(function() {
        //     $("#addbutton").click(function(){
        //         var lsthmtl = $(".clone").html();
        //         $(".increment").after(lsthmtl);
        //     });
        //     $("body").on("click",".btn-danger",function(){
        //         $(this).parents(".hdtuto").remove();
        //     });
        // });
    </script>
@endpush
