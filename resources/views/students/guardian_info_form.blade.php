<form class="row g-3 needs-validation" method="POST" action="{{ route('guardians.store') }}" novalidate>
    @csrf

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('relation_id')) is-invalid @endif" id="relationID"
                name="relation_id" aria-label="Relation select" required>
                <option value="">Please select a relationship</option>
                @foreach ($relations as $relation)
                    <option value="{{ $relation->id }}" {{ old('relation_id') == $relation->id ? 'selected' : '' }}>
                        {{ $relation->relation_name }}</option>
                @endforeach
            </select>
            <label for="relationID" class="form-label">Relationship <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('relation_id'))
                    {{ $errors->first('relation_id') }}
                @else
                    Relationship is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if ($errors->has('guardian_name')) is-invalid @endif"
                id="guardianName" name="guardian_name" placeholder="Please enter guardian name"
                value="{{ old('guardian_name') }}" required>
            <label for="guardianName" class="form-label">Name <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('guardian_name'))
                    {{ $errors->first('guardian_name') }}
                @else
                    Name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control mobile-mask @if ($errors->has('mobile')) is-invalid @endif"
                id="mobile" name="mobile" placeholder="Please enter mobile number" value="{{ old('mobile') }}"
                required>
            <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('mobile'))
                    {{ $errors->first('mobile') }}
                @else
                    Mobile Number is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if ($errors->has('is_parent')) is-invalid @endif" id="is_parent"
                name="is_parent" aria-label="Parent select" required>
                <option value="">Please select a Parent</option>
                <option value="yes" {{ old('is_parent') == 'yes' ? 'selected' : '' }}>Yes</option>
                <option value="no" {{ old('is_parent') == 'no' ? 'selected' : '' }}>No</option>
            </select>
            <label for="is_parent" class="form-label"> Parent in Super Nova<span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('is_parent'))
                    {{ $errors->first('is_parent') }}
                @else
                    Parent is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 employee-id-div" style="display:{{ old('is_parent') == 'yes' ? '' : 'none' }}">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if ($errors->has('employee_no')) is-invalid @endif"
                id="employee_no" name="employee_no" placeholder="Please enter Employee ID"
                value="{{ old('employee_no') }}">
            <label for="employee_no" class="form-label">Employee ID <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('employee_no'))
                    {{ $errors->first('employee_no') }}
                @else
                    Employee ID is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if ($errors->has('CNIC')) is-invalid @endif"
                id="CNIC" name="CNIC" placeholder="Please enter your CNIC" value="{{ old('CNIC') }}"
                required>
            <label for="CNIC" class="form-label">CNIC <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('CNIC'))
                    {{ $errors->first('CNIC') }}
                @else
                    CNIC is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif"
                id="email" name="email" placeholder="Please enter email address" value="{{ old('email') }}"
                required>
            <label for="email" class="form-label">Communication Email <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if ($errors->has('email'))
                    {{ $errors->first('email') }}
                @else
                    Communication Email is required!
                @endif
            </div>
        </div>
    </div>

    <input type="hidden" name="student_id" id="studentID" value="{{ isset($student) ? $student->id : 0 }}" />

    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>

@push('footer_scripts')
    <script>
        $('#guardianForm').submit(function(e) {
            e.preventDefault();

            if (!e.target.checkValidity()) return console.log('not validated')

            let data = {
                guardian_name: $('#guardianName').val(),
                email: $('#email').val(),
                mobile: $('#mobile').val(),
                student_id: $('#studentID').val(),
                CNIC: $('#CNIC').val(),
                employee_no: $('#employee_no').val(),
                is_parent: $('#is_parent').val(),
                relation_id: $('#relationID').val(),
                "_token": "{{ csrf_token() }}"
            }
            $.ajax({
                url: "{{ route('guardians.store') }}",
                type: "POST",
                data,
                success: function(response) {
                    $('#guardianForm').removeClass('was-validated')
                    $('#guardianAlert').removeClass('alert-danger').addClass('alert alert-success')
                        .text(response.success);
                    $("#guardianForm")[0].reset();
                    $('.is-invalid').removeClass('is-invalid')

                    //console.log(window.location)
                    if (window.location.href.split('tab=')[1] !== 'guardian') {
                        let url = `/students/${data.student_id}/edit?tab=guardian`;
                        window.location = url;
                    } else $('#guardian-data-table').DataTable().ajax.reload(null, false)
                },
                error: function(response) {
                    $('#guardianAlert').addClass('alert alert-danger').text(response.responseJSON
                        .message)
                    setInputErrors('guardianForm', response.responseJSON.errors)
                },
            });
        });

        $('#is_parent').change(function() {
            if ($(this).val() == 'yes') {
                $(".employee-id-div").show();
                $("#employee_no").prop('required', true);
                $('#CNIC').attr('readonly', true);
            } else {
                $(".employee-id-div").hide();
                $("#employee_no").prop('required', false);
                $('#CNIC').attr('readonly', false);
            }
        });

        $('#employee_no').change(function() {
            let emp_id = $(this).val();
            $.ajax({
                url: "{{ route('getEmployeeUsingEmpId') }}" + '?emp_id=' + emp_id,
                dataType: "json",
                type: "GET",
                success: function(response) {
                    $('#CNIC').val('');
                    if (response.code == 200) {
                        $('#CNIC').val(response.data.user.CNIC);
                    }
                },
                error: function(response) {
                    console.log(response);
                },
            });
        });
    </script>
@endpush
