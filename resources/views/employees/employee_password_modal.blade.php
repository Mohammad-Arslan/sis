<div id="editEmpPasswordModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Change Employee Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-5">
                    <div class="col-xxl-12">
                        <div class="card mt-xxl-n5">
                            <div class="card-body p-4">
                                <form class="row g-3 needs-validation" action="javascript:void(0)" id="PasswordForm" method="POST" novalidate>
                                    @csrf
                                    <div class="col-md-12 col-sm-12 text-danger" id="pass_error"></div>
                                    <div class="col-md-12 col-sm-12 text-success" id="pass_success"></div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" id="password" name="password" placeholder="New Password" value="" required>
                                            <label for="password" class="form-label">New Password</label>
                                            <div class="invalid-tooltip" id="new_pass">
                                                @if($errors->has('password'))
                                                {{ $errors->first('password') }}
                                                @else
                                                New Password is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="password" class="form-control @if($errors->has('confirm_password')) is-invalid @endif" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value=""  required>
                                            <label for="confirm_password" class="form-label">Confirm Password</label>
                                            <div class="invalid-tooltip" id="confirm_pass">
                                                @if($errors->has('confirm_password'))
                                                {{ $errors->first('confirm_password') }}
                                                @else
                                                Confirm new password!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border mt-3 border-dashed"></div>

                                    <div class="col-12 text-end">
                                        @if (isset($user_id))
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{ $user_id }}">
                                        <button class="btn btn-primary" type="submit" id="update_password">Update</button>
                                        @endif
                                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light" data-bs-dismiss="modal">Close</button>
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

$(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Clear messages when modal is opened
        $('#editEmpPasswordModal').on('show.bs.modal', function () {
            $('#pass_error').html('');
            $('#pass_success').html('');
            $('#PasswordForm')[0].reset();
        });

        $('#PasswordForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            
            // Clear previous error and success messages
            $('#pass_error').html('');
            $('#pass_success').html('');
            
            $.ajax({
                type:'POST',
                url: "{{ route('update-employee-password') }}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#pass_success').html(data.success);
                    // Only reset form on successful password update
                    $('#PasswordForm')[0].reset();
                },
                error: function(xhr, status, error){
                    console.log(xhr);
                    if(xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.error) {
                        // Handle validation errors
                        let errorMessage = '';
                        // Loop through all validation errors
                        Object.keys(xhr.responseJSON.error).forEach(function(key) {
                            if(Array.isArray(xhr.responseJSON.error[key])) {
                                xhr.responseJSON.error[key].forEach(function(message) {
                                    errorMessage += message + '<br>';
                                });
                            } else {
                                errorMessage += xhr.responseJSON.error[key] + '<br>';
                            }
                        });
                        $('#pass_error').html(errorMessage);
                    } else {
                        $('#pass_error').html('An error occurred. Please try again.');
                    }
                }
            });
        });
    });

</script>
