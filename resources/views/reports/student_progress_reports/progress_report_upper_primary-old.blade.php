<style>
  @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900;&family=Roboto:wght@100;300;400;500;700;800;900&display=swap');

  table,
  th,
  td {
    border: 1px solid black;

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
    margin: 0 0 10 60px;
    border-radius: 25px;
  }



  .all-text {
    font-size: 12px;
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

  .tbl-sub td {
    border-left: 1px solid grey !important;
    padding: 0 5px;

  }

  .tbl-sub td:first-child {
    border-left: none !important;
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
          <img style=" width:100%; height:130vh; position:absolute; z-index:-1; " src="{{ asset('assets/img/1x/img-cld-1.png') }}">
        </table>
      </td>
    </tr>
  </table>

<hr>

  <table>
    <tr>
      <td>
        <table>
          <tr>
            <td>
              <p class="info-headings">Progress Report - Term I</p>
            </td>
          </tr>
          <tr>
            <td>
              <table>
                <tr>
                  <td class="all-text">Student's Age:

                  </td>
                  <td class="all-text">Class Average Age:

                  </td>
                  <td class="all-text">1st Parent Teacher Meeting:

                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="text" name="" id="">
                  </td>
                  <td>
                    <input type="text" name="" id="">
                  </td>
                  <td>
                    <input type="text" name="" id="" placeholder="Attended/Not Attended">
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td>
              <table style="border:1px solid grey; border-radius:5px; background-color:#cdede8;">
                <tr>
                  <td>
                    No. of Working Days:
                    <input type="text">
                  </td>
                  <td>
                    Days Present:
                  </td>
                  <td>
                    Days Absent:
                  </td>
                </tr>
                <tr>
                  <td>
                    Percentage of Attendance:
                    <input type="text">
                  </td>
                  <td>
                    <input type="text">
                  </td>
                  <td>
                    <input type="text" name="" id="">
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td>
              <table style="border: 1px solid grey; border-radius: 5px; width:50%;" class="tbl-sub">
                <tr style="background-color:#1e398d; border: 1px solid grey; color:#fff;">
                  <th rowspan="2">Subjects</th>
                  <th>Class Work</th>
                  <th>Oral Work</th>
                  <th>Total Marks</th>
                  <th rowspan="2">Overall Grade</th>
                  <th rowspan="2">Teacher's Comments</th>
                </tr>
                <tr style="background-color:#1e398d; color:#fff;">
                  <th>30 Marks</th>
                  <th>20 Marks</th>
                  <th>50 Marks</th>
                </tr>
                <tr>
                  <td>English</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Mathematics</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Urdu</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Waaqfiat-e-aama</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Muasharti Aloom</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>General Science</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Islamiat</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Nazra</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Arts</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Music</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
                <tr>
                  <td>Games</td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                  <td><input type="text"></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>

      <td style="position: relative;">
        <table>
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
              <table>
                <tr>
                  <td>
                    <table class="tbl-grading">
                      <tr>
                        <td>
                          <h5 style="color:#1e398d;">Grading Key</h5>
                        </td>

                      </tr>
                      <tr>
                        <td>
                          <h5 style="color:#1e398d;">A: Excellent (90% and above)</h5>
                        </td>
                        <td>
                          <h5 style="color:#1e398d;">D: Average (50% - 59%)</h5>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h5 style="color:#1e398d;">B: Very good (80% - 89%)</h5>
                        </td>
                        <td>
                          <h5 style="color:#1e398d;">E: Below Average (35% - 49%)</h5>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h5 style="color:#1e398d;">C: Good (60% - 79%)</h5>
                        </td>
                        <td>
                          <h5 style="color:#1e398d;">U: Improvement Needed (34% and below)</h5>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <table style="border: 1px solid grey;">
                      <thead>
                        <tr style="background-color: #1e398d; color:#fff;">
                          <th scope="col" style="width: 80%"></th>
                          <th scope="col" style="width: 20%">Grade</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="text-white" style="background: #00A78D; color:#fff; text-align:left;">
                          <th colspan="2">General Behavior</th>
                        </tr>

                        <tr>
                          <td>Participates Actively in class</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr style="background-color: #cdede8;">
                          <td>Well Groomed</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>Well Mannered</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr style="background-color: #cdede8;">
                          <td>Cooperative</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>Good Listener</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr style="background-color: #cdede8;">
                          <td>Punctual and Responsible</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>Neat and Orderly</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr style="background-color: #cdede8;">
                          <td>Polite and Kind</td>
                          <td><input type="text"></td>
                        </tr>

                        <tr class="text-white" style="background: #00A78D; color:#fff; text-align:left;">
                          <th colspan="2">Social Skills</th>
                        </tr>
                        <tr>
                          <td>Works collaboratively</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr style="background-color: #cdede8;">
                          <td>Accepts responsibility for own behavior</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>Follow Rules</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr style="background-color: #cdede8;">
                          <td>Listens attentively and responds appropriately</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr class="text-white" style="background: #00A78D; color:#fff; text-align:left;">
                          <th colspan="2">Communication Skills</th>
                        </tr>
                        <tr>
                          <td>Communicates clearly and audibly</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr style="background-color: #cdede8;">
                          <td>Listens carefully without interrupting when others speak</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>Takes turns to speak</td>
                          <td><input type="text"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <img src="{{asset('assets/img/1x/family.png')}}" alt="">
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <hr>

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
                    <input style="border: none;" type="text" name="" id="" placeholder="Promoted/Not Promoted">
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
          <img style=" width:100%; height:130vh; position:absolute; z-index:-1; " src="{{ asset('assets/img/1x/img-cld-1.png') }}">
        </table>
      </td>
    </tr>
  </table>


</body>
