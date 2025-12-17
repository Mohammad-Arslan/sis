<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Support</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form id="supportForm" class="row g-3 needs-validation" method="POST" action="{{isset($support) ? route('support-query.update',$support->id) : route('support-query.store') }}" novalidate enctype="multipart/form-data">

                    @if(isset($support))
                        @method('PATCH')
                    @endif

                    <div class="snow-editor" style="height: 300px;line-height: 2.42 !important;"></div>
                    <textarea name="description" id="description" style="display: none"></textarea>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="file" class="form-control @if($errors->has('file')) is-invalid @endif" id="file" name="file" placeholder="file">
                            <label for="file" class="form-label"> Document</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('file'))
                                    {{ $errors->first('file') }}
                                @else
                                    Document is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('url')) is-invalid @endif" id="url" name="url" placeholder="Enter URL">
                            <label for="url" class="form-label"> URL</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('url'))
                                    {{ $errors->first('url') }}
                                @else
                                    URL is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('priority')) is-invalid @endif" id="priority" name="priority" required>
                                <option value="">Please select</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                            <label for="file" class="form-label">Priority <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('priority'))
                                    {{ $errors->first('priority') }}
                                @else
                                    Priority is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    @csrf
                    <div class="col-12 text-end">
                        <a href="javascript:void(0)" class="btn btn-primary submit-form">Save Changes</a>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            let editor;
            var snowEditor = document.querySelectorAll(".snow-editor")
            snowEditor.forEach(function (item) {
                var snowEditorData = {};
                var issnowEditorVal = item.classList.contains("snow-editor");
                if (issnowEditorVal == true) {
                    snowEditorData.theme = 'snow',
                        snowEditorData.modules = {
                            'toolbar': [
                                ['bold', 'italic', 'underline', 'strike'],
                                [{
                                    'color': []
                                }, {
                                    'background': []
                                }],
                                [{
                                    'header': [false, 1, 2, 3, 4, 5, 6]
                                }],
                                [{
                                    'list': 'ordered'
                                }, {
                                    'list': 'bullet'
                                }, {
                                    'indent': '-1'
                                }, {
                                    'indent': '+1'
                                }],
                            ]
                        }
                }
                editor = new Quill(item, snowEditorData);
            });

            $('.submit-form').on('click',function (){
                $('.description-alert').addClass('hide').removeClass('show');
                $('#priority').removeClass('is-invalid');

                let validation_hit = 0;
                if (editor.getLength() <= 1){
                    $('.description-alert').addClass('show').removeClass('hide');
                    validation_hit = 1;
                }
                if ($('#priority').val() == ''){
                    $('#priority').addClass('is-invalid');
                    validation_hit = 1;
                }

                if (validation_hit)
                    return false;

                $('#description').val(editor.root.innerHTML);
                $('#supportForm').submit();
            });

        });
    </script>
@endpush
