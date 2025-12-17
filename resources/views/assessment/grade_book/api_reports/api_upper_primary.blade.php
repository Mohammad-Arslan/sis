<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900;&family=Roboto:wght@100;300;400;500;700;800;900&display=swap');

    .single-border,
    .single-border td,
    .single-border th {
        border: 1px solid grey;
        border-collapse: collapse;

    }

    .custom-h5 {
        margin: 0px;
    }

    textarea:focus,
    input:focus {
        outline: none;
        border: none;
    }

    .montser-font {
        font-family: 'Montserrat', sans-serif;
        font-weight: thin;


    }

    .roboto-f {
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
        font-size: 14px;
    }

    .single-border td,
    .single-border th {
        padding: 10px;
        text-transform: capitalize;
        vertical-align: baseline;
        text-align: left;
        /* overflow-wrap: anywhere; */
        font-size: 10px;
        font-family: 'Roboto', sans-serif;
    }

    .white-space-nowrap {
        white-space: nowrap !important;
    }

    .info-headings {
        color: azure;
        background-color: #1e398d;
        padding: 2px;
        width: 220px;
        text-align: center;
        margin: 0 0 10 60px;
        border-radius: 25px;
    }

    .text-left {
        text-align: left !important;
    }


    .text-right {
        text-align: right !important;
    }

    .blue-clr {
        background-color: #cdede8;
    }

    .green-clr {
        background-color: #00A78D;
        color: #fff;
    }

    .tbl-grading {
        margin: 0 0 10 0;
        padding: 10px;
        border: none;

        border-radius: 5px;
        background-color: #cdede8;
    }

    .drk-blue-clr {
        background-color: #1e398d;
        color: #fff;
    }

    .tbl-inputs {
        border: 2px solid #00A88E;
        margin: 60 50 30 50px;
        padding: 20 20 100 20px;
        width: 70%;
        border-radius: 5px;
        background-color: #fff;
    }

    .name-inputs {
        color: #76a7ff;

    }

    .cover-table label {
        white-space: nowrap;
    }

    .small-text {
        color: blue;
        font-size: small;
    }

    .input-spc {
        margin: 10 0 10 0px;
    }

    .table-img tr td input {
        border: none !important;
        border-bottom: 1px solid #000 !important;
        background: transparent;
        width: 100%;
    }

    .table-img {
        position: absolute;
        width: 70%;
        height: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        left: 50%;
    }

    .table-img3 {
        position: absolute;
        width: 70%;
        height: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        left: 50%;
    }

    .all-text {
        font-size: 12px;
    }

    #lined {
        line-height: 31px;
        background-image: -webkit-linear-gradient(left, white 0, transparent 0), -webkit-linear-gradient(right, white 0, transparent 0), -webkit-linear-gradient(white 30px, #000 30px, #000 31px, white 31px);
        background-repeat: repeat-y;
        background-size: 100% 100%, 100% 100%, 100% 31px;
        background-attachment: local;
        border: none;
    }

    .d-flex {
        display: flex !important;
    }

    .input-border {
        border: none !important;
        border-bottom: 1px solid #000 !important;
        background: transparent;
    }

    /* .border-none{
      border: none !important;
    } */

    @media print {
        footer {
            page-break-after: always;
        }
    }
</style>

