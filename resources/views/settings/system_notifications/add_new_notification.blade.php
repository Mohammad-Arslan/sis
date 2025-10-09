@extends('layouts.master')
<style>
    #editor-container {
        height: 100%;
        /* added these styles */
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    #editor {
        height: 100%;
        /* added these styles */
        flex: 1;
        overflow-y: auto;
        width: 100%;
    }


    /* Custom button styling for "TO" button */
    .btn-custom-to {
        background-color: #18A689;
        /* Custom background color */
        color: #ffffff;
        /* Custom text color */
        font-size: 14px;
        /* Custom font size */
        padding: 5px 10px;
        /* Custom padding to adjust size */
        border-radius: 5px;
        /* Custom border radius */
        /* Add any additional styles you want */
        height: 40px;
        border: none;
    }

    #add_subject_button {
        background-color: #18A689;
        /* Custom background color */
        color: #ffffff;
        /* Custom text color */
        font-size: 14px;
        /* Custom font size */
        padding: 5px 10px;
        /* Custom padding to adjust size */
        border-radius: 5px;
        /* Custom border radius */
        /* Add any additional styles you want */
        height: 40px;
        border: none;
    }

    #message_button_custom {
        background-color: #18A689;
        /* Custom background color */
        color: #ffffff;
        /* Custom text color */
        font-size: 14px;
        /* Custom font size */
        padding: 5px 10px;
        /* Custom padding to adjust size */
        border-radius: 5px;
        /* Custom border radius */
        /* Add any additional styles you want */
        height: 40px;
        border: none;
    }
