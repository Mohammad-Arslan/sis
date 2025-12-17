<div id="changeUserPasswordModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Change Password</h5>
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
                                            <input type="password" class="form-control @if($errors->has('current_password')) is-invalid @endif" id="current_password" name="current_password" placeholder="Current Password" value="" required>
                                            <label for="password" class="form-label">Current Password</label>
                                            <div class="invalid-tooltip" id="cur_password">
                                                @if($errors->has('password'))
                                                {{ $errors->first('password') }}
                                                @else
                                                Current Password is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="password" class="form-control @if($errors->has('new_password')) is-invalid @endif"  min="8" id="new_password" name="new_password" placeholder="New Password" value="" required>
                                            <label for="password" class="form-label">New Password</label>
                                            <div class="invalid-tooltip" id="new_pass">
                                                @if($errors->has('new_password'))
                                                {{ $errors->first('new_password') }}
                                                @else
                                                New Password is required!
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <input type="password" class="form-control @if($errors->has('confirm_password')) is-invalid @endif" min="8" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value=""  required>
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
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{ auth()->user()->id }}">
                                        <button class="btn btn-primary" type="submit" id="update_password">Update</button>
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

$(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#PasswordForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $(formData).trigger("reset");
            $.ajax({
                type:'POST',
                url: "{{ route('change-employee-password') }}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    if(data.status==400){
                    $('#pass_error').html('');
                    $('#pass_error').html(data.error);

                }else{
                    $('#pass_success').html('');
                    $('#pass_success').html(data.success);
                }
            },
            error: function(data){
                // $('#pass_error').html('');
                // $('#cur_password').html('');
                // $('#new_pass').html('');
                // $('#confirm_pass').html('');
                // var obj = JSON.parse(JSON.stringify(data));
                // $('#pass_error').html(obj);
                // if(obj.current_password != '')
                // {
                //     $('#cur_password').html(obj.current_password);
                // }
                // if(obj.new_password != '')
                // {
                //     $('#new_pass').html(obj.new_password);
                // }
                // if(obj.confirm_password != '')
                // {
                //     $('#confirm_pass').html(obj.confirm_password);
                // }
                console.log(data);
            }
        });
        });
    });

</script>
