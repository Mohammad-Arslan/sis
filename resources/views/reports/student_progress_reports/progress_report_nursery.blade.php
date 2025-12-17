<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nursery Progress Report</title>
    <style>
        /* table,
  th,
  td {
    border: 1px solid black;

  } */
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
            margin-bottom: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #cdede8;
            white-space: nowrap !important;
        }

        .drk-blue-clr {
            background-color: #1e398d;
            color: #fff;
        }

        .tbl-inputs {
            border: 2px solid #00A88E;
            margin:50px 50px 30px 50px;
            padding: 15px 25px 100px;
            border-radius: 5px;
            width: 450px;
            background-color: #b9e4ef ;
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
            margin-top: 10px;
            margin-bottom: 10px;
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


        #linedTransparent {
            line-height: 31px;
            background-image: -webkit-linear-gradient(left, transparent 0, transparent 0), -webkit-linear-gradient(right, transparent 0, transparent 0), -webkit-linear-gradient(#b9e4ef 30px, #000 30px, #000 31px, transparent 31px);
            background-repeat: repeat-y;
            background-size: 100% 100%, 100% 100%, 100% 31px;
            background-attachment: local;
            border: none;
            width: -webkit-fill-available;
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

        textarea:focus,
        input:focus {
            outline: none;
            border: none;
        }

        input {
            border: 1px solid #00A88E;

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




        .info-headings {
            color: azure;
            background-color: #1e398d;
            padding: 2px;
            width: 220px;
            text-align: center;
            margin: 10 0 10 60px;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 180px;
            border-radius: 25px;
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


        .table-img {
            top: 50%;
        }

        .table-img,
        tr,
        td {
            border: none !important;
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

        .tbl-info2 td {
            border-left: 1px solid grey !important;
            padding: 0 5px;

        }

        .tbl-info2 td:first-child {
            border-left: none !important;

        }



        .tbl-lang {
            width: 100%;
            border: 1px solid grey;
            border-radius: 5px;

        }

        .tbl-urdu {
            width: 80%;
            margin-bottom: 20px;
            margin-left: 100px;
            padding: 10px;
            background-color: #cdede8;
            border: 1px solid grey;
            border-radius: 5px;

        }

        /* .table-img tr,.table-img td{
   width: 100%;
  } */
        .table-img tr td input {
            border: none !important;
            border-bottom: 1px solid #000 !important;
            background: transparent;
            width: 100%;
        }

        /* .table-img tr td  {
  display: flex;
  } */
        @media print {
            footer {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    <table style="width:100%; height:100%;">
        <tr>
            <td style="position: relative;">
                <table class="table-img2">
                    <tr>
                        <td>
                            <table class="tbl-inputs">
                                <tr>
                                    <td style="z-index: inherent;">
                                        <p class="all-text">Class Teacher's Comments:</p>
                                        <textarea name="" id="linedTransparent" cols="" rows="4"></textarea>
                                        <p class="all-text">School Head's Comments: </p>
                                        <textarea name="" id="linedTransparent" cols="" rows="2"></textarea>
                                        <p class="all-text">Class Teacher's Signature: <input class="input-border" type="text" style="width: 60%;">
                                        </p>
                                        <p class="all-text">School Head's Signature: <input class="input-border" type="text" style="width: 60%;">
                                        </p>
                                        <p class="all-text">Dated: <input class="input-border" type="text"></p>
                                    </td>
                                    <img style="margin-left: 50px; margin-top:400px; position:absolute; " src="{{ asset('assets/img/1x/new-kids-p.png') }}">
                                </tr>
                            </table>
                            <table style="margin-left:65px; margin-top:10px;">
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
                            <table style="margin-left:75px; margin-top:-25px;">
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/location_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">10-11 Gurumangat Road, Gulberg III, Lahore, Pakistan</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:125px; margin-top:-25px;">
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
                            <table style="margin-left:70px; ">
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
                        <td style="position:relative;">
                            <img src="{{asset('assets/img/1x/new-kids.png')}}" alt="">
                            <table class="table-img cover-table">
                                <tr>
                                    <td style="text-align: center;">
                                        <h2 style="color:#00A88E; margin-top: 70px; font-family:dexa, expanded, extra bold, Sans-serif; font-size:20px;">Progress Report: Term I</h2>
                                        <h3 style="color:#1e398d; margin-top: -10px; font-family:raleway, medium; font-size: 16px;;">Early Years: Nursery</h3>
                                        <h5 style="margin-top: -10px; font-size:12px;">ACADEMIC YEAR 20
                                            <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                                            -
                                            <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="margin-bottom: 80px; margin-top:-10px;">
                                            <tr>
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Name:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                            <tr class="d-flex input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Computer ID:</label>
                                                    <input type="text">
                                                </td>
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Class:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Class Teacher:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Campus:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <footer>

    </footer>

    <table style="width:100%; height:100%; margin-top:30px;">
        <tr>
            <td>
                <table style="width:110%; height:100%; position:relative;">
                    <tr>
                        <td>
                            <p class="info-headings">Student's Information</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width:100%; border:1px solid grey; border-collapse:collapse; " class="tbl-info2">
                                <tr>
                                    <td class="all-text">Student's Age</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr style="background-color:#cdede8;">
                                    <td class="all-text">Class Average Age</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr>
                                    <td class="all-text">Term 1: Total No. of Working Days</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr style="background-color:#cdede8;">
                                    <td class="all-text">Term 1: Student's Attendance</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr>
                                    <td class="all-text">1st Parent-Teacher Meeting</td>
                                    <td class="all-text"><input type="text" style="background: transparent; border:none;" placeholder="Attended/Not Attended"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="info-headings">Grading Key:</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-grading" style="width:100;">
                                <tr>
                                    <td>
                                        <h5 style="color:#1e398d;">E: Exceeding Expectations</h5>
                                        <p class="all-text">Demonstrates an in depth understanding and superior performance</p>
                                    </td>
                                    <td>
                                        <h5 style="color:#1e398d;">A: Approaching Expectations</h5>
                                        <p class="all-text">Needs occassional support</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 style="color:#1e398d;">M: Meeting Expectations</h5>
                                        <p class="all-text">Demonstrates proficient performance</p>
                                    </td>
                                    <td>
                                        <h5 style="color:#1e398d;">N: Not Yet Approaching Expectations</h5>
                                        <p class="all-text">Needs continuous support</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="info-headings">Language & Literacy - English</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-lang">
                                <tr>
                                    <td style="vertical-align: baseline;">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Listens respectfully without interruptions and follows instructions.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Listens to stories, poems, and rhymes with enjoyment.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Listens carefully to follow instructions.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Speaks clearly, audibly, and with confidence.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Responds appropriately to simple questions.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Identifies main events and characters in stories.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Link what he/she hears or reads to own experiences.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Recognizes and joins in with predictable phrases and words in the stories.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Segments and blends sounds in words.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Recognizes the following letters and their sounds <input style=" border:none; border-bottom: 1px solid grey;" type="text"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Holds a pencil/crayon/marker using an efficient grip.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Traces letters, numbers, lines, and circles to prepare for letter and number formation, e.g. straight, slanting, curved lines, circle. </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Recognises own name in print.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="info-headings">Language & Literacy - Urdu</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-urdu">
                                <tr>
                                    <td>
                                        <table style="width: 60px;">
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 20%;">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <img src="{{asset('assets/img/1x/new-kids4.png')}}" style="position:absolute; margin-top:20px;" alt="">
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
            <td style="position: relative;">
                <table style="width: 90%; height: 30%; margin-left:auto; vertical-align: baseline;">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/logo-hori.png')}}" alt="">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width:100%; height:100%; margin-bottom:490px;">
                                <tr>
                                    <td>
                                        <table style="height:100%; padding-bottom:120px; background-color:#cdede8;">
                                            <tr>
                                                <th style="background-color:#80d3c6; color:#00A88E; height:15px;">
                                                    Creative Development
                                            <tr>
                                                <th style="color:#fff; background-color:#00A88E; height:15px;">
                                                    Art
                                                </th>
                                            </tr>
                                            </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 80%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Names and identifies the following colours: red, yellow, blue, green, orange.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 80%;">
                                                </td>
                                                <td class="all-text">
                                                    Selects material for artwork with support.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 80%;">
                                                </td>
                                                <td class="all-text">
                                                    Creates art using lines and shapes to express ideas and feelings.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 80%;">
                                                </td>
                                                <td class="all-text">
                                                    Experiments with:
                                                    <table>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> Finger Painting
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> Vegetables Painting
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 80%;">
                                                </td>
                                                <td class="all-text">
                                                    Talks about what he/she has drawn/painted/made with play dough.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#00A88E; color:#fff;">
                                        Music
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 50%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Enjoys singing activities.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 50%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Responds to simple musical composition.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#00A88E; color:#fff;">
                                        Role-play
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Engages in imaginative and role play activities.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Plays cooperatively in a group while role playing.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="color:#fff; background-color: #00A88E;">
                                        Islamiyat
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width: 50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites Tasmiya.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width: 50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites Tasmiya with Urdu Translation.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width: 50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites first Kalima.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width: 50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites Urdu translation of first Kalima.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="height:100%; padding-bottom:120px; background-color:azure;">
                                <tr>
                                    <th style="background-color:#1e398d;  color:#fff; height:48px;">
                                        Mathematical Development
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Recognises names and sequences number 1-15.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Correctly identifies the following shapes: <br>
                                                        square, circle, triangle, rectangle.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <input type="text" style="width: 60%;">
                                                </td>
                                                <td class="all-text">
                                                    Demonstrates an understanding of:
                                                    <table>
                                                        <tr>
                                                            <td style="vertical-align: baseline; margin-left:40px;">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                big and small
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: baseline; margin-left:40px;">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                tall and short
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: baseline; margin-left:40px;">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                grouping things
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: baseline; ">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                more and fewer
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: baseline; ">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                same, not the same
                                                            </td>
                                                        </tr>
                                                </td>
                                            </tr>
                                        </table>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 60%;">
                                    </td>
                                    <td class="all-text">
                                        Counts in a sequence from 1-15.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 60%;">
                                    </td>
                                    <td class="all-text">
                                        Recognises and knows ordinal numbers first, second, third, fourth, fifth.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid white; height:30px; background-color:#1e398d; color:aliceblue;">
                            The World Around Us
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Talks about things in his/her surroundings.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Talks about habitats of various animals/birds.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Talks about different weathers and his/her favourite season.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Identifies shapes of different objects in his/her surroundings.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Knows his/her full name and vocabulary related to family members.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Discusses the differences between different types of houses in cities and villages.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table style="background-color:#cdede8; height:100%; padding-bottom:120px;">
                    <tr>
                        <th style="color:#fff; background-color:#00A88E; height:48px;">
                            Physical Development
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td>
                                        <p class="all-text">
                                            Bends and stretches with support.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Has begun to coordinate movements with other students.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Aims and throws kicks a ball at times.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Maintains balance during bending and stretching activities sometimes.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th style="color:#fff; background-color:#00A88E; height:30px;">
                            PSHE
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Shows an interest in the classroom activities.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Shows responsibility for personal belongings.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Demonstrates self-control most of the time.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        When working as part of a group:
                                        <table>
                                            <tr>
                                                <td class="all-text">
                                                    <input type="checkbox" name="" id=""> shares fairly
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="all-text">
                                                    <input type="checkbox" name="" id=""> uses polite words e.g. hello, goodbye
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Expresses own needs and feelings.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Is sensitive to others needs and feelings.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width: 80%;">
                                    </td>
                                    <td class="all-text">
                                        Recognises the importance of keeping healthy.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <img src="{{asset('assets/img/1x/new-kids3.png')}}" style="position:absolute; margin-top: 850px;  margin-left:350px; " alt="">
    </table>
    </td>
    </tr>
    </table>
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
                                    <td style="z-index: inherent;">
                                        <p class="all-text">Class Teacher's Comments:</p>
                                        <textarea name="" id="linedTransparent" cols="" rows="4"></textarea>
                                        <p class="all-text">School Head's Comments: </p>
                                        <textarea name="" id="linedTransparent" cols="" rows="2"></textarea>
                                        <p class="all-text">Class Teacher's Signature: <input class="input-border" type="text" style="width: 60%;">
                                        </p>
                                        <p class="all-text">School Head's Signature: <input class="input-border" type="text" style="width: 60%;">
                                        </p>
                                        <p class="all-text">Dated: <input class="input-border" type="text"></p>
                                    </td>
                                    <img style="margin-left: 50px; margin-top:400px; position:absolute; " src="{{ asset('assets/img/1x/new-kids-p.png') }}">
                                </tr>
                            </table>
                            <table style="margin-left:65px; margin-top:10px;">
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
                            <table style="margin-left:75px; margin-top:-25px;">
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/location_icon.png')}}" alt="">
                                    </td>
                                    <td>
                                        <p class="small-text">10-11 Gurumangat Road, Gulberg III, Lahore, Pakistan</p>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:125px; margin-top:-25px;">
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
                            <table style="margin-left:70px; ">
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
                        <td style="position:relative;">
                            <img src="{{asset('assets/img/1x/new-kids.png')}}" alt="">
                            <table class="table-img cover-table">
                                <tr>
                                    <td style="text-align: center;">
                                        <h2 style="color:#00A88E; margin-top: 70px; font-family:dexa, expanded, extra bold, Sans-serif; font-size:20px;">Progress Report: Term II</h2>
                                        <h3 style="color:#1e398d; margin-top: -10px; font-family:raleway, medium; font-size: 16px;;">Early Years: Nursery</h3>
                                        <h5 style="margin-top: -10px; font-size:12px;">ACADEMIC YEAR 20
                                            <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                                            -
                                            <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="margin-bottom: 80px; margin-top:-10px;">
                                            <tr>
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Name:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                            <tr class="d-flex input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Computer ID:</label>
                                                    <input type="text">
                                                </td>
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Class:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Class Teacher:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                            <tr class="input-spc">
                                                <td class="d-flex">
                                                    <label class="name-inputs all-text" style="color:#1e398d;" for="">Campus:</label>
                                                    <input type="text">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <footer>

    </footer>


    <table style="width:100%; height:100%; margin-top:60px;">
        <tr>
            <td>
                <table style="width:110%; height:100%; position:relative;">
                    <tr>
                        <td>
                            <p class="info-headings">Student's Information</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width:100%; border:1px solid grey; border-collapse:collapse; " class="tbl-info2">
                                <tr>
                                    <td class="all-text">Student's Age</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr style="background-color:#cdede8;">
                                    <td class="all-text">Class Average Age</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr>
                                    <td class="all-text">Term II: Total No. of Working Days</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr style="background-color:#cdede8;">
                                    <td class="all-text">Term II: Student's Attendance</td>
                                    <td><input type="text" style="background: transparent; border:none;"></td>
                                </tr>
                                <tr>
                                    <td class="all-text">2nd Parent-Teacher Meeting</td>
                                    <td class="all-text"><input type="text" style="background: transparent; border:none;" placeholder="Attended/Not Attended"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="info-headings">Grading Key:</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-grading" style="width:100%">
                                <tr>
                                    <td>
                                        <h5 style="color:#1e398d">E: Exceeding Expectations</h5>
                                        <p class="all-text">Demonstrates an in depth understanding and superior performance</p>
                                    </td>
                                    <td>
                                        <h5 style="color:#1e398d">A: Approaching Expectations</h5>
                                        <p class="all-text">Needs occassional support</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 style="color:#1e398d">M: Meeting Expectations</h5>
                                        <p class="all-text">Demonstrates proficient performance</p>
                                    </td>
                                    <td>
                                        <h5 style="color:#1e398d;">N: Not Yet Approaching Expectations</h5>
                                        <p class="all-text">Needs continuous support</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="info-headings">Language & Literacy - English</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-lang">
                                <tr>
                                    <td style="vertical-align: baseline;">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Listens respectfully without interrupting others.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Listens to stories, poems, and rhymes with enjoyment.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Listens carefully to follow simple instructions.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Speaks clearly, audibly, and with confidence.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Retells simple stories using narrative language.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Discusses stories/non-fiction information and answers relevant questions.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Predicts how events may unfold in a story.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Sequences events as they occurred in a story.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Segments and blends sounds in words.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Recognises patterns in stories and rhymes.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Recognises and joins in with predictable phrases and words in stories/poems.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Knows double letter sounds ff, ll, ss, zz.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Recognises the following letters and their sounds: <input type="text" style="border:none; border-bottom:1px solid black;"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Forms upper and lower case letters using the correct sequence of movements.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Holds pencil/crayon/marker using an efficient grip.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Can form plurals by adding an '-s' to the names of animals and objects.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: baseline; ">
                                        <input type="text" style="width:50%;">
                                    </td>
                                    <td>
                                        <p class="all-text">Writes own name correctly.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="info-headings">Language & Literacy - Urdu</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-urdu">
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:20%;">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <img src="{{asset('assets/img/1x/new-kids4.png')}}" style="position:absolute; margin-top:20px;" alt="">
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table style="width: 80%; height: 100%; margin-left:auto; position:relative;">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('assets/img/1x/logo-hori.png')}}" alt="">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%; height:100%;">
                                <tr>
                                    <td>
                                        <table style="height:100%; background-color:#cdede8;">
                                            <tr>
                                                <th style="background-color:#80d3c6; color:#00A88E; height:30px; white-space: nowrap !important;">
                                                    Creative Development
                                            <tr>
                                                <th style="color:#fff; background-color:#00A88E; height:30px;">
                                                    Art
                                                </th>
                                            </tr>
                                            </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:90%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Names and identifies the following colours: red, yellow, blue, green, orange, white, black, purple.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:90%;">
                                                </td>
                                                <td class="all-text">
                                                    Selects material for artwork independently.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:90%;">
                                                </td>
                                                <td class="all-text">
                                                    Creates patterns with a variety of lines and shapes.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:90%;">
                                                </td>
                                                <td class="all-text">
                                                    Explores and experiments with:
                                                    <table>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> Finger Painting
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> Vegetable Painting
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:90%;">
                                                </td>
                                                <td class="all-text">
                                                    Explains what he/she has drawn/painted/made with play dough.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#00A88E; color:#fff;">
                                        Music
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Sings a variety of simple songs, developing some control of words, expression, breathing and singing in tune.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Identifies and differentiates between high and low pitch sounds.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#00A88E; color:#fff;">
                                        Role-play
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="margin-bottom:430px;">
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Engages in imaginative and role play activities.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Acts out new roles from familiar stories.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="height:100%; background-color:aliceblue;">
                                <tr>
                                    <th style=" background-color:#1e398d; color:#fff; height:60px; white-space: nowrap !important;">
                                        Mathematical Development
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:60%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Recognises, names, and sequences numbers 1-20.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:60%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Correctly identifies the following shapes:
                                                        <br>
                                                        square, circle, triangle, rectangle, cone, cube, cylinder.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:60%;">
                                                </td>
                                                <td class="all-text">
                                                    Demonstrates an understanding of:
                                                    <table>
                                                        <tr>
                                                            <td style="vertical-align: baseline; ">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                one more
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: baseline; ">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                matching numbers and words
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: baseline; ">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                comparing and categorising objects according to their shape/size
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: baseline; ">
                                                                <input type="checkbox">
                                                            </td>
                                                            <td class="all-text">
                                                                odd and even numbers
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:60%;">
                                                </td>
                                                <td class="all-text">
                                                    Counts forwards and backwards.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:60%;">
                                                </td>
                                                <td class="all-text">
                                                    Recognises and knows ordinal numbers first, second, third, fourth, fifth, sixth, seventh, eighth, ninth, tenth.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color:#1e398d; color:aliceblue; height:30px;">
                                        The World Around Us
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="margin-bottom: 390px;">
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td class="all-text">
                                                    Identifies objects from surroundings in respect to shape, size, etc.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td class="all-text">
                                                    Knows the difference between pet and wild animals.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td class="all-text">
                                                    Identifies, sorts, and classifies animals according to their:
                                                    <table>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> Physical Characteristics
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> habitats
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td class="all-text">
                                                    Talks about family traditions and customs.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:70%;">
                                                </td>
                                                <td class="all-text">
                                                    Uses new vocabulary related to weather and how the environment changes in each season.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="background-color:#cdede8; height:100%; ">
                                <tr>
                                    <th style="color:#fff; background-color:#00A88E; height:60px; white-space: nowrap !important;">
                                        Physical Development
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td>
                                                    <p class="all-text">
                                                        Demonstrates body control when bending stretching and rolling.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    Coordinates movements with other students.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    Aims and throws/kicks a ball in the intended direction.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: baseline; ">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    Maintains balance during bending and stretching activities most of the time.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="color: #fff; background-color:#00A88E;">
                                        PSHE
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    Recognises and names feelings associated with happiness, sadness, anger.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    Shows responsibility for personal belongings.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    Follows simple safety rules.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    When working as part of a group:
                                                    <table>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> takes turns to speak
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> demonstrates concern for others
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="all-text">
                                                                <input type="checkbox" name="" id=""> uses courtesy words e.g. please, thank you, sorry
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:80%;">
                                                </td>
                                                <td class="all-text">
                                                    Comforts others when they are in distress.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="color:#fff; background-color:#00A88E;">
                                        Islamiyat
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="margin-bottom: 290px;">
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites Tasmiya with Urdu translation.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites first Kalima.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites Urdu translation of the first Kalima.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites Surah Ikhlas.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:baseline;">
                                                    <input type="text" style="width:50%;">
                                                </td>
                                                <td class="all-text">
                                                    Recites Urdu Translation of Surah Ikhlas.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <img src="{{asset('assets/img/1x/new-kids3.png')}}" style="position:absolute; margin-top: 980px; top:50px; margin-left:100px; " alt="">
                </table>
            </td>
        </tr>
    </table>
    </td>
    </tr>
    </table>
</body>

</html>
