@extends('layouts.master')
@section('content')
    @include('components.flash_message')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body form-steps">
                    <section class="admission-text ">
                        <h1>Apply for Franchise</h1>
                        <p>A warm welcome from the Franchise team at United Charter Schools. We seek to identify potential rather than
                            prior attainment in our applicants. At United Charter Schools, we welcome bright, motivated, engaging
                            students with enthusiasm for learning. We aim to allow many generations of children to benefit from a truly
                            exceptional education. Our academics draw on the very best teaching practices and traditions. Choosing the
                            right school for your child is a challenging decision; there are many factors to consider, and we are here
                            to help.
                            <br><br>
                            Please fill out the following form to register your interest in our school. Applicants for Franchise are
                            strongly encouraged to apply as early as possible.
                        </p>
                    </section>
                    @include('franchises-inquiry.franchise_inquiry_form')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">
        .hide-section{display: none;}
    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
    </script>
@endpush