<table style="width:100%; height:100%;">
    <tr>
        <td style="position: relative;">
            <table class="table-img2">
                <tr>
                    <td>
                        <table class="tbl-inputs">
                            <tr>
                                <td>
                                    <p class="all-text">Class Teacher's Comments:</p>
                                    <textarea name="" id="lined" cols="50" rows="4"></textarea>
                                    <p class="all-text">School Head's Comments: </p>
                                    <textarea name="" id="lined" cols="50" rows="2"></textarea>
                                    <br><br>
                                    <p class="all-text">Class Teacher's Signature: <input class="input-border" type="text" style="width: 60%;">
                                    </p>
                                    <p class="all-text">School Head's Signature: <input class="input-border" type="text" style="width: 60%;">
                                    </p>
                                    <p class="all-text">Dated: <input class="input-border" type="text"></p>
                                </td>
                                <img style="margin-left: 120px; margin-top:480px; position:absolute; " src="{{ asset('assets/img/1x/animate.png') }}">
                            </tr>
                        </table>


                        <table style="margin-left:70px; margin-top:100px;">
                            <tr style="width:100%; ">
                                <td>
                                    <img src="{{ asset('assets/img/1x/bss_logo.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/montserri_acad.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/educators.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/concordia_logo.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/remierdlc_logo.png') }}" style="margin: 10px;">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="small-text" style="margin-left: 10px;">Belgium | Malaysia | Oman | Pakistan | Phillipines | Thailand | UAE</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>

        <td>
            <table>
                <tr>
                    <td>
                        <img style="display:block;  margin:0 auto;" src="{{asset('assets/img/1x/ucs_logo.png')}}" alt="">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; position:relative;">
                        <img src="{{asset('assets/img/1x/title_bg.png')}}" style="margin-bottom: 80px;" alt="">
                        <table class="table-img cover-table" style="margin-top:20px; margin-left:10px; width:40%;">
                            <tr>
                                <td>
                                    <p style="color:#1e398d; font-family: 'Roboto', sans-serif; font-size:22px; font-weight:900; text-align:center; margin-left:-10px; margin-top:-30px; margin-bottom:-8px; line-height: 0.5px;">PROGRESS</p>
                                    <p style="color:#1e398d; font-family:'Roboto', sans-serif; font-size:29px; font-weight:900; text-align:center; margin-left:-10px; margin-bottom:-10px; line-height:0.5px;">REPORT</p>
                                    <p style="color:#1e398d; text-align:center; margin-left:-10px; font-size: 22px; font-weight:500; margin-bottom:-15px;" class="montser-font">TERM I</p>
                                    <h3 class="montser-font" style="color:#1e398d; font-weight:500; font-size:20px; text-align:center; margin-left:-10px;  ">Upper Primary</h3>
                                    <h5 style="margin-top: -20px; margin-left:10px; font-family: 'Open Sans', sans-serif; font-weight:300;">ACADEMIC YEAR 20
                                        <input type="number" style="width: 35px; border: none !important; margin-left:25px; border-bottom: 1px solid #000 !important;">
                                        -
                                        <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                                    </h5>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="position:relative;">
                        <table class="table-img cover-table">
                            <img style="margin-left: 40px; margin-top:-90px; position:absolute; " src="{{ asset('assets/img/1x/name2.png') }}">
                            <tr>
                                <td>

                                    <table style="margin-bottom: 165px;">
                                        <tr>
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Name:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                        <tr class="d-flex input-spc">
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Computer ID:</label>
                                                <input type="text">
                                            </td>
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Class:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                        <tr class="input-spc">
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Class Teacher:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                        <tr class="input-spc">
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Campus:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <table style="margin-left:65px; margin-top:100px;">
                                <tr class="input-spc">
                                    <td>
                                        <img src="{{asset('assets/img/1x/web_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">www.ucs.edu.pk |</p>
                                    </td>
                                    <td>
                                        <img src="{{asset('assets/img/1x/mail_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">info@ucs.edu.pk |</p>
                                    </td>
                                    <td>
                                        <img src="{{asset('assets/img/1x/call_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">042-111-827-111</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:75px;">
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/location_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">10-11 Gurumangat Road, Gulberg III, Lahore, Pakistan</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:125px;">
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/fb_icon.png')}}" alt="">
                                        <img src="{{asset('assets/img/1x/insta_icon.png')}}" alt="">
                                        <img src="{{asset('assets/img/1x/youtube_icon.png')}}" alt="">
                                        <img src="{{asset('assets/img/1x/linked_in.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">United Charter Schools</p>
                                    </td>
                                </tr>
                            </table>
                        </table>
                    </td>
                </tr>
                <img style=" width:100%; height:120vh; position:absolute; z-index:-1; " src="{{ asset('assets/img/1x/img-cld-1.png') }}">
            </table>
        </td>
    </tr>
</table>
<footer>

</footer>

