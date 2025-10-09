<div class="accordion-item mt-3">
    <h2 class="accordion-header" id="accordionborderedSLOs">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse2" aria-expanded="false" aria-controls="accor_borderedcollapse2" @if(!isset($lessonPlan)) disabled @endif>
            Student Learning Outcomes
        </button>
    </h2>
    <div id="accor_borderedcollapse2" class="accordion-collapse collapse" aria-labelledby="accordionborderedSLOs" data-bs-parent="#accordionBordered" style="">
        <div class="accordion-body">
            @if(isset($lessonPlan))
                <div class="table-responsive">
                    <table class="table align-top table-bordered {{$lessonPlan['language'] == 'urdu' ? 'text_dir_rtl' : ''}}" id="lesson_plan_table">
                        <thead>
                        <tr class="table-primary">
                            <th style="width:15%">{{__('Student Learning Outcomes')}}</th>
                            <th style="width:38%">{{__('Methodology')}}</th>
                            <th style="width:7%">{{__('Time') . ' ('.__('min').')'}}</th>
                            <th style="width:20%">{{__('Resources')}}</th>
                            <th style="width:15%">{{__('Assessment')}}</th>
                            <th style="width:5%">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($lessonPlan) && isset($lessonPlan['student_learning_outcomes']))
                            @foreach($lessonPlan['student_learning_outcomes'] as $outer_index => $SLO)
                               @include('lesson_plan.slo_row',['slo_count' => $outer_index + 1 ,'SLO' => $SLO])
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <a href="javascript:void(0)" class="btn btn-success" id="add-slo-row"><i class="ri-add-line align-bottom me-1"></i>Add SLO</a>

                    <input type="hidden" class="slo_count" value="{{isset($lessonPlan['student_learning_outcomes']) ? count($lessonPlan['student_learning_outcomes']) : '0'}}">
                    <input type="hidden" class="active_td_class_name">
                </div>
            @endif
        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            let editor;
            // ClassicEditor.create(document.querySelector('.ckeditor-classic'));
            // ClassicEditor
            //     .create( document.querySelector('.ckeditor-classic') )
            //     .then( newEditor => {
            //         editor = newEditor;
            //     })
            //     .catch( error => {
            //         console.error( error );
            //     });

            var snowEditor = document.querySelectorAll(".snow-editor")
            snowEditor.forEach(function (item) {
                var snowEditorData = {};
                var issnowEditorVal = item.classList.contains("snow-editor");
                if (issnowEditorVal == true) {
                    snowEditorData.theme = 'snow',
                        snowEditorData.modules = {
                            'toolbar': [
                                [{
                                    'font': []
                                }, {
                                    'size': []
                                }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{
                                    'color': []
                                }, {
                                    'background': []
                                }],
                                [{
                                    'script': 'super'
                                }, {
                                    'script': 'sub'
                                }],
                                [{
                                    'header': [false, 1, 2, 3, 4, 5, 6]
                                }, 'blockquote', 'code-block'],
                                [{
                                    'list': 'ordered'
                                }, {
                                    'list': 'bullet'
                                }, {
                                    'indent': '-1'
                                }, {
                                    'indent': '+1'
                                }],
                                ['direction', {
                                    'align': []
                                }],
                                ['link', 'image', 'video'],
                                ['clean']
                            ]
                        }
                }
                editor = new Quill(item, snowEditorData);
            });

            $(document).on('click','#add-slo-row',function(e){
                $.ajax({
                    url: '{{route('lesson-plans.add-slo-row')}}',
                    type: 'POST',
                    data: {
                        slo_count: $('.slo_count').val()
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function (data) {
                        if (data.code == 200){
                            let slo_row = data.data.slo_row;
                            $('#lesson_plan_table').append(slo_row);
                            $('.slo_count').val(data.data.slo_count);
                        }
                    },
                    error: function () {

                    },
                    beforeSend: function () {

                    },
                    complete: function () {
                    }
                });
            });

            $(document).on('click','.remove-slo',function(e) {
                let slo_no = $(this).data('slo-no');
                let student_learning_outcome_id = $('.student_learning_outcome_id_'+slo_no).val();

                Swal.fire({
                    html: '<div class="mt-3">' +
                        '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                        '<div class="mt-4 pt-2 fs-15 mx-5">' +
                        '<h4>Are you sure?</h4>' +
                        '<p class="text-muted mx-4 mb-0">Are you Sure You want to Delete SLO '+slo_no+' ?</p>' +
                        '</div>' +
                        '</div>',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, Delete It!',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'lesson-plans/remove-slo/'+student_learning_outcome_id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            cache: false,
                            success: function (data) {
                                $('.remove_slo'+slo_no).remove();
                            },
                            error: function () {

                            },
                            beforeSend: function () {

                            },
                            complete: function () {
                            }
                        });
                    }
                });
            });

            $(document).on('click','.add-activity',function(e){
                let slo_no = $(this).data('slo-no');
                let activity_no = $('.slo_activity_count'+slo_no).val();
                $.ajax({
                    url: '{{route('lesson-plans.add-activity')}}',
                    type: 'POST',
                    data: {
                        slo_no: slo_no,
                        activity_no: activity_no
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function (data) {
                        if (data.code == 200){
                            let activity_row = data.data.activity_row;
                            $('.add_activity_row'+slo_no).before(activity_row);
                            let rowspan = $('.slo_td'+slo_no).attr('rowspan');
                            $('.slo_td'+slo_no).attr('rowspan', parseInt(rowspan) + 1);
                            let activity_no = $('.slo_activity_count'+slo_no).val();
                            $('.slo_activity_count'+slo_no).val(parseInt(activity_no) + 1);
                        }
                    },
                    error: function () {

                    },
                    beforeSend: function () {

                    },
                    complete: function () {
                    }
                });
            });

            $(document).on('click','.remove-activity',function(e) {
                let slo_no = $(this).data('slo-no');
                let activity_no = $(this).data('activity-no');
                let student_activity_id = $('.student_activity_id_'+slo_no+'_'+activity_no).val();
                var this_var = $(this);

                Swal.fire({
                    html: '<div class="mt-3">' +
                        '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                        '<div class="mt-4 pt-2 fs-15 mx-5">' +
                        '<h4>Are you sure?</h4>' +
                        '<p class="text-muted mx-4 mb-0">Are you Sure You want to Delete this Record ?</p>' +
                        '</div>' +
                        '</div>',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    confirmButtonText: 'Yes, Delete It!',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function(result) {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'lesson-plans/remove-activity/'+student_activity_id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            cache: false,
                            success: function (data) {
                                this_var.closest('tr').remove();
                                let rowspan = $('.slo_td'+slo_no).attr('rowspan');
                                $('.slo_td'+slo_no).attr('rowspan', parseInt(rowspan) - 1);
                            },
                            error: function () {

                            },
                            beforeSend: function () {

                            },
                            complete: function () {
                            }
                        });
                    }
                });

            });

            $(document).on('click','.open_editor_modal',function(e) {
                $('#EditorModal').modal('show');
                $('.active_td_class_name').val($(this).data('class-name'));
                let editorHTML = $('.'+$(this).data('class-name')+'_text').html();
                // editor.setData(editorHTML); //For CKeditor
                editor.root.innerHTML = editorHTML; //For Quill Editor
                @if(isset($lessonPlan) && $lessonPlan['language'] == 'urdu')
                    editor.format('direction', 'rtl');
                    editor.format('align', 'right');
                    $(".ql-editor").attr('style',"line-height: 2.3 !important");
                @endif
            });

            $(document).on('click','#submit_editor',function(e) {
                // saveData(editor.getData()); //For CKeditor
                saveData(editor.root.innerHTML); //For Quill Editor
            });

            $(document).on('click','#close_editor',function(e) {
                $('#EditorModal').modal('hide');
            });

            $(document).on('change','.student_activity_duration',function(e) {
                $('.active_td_class_name').val($(this).data('class-name'));
                saveData($(this).val());
            });
        });

        function saveData(value){
            const editorData = value;
            let active_td_class_name = $('.active_td_class_name').val();
            let column_name = active_td_class_name?.split('_')[0];
            let slo_no = active_td_class_name?.split('_')[1];
            let activity_no = active_td_class_name?.split('_')[2];
            let student_learning_outcome_id = $('.student_learning_outcome_id_'+slo_no).val();
            let student_activity_id = $('.student_activity_id_'+slo_no+'_'+activity_no).val();

            $.ajax({
                url: 'lesson-plans/'+'{{isset($lessonPlan) ? $lessonPlan['id'] : 0}}',
                type: 'PATCH',
                data: {
                    editorData: editorData,
                    student_learning_outcome_id: student_learning_outcome_id,
                    student_activity_id: student_activity_id,
                    column_name: column_name.replace("-","_"),
                },
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                cache: false,
                success: function (data) {
                    if (data.code == 200){

                        if ($('#EditorModal').hasClass('show')){
                            //if contains img and video then dont slice,because on slice might be video path or base:64 get sliced
                            //let sliced_text = editorData.includes('<iframe') || editorData.includes('<img') ? editorData : editorData.slice(0, 150)+'...';
                            let sliced_text = editorData;
                            $('.'+active_td_class_name).empty().append(sliced_text);
                            $('.'+active_td_class_name+'_text').empty().append(editorData);
                            $('#EditorModal').modal('hide');
                        }

                        $('.student_learning_outcome_id_'+slo_no).val(data.data.student_learning_outcome_id);
                        $('.student_activity_id_'+slo_no+'_'+activity_no).val(data.data.student_activity_id);
                    }
                },
                error: function () {

                },
                beforeSend: function () {

                },
                complete: function () {
                }
            });
        }

    </script>
@endpush
