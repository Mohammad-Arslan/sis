<form class="row g-3 needs-validation mb-5" method="POST" action="{{ route('students.update', $student->id) }}" novalidate enctype="multipart/form-data">
    @method('PUT')
    <h5 class="text-muted d-flex align-items-center"><i class="ri-building-fill me-1"></i>Uplaod Image</h5>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="file" class="form-control @if($errors->has('student_image')) is-invalid @endif" id="EmpImage" name="student_image" accept=".jpg, .jpeg, .png">
            <label for="EmpImage" class="form-label">Student Image</label>
            <div class="invalid-tooltip">
                @if($errors->has('student_image'))
                    {{ $errors->first('student_image') }}
                @else
                    Student Image is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="flex-shrink-0">
            <img src="{{get_file_from_s3('images/'.$student['student_image'],$student['student_image'])}}" alt="" class="avatar-xs rounded-circle" />
        </div>
    </div>

    @csrf
    @permission('create-student-avatar')
    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Submit form</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
    @endpermission
</form>
