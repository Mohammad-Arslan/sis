<div id="visitStatusModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Visit Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-5">
                    <div class="col-xxl-12">
                        <div class="card mt-xxl-n5">
                            <div class="card-body p-4">
                                <form class="row g-3 needs-validation" action="{{ route('update-visit-status') }}" id="PasswordForm" method="POST" novalidate>
                                    @csrf
                                    {{-- <div class="col-md-12 col-sm-12 text-danger" id="pass_error"></div>
                                    <div class="col-md-12 col-sm-12 text-success" id="pass_success"></div> --}}
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <select class="form-select @if($errors->has('approval_status')) is-invalid @endif" id="approval_status" name="approval_status" aria-label="Visit Status Select" required>
                                                <option value="">Please select</option>
                                                <option value="approve" {{ old("approval_status") == 'approve' ? 'selected' : '' }}>approve</option>
                                                <option value="cancel" {{ old("approval_status") == 'cancel' ? 'selected' : '' }}>cancel</option>
                                            </select>
                                            <label for="approval_status" class="form-label">Visit Status</label>
                                            <div class="invalid-tooltip" id="new_pass">
                                                @if($errors->has('approval_status'))
                                                {{ $errors->first('approval_status') }}
                                                @else
                                                Approval status is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border mt-3 border-dashed"></div>

                                    <div class="col-12 text-end">
                                        @if (isset($id))
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{ $id }}">
                                        <button class="btn btn-primary" type="submit" id="update_password">Update</button>
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

<script type="text/javascript">

    // $(document).ready( function () {
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     $('#PasswordForm').submit(function(e) {
    //         e.preventDefault();
    //         var formData = new FormData(this);
    //         $(formData).trigger("reset");
    //         $.ajax({
    //             type:'POST',
    //             url: "{{ route('update-employee-password') }}",
    //             data: formData,
    //             cache:false,
    //             contentType: false,
    //             processData: false,
    //             success: (data) => {
    //                 if(data.status==400){
    //                 $('#pass_error').html('');
    //                 $('#pass_error').html(data.error);

    //             }else{
    //                 $('#pass_success').html('');
    //                 $('#pass_success').html(data.success);
    //             }
    //         },
    //         error: function(data){
    //             console.log(data);
    //         }
    //     });
    //     });
    // });

</script>