<table>
    <tr style="vertical-align: baseline;">
        <td class="first-table">
            <p class="info-headings">Progress Report - Term I</p>
            <table style="width:100%; border: 1px solid grey; border-radius:5px;" class="single-border">
                <tr>
                    <td>student's age:</td>
                    <td>class average age:</td>
                    <td>
                        2nd parent-teacher meeting:
                        <br><br>
                        attended/not attended
                    </td>
                </tr>
            </table>
            <br>
            <table style="width:100%; border: 1px solid grey; border-radius:5px; background-color:#cdede8;" class="single-border">
                <tr>
                    <td>no. of working days:</td>
                    <td rowspan="2">days present:</td>
                    <td rowspan="2"> days absent:</td>
                </tr>
                <tr>
                    <td>percentage of attendence:</td>
                </tr>
            </table>
            <br>
            <table style="width:100%; border: 1px solid grey; border-radius:5px;" class="single-border">
                <tr class="drk-blue-clr">
                    <th rowspan="2">subjects</th>
                    <td>class work (average)</td>
                    <td>oral work/project work</td>
                    <td>1st assessment</td>
                    <td>2nd assessment</td>
                    <td>end of year examination</td>
                    <td>total marks</td>
                    <td rowspan="2">overall grade</td>
                    <th rowspan="2" class="white-space-nowrap">teacher's comment:</th>

                </tr>

                <tr class="drk-blue-clr">
                    <td style="font-weight: bold !important;">30 <br> marks</td>
                    <td style="font-weight: bolder !important;">20 <br> marks</td>
                    <td style="font-weight: bolder !important;">25 <br> marks</td>
                    <td style="font-weight: bolder !important;">25 <br> marks</td>
                    <td style="font-weight: bolder !important;">50 <br> marks</td>
                    <td style="font-weight: bolder !important;">150 <br> marks</td>
                </tr>

                <tr>
                    <th>english</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>math</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>urdu</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th>Waaqfiat-e-Aama</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Muasharti Aloom</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>General Science</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th>Islamiat</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Nazra</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Arts</th>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Music</th>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Games</th>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td class="second-table">
            <table style="width:100%;">
                <tr>
                    <td>
                        <img src="{{asset('assets/img/1x/logo-hori.png')}}" alt="">
                    </td>
                </tr>
            </table>
            <br>
            <table style="width:100%;" class="tbl-grading">
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">Grading Key</h5>
                    </td>

                </tr>
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">A: Excellent (90% and above)</h5>
                    </td>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">D: Average (50% - 59%)</h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">B: Very good (80% - 89%)</h5>
                    </td>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">E: Below Average (35% - 49%)</h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">C: Good (60% - 79%)</h5>
                    </td>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">U: Improvement Needed (34% and below)</h5>
                    </td>
                </tr>
            </table>
            <table style="width:100%" class="single-border">
                <tr class="drk-blue-clr">
                    <td class="not for use"></td>
                    <th class="roboto-f text-right" style="font-weight: bolder !important;">grade</th>
                </tr>
                <tr class="green-clr">
                    <th class=" text-left" style="font-weight: bolder !important;">
                        general behaviour
                    </th>
                    <td class="not for use"></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        participates actively in class
                    </td>
                    <td>
                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Well Groomed
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Well Mannered
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Cooperative
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Good Listener
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Punctual and Responsible
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Neat and Orderly
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Polite and Kind
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="green-clr">
                    <th class="text-left" style="font-weight: bolder !important;">
                        Social Skills
                    </th>
                    <td class="not for use"></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Works collaboratively
                    </td>
                    <td>
                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Accepts responsibility for own behavior
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Follow Rules
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Listens attentively and responds appropriately
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="green-clr">
                    <th class="text-left" style="font-weight: bolder !important;">
                        Communication Skills
                    </th>
                    <td class="not for use"></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Communicates clearly and audibly
                    </td>
                    <td>
                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Listens carefully without interrupting when others speak
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Takes turns to speak
                    </td>
                    <td>

                    </td>
                </tr>
            </table>
            <br>
            <img src="{{asset('assets/img/1x/family.png')}}" alt="">
        </td>
    </tr>

</table>

<footer>

</footer>

