@extends('layouts.master')
@section('content')
    @include('components.flash_message')
    <div class="row">
        <div class="col-xl-12">

            <div class="card">
                <div class="card-header">
                    Application&nbsp;Type:<br><br>
                    <div class="live-preview">
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            @forelse(getApplicationTypes() as $key => $applicationType)
                                <div class="form-check form-check-inline" style="margin-left: 10px;">
                                    <input data-name="{{ $applicationType->name }}"
                                        data-route="{{ route('leave.applications.forms') }}"
                                        class="form-check-input application_type" {{ $key == 0 ? 'checked' : '' }}
                                        type="radio" id="application_type_{{ $applicationType->id }}"
                                        name="application_type" value="{{ $applicationType->id }}" style="cursor:pointer;">
                                    <label class="form-check-label" for="application_type_{{ $applicationType->id }}"
                                        style="cursor:pointer;">
                                        {{ $applicationType->name }}
                                    </label>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <input type="hidden" value="{{ getApplicationTypes()[0]->name ?? '' }}"
                            id="default_application_to_load">
                    </div>
                </div>
                <form action="{{ route('leave.application.create') }}"
                    data-route="{{ route('leave.application.already-applied') }}" method="POST"
                    class="card-body row g-3 needs-validation leave-application-forms" novalidate>
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $employee_id }}" name="employee_id">
                    <div class="leave-forms-area">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function() {

            let application_type = 'undefined';
            let url_params = getUrlVars();
            if (typeof url_params['application_type'] !== 'undefined') {
                application_type = url_params['application_type'].replace(/\%20/g, ' ');
            }
            let found = false;

            /*check if application type exists in url then load specific application*/
            if (typeof application_type !== 'undefined' && application_type != '') {
                $('.application_type').each(function(i, el) {
                    if ($(el).attr('data-name') == application_type) {
                        $(el).click();
                        found = true;
                        return false;
                    }
                })
            }

            /**load default application type if not application type found in url*/
            if (!found) {
                let default_application_name = $('#default_application_to_load').val();
                $('.application_type').each(function(i, el) {
                    if ($(el).attr('data-name') == default_application_name) {
                        $(el).click();
                        return false;
                    }
                })
            }


        })

        /**load leave forms according to selected leave option*/
        $(document).on('click', '.application_type', function() {
            let application_type_id = $(this).val();
            let url = $(this).attr('data-route');
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    application_type_id
                },
                success: function(response) {
                    $('.leave-forms-area').html(response);
                }
            })
        })

        /**whenever employee submit a leave form this function will check if leave already applied or not*/
        $(document).on('submit', '.leave-application-forms', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('data-route');

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function(response) {
                    if (response == 1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Leave already applied for selected date',
                            allowOutsideClick: false
                        })
                    } else if (response == 2) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'You have already used your leave assigned quota for the selected leave type.',
                            allowOutsideClick: false
                        })
                    } else {
                        form[0].submit();
                    }
                }
            })
        })
    </script>
@endpush
