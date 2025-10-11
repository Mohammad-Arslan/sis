<style>
  /* table,
  th,
  td {
    border: 1px solid black;

  } */

  textarea:focus,
  input:focus {
    outline: none;
    border: none;
  }
input{
  border:1px solid #00A88E;

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
    margin: 0 0 10 60px;
    border-radius: 25px;
  }

  .info-headings {
    color: azure;
    background-color: #1e398d;
    padding: 2px;
    width: 220px;
    text-align: center;
    margin: 10 0 10 60px;
    border-radius: 25px;
  }

  .all-text {
    font-size: 12px;
  }

  .info-headings {
    color: azure;
    background-color: #1e398d;
    padding: 2px;
    width: 220px;
    text-align: center;
    margin: 10 0 10 60px;
    border-radius: 25px;
  }

  .info-headings {
    color: azure;
    background-color: #1e398d;
    padding: 2px;
    width: 220px;
    text-align: center;
    margin: 10 0 10 60px;
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

  .tbl-inputs {
    border: 2px solid #00A88E;
    margin: 60 50 30 50px;
    padding: 20 20 100 20px;
    width: 70%;
    border-radius: 5px;
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

  .tbl-info {
    width: 80%;
    margin: 0 0 20 60px;
    border: 1px solid grey;
    border-radius: 5px;
  }

  .tbl-info2 td {
    border-left: 1px solid grey !important;
    padding: 0 5px;

  }

  .tbl-info2 td:first-child {
    border-left: none !important;

  }

  .tbl-grading {
    width: 100%;
    margin: 0 0 10 0;
    padding: 10px;
    border: 1px solid grey;
    border-radius: 5px;
    background-color: #cdede8;
  }

  .tbl-lang {
    width: 100%;
    border: 1px solid grey;
    border-radius: 5px;
  }

  .tbl-urdu {
    width: 80%;
    margin: 0 0 20 30px;
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

<body>
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
                    <p class="all-text">Class Teacher's Signature: <input class="input-border" type="text" style="width: 60%;">
                    </p>
                    <p class="all-text">School Head's Signature: <input class="input-border" type="text" style="width: 60%;">
                    </p>
                    <p class="all-text">Dated: <input class="input-border" type="text"></p>
                  </td>
                  <img style="margin-left: 150px; margin-top:490px; position:absolute; " src="{{ asset('assets/img/1x/kids.png') }}">
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
            <td>
              <img style="display:block;  margin:0 auto;" src="{{asset('assets/img/1x/ucs_logo.png')}}" alt="">
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">
              <h2 style="color:00A88E; margin-top: 10px; font-family:dexa, expanded, extra bold, Sans-serif;">Progress Report: Term I</h2>
              <h3 style="color:darkblue; margin-top: -10px; font-family:raleway, medium;">Early Years: Kindergarten</h3>
              <h5 style="margin-top: -10px;">ACADEMIC YEAR 20
                <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                -
                <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
              </h5>
            </td>
          </tr>

          <tr>
            <td style="position:relative;">
              <img src="{{asset('assets/img/1x/name.png')}}" alt="">
              <table class="table-img cover-table">
                <tr>
                  <td>
                    <table>
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
                <table style="margin-left:65px; margin-top:20px;">
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
              <table class="tbl-grading">
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
                    <p class="all-text">Speaks clearly, audibly and with confidence and is aware of the listeners.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Predicts how events may unfold in a story and sequences them correctly.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Identifies main events and characters in stories, and finds specific information from within a text.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Expresses personal point of view and justifies ideas and opinions with reasons.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Frames, asks and answers relevant questions.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Sounds out, blends and segments the sounds in words in order to spell and read new words/names.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Identifies and reads 'captions' and 'labels'.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Reads with expression.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Reads and writes words with ai, ee, igh, oa, oo, ar, or, ur, ow, oi, ear, ure, er sounds.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Write simple sentences to communicate meaning.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Uses nouns, verbs, adjectives, correctly in writing.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Uses capital letters, full stops and question marks correctly in writing.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Uses pronouns he, she, it, him, her, you, they, them correctly in the writing.</p>
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
                <img src="{{asset('assets/img/1x/kid1.png')}}" style="position:absolute; margin-top:80px;" alt="">
              </table>
            </td>
          </tr>

        </table>
      </td>
      <td style="position: relative;">
        <table style="width: 90%; height: 30%; margin-left:auto;">
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
              <table style="width:100%; height:100%;" >
                <tr>
                  <td>
                    <table style="height:100%; background-color:#cdede8;">
                      <tr>
                        <th style="background-color:#80d3c6; color:#00A88E;">
                          Creative Development
                      <tr>
                        <th style="color:#fff; background-color:#00A88E;">
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
                            Names and identifies the following colours: red, yellow, blue, green, orange, white, black, purple, and pink.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline;">
                          <input type="text" style="width: 80%;">
                        </td>
                        <td class="all-text">
                          Creates art using range of everyday items to express ideas and feelings.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline;">
                          <input type="text" style="width: 80%;">
                        </td>
                        <td class="all-text">
                          Creates art using range of everyday items to express ideas and feelings.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline;">
                          <input type="text" style="width: 80%;">
                        </td>
                        <td class="all-text">
                          Creates art using range of everyday items to express ideas and feelings.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline;">
                          <input type="text" style="width: 80%;">
                        </td>
                        <td class="all-text">
                          Creates art using range of everyday items to express ideas and feelings.
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
                          <input type="text" style="width: 80%;">
                        </td>
                        <td>
                          <p class="all-text">
                            Sings a variety of songs developing some control of words, expression, breathing and singing in tune.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline;">
                          <input type="text" style="width: 80%;">
                        </td>
                        <td>
                          <p class="all-text">
                            Creates simple sound effects to illustrate particular words in rhymes and stories.
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
                            Acts out new roles from familiar stories.
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
                    <table style="margin-bottom: 290px;">
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width: 50%;">
                        </td>
                        <td class="all-text">
                          Recites Tasmiya with Urdu translation.
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
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width: 50%;">
                        </td>
                        <td class="all-text">
                          Recites Surah Ikhlas.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width: 50%;">
                        </td>
                        <td class="all-text">
                          Recites Urdu translation of Surah Ikhlas.
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table style="height:100%; background-color:azure;">
                <tr>
                  <th style="background-color:#1e398d; color:#fff; height:48px;">
                    Mathematical Development
                  </th>
                </tr>
                <tr>
                  <td>
                    <table >
                      <tr>
                        <td style="vertical-align: baseline;">
                          <input type="text" style="width: 60%;">
                        </td>
                        <td>
                          <p class="all-text">
                            Recognises names and sequences number 1-20.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline;">
                          <input type="text" style="width: 60%;">
                        </td>
                        <td>
                          <p class="all-text">
                            Uses 1st to 10th ordinal numbers correctly.
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
                                more/fewer
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; margin-left:40px;">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                most/fewest
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
                                longer and shorter
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                longest and shortest
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                bigger and smaller
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                heavier and lighter
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
                    Tells the time using the hour hand.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 60%;">
                  </td>
                  <td class="all-text">
                    Uses the terms heavy and light to compare the mass of the objects.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 60%;">
                  </td>
                  <td class="all-text">
                    Writes number sentences for addition and subtraction.
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
              <table style="margin-bottom: 420px;">
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Knows the names and order of months of the year.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Talks about different types of animals and uses of the farm animals.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Talks about different ways to save our environment.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Talks about importance of 2-3 professions using relevant vocabulary.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Talks about difference between insects, creepy crawlies, birds, animals found in jungles/pet animals and animals found in sea.
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
      <td>
        <table style="background-color:#cdede8; height:100%;">
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
                      Listens to and responds readily to instructions and signals within established routines.
                    </p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Demonstrates a sense of fair play.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Is able to move at different speeds and in various directions maintaining balance.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Creates and remembers a simple movement sequence.
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
              <table style="margin-bottom: 590px;">
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Express positive qualities about themselves.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Takes part in making class and group rules, follows them and understands how these rules help them.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Respects others' needs, feelings, and opinions.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Voices a difference of opinion sensitively and courteously.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width: 80%;">
                  </td>
                  <td class="all-text">
                    Follows safety rules in various situations.
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <img src="{{asset('assets/img/1x/kids2.png')}}" style="position:absolute; margin-top: 1020px;  margin-left:380px; " alt="">
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
      <td style="position: relative;">
        <table class="table-img2" style="width:100%; height:100%;">
          <tr>
            <td>
              <table class="tbl-inputs">
                <tr>
                  <td>
                    <p class="all-text">Class Teacher's Comments:</p>
                    <textarea name="" id="lined" cols="50" rows="4"></textarea>
                    <p class="all-text">School Head's Comments: </p>
                    <textarea name="" id="lined" cols="50" rows="2"></textarea>
                    <p class="all-text">Class Teacher's Signature: <input class="input-border" type="text" style="width: 60%;">
                    </p>
                    <p class="all-text">School Head's Signature: <input class="input-border" type="text" style="width: 60%;">
                    </p>
                    <p class="all-text">Dated: <input class="input-border" type="text"></p>
                    <input type="text" placeholder="Promoted/Not Promoted" style="border: none;">
                  </td>
                  <img style="margin-left: 150px; margin-top:520px; position:absolute; " src="{{ asset('assets/img/1x/kids.png') }}">
                </tr>
              </table>

              <table style="margin-left:70px;">
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
            <td style="text-align: center;">
              <h2 style="color:00A88E; margin-top: 10px; font-family:dexa, expanded, extra bold, Sans-serif;">Progress Report: Term II</h2>
              <h3 style="color:darkblue; margin-top: -10px; font-family:raleway, medium;">Early Years: Kindergarten</h3>
              <h5 style="margin-top: -10px;">ACADEMIC YEAR 20
                <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
                -
                <input type="number" style="width: 35px; border: none !important; border-bottom: 1px solid #000 !important;">
              </h5>
            </td>
          </tr>

          <tr>
            <td style="position:relative;">
              <img src="{{asset('assets/img/1x/name.png')}}" alt="">
              <table class="table-img cover-table">
                <tr>
                  <td>
                    <table>
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
                <table style="margin-left:65px; margin-top:20px;">
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
                    <p class="all-text">Listens respectfully without interruptions and follows instructions.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Speaks clearly, audibly and with confidence and is aware of the listeners.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Predicts how events may unfold in a story and sequences them correctly.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Identifies main events and characters in stories, and finds specific information from within a text.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Expresses personal point of view and justifies ideas and opinions with reasons.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Frames, asks and answers relevant questions.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Sounds out, blends and segments the sounds in words in order to spell and read new words/names.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Identifies and reads 'captions' and 'labels'.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Reads with expression.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Reads and writes words with ai, ee, igh, oa, oo, ar, or, ur, ow, oi, ear, ure, er sounds.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Write simple sentences to communicate meaning.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Uses nouns, verbs, adjectives, correctly in writing.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Uses capital letters, full stops and question marks correctly in writing.</p>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: baseline; ">
                    <input type="text" style="width:50%;">
                  </td>
                  <td>
                    <p class="all-text">Uses pronouns he, she, it, him, her, you, they, them correctly in the writing.</p>
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
                 <img src="{{asset('assets/img/1x/kid1.png')}}" style="position:absolute; margin-top:80px;" alt="">
              </table>
            </td>
          </tr>
        </table>
      </td>
      <td>
        <table style="width: 90%; height: 100%; margin-left:auto; position:relative;">
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
              <table style="width: 100%; height:100%;" >
                <tr>
                  <td>
                    <table style="height:100%; background-color:#cdede8;">
                      <tr>
                        <th style="background-color:#80d3c6; color:#00A88E; height:30px;">
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
                            Names and identifies the following colours: red, yellow, blue, green, orange, white, black, purple, and pink.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:90%;">
                        </td>
                        <td class="all-text">
                          Creates art using range of everyday items to express ideas and feelings.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:90%;">
                        </td>
                        <td class="all-text">
                          Experiments with different mediums to create patterns using a variety of lines and shapes.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:90%;">
                        </td>
                        <td class="all-text">
                          Identifies and describes art in the environment.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:90%;">
                        </td>
                        <td class="all-text">
                          Explain what he/she has drawn/painted/made with play dough.
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
                            Sings a variety of songs developing some control of words, expression, breathing and singing in tune.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:70%;">
                        </td>
                        <td>
                          <p class="all-text">
                            Creates simple sound effects to illustrate particular words in rhymes and stories.
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
                            Makes up new roles and acts them out, especially from familiar stories.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:70%;">
                        </td>
                        <td>
                          <p class="all-text">
                            Cooperates, negotiates, and works together collaboratively during role play.
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
                  <th style=" background-color:#1e398d; color:#fff; height:60px;">
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
                            Counts and sequence numbers 1-100.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:60%;">
                        </td>
                        <td>
                          <p class="all-text">
                            Uses ordinal numbers to rank sets by size.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:60%;">
                        </td>
                        <td class="all-text">
                          Uses non-standard units of measurement to compare:
                          <table>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                capacity/volume
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                length
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                height
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                capacity
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                how many more
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: baseline; ">
                                <input type="checkbox">
                              </td>
                              <td class="all-text">
                                heavy and light objects
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
                          Tells the time using the hour hand.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:60%;">
                        </td>
                        <td class="all-text">
                          Uses the terms heavy and light to compare the mass of the objects.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:60%;">
                        </td>
                        <td class="all-text">
                          Writes number sentences for addition and subtraction.
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
                          Knows the names and order of months of the year.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:70%;">
                        </td>
                        <td class="all-text">
                          Talks about different types of animals and uses of the farm animals.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:70%;">
                        </td>
                        <td class="all-text">
                          Talks about different ways to save our environment.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:70%;">
                        </td>
                        <td class="all-text">
                          Talks about importance of 2-3 professions using relevant vocabulary.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:70%;">
                        </td>
                        <td class="all-text">
                          Talks about difference between insects, creepy crawlies, birds, animals found in jungles/pet animals and animals found in sea.
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
                  <th style="color:#fff; background-color:#00A88E; height:60px;">
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
                            Listens to and responds readily to instructions and signals within established routines.
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:80%;">
                        </td>
                        <td class="all-text">
                          Demonstrates a sense of fair play.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:80%;">
                        </td>
                        <td class="all-text">
                          Is able to move at different speeds and in various directions maintaining balance.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: baseline; ">
                          <input type="text" style="width:80%;">
                        </td>
                        <td class="all-text">
                          Creates and remembers a simple movement sequence.
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
                          Express positive qualities about themselves.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align:baseline;">
                          <input type="text" style="width:80%;">
                        </td>
                        <td class="all-text">
                          Takes part in making class and group rules, follows them and understands how these rules help them.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align:baseline;">
                          <input type="text" style="width:80%;">
                        </td>
                        <td class="all-text">
                          Respects others' needs, feelings, and opinions.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align:baseline;">
                          <input type="text" style="width:80%;">
                        </td>
                        <td class="all-text">
                          Voices a difference of opinion sensitively and courteously.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align:baseline;">
                          <input type="text" style="width:80%;">
                        </td>
                        <td class="all-text">
                          Follows safety rules in various situations.
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
                      <tr>
                        <td style="vertical-align:baseline;">
                          <input type="text" style="width:50%;">
                        </td>
                        <td class="all-text">
                          Recites Second Kalima.
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align:baseline;">
                          <input type="text" style="width:50%;">
                        </td>
                        <td class="all-text">
                          Recites Second Kalima with Urdu Translation.
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <img src="{{asset('assets/img/1x/kids2.png')}}" style="position:absolute; margin-top: 980px; top:50px; margin-left:100px; " alt="">
        </table>
      </td>
    </tr>
  </table>
  </td>
  </tr>
  </table>