<table style="width:100%; height:100%;">
    <tr>
        <td style="position: relative;">
            <table class="table-img2">
                <tr>
                    <td>
                        <table class="tbl-inputs">
                            <tr>
                                <td>
                                    <p class="all-text">Class Teacher's Comments:</p>
                                    <textarea name="" id="lined" cols="50" rows="4"></textarea>
                                    <p class="all-text">School Head's Comments: </p>
                                    <textarea name="" id="lined" cols="50" rows="2"></textarea>
                                    <br><br>
                                    <p class="all-text">Class Teacher's Signature: <input class="input-border" type="text" style="width: 60%;">
                                    </p>
                                    <p class="all-text">School Head's Signature: <input class="input-border" type="text" style="width: 60%;">
                                    </p>
                                    <p class="all-text">Dated: <input class="input-border" type="text"></p>
                                </td>
                                <img style="margin-left: 120px; margin-top:480px; position:absolute; " src="{{ asset('assets/img/1x/animate.png') }}">
                            </tr>

                        </table>


                        <table style="margin-left:70px; margin-top:100px;">
                            <tr style="width:100%; ">
                                <td>
                                    <img src="{{ asset('assets/img/1x/bss_logo.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/montserri_acad.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/educators.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/concordia_logo.png') }}" style="margin: 10px;">
                                    <img src="{{ asset('assets/img/1x/remierdlc_logo.png') }}" style="margin: 10px;">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="small-text" style="margin-left: 10px;">Belgium | Malaysia | Oman | Pakistan | Phillipines | Thailand | UAE</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>

        <td>
            <table>
                <tr>
                    <td>
                        <img style="display:block;  margin:0 auto;" src="{{asset('assets/img/1x/ucs_logo.png')}}" alt="">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; position:relative;">
                        <img src="{{asset('assets/img/1x/title_bg.png')}}" style="margin-bottom: 80px;" alt="">
                        <table class="table-img cover-table" style="margin-top:20px; margin-left:10px; width:40%;">
                            <tr>
                                <td>
                                    <p style="color:#1e398d; font-family: 'Roboto', sans-serif; font-size:22px; font-weight:900; text-align:center; margin-left:-10px; margin-top:-30px; margin-bottom:-8px; line-height: 0.5px;">PROGRESS</p>
                                    <p style="color:#1e398d; font-family:'Roboto', sans-serif; font-size:29px; font-weight:900; text-align:center; margin-left:-10px; margin-bottom:-10px; line-height:0.5px;">REPORT</p>
                                    <p style="color:#1e398d; text-align:center; margin-left:-10px; font-size: 22px; font-weight:500; margin-bottom:-15px;" class="montser-font">TERM II</p>
                                    <h3 class="montser-font" style="color:#1e398d; font-weight:500; font-size:20px; text-align:center; margin-left:-10px;  ">Upper Primary</h3>
                                    <h5 style="margin-top: -20px; margin-left:10px; font-family: 'Open Sans', sans-serif; font-weight:300;">ACADEMIC YEAR 20
                                        <input type="number" style="width: 35px; border: none !important; margin-left:25px; border-bottom: 1px solid #000 !important;">
                                        -
                                        <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                                    </h5>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="position:relative;">
                        <table class="table-img cover-table">
                            <img style="margin-left: 40px; margin-top:-90px; position:absolute; " src="{{ asset('assets/img/1x/name2.png') }}">
                            <tr>
                                <td>

                                    <table style="margin-bottom: 165px;">
                                        <tr>
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Name:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                        <tr class="d-flex input-spc">
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Computer ID:</label>
                                                <input type="text">
                                            </td>
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Class:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                        <tr class="input-spc">
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Class Teacher:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                        <tr class="input-spc">
                                            <td class="d-flex">
                                                <label class="name-inputs all-text" style="color: #1e398d;" for="">Campus:</label>
                                                <input type="text">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <table style="margin-left:65px; margin-top:100px;">
                                <tr class="input-spc">
                                    <td>
                                        <img src="{{asset('assets/img/1x/web_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">www.ucs.edu.pk |</p>
                                    </td>
                                    <td>
                                        <img src="{{asset('assets/img/1x/mail_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">info@ucs.edu.pk |</p>
                                    </td>
                                    <td>
                                        <img src="{{asset('assets/img/1x/call_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">042-111-827-111</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:75px;">
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/location_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">10-11 Gurumangat Road, Gulberg III, Lahore, Pakistan</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:125px;">
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/fb_icon.png')}}" alt="">
                                        <img src="{{asset('assets/img/1x/insta_icon.png')}}" alt="">
                                        <img src="{{asset('assets/img/1x/youtube_icon.png')}}" alt="">
                                        <img src="{{asset('assets/img/1x/linked_in.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">United Charter Schools</p>
                                    </td>
                                </tr>
                            </table>
                        </table>
                    </td>
                </tr>
                <img style=" width:100%; height:120vh; position:absolute; z-index:-1; " src="{{ asset('assets/img/1x/img-cld-1.png') }}">
            </table>
        </td>
    </tr>
</table>

<footer>

</footer>

