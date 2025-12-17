<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Franchise Registration</title>

    <style type="text/css">
        .school-info{min-height: 690px;}
        .submit-bg{ min-height: 90px; }
        .captcha-info{
            border: 1px solid #dee2e6;
            padding: 25px 15px;
            position: relative;
            background: #fbfbfb;
        }

        .submit-btn {
            background: #1d398d;
            padding: 5px 35px;
            color:  #fff !important;
            font-size: 20px;
            font-family: "open sans", sans-serif;
            border-radius: 100px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .other-projects ul li{
            padding-left: 30px !important;
            padding-right: 30px;
        }
        .other-country-names ul li{
            padding: 10px 43px ;
        }
        .other-country-names ul {
            margin: 0 auto !important;
            width: 1024px !important;
        }
        .other-country-names {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .other-projects img {
            width: 100%;
        }
        ul.address li {
            width: 100%;
        }

        .custom_margin {

            margin-left: 12%;

        }

        .footer-text-1 h3{
            margin-bottom: 13px;
            float: left;
            width: 100%;
            color: #1d398d;
            font-weight: 700;
            font-size: 24px;
        }
        @media screen and (min-width: 600px) and (max-width: 1200px){
            .other-country-names ul li {
                width: 25%;
            }
        }

        @media screen and (min-width: 376px) and (max-width: 780px) {
            .other-country-names ul li {
                width: 50% !important;
            }
            .footer-logo img{
                width: 300px;
            }
            .school-info{min-height: 544px;}
        }
        @font-face {
            font-family: 'Open Sans Regular';
            font-style: normal;
            font-weight: 400;
            src: url('chrome-extension://gkkdmjjodidppndkbkhhknakbeflbomf/fonts/open_sans/open-sans-v18-latin-regular.woff');
        }
        @font-face {
            font-family: 'Open Sans Bold';
            font-style: normal;
            font-weight: 800;
            src: url('chrome-extension://gkkdmjjodidppndkbkhhknakbeflbomf/fonts/open_sans/open-sans-v18-latin-800.woff');
        }
        @font-face {
            font-weight: 400;
            font-style:  normal;
            font-family: 'Circular-Loom';

            src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Book-cd7d2bcec649b1243839a15d5eb8f0a3.woff2') format('woff2');
        }
        @font-face {
            font-weight: 500;
            font-style:  normal;
            font-family: 'Circular-Loom';

            src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Medium-d74eac43c78bd5852478998ce63dceb3.woff2') format('woff2');
        }
        @font-face {
            font-weight: 700;
            font-style:  normal;
            font-family: 'Circular-Loom';

            src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Bold-83b8ceaf77f49c7cffa44107561909e4.woff2') format('woff2');
        }
        @font-face {
            font-weight: 900;
            font-style:  normal;
            font-family: 'Circular-Loom';

            src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Black-bf067ecb8aa777ceb6df7d72226febca.woff2') format('woff2');
        }
    </style>
</head>

<body style="overflow-x: hidden">
    <div class="container">
        <div class="franchise-header">
            <div class="logo-header ">
                <a href="https://www.unitedcharterschools.net/" target="_blank">
                    <img src="{{ asset('ucs-logo.png') }}" width="300">
                </a>
            </div>
        </div>
        <div class="clear"></div>
        <section class="admission-text ">
            <h1>Apply for Franchise</h1>
            <p>United Charter Schools was launched with the intention of combining Beaconhouse's 46 years of excellence in education with the innovations of modern technology.
                <br>
                Fill out the form below and join us as a franchisee on our shared journey towards giving the children of tomorrow a solid foundation to build their future.
            </p>
        </section>

        <section class="form-bg">
            @include('components.flash_message')
            <br>
            <form id="ucsForm" class="needs-validation" novalidate action="{{ route('franchises-inquiry.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="campaign" name="created_by">
                <div class="row">
                    <div class="col-md-6">
                        <div class="school-info">
                            <h2>Personal Information </h2>
                            <div class="form form-right col-md-12">
                                <label>Full Name<span class="star-red">*</span></label>
                                <input type="text" name="full_name" class="form-control" maxlength="50" value="{{ old('full_name') }}" id="full_name" required>
                                <label id="full-name-error" class="error @if(!$errors->has('full_name')) hide @endif" for="full_name">
                                    @if($errors->has('full_name'))
                                        {{ $errors->first('full_name') }}
                                    @else
                                        Name is required.
                                    @endif
                                </label>
                            </div>

                            <div class="form form-right col-md-12">
                                <label>CNIC (35202-1234567-8)<span class="star-red">*</span></label>
                                <input type="text" name="CNIC" class="form-control" pattern="^[0-9]{5}-[0-9]{7}-[0-9]$" value="{{ old('CNIC') }}" maxlength="15" id="cnic" placeholder="Enter 13 digit with dashes" required>
                                <label id="cnic-error" class="error @if(!$errors->has('CNIC')) hide @endif" for="cnic">
                                    @if($errors->has('CNIC'))
                                        {{ $errors->first('CNIC') }}
                                    @else
                                        CNIC is required.
                                    @endif
                                </label>
                            </div>

                            <div class="form form-right col-md-12">
                                <label>Address<span class="star-red">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" maxlength="200" id="address" required>
                                <label id="address-error" class="error @if(!$errors->has('address')) hide @endif" for="address">
                                    @if($errors->has('address'))
                                        {{ $errors->first('address') }}
                                    @else
                                        Address is required.
                                    @endif
                                </label>
                            </div>

                            <div class="form form-right col-md-12">
                                <label>City<span class="star-red">*</span></label>
                                <select name="city_id" id="city_id" class="select2_demo_1 form-control" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                                <label id="city_id-error" class="error @if(!$errors->has('city_id')) hide @endif" for="city_id">
                                    @if($errors->has('city_id'))
                                        {{ $errors->first('city_id') }}
                                    @else
                                        City is required.
                                    @endif
                                </label>
                            </div>
                            <div class="form form-right col-md-12">
                                <label>Contact  No. 1<span class="star-red">*</span></label>
                                <input type="text" value="{{ old('contact_no_1') }}" class="form-control mob_no_1" maxlength="11" placeholder="Mobile No (03001234567)" id="contact_no_1" name="contact_no_1" required>
                                <label id="contact_no_1-error" class="error @if(!$errors->has('contact_no_1')) hide @endif" for="contact_no_1">
                                    @if($errors->has('contact_no_1'))
                                        {{ $errors->first('contact_no_1') }}
                                    @else
                                        Contact no 1 is required.
                                    @endif
                                </label>
                            </div>
                            <div class="form form-right col-md-12">
                                <label>Contact No. 2</label>
                                <input type="text" value="{{ old('contact_no_2') }}" class="form-control mob_no_2" name="contact_no_2" maxlength="11" placeholder="Mobile No (03001234567)">
                                <label id="contact_no_2-error" class="error @if(!$errors->has('contact_no_2')) hide @endif" for="contact_no_2">
                                    @if($errors->has('contact_no_2'))
                                        {{ $errors->first('contact_no_2') }}
                                    @endif
                                </label>
                            </div>

                            <div class="form form-right col-md-12">
                                <label>Email</label>
                                <input type="text" value="{{ old('email') }}" name="email" placeholder="example@email.com" maxlength="50" class="form-control email">
                                <label id="email-error" class="error @if(!$errors->has('email')) hide @endif" for="email">
                                    @if($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="school-info">
                            <h2>Other Information</h2>
                            <div class="form form-left col-md-12">
                                <label>Current Business/Occupation<span class="star-red"></span></label>
                                <input type="text" value="{{ old('current_occupation') }}" name="current_occupation" class="form-control" id="current_occupation" maxlength="200">
                                <label id="current_occupation-error" class="error @if(!$errors->has('current_occupation')) hide @endif" for="current_occupation">
                                    @if($errors->has('current_occupation'))
                                        {{ $errors->first('current_occupation') }}
                                    @endif
                                </label>
                            </div>
                            <div class="form form-right col-md-12">
                                <label>Have you led a franchise? If yes, please mention the name</label>
                                <div>
                                    <input type="radio" name="led_franchise" id="yes" value="yes" {{ old('led_franchise') == 'yes' ? 'checked' : '' }}> &nbsp; Yes
                                    <br>
                                    <input type="radio" class="" name="led_franchise" id="no" value="no" {{ old('led_franchise') == 'no' ? 'checked' : '' }}> &nbsp; No
                                </div>
                                <label id="led_franchise-error" class="error @if(!$errors->has('led_franchise')) hide @endif" for="led_franchise">
                                    @if($errors->has('led_franchise'))
                                        {{ $errors->first('led_franchise') }}
                                    @endif
                                </label>
                                <input type="text" value="{{ old('franchise_name') }}" name="franchise_name" class="form-control mt-3 explaination" maxlength="50">
                            </div>
                            <div class="form form-right col-md-12">
                                <label>Are you interested in<span class="star-red">*</span></label>
                                <div>
                                    <input type="radio" name="franchise_interest" id="franchise_interest" value="new_franchise" {{ old('franchise_interest') == 'new_franchise' ? 'checked' : '' }} required> &nbsp; Opening a new franchise
                                    <br>
                                    <input type="radio" class="" name="franchise_interest" id="franchise_interest" value="convert_existing" {{ old('franchise_interest') == 'convert_existing' ? 'checked' : '' }} required> &nbsp; Converting an existing school/college
                                    <label id="franchise_interest-error" class="error @if(!$errors->has('franchise_interest')) hide @endif" for="franchise_interest">
                                        @if($errors->has('franchise_interest'))
                                            {{ $errors->first('franchise_interest') }}
                                        @else
                                            Franchise interest is required!
                                        @endif
                                    </label>
                                </div>
                            </div>
                            <div class="form form-right col-md-12">
                                <label>Area/Location of interest for UCS franchise<span class="star-red">*</span></label>
                                <input type="text" name="area_location" class="form-control area_name" maxlength="50" id="area_location" value="{{ old('area_location') }}" required>
                                <label id="area_location-error" class="error @if(!$errors->has('area_location')) hide @endif" for="area_location">
                                    @if($errors->has('area_location'))
                                        {{ $errors->first('area_location') }}
                                    @else
                                        Area/Location is required!
                                    @endif
                                </label>
                            </div>
                            <div class="form form-right col-md-12">
                                <label>Where did you hear about us? <span class="star-red">*</span></label>
                                <select name="source_id" class="select2_demo_1 form-control source" id="source_id" required>
                                    <option value="">Select Option </option>
                                    @foreach($sources as $source)
                                        <option value="{{ $source->id }}" {{ old('source_id') == $source->id ? 'selected' : '' }}>{{ $source->source_name }}</option>
                                    @endforeach
                                </select>
                                <label id="source_id-error" class="error @if(!$errors->has('source_id')) hide @endif" for="source_id">
                                    @if($errors->has('source_id'))
                                        {{ $errors->first('source_id') }}
                                    @else
                                        Source is required!
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 submit-bg mt-3 mb-3 custom_btn_main">
                    <button class="submit-btn custom_btn_submit">
                        Submit
                    </button>
                </div>
                <div class="clear"></div>
            </form>
        </section>
    </div>
</body>
</html>
