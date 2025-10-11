<style>
    .my-custom-scrollbar {
    position: relative;
    height: 200px;
    overflow: auto;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
</style>
<div id="diaryEditModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Edit Home Work</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <div class="row mt-5">
                    <div class="col-xxl-12">
                        <div class="card mt-xxl-n5">
                            <div class="card-body p-4">
                                <form class="row g-3 needs-validation" action="{{ route('homeWorkDiaryDetial.update', $homeWorkDiaryDetial->id) }}" id="HomeWorkForm" method="POST" novalidate enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    {{-- <div class="col-md-12 col-sm-12 text-danger" id="pass_error"></div>
                                    <div class="col-md-12 col-sm-12 text-success" id="pass_success"></div> --}}
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select class="form-select @if($errors->has('subject_id')) is-invalid @endif" id="subject_id" name="subject_id" aria-label="Visit Status Select" required>
                                                <option value="">Please select</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{$subject->subject->id}}" {{ $homeWorkDiaryDetial->subject_id == $subject->subject->id  ? 'selected' : '' }}>{{ $subject->subject->subject_name }}</option>
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
                                            <textarea class="form-control @if($errors->has('homework')) is-invalid @endif" placeholder="Enter Home Work" name="homework" id="homework" rows="2">{{$homeWorkDiaryDetial->homework}}</textarea>
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

                                    <div class="border mt-2 border-dashed"></div>
                                    <div class="col-md-12 table-wrapper-scroll-y my-custom-scrollbar">
                                        <table class="table table-borderless table-striped align-middle table-wrap mb-0" style="width:100%" id="homework-table">
                                        <thead>
                                            <tr>
                                                <th class="col-md-10">Attached File</th>
                                                <th class="col-md-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{getHomeWorkDiaryAttachments($homeWorkDiaryDetial->id,'EDIT')}}
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="border mt-2 border-dashed"></div>

                                    <div class="col-12 text-end">
                                        @if (isset($homeWorkDiaryDetial->diary_id))
                                        <input type="hidden" class="form-control" id="diary_id" name="diary_id" value="{{ $homeWorkDiaryDetial->diary_id }}">
                                        <input type="hidden" id="updated_by" name="updated_by" value="{{Auth::user()->id}}" />
                                        <button class="btn btn-primary" type="submit" id="save">Update</button>
                                        @endif
                                        <button type="button" class="btn btn-secondary bg-gradient waves-effect waves-light" data-bs-dismiss="modal">Close</button>
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
</script>