<table>
    <tr style="vertical-align: baseline;">
        <td class="first-table">
            <p class="info-headings">Progress Report - Term II</p>
            <table style="width:100%; border: 1px solid grey; border-radius:5px;" class="single-border">
                <tr>
                    <td>student's age:</td>
                    <td>class average age:</td>
                    <td>
                        2nd parent-teacher meeting:
                        <br><br>
                        attended/not attended
                    </td>
                </tr>
            </table>
            <br>
            <table style="width:100%; border: 1px solid grey; border-radius:5px; background-color:#cdede8;" class="single-border">
                <tr>
                    <td>no. of working days:</td>
                    <td rowspan="2">days present:</td>
                    <td rowspan="2"> days absent:</td>
                </tr>
                <tr>
                    <td>percentage of attendence:</td>
                </tr>
            </table>
            <br>
            <table style="width:100%; border: 1px solid grey; border-radius:5px;" class="single-border">
                <tr class="drk-blue-clr">
                    <th rowspan="2">subjects</th>
                    <td>class work (average)</td>
                    <td>oral work/project work</td>
                    <td>1st assessment</td>
                    <td>2nd assessment</td>
                    <td>end of year examination</td>
                    <td>total marks</td>
                    <td rowspan="2">overall grade</td>
                    <th rowspan="2" class="white-space-nowrap">teacher's comment:</th>

                </tr>

                <tr class="drk-blue-clr">
                    <td style="font-weight: bold !important;">30 <br> marks</td>
                    <td style="font-weight: bolder !important;">20 <br> marks</td>
                    <td style="font-weight: bolder !important;">25 <br> marks</td>
                    <td style="font-weight: bolder !important;">25 <br> marks</td>
                    <td style="font-weight: bolder !important;">50 <br> marks</td>
                    <td style="font-weight: bolder !important;">150 <br> marks</td>
                </tr>

                <tr>
                    <th>english</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>math</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>urdu</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th>Waaqfiat-e-Aama</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Muasharti Aloom</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>General Science</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th>Islamiat</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Nazra</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Arts</th>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Music</th>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Games</th>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td class="blue-clr"></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td class="second-table">
            <table>
                <tr>
                    <td>
                        <img src="{{asset('assets/img/1x/logo-hori.png')}}" alt="">
                    </td>
                </tr>
            </table>
            <br>
            <table style="width:100%;" class="tbl-grading">
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">Grading Key</h5>
                    </td>

                </tr>
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">A: Excellent (90% and above)</h5>
                    </td>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">D: Average (50% - 59%)</h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">B: Very good (80% - 89%)</h5>
                    </td>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">E: Below Average (35% - 49%)</h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">C: Good (60% - 79%)</h5>
                    </td>
                    <td>
                        <h5 style="color:#1e398d;white-space: nowrap;" class="custom-h5">U: Improvement Needed (34% and below)</h5>
                    </td>
                </tr>
            </table>
            <table style="width:100%" class="single-border">
                <tr class="drk-blue-clr">
                    <td class="not for use"></td>
                    <th class="text-right" style="font-weight: bolder !important;">grade</th>
                </tr>
                <tr class="green-clr">
                    <th class="text-left" style="font-weight: bolder !important;">
                        general behaviour
                    </th>
                    <td class="not for use"></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        participates actively in class
                    </td>
                    <td>
                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Well Groomed
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Well Mannered
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Cooperative
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Good Listener
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Punctual and Responsible
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Neat and Orderly
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Polite and Kind
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="green-clr">
                    <th class="text-left" style="font-weight: bolder !important;">
                        Social Skills
                    </th>
                    <td class="not for use"></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Works collaboratively
                    </td>
                    <td>
                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Accepts responsibility for own behavior
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Follow Rules
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Listens attentively and responds appropriately
                    </td>
                    <td>

                    </td>
                </tr>
                <tr class="green-clr">
                    <th class="text-left" style="font-weight: bolder !important;">
                        Communication Skills
                    </th>
                    <td class="not for use"></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Communicates clearly and audibly
                    </td>
                    <td>
                    </td>
                </tr>
                <tr class="blue-clr">
                    <td>
                        Listens carefully without interrupting when others speak
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="white-space-nowrap">
                        Takes turns to speak
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
            <br>
            <img src="{{asset('assets/img/1x/family.png')}}" alt="">
        </td>
    </tr>
</table>
