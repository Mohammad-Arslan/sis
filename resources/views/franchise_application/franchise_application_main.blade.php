@extends('layouts.master')
@section('content')

    @if(auth()->user())
        <x-breadcrumb>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('franchise-applications.index')}}">Franchise Applications</a></li>
            <li class="breadcrumb-item active">{{isset($franchise) ? 'Edit' : 'Create'}} Franchise Applications</li>
        </x-breadcrumb>
    @endif

    @include('components.flash_message')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body form-steps">
                    {{--@include('franchise_application.guidelines')--}}
                    @include('franchise_application.instructions')
                    @include('franchise_application.add_franchise_application_form')
                </div>
            </div>
        </div>
    </div>
    @include('franchise_application.modals.qualification_modal')
    @include('franchise_application.modals.edit_qualification_modal')
    @include('franchise_application.modals.occupation_modal')
    @include('franchise_application.modals.edit_occupation_modal')
    @include('franchise_application.modals.other_information_modal')
    @include('franchise_application.modals.edit_other_information_modal')
    @include('franchise_application.modals.desired_franchise_detail_modal')
    @include('franchise_application.modals.edit_desired_franchise_detail_modal')

@endsection
@push('header_scripts')
    <style type="text/css">
        .hide-section{display: none;}
    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).on('click', '#service', function (e){
            // $('#otherProfessionalBackground').val('');
            // $('.form-check-input').prop('checked',false);
            // $('#service').prop('checked',true);
            $('.service-section').fadeIn();
            $('.business-section').fadeOut('slow');
        });

        $(document).on('change', '.get-state-constituencies', function(e) {
            var url = '{{route('constituencies.get_state_constituencies')}}';

            $.ajax({

                url: url + '?id=' + $(this).val(),
                type: "GET",
                cache: false,
                success: function(data) {

                    var options = `<option value="">Please select a constituency</option>`;

                    if (data['code'] == 200) {
                        $.each(data['data'], function(index, value) {
                            options += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                    }

                    $('#constituency_id').html(options).attr('disabled', false);
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

        $(document).on('click', '#business', function (e){
            // $('#otherProfessionalBackground').val('');
            // $('.form-check-input').prop('checked',false);
            // $('#business').prop('checked',true);
            $('.service-section').fadeOut('slow');
            $('.business-section').fadeIn('slow');
        });
        $(document).on('click', '#both', function (e){
            // $('#otherProfessionalBackground').val('');
            // $('.form-check-input').prop('checked',false);
            // $('#both').prop('checked',true);
            $('.service-section').fadeIn('slow');
            $('.business-section').fadeIn('slow');
        });

        $(document).on('click', '.copyURL', function(e) {
            /* Get the text field */
            let copyText = document.getElementById('urlText');

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.value);

            $(this).hide();
            $('.copiedBtn').show();
        });

        $('input[name="current_occupation"]').on('change', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        })

    </script>
@endpush
