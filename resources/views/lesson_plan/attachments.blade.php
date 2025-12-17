<div class="accordion-item mt-3">
    <h2 class="accordion-header" id="accordionborderedAttachments">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_borderedcollapse1" aria-expanded="false" aria-controls="accor_borderedcollapse1" @if(!isset($lessonPlan)) disabled @endif>
            Attachments
        </button>
    </h2>
    <div id="accor_borderedcollapse1" class="accordion-collapse collapse" aria-labelledby="accordionborderedAttachments" data-bs-parent="#accordionBordered" style="">
        <div class="accordion-body">
            @if(isset($lessonPlan))
                <form method="POST" class="row g-3 needs-validation" action="{{ route('lesson-plans.update',$lessonPlan['id']) }}" novalidate id="attachmentForm" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-4 col-sm-4 mt-3">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control @if($errors->has('document_name')) is-invalid @endif" id="document_name" name="document_name" placeholder="Please enter document_name" value="{{ old('document_name') }}" required>
                            <label for="document_name" class="form-label">Document Name</label>
                            <div class="invalid-tooltip">
                                @if($errors->has('document_name'))
                                    {{ $errors->first('document_name') }}
                                @else
                                    Document Name is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 mt-3">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="attachment_type" name="attachment_type" aria-label="Attachment type select" required>
                                <option value="">Please select</option>
                                <option value="file">File</option>
                            </select>
                            <label for="attachment_type" class="form-label">Attachment Type</label>
                            <div class="invalid-tooltip">
                                Attachment Type is required!
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-label-group in-border">
                            <input type="file" class="form-control" id="file" name="file[]" placeholder="file" required multiple>
                            <label for="file" class="form-label"> Attachment</label>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" id="attachment_remarks" name="attachment_remarks" placeholder="attachment_remarks"></textarea>
                            <label for="attachment_remarks" class="form-label">Remarks</label>
                        </div>
                    </div>



                    <div class="col-12 text-end">
                        <button class="btn btn-primary save-file" type="submit" form="attachmentForm">Submit form</button>
                    </div>
                </form>

                <div class="col-md-12 col-sm-12 mt-3">
                    <div class="table-responsive">
                        <table id="attachments-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Upload By</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($lessonPlan) && isset($lessonPlan['attachments']))
                                @foreach($lessonPlan['attachments'] as $attachment)
                                    <tr>
                                        <td>
                                            <a href="{{ get_file_from_s3('images/'.$attachment['file_name']) }}" class="avatar-group-item" target="_blank">{{$attachment->file_name}}</a>
                                        </td>
                                        <td>{{$attachment['user']['first_name'].' '. $attachment['user']['last_name']}}</td>
                                        <td>{{$attachment['created_at']}}</td>
                                        <td>{{!empty($attachment['remarks']) ? $attachment['remarks'] : '-'}}</td>
                                        <td>
                                            <a href="{{ route('lesson-plans.remove_attachment', $attachment->id) }}" data-table="attachments-data-table" data-isajax="false"
                                               class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-record">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Upload By</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@push('footer_scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
