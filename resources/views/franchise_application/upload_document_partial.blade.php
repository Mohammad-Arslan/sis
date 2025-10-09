@if(!auth()->user() || auth()->user()->hasPermission('add-franchapp-upload-doc'))
    <form method="POST" class="row g-3 needs-validation" novalidate id="uploadDocumentForm" enctype="multipart/form-data" action ="{{ route('save-franchise-application-docs') }}">
        @csrf
        <input type="hidden" id="franchise_application_id" name="franchise_application_id" value="{{isset($franchise) ? $franchise->id : ''}}">
        <div class="col-md-12 col-sm-12 mt-3">
            <div class="form-label-group in-border">
                <select class="form-select" id="attachmentTypeId" name="attachment_type_id" aria-label="Attachment type select" required>
                    <option value="">Please select</option>
                    @if(isset($attachment_types))
                        @foreach ($attachment_types as $attachment)
                            <option value="{{ $attachment->id }}" >{{ $attachment->name }}</option>
                        @endforeach
                    @endif
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
            <button class="btn btn-primary save-file" type="submit">Submit form</button>
        </div>
    </form>
@endif


<div class="col-md-12 col-sm-12 mt-3">
    <div class="table-responsive">
        <table class="table table-nowrap mb-0" id="franchiseApplicationDocTable" style="width:100%">
            <thead class="table-light">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Document Type</th>
                @if(auth()->user())
                    <th scope="col">Uploaded By</th>
                @endif
                <th scope="col">Date</th>
                <th scope="col">Remarks</th>
                <th scope="col" class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody class="upload_docs"><tr><td></td></tr></tbody>
        </table>
    </div>
</div>

@push('footer_scripts')
    <script>
        $(document).ready(function() {
            getFranchiseApplicationDocs();

            $(document).on('click', '.uploadDocument', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                var franchise_application_id = $(this).data('franchise_application_id');
                $("#uploadDocumentModal .modal-body #uploadDocumentForm #franchise_application_id").val(franchise_application_id);
                getFranchiseApplicationDocs(franchise_application_id);

                $(target).modal('show');
            });

            $(document).on('click', '.remove_attachment', function(e) {
                e.preventDefault();

                var row_id = $(this).data('id');
                let route = $(this).data('route');
                let remove_attachment_this = $(this);
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

                            url: route,
                            type: "DELETE",
                            // data : filters,
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            cache: false,
                            success: function(data) {
                                $(remove_attachment_this).closest('tr').remove();
                            },
                            error: function() {

                            },
                            beforeSend: function() {

                            },
                            complete: function() {

                            }
                        });
                    }
                });
            });
        });


        function getFranchiseApplicationDocs(franchise_app_id = 0){
            var franchise_application_id = {{isset($franchise) ? $franchise->id : 0}}
            franchise_application_id = franchise_application_id ? franchise_application_id : franchise_app_id;

            $.ajax({
                url: "{{ route('get-franchise-application-docs') }}?franchise_application_id=" + franchise_application_id,
                type: "GET",
                cache: false,
                success: function (data) {
                    $("#franchiseApplicationDocTable .upload_docs").html(data);
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


