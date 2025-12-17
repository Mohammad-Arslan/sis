<div id="teacherTypeModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="zoomInModalLabel">Change Teacher Type</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{ route('class-teachers-type-update') }}">
              <input type="hidden" name="class_teacher_id" value="{{ $class_teacher->id }}" />
              @foreach ($teacher_types as $teacher_type)
                <div class="form-check mb-2">
                  <label class="form-check-label" for="{{ 'teacherTypeId'.$teacher_type->id }}">
                  <input class="form-check-input" type="radio" name="teacher_type_id" value="{{ $teacher_type->id }}" id="{{ 'teacherTypeId'.$teacher_type->id }}" {{ $teacher_type->id == $class_teacher->teacher_type_id ? 'checked' : '' }} >
                      {{ $teacher_type->name }}
                  </label>
                </div>
              @endforeach
              @csrf
              <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Submit form</button>
                <button type="button" data-bs-dismiss="modal" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
              </div>
            </form>
          </div>
      </div>
  </div>
</div>
