<section class="form-bg">
    <form id="ucsForm" action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="school-info">
                    <h2>Personal Information </h2>
                    <div class="form form-right col-md-12">
                        <label>Full Name<span class="star-red">*</span></label>
                        <input type="text" name="inquiry_name" class="form-control" maxlength="50" id="inquiry_name">
                    </div>

                    <div class="form form-right col-md-12">
                        <label>CNIC<span class="star-red">*</span></label>
                        <input type="text" name="cnic" class="form-control" maxlength="13" id="cnic" placeholder="Enter 13 digit without dashes">
                    </div>

                    <div class="form form-right col-md-12">
                        <label>Address<span class="star-red">*</span></label>
                        <input type="text" name="address" class="form-control" maxlength="200" id="address">
                    </div>

                    <div class="form form-right col-md-12">
                        <label>City<span class="star-red">*</span></label>
                        <select name="city" id="city" class="select2_demo_1 form-control">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form form-right col-md-12">
                        <label>Contact  No. 1<span class="star-red">*</span></label>
                        <input type="text" class="form-control mob_no_1" maxlength="11" placeholder="Mobile No (03001234567)" id="mob_no_1" name="mob_no_1">
                    </div>
                    <div class="form form-right col-md-12">
                        <label>Contact No. 2</label>
                        <input type="text" class="form-control mob_no_2" name="mob_no_2" maxlength="11" placeholder="Mobile No (03001234567)">
                    </div>

                    <div class="form form-right col-md-12">
                        <label>Email</label>
                        <input type="text" name="email" placeholder="example@email.com" maxlength="50" class="form-control email">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="school-info">
                    <h2>Other Information</h2>
                    <div class="form form-left col-md-12">
                        <label>Current Business/Occupation<span class="star-red"></span></label>
                        <input type="text" name="current_bussiness" class="form-control" maxlength="200" placeholder="Import Export">
                    </div>
                    <div class="form form-right col-md-12">
                        <label>Have you led a franchise? If yes, please mention the name</label>
                        <div>
                            <input type="radio" name="led_franchise" id="yes" value="y"> &nbsp; Yes
                            <br>
                            <input type="radio" class="" name="led_franchise" id="no" value="n"> &nbsp; No
                        </div>
                        <input type="text" name="explaination" class="form-control mt-3" maxlength="50">
                    </div>
                    <div class="form form-right col-md-12">
                        <label>Are you interested in</label>
                        <div>
                            <input type="radio" name="franchise" id="newFranchise" value="New"> &nbsp; Opening a new franchise
                            <br>
                            <input type="radio" class="" name="franchise" id="convertFranchise" value="Old"> &nbsp; Converting an existing school/college
                        </div>
                    </div>
                    <div class="form form-right col-md-12">
                        <label>Area/Location of interest for UCS franchise</label>
                        <input type="text" name="area_name" class="form-control area_name" maxlength="50">
                    </div>
                    <div class="form form-right col-md-12">
                        <label>Where did you hear about us? <span class="star-red">*</span></label>
                        <select name="source" class="select2_demo_1 form-control source" id="source">
                            <option value="">Select Option </option>
                            <option value="email">Email </option>
                            <option value="sms">SMS </option>
                            <option value="whatsapp">Whatsapp </option>
                            <option value="social media">Social Media </option>
                            <option value="newspaper_tv">Newspaper / TV Ads</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-md-12 mb-3">
                <div class="captcha-info">
                    <div class="g-recaptcha" data-sitekey="6LcCkgYeAAAAAKRy126ArDid7fYnRuTkLPnm0lqg"><div style="width: 304px; height: 78px;"><div><iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LcCkgYeAAAAAKRy126ArDid7fYnRuTkLPnm0lqg&amp;co=aHR0cDovL2xvY2FsaG9zdDo4MA..&amp;hl=en&amp;v=QENb_qRrX0-mQMyENQjD6Fuj&amp;size=normal&amp;cb=m5mohv4e4msl" width="304" height="78" role="presentation" name="a-hprfs8ulkfel" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe style="display: none;"></iframe></div>
                    <div>
                        For Call: <a href="tel:042-38096666" data-rel="external"><strong>042-38096666</strong></a>
                    </div>
                    <div>
                        <a href="javascript:void(0)" style="margin-top: -60px;" class="submit-btn float-right" onclick="submitForm()">Submit
                        </a>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="row mt-3 mb-3">--}}
{{--            <div class="col-md-12 mb-3">--}}
{{--                <div class="captcha-info">--}}
{{--                    <div class="g-recaptcha" data-sitekey="6LcCkgYeAAAAAKRy126ArDid7fYnRuTkLPnm0lqg"></div>--}}
{{--                    <div>--}}
{{--                        For Call: <a href="tel:042-38096666" data-rel="external"><strong>042-38096666</strong></a>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <a href="javascript:void(0)" style="margin-top: -60px;" class="submit-btn float-right" onClick="submitForm()">Submit--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="clear"></div>
    </form>
</section>
