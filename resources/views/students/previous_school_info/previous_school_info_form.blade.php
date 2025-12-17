<form class="row g-3 needs-validation update_student_previous_school_form" method="post"
    action="{{ route('students.update-previous-school') }}" novalidate>
    @csrf
    @method('PUT')

    <div class="col-md-6 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select get_previous_school_description" id="school_id" name="school_id"
                aria-label="School select" required>
                <option value="">Please select a school</option>
                @if (isset($previous_schools))
                    @foreach ($previous_schools as $school)
                        <option value="{{ $school->id }}"
                            {{ $school->id == (isset($student) ? $student->previous_school_id : old('school_id')) ? 'selected' : '' }}>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <label for="school_id" class="form-label">Schools <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                School is required!
            </div>
        </div>
    </div>
    {{--  --}}
    <div class="col-md-6 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control" id="school-description"
                value="{{ isset($student) ? (isset($student->student_previous_school->description) ? $student->student_previous_school->description : '') : '' }}"
                placeholder="" disabled>
            <label for="school-description" class="form-label">Description</label>
        </div>
    </div>

    <input type="hidden" id="studentID" name="student_id" value="{{ isset($student) ? $student->id : 0 }}" />

    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>

@push('header_scripts')
    <style type="text/css">
        .select2-container--default .select2-selection--single {
            position: relative;
            height: 37px;
            display: block;
            width: 100%;
            padding: 0.5rem 0.9rem;
            font-size: .8125rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            /*background-color: #eff2f7;*/
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 20px !important;
            font-size: .8125rem;
            font-weight: 400;
            padding: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

        .select2-container {
            width: 100% !important;
            cursor: pointer;
            max-width: -webkit-fill-available;
            max-width: fill-available;
        }
    </style>
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '.get_previous_school_description', function() {
                const previous_school_id = $('.get_previous_school_description').find(':selected').val();

                $.ajax({
                    url: '{{ route('student.school-description') }}',
                    type: 'GET',
                    data: {
                        previous_school_id: previous_school_id,
                    },
                    cache: false,
                    success: function(result) {
                        $('#school-description').val(result);
                    },
                    error: function() {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 3000
                }).fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });
            $(document).ready(function() {
                $('.get_previous_school_description').select2();
            });

            /*$(document).on('submit', '.update_student_previous_school_form', function (event) {
                event.preventDefault();
                const previous_school_id = $('.get_previous_school_description').find(':selected').val();

                $.ajax({
                    url: '{{ route('students.update-previous-school') }}',
                    type: 'POST',
                    method: "PUT",
                    data: {
                        school_id: previous_school_id,
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function (result) {
                        if(result.status){
                            $('.alert-success').html(result.success);
                        }
                    }, error: function () {
                        console.log("Sorry! Server error!");
                    },
                    timeout: 3000
                }).fail(function (jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        console.log("Sorry Please Wait... Slow connection!");
                    }
                });
            });*/

        });
    </script>
@endpush
