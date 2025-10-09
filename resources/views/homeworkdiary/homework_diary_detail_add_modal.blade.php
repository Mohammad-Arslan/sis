<div id="diaryAddModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Add Homework</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-5">
                    <div class="col-xxl-12">
                        <div class="card mt-xxl-n5">
                            <div class="card-body p-4">
                                <form class="row g-3 needs-validation" action="{{ route('homeWorkDiaryDetial.store') }}" id="PasswordForm" method="POST" novalidate enctype="multipart/form-data">
                                    @csrf
                                    {{-- <div class="col-md-12 col-sm-12 text-danger" id="pass_error"></div>
                                    <div class="col-md-12 col-sm-12 text-success" id="pass_success"></div> --}}
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select class="form-select @if($errors->has('subject_id')) is-invalid @endif" id="subject_id" name="subject_id" aria-label="Visit Status Select" required>
                                                <option value="">Please select</option>
                                                @foreach($subjects as $subject)
                                                    @if(checkHomeWorkDiarySubject($subject->subject->id,$id))
                                                    <option value="{{$subject->subject->id}}" {{ old("subject_id") == $subject->subject->id  ? 'selected' : '' }}>{{ $subject->subject->subject_name }}</option>
                                                    @else
                                                    <option value="{{$subject->subject->id}}" {{ 'disabled' }}>{{ $subject->subject->subject_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <label for="subject_id" class="form-label">Subjects *</label>
                                            <div class="invalid-tooltip" id="new_pass">
                                                @if($errors->has('subject_id'))
                                                {{ $errors->first('subject_id') }}
                                                @else
                                                Subject is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <textarea class="form-control @if($errors->has('homework')) is-invalid @endif" placeholder="Enter Home Work" name="homework" id="homework" rows="2" required></textarea>
                                            <label for="homework" class="form-label">Home Work *</label>
                                            <div class="invalid-tooltip">
                                                @if($errors->has('homework'))
                                                {{ $errors->first('homework') }}
                                                @else
                                                Home work is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="input-group in-border hdtuto control-group lst increment">
                                            <input type="file" name="attachment[]" class="myfrm form-control @if($errors->has('attachment')) is-invalid @endif" required>
                                            <div class="input-group-btn">
                                                <button class="btn btn-success" type="button" id="addbutton"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                                            </div>
                                            <div class="invalid-tooltip">
                                                @if($errors->has('attachment'))
                                                {{ $errors->first('attachment') }}
                                                @else
                                                Attachment is required!
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clone hide">
                                            <div class="in-border hdtuto control-group lst input-group" style="margin-top:10px">
                                              <input type="file" name="attachment[]" class="myfrm form-control @if($errors->has('attachment')) is-invalid @endif" required>
                                              <div class="input-group-btn">
                                                <button class="btn btn-danger" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                                              </div>
                                            </div>
                                          </div>
                                    </div>

                                    <div class="border mt-3 border-dashed"></div>

                                    <div class="col-12 text-end">
                                        @if (isset($id))
                                        <input type="hidden" class="form-control" id="diary_id" name="diary_id" value="{{ $id }}">
                                        <input type="hidden" id="created_by" name="created_by" value="{{Auth::user()->id}}" />
                                        <button class="btn btn-primary" type="submit" id="save">Save</button>
                                        @endif
                                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('theme/dist/default/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addbutton").click(function(){
            var lsthmtl = $(".clone").html();
            $(".increment").after(lsthmtl);
        });
        $("body").on("click",".btn-danger",function(){
            $(this).parents(".hdtuto").remove();
        });
    });



        function setInputErrors(form, errorsArr) {
        var objKeys = Object.keys(errorsArr)

        objKeys.forEach(element => {
            var input = $(`[name="${element}"]`);
            input.addClass('is-invalid');
            input.siblings('.invalid-tooltip').text(errorsArr[element][0]);
        });
    }
</script>
