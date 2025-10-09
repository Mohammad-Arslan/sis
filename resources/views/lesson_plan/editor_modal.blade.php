<div class="modal fade" id="EditorModal" tabindex="-1" aria-labelledby="EditorModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditorModalLabel">Lesson Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 mt-3">
                    {{--<textarea class="ckeditor-classic"></textarea>--}}
                    <div class="snow-editor {{$lessonPlan['language'] == 'urdu' ? 'text_dir_rtl' : ''}}" style="height: 300px;line-height: 2.42 !important;"></div> <!-- end Snow-editor-->
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" class="btn btn-primary" id="submit_editor">Submit</a>
                <a href="javascript:void(0)" class="btn btn-light" id="close_editor">Cancel</a>
            </div>
        </div>
    </div>
</div>

<input type="hidden" class="td_class_name">
