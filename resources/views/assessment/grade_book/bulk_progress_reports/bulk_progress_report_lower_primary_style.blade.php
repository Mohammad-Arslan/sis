 <style type="text/css">
     /*    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800;900;&family=Roboto:wght@100;300;400;500;700;800;900&display=swap'); */

     /*@page { size: a4 portrait; }  @page { size: 595pt 942pt; }*/
     /*@page {
            size: 21cm 29.7cm;
            margin: 30mm 45mm 30mm 45mm;
        / change the margins as you want them to be. /
        }*/

     .for-renove-padding td {
         padding-top: 0px !important;
         padding-bottom: 0px !important;
     }

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
         font-weight: lighter;


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


     .use-flex {
         display: flex;
         padding: 0 20px;
         gap: 30px;
         width: 100%;
     }

     .modal-body.student_progress_report_modal {
         z-index: 9;

     }

     .white-space-nowrap {
         white-space: nowrap !important;
     }

     .info-headings-1 {
         color: azure;
         background-color: #1e398d;
         padding: 3px 33px 3px 33px;
         display: inline-block;
         margin: 5px 145px !important;
         border-radius: 25px;
         white-space: nowrap;
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

     .tbl-grading2 {
         margin-bottom: 10px;
         padding: 10px;
         border: none;
         border-radius: 5px;
         background-color: #dcdddf;
     }

     .drk-blue-clr {
         background-color: #1e398d;
         color: #fff;
     }

     .grey-clr {
         background-color: #dcdddf;
         color: #000;
     }

     .grey1-clr {
         background-color: #edeeef;
         color: #000;
     }

     .tbl-inputs2 {
         border: 2px solid #00A88E;
         margin-right: 50px;
         margin-bottom: 60px;
         padding-top: 30px;
         padding-right: 40px;
         padding-bottom: 120px;
         padding-left: 40px;
         width: 60%;
         border-radius: 5px;
         background-color: #fff;
     }

     .name-inputs {
         color: #76a7ff;
         text-align: center;

     }

     .cover-table label {
         white-space: nowrap;
         margin-bottom: 0;
         margin-right: 5px;
         align-self: center;
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
         background-image: -webkit-linear-gradient(left, #ffffff00 0, #ffffff00 0),
             -webkit-linear-gradient(right, #ffffff00 0, #ffffff00 0),
             -webkit-linear-gradient(#dcddde 30px, #000 30px, #000 31px, #fff 31px);
         background-repeat: repeat-y;
         background-size: 100% 100%, 100% 100%, 100% 31px;
         background-attachment: local;
         border: none;
     }

     #lined1 {
         line-height: 31px;
         background-image: -webkit-linear-gradient(left, #ffffff00 30px, #ffffff00 30px),
             -webkit-linear-gradient(right, #ffffff00 30px, #ffffff00 30px),
             -webkit-linear-gradient(#fff 30px, #000 30px, #000 31px, #fff 31px);
         background-repeat: repeat-y;
         background-size: 100% 100%, 100% 100%, 100% 31px;
         background-attachment: local;
         border: none !important;
     }

     .d-flex {
         display: flex !important;
     }

     .input-border {
         border: none !important;
         border-bottom: 1px solid #000 !important;
         background: transparent;
     }

     input[type='number']::-webkit-outer-spin-button,
     input[type='number']::-webkit-inner-spin-button {
         -webkit-appearance: none;
         margin: 0;
     }

     /* .border-none{
        border: none !important;
      } */

     @media print {
         footer {
             page-break-after: always;
         }
     }

     .set-bottom-img {
         position: relative;
         border: 2px solid #00A88E;
         padding: 20px 30px;

     }

     .b-img {
         position: absolute;
         bottom: -80px;
         left: 50%;
         transform: translateX(-50%);
         max-width: 260px;
         width: 100%;
     }

     .w-100 {
         width: 100% !important;
     }

     .w-50 {
         width: 50% !important;
     }

     .small-text {
         margin-bottom: 0;
     }

     .logo-country {
         vertical-align: middle;
         text-align: center;
         max-width: 420px;
         width: 100%;
         margin: 0 auto;
     }

     .logo-country .table-small-img img {
         max-width: 100%;
         height: auto;
     }

     .logo-country .table-small-img {
         height: 25px;
         max-width: 60px;
         width: 100%;
         /* margin: 0 auto; */
         display: flex;
         justify-content: center;
         overflow: hidden;
     }

     .logo-country .text-inline-p {
         display: flex;
         align-items: center;
         justify-content: space-between;
     }

     .logo-country .text-inline-p p {
         border-right: 1px solid blue;
         margin-top: 8px;
         padding-right: 5px;
         margin-right: 5px;
         line-height: 10px;
     }

     .logo-country .text-inline-p p:last-child {
         border-right: none;
     }

     .table-spacing {
         border-collapse: separate;
         border-spacing: 0;
     }

     .group-icons {
         display: flex;
         align-items: center;
         justify-content: space-between;
     }

     .add-flex-equal {
         display: flex;
         align-items: center;
         width: 100%;
         padding: 25px;
         margin: 0 auto;
         gap: 20px;
     }

     .add-flex-equal .flex-td {
         width: 100%;
     }

     .wrap-table td,
     .wrap-table th {
         padding-left: 5px !important;
         padding-right: 5px !important;
     }

     .big-table-flex1 {
         width: 50%;
     }

     .big-table-flex {
         width: 100%;
     }

     .bg-img-main {
         top: 0;
         left: 50%;
         transform: translateX(-50%)
     }

     textarea,
     input[type] {
         pointer-events: none;
     }

     textarea {
         background: #dcdddf;
     }

     @media print {
         .page-break2 {
             page-break-before: always;
         }

     }
 </style>
