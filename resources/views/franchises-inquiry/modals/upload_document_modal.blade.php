<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Upload a attachment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="row g-3 needs-validation" novalidate id="uploadDocumentForm" enctype="multipart/form-data" action ="{{ route('save-franchise-applications-docs') }}">
                    @csrf
                    <input type="hidden" id="inquiryId" name="inquiry_id" value="">
                    <div class="col-md-12 col-sm-12 mt-3">
                        <div class="form-label-group in-border">
                            <select class="form-select" id="attachmentTypeId" name="attachment_type_id" aria-label="Attachment type select" required>
                                <option value="">Please select</option>
                                @foreach ($attachment_types as $attachment)
                                <option value="{{ $attachment->id }}" >{{ $attachment->name }}</option>
                                @endforeach
                            </select>
                            <label for="attachmentTypeId" class="form-label">Document Type</label>
                            <div class="invalid-tooltip">
                                Attachment Type is required!
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                            <input type="file" class="form-control" id="file" name="file" placeholder="file" required>
                            <label for="file" class="form-label"> Attachment</label>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-label-group in-border">
                        	<textarea class="form-control" id="details" name="details" placeholder="details" required></textarea>
                            <label for="details" class="form-label">Details</label>
                        </div>
                    </div>

                    

                    <div class="col-12 text-end">
                        <button class="btn btn-primary save-file" type="submit">Save Changes</button>
                    </div>
                </form>
                <div class="col-md-12 col-sm-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0" id="schoolBuildingTable" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Upload By</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Remarks</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="upload_docs"><tr><td></td></tr></tbody>
                        </table>
                    </div>                      
                </div>
            </div>
        </div>
    </div>
</div>
