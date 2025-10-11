@extends('layouts.master')
@section('content')
@include('components.flash_message')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body form-steps">
                {{--@include('franchises-inquiry.guidelines')--}}
                @include('franchises-inquiry.instructions')
                @include('franchises-inquiry.add_franchises_form')
            </div>
        </div>
    </div>
</div>
@include('franchises-inquiry.modals.qualification_modal')
@include('franchises-inquiry.modals.edit_qualification_modal')
@include('franchises-inquiry.modals.occupation_modal')
@include('franchises-inquiry.modals.edit_occupation_modal')
@include('franchises-inquiry.modals.other_information_modal')
@include('franchises-inquiry.modals.edit_other_information_modal')
@include('franchises-inquiry.modals.desired_franchise_detail_modal')
@include('franchises-inquiry.modals.edit_desired_franchise_detail_modal')

@endsection
@push('header_scripts')
<style type="text/css">
    .hide-section{display: none;}
</style>
@endpush
@push('footer_scripts')
<script type="text/javascript">
    $(document).on('click', '#service', function (e){
        $('#otherProfessionalBackground').val('');
        $('.form-check-input').prop('checked',false);
        $('#service').prop('checked',true);
        $('.service-section').fadeIn();
        $('.business-section').fadeOut('slow');
    });
    $(document).on('click', '#business', function (e){
        $('#otherProfessionalBackground').val('');
        $('.form-check-input').prop('checked',false);
        $('#business').prop('checked',true);
        $('.service-section').fadeOut('slow');
        $('.business-section').fadeIn('slow');
    });
    $(document).on('click', '#both', function (e){
        $('#otherProfessionalBackground').val('');
        $('.form-check-input').prop('checked',false);
        $('#both').prop('checked',true);
        $('.service-section').fadeIn('slow');
        $('.business-section').fadeIn('slow');
    });


</script>
@endpush