</style>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="mb-0 card-title flex-grow-1">Add Announcement </h4>
            </div>

            <div class="card-body">
                <form class="row needs-validation" action="{{ route('system-notifications.store') }}" method="POST"
                    novalidate>
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    {{-- @role('super_admin') --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('country_id')) is-invalid @endif"
                                id="country" name="country_id" data-target="state_id"
                                data-url="{{ route('list-states') }}" aria-label="Country select">
                                <option value="">Please select a country</option>
                                @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id')==$country->id ? 'selected' : ''
                                    }}>
                                    {{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <label for="country" class="form-label">Country </label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('country_id'))
                                {{ $errors->first('country_id') }}
                                @else
                                Country is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select @if ($errors->has('state_id')) is-invalid @endif"
                                id="state" name="state_id" data-target="branch_id"
                                data-url="{{ route('list-branches-by-state') }}" aria-label="State select">
                                <option value="">Please select a state/province</option>
                                @if (old('state_id'))
                                @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ old('state_id')==$state->id ? 'selected' : '' }}>
                                    {{ $state->state_name }}</option>
                                @endforeach
                                @endif
                            </select>
                            <label for="state" class="form-label">State/Province </label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('state_id'))
                                {{ $errors->first('state_id') }}
                                @else
                                State/Province is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- @endrole --}}

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select
                                class="load-select refresh-table form-select @if ($errors->has('branch_id')) is-invalid @endif"
                                data-target="class_id" data-url="{{ route('list-branch-classes') }}" id="branch_id"
                                name="branch_id" aria-label="Branch select">
                                <option value="">Please select a branch</option>
                                @if (old('branch_id') || isset($branches))
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id')==$branch->id ? 'selected' : '' }}>
                                    {{ $branch->br_name }}</option>
                                @endforeach
                                @endif
                            </select>
                            <label for="branch" class="form-label">Branch </label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('branch_id'))
                                {{ $errors->first('branch_id') }}
                                @else
                                Branch is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select load-select @if ($errors->has('class_id')) is-invalid @endif"
                                data-target="subject_id,section_id" id="class_id" data-url name="class_id">
                                <option value="">Please select a class</option>
                                @if (isset($classes))
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @role('super_admin') {{ old('class_id')==$class->id ?
                                    'selected' : '' }}>
                                    {{ $class->class_name }}
                                    @else
                                    {{ old('class_id') == $class->class_id ? 'selected' : '' }}>
                                    {{ $class->com_classes->class_name }}
                                    @endrole
                                </option>
                                @endforeach
                                @endif
                            </select>
                            <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('class_id'))
                                {{ $errors->first('class_id') }}
                                @else
                                Class is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select refresh-table @if ($errors->has('section_id')) is-invalid @endif"
                                id="section_id" name="section_id" aria-label="Branch select">
                                <option value="">Please select a section</option>
                                @if (isset($sections))
                                @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ isset($studentBehaviourSkill) &&
                                    $studentBehaviourSkill['section_id']==$section->section_id ? 'selected' : '' }}>
                                    {{ $section['section_name'] }}</option>
                                @endforeach
                                @endif
                            </select>
                            <label class="form-label">Section <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('section_id'))
                                {{ $errors->first('section_id') }}
                                @else
                                Section is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if ($errors->has('notification_type')) is-invalid @endif"
                                id="notification_type" name="notification_type" aria-label="Notification Type select"
                                required>
                                <option value="">Please select type</option>
                                <option value="SMS" {{ old('notification_type')=='SMS' ? 'selected' : '' }}>SMS
                                </option>
                                <option value="Email" {{ old('notification_type')=='Email' ? 'selected' : '' }}>
                                    Email</option>
                                <option value="Push Notification" {{ old('notification_type')=='Push Notification'
                                    ? 'selected' : '' }}>
                                    Push Notification</option>
                                <option value="WhatsApp Message" {{ old('notification_type')=='WhatsApp Message'
                                    ? 'selected' : '' }}>
                                    WhatsApp Message</option>
                                <option value="SMS, Email, Push Notification" {{
                                    old('notification_type')=='SMS, Email, Push Notification' ? 'selected' : '' }}>
                                    All</option>
                            </select>
                            <label for="notification_type" class="form-label">Announcement Type <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('notification_type'))
                                {{ $errors->first('notification_type') }}
                                @else
                                Notification Type is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn-custom-to">TO</button>

                            <div style="margin-left: 20px;" class="form-label-group in-border w-100">
                                <select class="form-select @if ($errors->has('audience')) is-invalid @endif"
                                    id="audience" name="audience" aria-label="Audience select" required>
                                    <option value="">Please select an audience</option>
                                    {{-- @role('super_admin') --}}
                                    <option value="NWA" {{ old('audience')=='NWA' ? 'selected' : '' }}>NWA</option>
                                    {{-- @endrole --}}
                                    <option value="Parents" {{ old('audience')=='Parents' ? 'selected' : '' }}>Parents
                                    </option>
                                    <option value="Employees" {{ old('audience')=='Employees' ? 'selected' : '' }}>
                                        Employees</option>
                                </select>
                                <label for="audience" class="form-label">Audience <span
                                        class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('audience'))
                                    {{ $errors->first('audience') }}
                                    @else
                                    Audience is required!
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br><br>


                    {{-- <div id="header_div" class="col-md-12 col-sm-12" style="display: none">
                        <div class="form-label-group in-border">
                            <textarea name="email_header"
                                class="form-control @if ($errors->has('email_header')) is-invalid @endif"
                                id="email_header"
                                placeholder="Please enter notification email header">{{ old('email_header') }}</textarea>
                            <label for="email_header" class="form-label">Email Header <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('email_header'))
                                {{ $errors->first('email_header') }}
                                @else
                                Email Header is required!
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <div id="header_div" class="col-md-12 col-sm-12" style="display: none">
                        <div class="form-label-group in-border d-flex">
                            <button type="button" id="add_subject_button">Subject</button>
                            <div style="margin-left: 20px;" class="form-label-group in-border w-100">
                                <textarea name="email_header"
                                    class="form-control @if ($errors->has('email_header')) is-invalid @endif"
                                    id="email_header"
                                    placeholder="Please enter notification email header">{{ old('email_header') }}</textarea>
                                <label for="email_header" class="form-label">Email Header <span
                                        class="text-danger">*</span></label>
                                <div class="invalid-tooltip">
                                    @if ($errors->has('email_header'))
                                    {{ $errors->first('email_header') }}
                                    @else
                                    Email Header is required!
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>



                    <div id="message_div_email" class="col-md-12 col-sm-12" style="display: none">
                        <button type="button" id="message_button_custom" for="message" class="form-label">Message Body
                            <span class="text-danger">*</span></button>

                        <div class="form-label-group in-border">
                            <div class="snow-editor" style="height: 300px;line-height: 2.42 !important;"></div>
                            <!-- end Snow-editor-->
                            <input type="hidden" name="message_email" id="message_content">
                            <div class="invalid-tooltip">
                                @if ($errors->has('message'))
                                {{ $errors->first('message') }}
                                @else
                                Message Body is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div id="message_div" class="col-md-12 col-sm-12" style="display: none">
                        <div class="form-label-group in-border">
                            <textarea name="message"
                                class="form-control @if ($errors->has('message')) is-invalid @endif" id="message"
                                placeholder="Please enter notification email body" cols="30"
                                rows="10">{{ old('message') }}</textarea>
                            <label for="message" class="form-label">Message Body <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('message'))
                                {{ $errors->first('message') }}
                                @else
                                Message Body is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div>
                        <input class="form-control form-control-md" id="formFileLg" type="file"
                            style="  width: 30%; margin-bottom: 20px;" />
                    </div> --}}

                    <div id="salutation_div" class="col-md-12 col-sm-12" style="display: none">
                        <div class="form-label-group in-border">

                            <textarea name="email_salutation"
                                class="form-control @if ($errors->has('email_salutation')) is-invalid @endif"
                                id="email_salutation"
                                placeholder="Please enter notification email salutation">{{ old('email_salutation') }}</textarea>
                            <label for="email_salutation" class="form-label">Email Salutation <span
                                    class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('email_salutation'))
                                {{ $errors->first('email_salutation') }}
                                @else
                                Email Salutation is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit" style="background-color: #18A689;">Save
                            Changes</button>

                        <a href="{{ route('system-notifications.index') }}" type="reset"
                            class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('header_scripts')
