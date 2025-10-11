@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .square-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 10px;
        transition: transform 0.2s, box-shadow 0.2s;

    }

    .square-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
    }

    /* .card-to-toggle {
    display: none;
    } */
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="row">

    <div class="col-lg-4 mb-lg-0 mb-4">
        <div class="card z-index-2 h-100">
            <div style="margin-top: 20px"
                class=" text-center profile-user position-relative d-inline-block mx-auto  mb-4">
                @if (Auth::user()->employee->emp_image != '')
                <img src="{{ get_file_from_s3('images/' . Auth::user()->employee->emp_image) }}"
                    class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                @else
                <img src="{{ asset('uploads/employees/user-dummy-img.jpg') }}"
                    class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                @endif
                <h5 style="color: #000; font-weight: 600; font-size: 20px !important;margin-top: 20px;"
                    class="fs-12 mb-1">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</h5>


                <p class="text-muted mb-0">
                    {{ isset(Auth::user()->employee->department) ? Auth::user()->employee->department->department_name :
                    ''
                    }}
                    /
                    {{ isset(Auth::user()->employee->designation) ?
                    Auth::user()->employee->designation->designation_name :
                    '' }}
                </p>
            </div>


            <div style="margin-left:20px;" class="text-left">
                <p class="fs-12 mb-1">
                    <i style="border-radius:10px; padding: 15px; background-color: #F5F8FA; margin-right: 10px; font-size: 15px;"
                        class="fas fa-cogs"></i>
                    <b style="font-size: 14px;"> Service Length: </b>

                    <span
                        style="color:#50CD96; margin-left: 29%; background-color:#E8FFF3; padding:6px; border-radius:7px; font-weight:500;">
                        {{ now()->diffInYears(Auth::user()->employee->hiring_date) }} years
                    </span>
                </p>


                <p style="margin-top: 35px" class="fs-12 mb-1">
                    <i style="border-radius:10px; padding: 15px; background-color: #F5F8FA; margin-right: 10px; font-size: 15px;"
                        class="fas fa-check-circle"></i>
                    <b style="font-size: 14px;">Status:</b>
                    <span
                        style="color:#D2004D; margin-left: 48%; background-color:#FFF5F8; padding:6px; border-radius:7px; font-weight:500;">{{
                        Auth::user()->employee->job_status }}</span>
                </p>

                <p style="margin-top: 35px" class="fs-12 mb-1">
                    <i style="border-radius:10px; padding: 15px; background-color: #F5F8FA; margin-right: 10px; font-size: 15px;"
                    class="fas fa-university"></i>
                    <b style="font-size: 14px;">Branch:</b>
                    <span
                        style="color:#FBC400; margin-left: 30%; background-color:#FFF8DD; padding:6px; border-radius:7px; font-weight:500;">{{
                        Auth::user()->employee->branch->br_name }}</span>
                </p>

                {{-- <p class="fs-12 mb-1"><b>Employee ID:</b> {{ Auth::user()->employee->employee_id }}</p> --}}

            </div>


        </div>
    </div>
    <div class="col-lg-4 mb-lg-0 mb-4">
        <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
                <h4 style="color: #000; font-weight:550" class="text-capitalize">Empowering Educators</h4>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="square-card" style="background-color: #FFF5F8; width: 150px; height: 100px;">
                            <div class="card-content">
                                <img src="{{ asset('theme/dist/default/assets/images/education_image.png') }}"
                                    style="width: 50%; height: 60%;" alt="">
                                <div class="text-container">
                                    <h5 style="color: #D2004D; " >Scholastic LTMS</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 mb-4">
                        <div class="square-card" style="background-color: #F1FAFF; width: 150px; height: 100px;">
                            <img src="{{ asset('theme/dist/default/assets/images/curriculum_center.png') }}" width="60%"
                                height="60%" alt="">
                            <div class="text-container">
                                <h5 style="color: #19A8FB;">Curriculum Center</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="square-card" style="background-color: #E8FFF3; width: 150px; height: 100px;">
                            <img src="{{ asset('theme/dist/default/assets/images/teacher_effectiveness.png') }}"
                                width="65%" height="65%" alt="">
                            <div class="text-container">
                                <h5 style="color: #50CD96;">Teacher Effectiveness</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 ">
                        <div class="square-card" style="background-color: #F7F4FE; width: 150px; height: 100px;">
                            <img src="{{ asset('theme/dist/default/assets/images/lesson_plan.png') }}" width="65%"
                                height="65%" alt="">
                            <div class="text-container">
                                <h5 style="color: #7239EA;">Lesson Planning</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ">
                        <div class="square-card" style="background-color: #FFF8DD; width: 150px; height: 100px;">
                            <img src="{{ asset('theme/dist/default/assets/images/grade_book.png') }}" width="65%"
                                height="65%" alt="">
                            <div class="text-container">
                                <h5 style="color: #FBC400;">Student Grade Book</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 ">
                        <div class="square-card" style="background-color: #FEF4F7; width: 150px; height: 100px;">
                            <img src="{{ asset('theme/dist/default/assets/images/parent_app.png') }}" width="65%"
                                height="65%" alt="">
                            <div class="text-container">
                                <h5 style="color: #D2004D;">Scholastic Parent App</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-lg-0 mb-4">
        <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
                <h4 style="color: #000; font-weight:550" class="text-capitalize">What's New</h4>
            </div>
          <img style="d-flex align-items-center mb-8; padding:30px; border-radius:10%;" src="https://b2training.beaconhouse.net/home/home_dashboard/images/new-beams.gif" alt="" srcset="">
        </div>
    </div>

</div>
<br>
@endsection

@push('header_scripts')
@endpush

@push('footer_scripts')
<script src="{{ asset('theme/dist/default/assets/libs/dragula/dragula.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/dom-autoscroller/dom-autoscroller.min.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

{{-- <script>
    const toggleButton = document.getElementById("toggleCardsButton");
    const cardsToToggle = document.querySelectorAll(".card-to-toggle");
    let cardsVisible = false;

    toggleButton.addEventListener("click", function() {
        cardsToToggle.forEach(card => {
            if (cardsVisible) {
                card.style.display = "none";
            } else {
                card.style.display = "block";
            }
        });

        cardsVisible = !cardsVisible;

        // Change the button text
        toggleButton.textContent = cardsVisible ? "Show More" : "Show Less";
    });
</script> --}}

@endpush
