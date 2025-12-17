<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Skills List</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="form-select filter" id="term_id_list" name="term_id_list">
                            <option value="">Please select a Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                        <label for="section" class="form-label">Term <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="load-select form-select filter"
                                data-url="{{ route('get-class-subjects-by-classID') }}" data-target="subject_id_list" id="class_id_list" name="class_id_list">
                            <option value="">Please select a class</option>
                            @foreach ($com_classes as $class)
                                <option value="{{ $class->id }}">{{ $class['class_name'] }}</option>
                            @endforeach
                        </select>
                        <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="form-select filter" id="subject_id_list" name="subject_id_list" aria-label="Select Subject">
                            <option value="">Please select a subject</option>
                            @if(isset($subjects))
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="sectionID" class="form-label">Subject <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-label-group in-border">
                        <select class="form-select filter" id="type_list" required>
                            <option value="">Please select</option>
                            <option value="title">Title</option>
                            <option value="grade">Grade</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                        <label for="section" class="form-label">Skill Type <span class="text-danger">*</span></label>
                        <div class="invalid-tooltip">
                            @if($errors->has('type'))
                                {{ $errors->first('type') }}
                            @else
                                Skill Type is required!
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <table id="skill-datatable" class="table table-bordered table-striped align-middle mb-0"
                   style="width:100%">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Parent</th>
                    <th>Term</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>Title</th>
                    <th>Parent</th>
                    <th>Term</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>


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

            $('#skill-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: `<img class='ucs_loader' src='{{asset('loader.gif')}}' />`,
                    searchPlaceholder: "Search..."
                },
                ajax: {
                    url: "{{ route('skill.index') }}",
                    dataType: "json",
                    method: 'GET',
                    data: function(d) {
                        d.class_id = $('#class_id_list').val();
                        d.subject_id = $('#subject_id_list').val();
                        d.term_id = $('#term_id_list').val();
                        d.type = $('#type_list').val();
                    }
                },
                columns: [
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'parent',
                        name: 'parent'
                    },
                    {
                        data: 'term.name',
                        name: 'term.name',
                        width: "7%"
                    },
                    {
                        data: 'com_class.class_name',
                        name: 'com_class.class_name',
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'subject.subject_name',
                        name: 'subject.subject_name',
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "10%"
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

            $(document).on('change', '.filter', function() {
                $('#skill-datatable').DataTable().ajax.reload(null, false);
            });
        });
    </script>
@endpush