@endpush

@push('footer_scripts')
<script type="text/javascript">
    $(document).on('change', '.load-select-sections', function(e) {
            var target = $(this).data('target');
            var branch_id = $('#branch_id').children("option:selected").val();;
            // var url = '/list-sections' + /branch_id
            console.log(branch_id);

            $.ajax({

                url: `/list-sections/${branch_id}`,
                type: "GET",
                cache: false,
                success: function(data) {

                    var options = '<option selected disabled> Select an Option</option>';

                    if (data) {
                        $.each(data, function(index, value) {
                            options += '<option value="' + value.id + '">' + value.title +
                                '</option>';
                        });
                    }
                    $('select[name="' + target + '"]').html(options).attr('disabled', false);
                },
                error: function() {

                },
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                }
            });
        });
        $(document).ready(function() {
            $('#branch_id').on('change', function() {
                $('#subject_id').find('option').not(':first').remove();

                let route = 'get-class-section-subjects/' + $(this).val();
                $('#class_id').data('url', route);
            });

            $("#notification_type").change(function() {
                var selected_option = $('#notification_type').val();
                if (selected_option == 'SMS' || selected_option == 'Push Notification') {
                    document.getElementById("message_div_email").style.display = "none";
                    document.getElementById("message_div").style.display = "block";
                    document.getElementById('header_div').style.display = "none";
                    document.getElementById('salutation_div').style.display = "none";
                }
                if (selected_option == 'Email') {
                    document.getElementById("message_div_email").style.display = "block";
                    document.getElementById("message_div").style.display = "none";
                    document.getElementById('header_div').style.display = "block";
                    document.getElementById('salutation_div').style.display = "block";
                }
                if (selected_option == 'WhatsApp Message') {
                    document.getElementById("message_div_email").style.display = "none";
                    document.getElementById("message_div").style.display = "block";
                    document.getElementById('header_div').style.display = "none";
                    document.getElementById('salutation_div').style.display = "none";
                }
                if (selected_option == 'SMS, Email, Push Notification') {
                    document.getElementById("message_div_email").style.display = "none";
                    document.getElementById("message_div").style.display = "block";
                    document.getElementById('header_div').style.display = "block";
                    document.getElementById('salutation_div').style.display = "block";
                }
            });
        });
</script>
@endpush

@push('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {
            var snowEditor = document.querySelectorAll(".snow-editor")
            snowEditor.forEach(function (item) {
                var snowEditorData = {};
                var issnowEditorVal = item.classList.contains("snow-editor");
                if (issnowEditorVal == true) {
                    snowEditorData.theme = 'snow',
                        snowEditorData.modules = {
                            'toolbar': [
                                [{
                                    'font': []
                                }, {
                                    'size': []
                                }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{
                                    'color': []
                                }, {
                                    'background': []
                                }],
                                [{
                                    'script': 'super'
                                }, {
                                    'script': 'sub'
                                }],
                                [{
                                    'header': [false, 1, 2, 3, 4, 5, 6]
                                }, 'blockquote', 'code-block'],
                                [{
                                    'list': 'ordered'
                                }, {
                                    'list': 'bullet'
                                }, {
                                    'indent': '-1'
                                }, {
                                    'indent': '+1'
                                }],
                                ['direction', {
                                    'align': []
                                }],
                                ['link', 'image', 'video'],
                                ['clean']
                            ]
                        }
                }
                editor = new Quill(item, snowEditorData);
                editor.on('text-change', function() {
                    var editorContent = item.querySelector('.ql-editor').innerHTML;
                    document.querySelector('#message_content').value = editorContent;
                });
            });
        });
</script>
@endpush